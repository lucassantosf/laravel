import Echo from 'laravel-echo';

var host = document.currentScript.getAttribute('host');

console.log('socket conectado com sucesso!!!');


window.Echo = new Echo({
	broadcaster: 'socket.io',
	host: host,
	auth: {
	  headers:
		{
		  'Authorization': 'Bearer ' + JSON.parse(window.localStorage.getItem('jwt')),
		  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		}
	}
});


/*
Tutorial de base:
https://raviyatechnical.medium.com/laravel-advance-laravel-broadcast-redis-socket-io-tutorial-4c7fa3b94101

*/
