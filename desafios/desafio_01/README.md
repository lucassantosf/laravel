# Desafio 02: Pipeline de Pagamentos & Audit Logger 💳

## Contexto
Você assumiu a manutenção de um microserviço de pagamentos que processa lotes (batches) de transações através de uma Pipeline de Taxas, integrando com um Gateway externo e registrando logs de auditoria num serviço de auditoria (`AuditLogger`).

Ao executar o script de processamento (`run.php`), o sistema falha em múltiplos pontos: erros fatais de exceção, erros de tipagem estrita e vazamento de estado entre lotes de transações.

## Como Executar
No seu terminal, navegue até este diretório ou execute a partir da raiz:

```bash
cd desafios/desafio_02
php run.php
```

## Seu Objetivo
1. **Tratamento de Exceções**: Resolver o erro fatal lançado ao tentar simular uma falha de conexão no Gateway.
2. **Pipeline de Taxas (Functional & Strict Types)**: Corrigir o processamento de estágios na pipeline de taxas para que respeite o `strict_types=1` e retorne os valores calculados.
3. **Isolamento de Estado (Static Memory Leak)**: Garantir que cada lote (`batch`) processado possua seus logs de auditoria isolados, sem acumular resíduos de lotes anteriores.

## Regras
- O arquivo `run.php` ativa `declare(strict_types=1);`.
- Você pode editar qualquer arquivo da pasta `src/` ou `run.php`.
- A mensagem final deve ser: `🎉 PARABÉNS! Você superou o Desafio 02 com maestria!`.
