# 🚀 Desafio 06: Laravel Debugging & Architecture Challenge

Bem-vindo ao **Desafio 06**! Este é um teste prático focado em resolução de problemas reais do ecossistema **Laravel**.
O projeto contém uma API de Blog/Posts com **10 falhas intencionais de arquitetura, validação, migrations, relacionamentos e componentes Laravel**.

Sua missão é investigar cada um dos pontos abaixo e corrigi-los para que a aplicação execute 100% sem erros!

---

### 📋 Lista de Afazeres (Ordenada pelo Fluxo Lógico de Desenvolvimento)

#### [*OK*] 1. 🗑️ SoftDeletes na Migration de `posts`
- O controller tenta realizar a exclusão suave (`destroy()`), mas ocorre um erro de SQL informando coluna inexistente (`deleted_at`).
- **Objetivo**: Garantir que a estrutura da tabela `posts` suporte o recurso de `SoftDeletes` (`$table->softDeletes()`).

#### [*OK*] 2. 🗄️ Relacionamento da Tabela `comments` (Migration)
- Ao tentar cadastrar ou relacionar um comentário no banco de dados, o banco de dados lança um erro informando que a tabela de relacionamento `tbl_posts` não existe.
- **Objetivo**: Corrigir a referência da chave estrangeira no arquivo de migration da tabela `comments`.

#### [*] 3. 🚦 FormRequest de Validação (`StorePostRequest`)
- A requisição `POST /api/posts` retorna `403 Forbidden` imediatamente. Além disso, ao tentar validar se o título é único, ocorre um erro no banco de dados.
- **Objetivo**: Corrigir a autorização no método `authorize()` do `StorePostRequest` e ajustar a regra de validação `unique` para validar a coluna correta (`title`).

#### [*] 4. 🛡️ Mass Assignment no Model `Post`
- Ao tentar cadastrar um novo post contendo `is_published` ou `user_id`, esses campos não são gravados no banco de dados.
- **Objetivo**: Atualizar a propriedade `$fillable` do model `Post` para permitir a atribuição em massa dessas colunas.

#### [OK] 5. 🔍 Scope Local `scopePublished`
- A listagem de posts no `PostController::index` utiliza o scope `published()`, porém a requisição resulta em um erro de coluna inexistente na consulta SQL.
- **Objetivo**: Ajustar a instrução SQL dentro do método `scopePublished` na classe `Post` para usar o nome correto da coluna no banco (`is_published`).

#### [*] 6. 🔗 Divergência de Route Model Binding (`routes/api.php`)
- A rota `GET /api/posts/{id}` não injeta o objeto `Post` no controller `show(Post $post)`.
- **Objetivo**: Corrigir o nome do parâmetro da rota no `routes/api.php` para que o Laravel faça o *Implicit Route Model Binding* corretamente (`{post}`).

#### [*] 7. 🎁 Transformação de Dados via API Resource (`PostResource`)
- Ao visualizar os detalhes de um post via `GET /api/posts/{id}`, a API lança a exceção `Property [author] does not exist on this model instance`.
- **Objetivo**: Ajustar o `PostResource` para utilizar o nome correto do relacionamento configurado no Model (`user`).

#### [*] 8. 🔐 Permissões e Autorização (`PostPolicy`)
- Ao tentar atualizar um post via `PUT /api/posts/{id}`, o método `update` no controller invoca `$this->authorize('update', $post)` e retorna erro 403 de permissão mesmo para o dono do post.
- **Objetivo**: Corrigir o atributo verificado na `PostPolicy` para validar a chave do usuário proprietário corretamente (`user_id`).

#### [*] 9. 📢 Registro de Observers (`PostObserver`)
- Foi criado o Observer `PostObserver` para disparar um Job quando um novo post é criado, porém o evento `created` nunca é executado.
- **Objetivo**: Registrar o `PostObserver` no ciclo de vida do Laravel (`AppServiceProvider`).

#### [*] 10. ⚡ Despacho de Queued Job (`ProcessPostSlugJob`)
- O Job `ProcessPostSlugJob` é disparado pelo Observer, porém ocorre uma falha de serialização da instância do Model ao ser enviado para a fila.
- **Objetivo**: Incluir a Trait apropriada do Laravel (`SerializesModels`) na classe do Job para permitir a serialização dos dados.

---

### 🧪 Como testar a aplicação:

1. Acesse o diretório do desafio no terminal:
   ```bash
   cd desafios/desafio_06
   ```
2. Inicie o servidor dev do Laravel:
   ```bash
   php artisan serve
   ```
3. Siga a ordem dos itens de 1 a 10 no `README.md` para uma experiência de desenvolvimento perfeita!
