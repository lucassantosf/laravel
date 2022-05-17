<nav class="navbar navbar-expand-lg navbar-dark bg-primary rounded">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbar">
    <ul class="navbar-nav mr-auto">
      <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
        <a class="nav-link" href="/">Contatos</a>
      </li>

      <li @if($current=="cadastrar") class="nav-item active" @else class="nav-item" @endif>
        <a class="nav-link" href="/contato">Cadastros</span></a>
      </li>

      <li @if($current=="relatorios") class="nav-item active" @else class="nav-item" @endif>
        <a class="nav-link" href="/dashboard">Relatórios</span></a>
      </li>

    </ul>

  </div>
</nav>