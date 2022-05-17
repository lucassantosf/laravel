@extends('./layout.app',["current"=>"cadastrar"])

@section('body')
		<div class="card border">
		<div class="card-body">
			<h5 class="card-title">Cadastro de Contatos</h5>
@if(count($conts) > 0)
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Id</th>
						<th>Nome</th>
						<th>Email</th>
						<th>Idade</th>
						<th>Telefone</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($conts as $c)
					<tr>
						<td>{{$c->id}}</td>	
						<td>{{$c->nome}}</td>	
						<td>{{$c->email}}</td>	
						<td>{{$c->idade}}</td>	
						<td>{{$c->telefone}}</td>	
						<td>
							<a href="/contato/editar/{{$c->id}}" class="btn btn-sm btn-primary">Editar</a>
							<a href="/contato/remover/{{$c->id}}" class="btn btn-sm btn-danger">Apagar</a>
						</td>	
					</tr>
					@endforeach
				</tbody>
			</table>
@endif
		</div>
		<div class="card-footer">
			<a href="/cadastrar" class="btn btn-sm btn-primary">Cadastrar</a>
		</div>
	</div>
@endsection