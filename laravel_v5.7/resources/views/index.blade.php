@extends('layout.app',["current"=>"home"])

@section('body')
	
	<div class="row">
		
		<div class="col-md-12">
			<div class="card border">
				<div class="card-body">
					<h5 class="card-title">Listagem</h5>
					@if(count($conts) > 0)
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Nome</th>
									<th>Telefone</th>
									<th>Ações</th>
								</tr>
							</thead>
							<tbody>
								@foreach($conts as $c)
								<tr>
									<td>{{$c->nome}}</td>	
									<td>{{$c->telefone}}</td>	
									<td>
										<button class="btn btn-sm btn-primary" role="button" onclick="detalhesContato({{$c->id}})">Detalhes</a>
									</td>	
								</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>			
			</div>
		</div>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="dlgContatos">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Detalhes do contato </h5>
				</div>
				<div class="modal-body">
					<label class="control-label">Id:<span id="codContato"></span></label><br>
					<label class="control-label">Nome:<span id="nomeContato"></span></label><br>
					<label class="control-label">Telefone:<span id="telefoneContato"></span></label><br>
					<label class="control-label">Email:<span id="emailContato"></span></label><br>
					<label class="control-label">Idade:<span id="ageContato"></span>anos</label><br>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('jquery')
	<script type="text/javascript">
		
		let i = 0;

		function detalhesContato(id){
			$('#dlgContatos').modal('show');
			carregarContato(id);
		}

		function carregarContato(id){
			$.getJSON('/api/contato/'+id, function(data){
				if(i>0) {
					limparDetalhesContato();
				}
				$('#codContato').append(data.id);
				$('#nomeContato').append(data.nome);
				$('#telefoneContato').append(data.telefone);
				$('#emailContato').append(data.email);
				$('#ageContato').append(data.idade);
				i++;
			});
		}

		function limparDetalhesContato(){
			$('#codContato').html('');
			$('#nomeContato').html('');
			$('#telefoneContato').html('');
			$('#emailContato').html('');
			$('#ageContato').html('');
		}

		$(function(){
			//sempre executar	
						
		})
	</script>
@endsection