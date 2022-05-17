@extends('./layout.app',["current"=>"cadastrar"])

@section('body')
		<div class="col-ms-6">
			<div class="card border">
				<div class="card-body">
					<h5>Cadastro de Contato</h5>
					<form method="POST" action="/contato/editar/{{$contact->id}}" id="formContatoEdit">
						@csrf
						<label for="">Nome</label>
						<input class="form-control" type="text" id="nome" name="nome" value="{{$contact->nome}}">

						<label for="emailContato">Email</label>
						<input class="form-control" type="email" id="email" name="email" value="{{$contact->email}}">

						<label for="ageContato">Idade</label>
						<input class="form-control" type="number" id="idade" name="idade" value="{{$contact->idade}}">

						<label for="telContato">Telefone</label>
						<input class="form-control" type="text" id="telefone" name="telefone" value="{{$contact->telefone}}">			

				</div>
				@if($errors->any())
					@foreach($errors->all() as $error)
						<div class="alert alert-danger" role="alert">
							{{$error}}
						</div>
					@endforeach
				@endif
				<div class="card-footer">
						<button type="submit" class="btn btn-primary btn-sm">Salvar</button>
						<a href="/contato" class="btn btn-danger btn-sm">Cancelar</a>
					</form>
				</div>
			</div>

		</div>	
@endsection
@section('jquery')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
	<script type="text/javascript">

		$(document).ready(function() {    

			$("#formContatoEdit").submit(function() {
				let idade = $("#idade").val();
				if(idade>120){
					alert('Idade não permitida - deve ser menor ou igual que 120 anos');
					return false;
				}				
			});

		    $("#telefone").mask('(00)00000-0000', {reverse: true});
			$("#idade").mask('00#', {reverse: true});

    	});

	</script>		
@endsection