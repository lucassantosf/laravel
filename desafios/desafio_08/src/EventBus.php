<?php 

declare(strict_types=1);

namespace Desafio08;

use Desafio08\Traits\MetricasEventBusTrait;
use Desafio08\Outbox\OutboxStore;
use Desafio08\Interfaces\HandlerEventoInterface;
use Desafio08\Exceptions\TopicoInvalidoException;
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
s
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

    }
}
// - **Ordenação**: ordene os handlers encontrados por `getPrioridade()` (crescente).
// - **Execução com Retry**: para cada handler, dentro de um bloco `try / catch (EventoFalhouException $e)`:
//   - No `try`:
//     - Atualize o status do evento para `PROCESSANDO`.
//     - Execute `$handler->processar($evento)` e chame `incrementarHandlerExecutado()`.
//     - Atualize o status para `PROCESSADO` e marque a mensagem do Outbox como `'ENVIADO'`.
//   - No `catch`:
//     - Chame `incrementarHandlerFalhou()`.
//     - Chame `$evento->incrementarTentativa()`.
//     - Imprima o aviso: `"[ERRO] Handler '{nome}' falhou para o evento {id}: {mensagem} (Tentativa {tentativa}/{maxTentativas})"`.
//     - Se `$evento->isMaxTentativasExcedidas($this->maxTentativas)`:
//       - Defina o status do evento como `FALHOU`.
//       - Marque a mensagem do Outbox como `'FALHOU'`.
//       - Adicione o evento na DLQ (`adicionarDeadLetter($evento)`) e chame `incrementarDeadLetter()`.
//       - Imprima o aviso: `"[DLQ] Evento {id} excedeu {maxTentativas} tentativas e foi enviado para a Dead Letter Queue."`.
//     - Senão:
//       - Imprima `"[RETRY] Evento {id} será reprocessado nas próximas tentativas..."` e chame `incrementarReenfileirado()`.
