var io = require('socket.io-client');
var notification = io.connect('http://localhost:3000');
notification.on('notification', function(msg){
	var d = document.getElementById('rs');
	d.innerHTML += msg+'<br>';
});