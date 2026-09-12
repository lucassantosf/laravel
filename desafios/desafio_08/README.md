# Desafio 08: Barramento de Eventos & Padrão Outbox (Event-Driven Architecture)

## Contexto

Você precisa construir um **Barramento de Eventos (Event Bus)** inspirado em arquiteturas
orientadas a eventos (Event-Driven Architecture). O sistema publica eventos em **tópicos**
(ex: `order.created`, `order.paid`, `user.registered`), roteia cada evento para os
**handlers (subscribers)** que se declararam interessados, suportando **wildcards**
(`order.*` — casa com qualquer sub-tópico de `order`) e **prioridades de execução**.

Para garantir uma entrega confiável (sem perda de eventos), o sistema persiste todo evento
publicado em um **Outbox** antes do roteamento (padrão Transactional Outbox). Handlers que
falham passam por um mecanismo de **Retry** e, após estourar o limite, são enviados para uma
**Dead Letter Queue (DLQ)**. O barramento ainda expõe **métricas** e um relatório de auditoria
do ciclo de vida de cada evento.

## Como Executar

```bash
cd desafios/desafio_08
php run.php
```

## Passo a Passo (Siga Nesta Ordem)

---

### [OK] Passo 1: As Exceções Customizadas
Crie a pasta `src/Exceptions/` com 3 classes estendendo `\Exception`:
- `TopicoInvalidoException`: lançada ao publicar um evento com tópico vazio ou inválido.
- `SemHandlerException`: lançada quando nenhum handler está registrado para o tópico do evento.
- `EventoFalhouException`: lançada quando um handler não consegue processar o evento.

---

### [OK] Passo 2: Os Enums de Prioridade e Status
Crie os Enums (PHP 8.1+) na pasta `src/Enums/`:
- `PrioridadeEvento.php`: casos `ALTA` (valor 1), `MEDIA` (valor 2), `BAIXA` (valor 3).
  Adicione o método `public function obterOrdem(): int` (retorna o valor do caso).
- `StatusEvento.php`: casos `PUBLICADO`, `PROCESSANDO`, `PROCESSADO`, `FALHOU`, `DESCARTADO`.

---

### [OK] Passo 3: O DTO do Evento (Evento)
Crie a classe `Evento` em `src/Evento.php`:
- Propriedades privadas:
  - `string $id`
  - `string $topico`
  - `array $payload`
  - `PrioridadeEvento $prioridade`
  - `float $criadoEm` (inicia com `microtime(true)`)
  - `int $tentativas` (inicia em `0`)
  - `StatusEvento $status` (inicia com `StatusEvento::PUBLICADO`)
- Construtor recebendo `id`, `topico`, `payload` e `prioridade`.
- Getters para todas as propriedades e Setters para `status` e `payload`.
- Métodos utilitários:
  - `incrementarTentativa(): void` (soma 1 em `$tentativas`).
  - `isMaxTentativasExcedidas(int $max): bool` (retorna `true` se `$tentativas >= $max`).

---

### [OK] Passo 4: A Interface do Handler (HandlerEventoInterface)
Crie a interface `HandlerEventoInterface` em `src/Interfaces/HandlerEventoInterface.php`:
- `public function getNome(): string;`
- `public function getTopicoDeInteresse(): string;` (aceita wildcard `*`, ex: `order.*`)
- `public function getPrioridade(): int;` (quanto MENOR o valor, MAIS CEDO executa)
- `public function processar(Evento $evento): bool;`

---

### [OK] Passo 5: Implementação dos Handlers Concretos
Crie a pasta `src/Handlers/` com 4 classes implementando `HandlerEventoInterface`:

- `AtualizarEstoqueHandler.php`:
  - `getNome()`: `"Atualizar Estoque"`.
  - `getTopicoDeInteresse()`: `"order.created"`.
  - `getPrioridade()`: `1`.
  - `processar()`: se o payload contiver `'simular_falha'` igual a `true`, lança
    `EventoFalhouException("Estoque indisponível para o produto " . $payload['produto'])`.
    Imprime `"[HANDLER OK] Estoque reservado para o produto {produto}."` e retorna `true`.

- `NotificarClienteHandler.php`:
  - `getNome()`: `"Notificar Cliente"`.
  - `getTopicoDeInteresse()`: `"order.created"`.
  - `getPrioridade()`: `2`.
  - `processar()`: se o payload não tiver a chave `email` (ou for vazio), lança
    `EventoFalhouException("Cliente sem e-mail válido.")`. Imprime
    `"[HANDLER OK] E-mail de confirmação enviado para {email}."` e retorna `true`.

