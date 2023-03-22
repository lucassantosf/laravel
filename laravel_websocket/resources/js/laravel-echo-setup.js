import Echo from 'laravel-echo';

var host = document.currentScript.getAttribute('host');




window.Echo = new Echo({
	broadcaster: 'socket.io',
	host: "http://localhost:6001"
	// auth: {
	//   headers:
	// 	{
	// 	  'Authorization': 'Bearer ' + JSON.parse(window.localStorage.getItem('jwt')),
	// 	  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
	// 	}
	// }
});

console.log('socket conectado com sucesso!!!');

/*
Tutorial de base:
https://raviyatechnical.medium.com/laravel-advance-laravel-broadcast-redis-socket-io-tutorial-4c7fa3b94101

*/
