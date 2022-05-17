<!DOCTYPE html>
<html>
<head>
	<title>Agenda de contatos</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
	<meta name="csrf-token" content="{{csrf_token()}}" charset="utf-8">

	
</head>
<body>

	<div class="container">
		
		@component('component_navbar',["current"=>$current])
			
		@endcomponent

		<div class="main">
			@hasSection('body')
				@yield('body')
			@endif
		</div>
	</div>

	<script type="text/javascript" src="{{asset('js/app.js')}}"></script>

	@hasSection('jquery')
		@yield('jquery')
	@endif
</body>
</html>