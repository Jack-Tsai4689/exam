<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="keywords" content="線上測驗系統" />
	<meta name="description" content="線上測驗系統平台，老師自行上題目，即測即評。診斷學生答題分析。" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}">
	<noscript>
		<meta http-equiv="Refresh" content="0;URL=noscript.html" />
	</noscript>
	<script type="text/javascript">
	if (navigator.userAgent.search('Mozilla/4.0')==0){
		location.href = 'ie.html';
	}
	</script>
	<style type="text/css">
	#all {
		margin: 0 auto;
	}
	#img_bg {
		margin-top: 7%;
		text-align: center;
		/*margin-bottom: -15px;*/
		font-size: 40px;
		font-weight: bolder;
	}
	#content {
		width: 370px;
		margin: 25px auto;
		background-color: white;
		border-bottom: 2px #B4B5B5 solid;
		border-left: 2px #B4B5B5 solid;
		border-right: 2px #B4B5B5 solid;
	}
	#content div{
		padding: 0px 20px 0px 20px;
	}
	#remember {
		height: 15px;
	}
	#ucode, .keyin, #sure, #register {
		height: 30px;
	}
	.keyin {
		width: 275px;
		font-size: 15px;
	}
	#ucode {
		width: 250px;
	}
	.btn_di {
		text-align: center;
	}
	#sure, #register {
		width: 150px;
	}
	#codegroup div{
		float: left;
		padding: 0px;
	}
	#codegroup {
		float: left;
	}
	.f13 {
		height: 15px;
	}
	</style>
	<title>線上測驗系統</title>
</head>
<body>
<div id="top"><div style="font-size: 20px; margin-left: 20px;">線上測驗系統</div></div>
<div id="all">
	<div id="img_bg">線上測驗系統</div>
	<form name="form1" id="form1" method="post" action="{{ url('login') }}" onSubmit="return check()">
	<div id="content">
		<div style="padding-top:20px;padding-bottom:5px;"><font class="f15">代碼</font><input type="text" class="input_field keyin" tabindex="1" name="code" id="code" value=""></div>
		<div style="padding-bottom:5px;"><font class="f15">帳號</font><input type="text" class="input_field keyin" tabindex="2" name="accname" id="accname" value="{{ $accname }}"></div>
		<div style="padding-bottom:5px;"><font class="f15">密碼</font><input type="password" class="input_field keyin" name="pwd" id="pwd" value=""></div>
		<div style="padding-bottom:10px;"><label><input type="checkbox" name="remember" id="remember" {{ $rem_chk }}><font class="f15" style="margin-left:5px;">記住我的帳號</font></label></div>
		<div style="padding-bottom:10px;">
			<center>
				<div id="tp"><label><input type="radio" name="identity" id="identity_1" value="T">老師</label>
				<label><input type="radio" name="identity" id="identity_2" value="S">學生</label>
				</div>
				<label><font color="red" id="msg">@if(session()->has('msg')) {{ session()->get('msg') }} @endif</font></label>
			</center>
		</div>
		<div class="btn_di" style="padding-bottom:10px;"><input type="submit" name="sure" class="btn f16" id="sure" value="登入"></div>
		<div style="text-align:center;padding-bottom:5px;"><a href="#" class="f13">忘記帳號或密碼</a></div>
		<div class="btn_di" style="padding-bottom:25px;"><input type="button" name="register" class="btn f16" id="register" value="我要註冊"></div>
	</div>
	</form>
</div>
</body>
</html>
<script type="text/javascript">
	gb('code').focus();
	function gb(v){
		return document.getElementById(v);
	}
	function check(){
		if (!gb('identity_1').checked && !gb('identity_2').checked){
			gb('msg').innerHTML = '請確認身份';
			return false;
		}
		if (gb('accname').value==''){
			gb('accname').focus();
		}else{
			gb('pwd').focus();
		}
		if (gb('accname').value==''){
			gb('accname').focus();
			return false;
		}
		if (gb('pwd').value==''){
			gb('pwd').focus();
			return false;
		}
	}
</script>