- `RegistrarFinanceiroHandler.php`:
  - `getNome()`: `"Registrar Financeiro"`.
  - `getTopicoDeInteresse()`: `"order.paid"`.
  - `getPrioridade()`: `1`.
  - `processar()`: se o valor do payload (`'valor'`) for menor que `1.0`, lança
    `EventoFalhouException("Pagamento sem valor mínimo para conciliação.")`. Imprime
    `"[HANDLER OK] Pagamento de R$ {valor} conciliado no financeiro."` e retorna `true`.

- `AuditoriaGeralHandler.php` (usa wildcard):
  - `getNome()`: `"Auditoria Geral"`.
  - `getTopicoDeInteresse()`: `"order.*"`.
  - `getPrioridade()`: `9` (executa por último).
  - `processar()`: armazena o evento num array estático `$historico` e imprime
    `"[AUDITORIA] Evento {id} do tópico {topico} registrado. Total: {n}."` e retorna `true`.
  - Adicione o método estático `getHistorico(): array`.

---

### [*] Passo 6: O Padrão Outbox (OutboxMessage e OutboxStore)
Crie a pasta `src/Outbox/`:

- `OutboxMessage.php`:
  - Propriedades privadas: `Evento $evento`, `string $estado` (inicia com `'PENDENTE'`).
  - Getter para o `$evento` e getter/setter para `$estado`.

- `OutboxStore.php`:
  - Propriedade privada: `array $mensagens = []`.
  - Método `adicionar(OutboxMessage $mensagem): void`.
  - Método `marcarComoEnviado(string $eventoId): void` (altera estado para `'ENVIADO'`).
  - Método `marcarComoFalhou(string $eventoId): void` (altera estado para `'FALHOU'`).
  - Método `getPendentes(): array` (retorna apenas as mensagens `'PENDENTE'`).
  - Método `getTodas(): array` (retorna todas as mensagens).

---

### [*] Passo 7: A Trait de Métricas (MetricasEventBusTrait)
Crie a trait `MetricasEventBusTrait` em `src/Traits/MetricasEventBusTrait.php`:
- Propriedades privadas:
  - `int $eventosPublicados = 0`
  - `int $handlersExecutados = 0`
  - `int $handlersFalharam = 0`
  - `int $eventosReenfileirados = 0`
  - `int $eventosDeadLetter = 0`
  - `array $deadLetterQueue = []`
- Métodos incrementadores: `incrementarPublicado()`, `incrementarHandlerExecutado()`,
  `incrementarHandlerFalhou()`, `incrementarReenfileirado()`, `incrementarDeadLetter()`.
- Método `adicionarDeadLetter(Evento $evento): void`.
- Método `getDeadLetterQueue(): array`.
- Método `getResumoMetricas(): array` (retorna o resumo acumulado).

---

### [*] Passo 8: O Barramento de Eventos — Estrutura (EventBus)
Crie a classe `EventBus` em `src/EventBus.php` (namespace `Desafio08`) utilizando a
`MetricasEventBusTrait`:
- Propriedades privadas:
  - `array $handlers = []`
  - `OutboxStore $outbox`
  - `int $maxTentativas` (default: `3`)
- Construtor recebendo `OutboxStore` e `maxTentativas`.
- Método `registrarHandler(HandlerEventoInterface $handler): void` (adiciona no array `$handlers`).
- Método privado `matchTopico(string $topicoEvento, string $topicoInteresse): bool`:
  - Se `$topicoInteresse` terminar com `.*`, compara o prefixo antes de `.*` com o prefixo
    do tópico do evento (ex: `order.*` casa com `order.created`, `order.paid`, mas NÃO com `user.registered`).
  - Caso contrário, faz comparação exata.

---

### [*] Passo 9: O Algoritmo de Publicação com Outbox, Retry e DLQ (EventBus)
Em `EventBus.php`, crie o método `publicar(Evento $evento): void`:
- **Validação**: se `trim($evento->getTopico())` estiver vazio, lance `TopicoInvalidoException("O tópico do evento não pode ser vazio.")`.
- **Persistência no Outbox**: adicione o evento no Outbox com estado `'PENDENTE'` e chame `incrementarPublicado()`.
- **Resolução de handlers**: filtre os `$handlers` cujo `matchTopico()` retorne `true`.
  - Se nenhum handler foi encontrado: marque a mensagem do Outbox como `'FALHOU'`, defina o status do evento como `DESCARTADO`, imprima `"[OUTBOX] Nenhum handler para o tópico {topico}. Evento descartado."` e lance `SemHandlerException("Nenhum handler registrado para o tópico " . $evento->getTopico())`.
