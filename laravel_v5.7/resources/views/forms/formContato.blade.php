@extends('./layout.app',["current"=>"cadastrar"])

@section('body')

		<div class="col-ms-6">
			<div class="card border">
				<div class="card-body">
					<h5>Cadastro de Contato</h5>
					<form method="POST" action="/contato" id="formContato">
						@csrf
						<label for="">Nome</label>
						<input class="form-control" type="text" id="nome" name="nome" placeholder="Nome do contato">

						<label for="emailContato">Email</label>
						<input class="form-control" type="email" id="email" name="email" placeholder="Email">

						<label for="ageContato">Idade</label>
						<input class="form-control" type="number" id="idade" name="idade" placeholder="Idade">

						<label for="telContato">Telefone</label>
						<input class="form-control" type="text" id="telefone" name="telefone" placeholder="(00)0 0000-0000">
 
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
						<button type="cancel" class="btn btn-danger btn-sm">Cancelar</button>
					</form>

				</div>

			</div>

		</div>	
@endsection

@section('jquery')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
	<script type="text/javascript">
			
		$(document).ready(function() {    

			$("#formContato").submit(function() {
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