//nodejs 實現 curl post
var request = require('request');

	request.post(
		{
			url: "http://localhost:8080/gold/main/test",
			form:{
				user:'asdf',
				www:'acv'
			},
			encoding:'utf8'
		},
		function(err, res, body){
			if (res.statusCode ==200){
				console.log(body);
			}else{
				console.log(res.data);
			}
		}
	);