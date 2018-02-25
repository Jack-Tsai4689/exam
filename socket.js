var app = require('express');
var request = require('request');
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis(6379);

// redis.subscribe('notification', function(err, count){
// 	console.log('connect');
// });
// redis.on('message', function(channel, notification){
// 	//console.log(notification);
// 	notification = JSON.parse(notification);
// 	user = notification.data.token;
// 	//io.emit('notification', notification.data.message);
// });
function getCookie(cookie, name){
	var arr = cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arr != null) return unescape(arr[2]);	
}

io.sockets.on('connection', function (socket) {
	socket.on('exam', function(token){
		var cio = getCookie(socket.handshake.headers.cookie, 'io');
		redis.set(cio, token);
		redis.set(token, cio);
	});
	socket.on('disconnect', function() {
		//nodejs 實現 curl post
		var cio = getCookie(socket.handshake.headers.cookie, 'io');
		var d = new Date();
		redis.get(cio).then(function(rs){
			request.post(
				{
					url: "http://localhost:8081/gold/public/exam/quit",
					form:{
						token: rs,
						time: d.getTime()
					},
					encoding:'utf8'
				},
				function(err, res, body){
					// if (res.statusCode ==200){
					// 	console.log(body);
					// }else{
					// 	console.log(res.data);
					// }
				}
			);
		});
	});
});
http.listen(3000, function(){
	console.log('ok');
});