- **Ordenação**: ordene os handlers encontrados por `getPrioridade()` (crescente).
- **Execução com Retry**: para cada handler, dentro de um bloco `try / catch (EventoFalhouException $e)`:
  - No `try`:
    - Atualize o status do evento para `PROCESSANDO`.
    - Execute `$handler->processar($evento)` e chame `incrementarHandlerExecutado()`.
    - Atualize o status para `PROCESSADO` e marque a mensagem do Outbox como `'ENVIADO'`.
  - No `catch`:
    - Chame `incrementarHandlerFalhou()`.
    - Chame `$evento->incrementarTentativa()`.
    - Imprima o aviso: `"[ERRO] Handler '{nome}' falhou para o evento {id}: {mensagem} (Tentativa {tentativa}/{maxTentativas})"`.
    - Se `$evento->isMaxTentativasExcedidas($this->maxTentativas)`:
      - Defina o status do evento como `FALHOU`.
      - Marque a mensagem do Outbox como `'FALHOU'`.
      - Adicione o evento na DLQ (`adicionarDeadLetter($evento)`) e chame `incrementarDeadLetter()`.
      - Imprima o aviso: `"[DLQ] Evento {id} excedeu {maxTentativas} tentativas e foi enviado para a Dead Letter Queue."`.
    - Senão:
      - Imprima `"[RETRY] Evento {id} será reprocessado nas próximas tentativas..."` e chame `incrementarReenfileirado()`.

---

### [*] Passo 10: Script Principal e Relatório (run.php)
Crie o arquivo `run.php` com autoloader PSR-4 para o namespace `Desafio08` (padrão dos desafios anteriores):

- Instancie o `OutboxStore` e o `EventBus` com `maxTentativas = 3`, e registre os 4 handlers
  (`AtualizarEstoqueHandler`, `NotificarClienteHandler`, `RegistrarFinanceiroHandler`, `AuditoriaGeralHandler`).

  --- **CENÁRIO 1: EVENTO COM MÚLTIPLOS HANDLERS E WILDCARD** ---
  - Publique um evento `order.created` (Prioridade `ALTA`) com payload:
    `['produto' => 'Notebook', 'email' => 'cliente@test.com', 'valor' => 350.00]`.
  - Esperado: `AtualizarEstoqueHandler` (prio 1) executa primeiro, depois `NotificarClienteHandler` (prio 2),
    e por fim `AuditoriaGeralHandler` (wildcard `order.*`, prio 9).
  - Imprima o conteúdo do `AuditoriaGeralHandler::getHistorico()`.

  --- **CENÁRIO 2: ROTEAMENTO DIRECIONADO** ---
  - Publique um evento `order.paid` (Prioridade `MEDIA`) com payload `['valor' => 250.00]`.
  - Esperado: `RegistrarFinanceiroHandler` e `AuditoriaGeralHandler` (wildcard) executam;
    os handlers de `order.created` NÃO são acionados.

  --- **CENÁRIO 3: FALHA, RETRY E DEAD LETTER QUEUE** ---
  - Publique um evento `order.created` com `'simular_falha' => true` no payload.
  - Esperado: `AtualizarEstoqueHandler` falha nas 3 tentativas e o evento
    vai para a DLQ. Os demais handlers ainda devem processar o evento.
  - Imprima o conteúdo da DLQ (`getDeadLetterQueue()`).

  --- **CENÁRIO 4: TÓPICO SEM HANDLER** ---
  - Publique um evento `user.registered` (Prioridade `BAIXA`) com um payload genérico
    e capture a `SemHandlerException` num bloco `try / catch`, imprimindo a mensagem de erro.

- Ao final, imprima o relatório final:
  - `getResumoMetricas()` (eventos publicados, handlers executados/falharam, reenfileirados, DLQ).
  - `$outboxStore->getTodas()` (comprovar o ciclo de vida `PENDENTE` → `ENVIADO`/`FALHOU`).

## Regras

- O arquivo `run.php` ativa `declare(strict_types=1);`.
- Os nomes de classes, métodos, mensagens e prints devem seguir exatamente o enunciado.
- Explore por conta própria: propriedades `protected`/`private`, `matchTopico` com wildcard e a ordem LIFO/FIFO da DLQ.
- Mensagem final esperada no terminal: `>>> Script finalizado com sucesso! <<<`