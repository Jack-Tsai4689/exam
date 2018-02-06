<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="keywords" content="政龍文教-金牌教師線上測驗系統" />
	<meta name="description" content="專門的補習班管理系統公司，金牌教師是線上測驗系統平台，老師自行上題目，學生登入考式，即測即評。老師線上解題，學生就在雲端上課；切片檢查系統，診斷學生答題分析；為學生量身訂做，針對個別缺點再加強。" />
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
		margin-bottom: -15px;
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
	<title>政龍文教-金牌教師線上測驗系統</title>
</head>
<body>
<div id="top"><img src="{{ URL::asset('img/logo.png')}}" style="height:50px;margin-left:50px;"></div>
<div id="all">
	<div id="img_bg">
		<img src="{{ URL::asset('img/logo.png')}}" style="height:100px;">
	</div>
	<form name="form1" id="form1" method="post" action="{{ url('login') }}" onSubmit="return check()">
	<div id="content">
		<div style="padding-top:20px;padding-bottom:5px;"><font class="f15">代碼</font><input type="text" class="input_field keyin" tabindex="1" name="code" id="code" value="1001"></div>
		<div style="padding-bottom:5px;"><font class="f15">帳號</font><input type="text" class="input_field keyin" tabindex="2" name="accname" id="accname" value=""></div>
		<div style="padding-bottom:5px;"><font class="f15">密碼</font><input type="password" class="input_field keyin" name="pwd" id="pwd" value="1"></div>
		<div style="padding-bottom:10px;"><label><input type="checkbox" name="remember" id="remember"><font class="f15" style="margin-left:5px;">記住我的帳號</font></label></div>
		<div style="padding-bottom:10px;">
			<center>
				<div id="tp"><label><input type="radio" name="identity" id="identity_1" value="T">老師</label>
				<label><input type="radio" name="identity" id="identity_2" value="S">學生</label>
				</div>
				<label><font color="red"></font></label>
			</center>
		</div>
		<div class="btn_di" style="padding-bottom:10px;"><input type="submit" name="sure" class="btn f16" id="sure" value="登入"></div>
		<div style="text-align:center;padding-bottom:5px;"><a href="forgot.php" class="f13">忘記帳號或密碼</a></div>
		<div class="btn_di" style="padding-bottom:25px;"><input type="button" name="register" class="btn f16" id="register" value="我要註冊"  onclick="location.href='register1.html'"></div>
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
		if (gb('accname').value==''){
			gb('accname').focus();
		}else{
			gb('pwd').focus();
		}
		if (gb('remember').checked){
			document.cookie='id='+document.form1.accname.value;
		}else{document.cookie='id=';}
		if (gb('code').checked){
			document.cookie='ucode='+document.form1.ucode.value;
		}else{document.cookie='ucode=';}
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