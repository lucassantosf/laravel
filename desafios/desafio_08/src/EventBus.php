<?php 

declare(strict_types=1);

namespace Desafio08;

use Desafio08\Traits\MetricasEventBusTrait;
use Desafio08\Outbox\OutboxStore;
use Desafio08\Interfaces\HandlerEventoInterface;
use Desafio08\Exceptions\TopicoInvalidoException;
use Desafio08\Exceptions\EventoFalhouException;
use Desafio08\Exceptions\SemHandlerException;
use Desafio08\Enums\StatusEvento;
use Desafio08\Outbox\OutboxMessage;
 
class EventBus{

    use MetricasEventBusTrait;

    private array $handlers = [];
    private OutboxStore $outbox;
    private int $maxTentativas = 3;

    public function __construct(OutboxStore $outbox,int $maxTentativas = 3){
        $this->outbox = $outbox;
        $this->maxTentativas = $maxTentativas;
    }

    public function registrarHandler(HandlerEventoInterface $handler): void{
        $this->handlers[] = $handler;
    }

    private function matchTopico(string $topicoEvento, string $topicoInteresse): bool{
        if (str_ends_with($topicoInteresse, '.*')) {
            $prefixo = substr($topicoInteresse, 0, -2) . '.';
            return str_starts_with($topicoEvento, $prefixo);
        }

        return $topicoEvento === $topicoInteresse;
    }

    public function publicar(Evento $evento): void{
        if (empty(trim($evento->getTopico()))) {
            throw new TopicoInvalidoException("O tópico do evento não pode ser vazio.");            
        }

        $this->outbox->adicionar(new OutboxMessage($evento, 'PENDENTE'));
        $this->incrementarPublicado();

        // 1. Filtra os handlers cujo tópico casa com o do evento
        $handlersCompativeis = array_filter(
            $this->handlers,
            fn(HandlerEventoInterface $h) => $this->matchTopico($evento->getTopico(), $h->getTopicoDeInteresse())
        );

        // 2. Se nenhum handler foi encontrado
        if (empty($handlersCompativeis)) {
            $this->outbox->marcarComoFalhou($evento->getId());
            $evento->setStatus(StatusEvento::DESCARTADO);
            echo PHP_EOL . "[OUTBOX] Nenhum handler para o tópico {$evento->getTopico()}. Evento descartado." . PHP_EOL;
            throw new SemHandlerException("Nenhum handler registrado para o tópico " . $evento->getTopico());
        }

        // 3. Ordenação: ordene os handlers encontrados por `getPrioridade()` (crescente).
        usort(
            $handlersCompativeis,
            fn(HandlerEventoInterface $a, HandlerEventoInterface $b) => $a->getPrioridade() <=> $b->getPrioridade()
        );

        foreach($handlersCompativeis as $handler){
            while (true) {

                try {

                    $evento->setStatus(StatusEvento::PROCESSANDO);
                    $handler->processar($evento);
                    $this->incrementarHandlerExecutado();

                    $evento->setStatus(StatusEvento::PROCESSADO);
                    $this->outbox->marcarComoEnviado($evento->getId());

                    break; // Sucesso: sai do loop e vai para o próximo handler
                } catch (EventoFalhouException $e) {

                    $this->incrementarHandlerFalhou();
                    $evento->incrementarTentativa();

                    echo PHP_EOL."[ERRO] Handler '{$handler->getNome()}' falhou para o evento {$evento->getId()}: {$e->getMessage()} (Tentativa {$evento->getTentativas()}/{$this->maxTentativas})".PHP_EOL;

                    if($evento->isMaxTentativasExcedidas($this->maxTentativas)){
                        $evento->setStatus(StatusEvento::FALHOU);
                        $this->outbox->marcarComoFalhou($evento->getId());
                        $this->adicionarDeadLetter($evento);
                        $this->incrementarDeadLetter();

                        echo PHP_EOL."[DLQ] Evento {$evento->getId()} excedeu {$this->maxTentativas} tentativas e foi enviado para a Dead Letter Queue.".PHP_EOL;
                        break; // Limite excedido: enviado para DLQ e encerra o retry deste handle

                    }else{
                        echo PHP_EOL."[RETRY] Evento {$evento->getId()} será reprocessado nas próximas tentativas...".PHP_EOL;
                        $this->incrementarReenfileirado();

                    }

                }

            }
        }

    }
}
