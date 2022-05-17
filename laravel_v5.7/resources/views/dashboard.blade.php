@extends('layout.app',["current"=>"relatorios"])

@section('body')
	<div class="card border">
		<div class="card-body">
			<h5>Dashboard Adminitrativo</h5>
			<table class="table table-hover">				
				<tbody>					
					<tr>
						<td>Total de contatos da agenda</td>	
						<td>{{$conts}}</td>							
					</tr>

					<tr>
						<td>Contato mais antigo cadastrado</td>	
						<td>{{$first->nome}}</td>							
					</tr>

					<tr>
						<td>Ultimo contato cadastrado na agenda</td>	
						<td>{{$last->nome}}</td>							
					</tr>					
				</tbody>
			</table>

		</div>		

	</div>
@endsection
	
@section('jquery')
	<script type="text/javascript">		
		
		$(function(){
									
		})
	</script>
@endsection