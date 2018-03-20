<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	<title>{{ $title }}  線上測驗</title>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta http-equiv="X-UA-Compatible" content="IE=11; IE=10; IE=9; IE=8; IE=7" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/main.css') }}">
	@yield('style')
</head>
<body>
<div id="top"><a href="{{ url('/') }}" title="回首頁"><img src="{{ URL::asset('img/logo.png') }}" style="height:50px;margin-left:50px;"></a>
	<div id="menu">
		<ul id="navigation">
			@if (session('ident')==="T")
			<li><a href="{{ url('basic') }}" style="text-align:center; width:85px;">基本設定</a></li>
			<li>
				<a href="javascript:void(0)">出題系統</a>
				<ul>
					<li><div class="ex"><a href="{{ url('sets') }}">試卷庫</a></div></li>
					<li><div class="ex"><a href="{{ url('pub') }}">發佈記錄</a></div></li>
					<li><div class="ex"><a href="{{ url('ques') }}">我的題庫</a></div></li>
					<li><div class="ex"><a href="{{ url('know') }}">知識點管理</a></div></li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0)">成績系統</a>
				<ul>
					{{-- 選班級、考卷(下拉式)，列出每個人成績 --}}
					<li><div class="ex"><a href="{{ url('/score') }}">班級查詢</a></div></li>
					{{-- 以學號查詢 --}}
					<li><div class="ex"><a href="">個人查詢</a></div></li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0)">診斷系統</a>
				<ul>
					{{-- 選班級、考卷(下拉式)，列出每個人。或看整體分析 --}}
					<li><div class="ex"><a href="">班級查詢</a></div></li>
					{{-- 以學號查詢 --}}
					<li><div class="ex"><a href="">個人查詢</a></div></li>
				</ul>
			</li>
			@endif
			@if (session('ident')==="S")
			<li><a href="{{ url('/') }}">測驗</a></li>
			<li><a href="{{ url('/score') }}">成績</a></li>
			@endif
			{{-- <li>
				<a href="javascript:void(0)">線上測驗</a>
				<ul>
					<li><div class="te"><a href="#">評量測驗</a></div></li>
					<li><div class="te"><a href="javascript:void(0)">成績查詢</a></div></li>
					<li><div class="te"><a href="score_status.php">測驗狀態查詢</a></div></li>
					<li><div class="te"><a href="ex_print.php">診斷報告列印</a></div></li>
				</ul>
			</li> --}}
		</ul>
		<div class="top_per" id="top_id"><a href="">{{ $menu_user }}</a></div><div class="top_per"><a href="{{ url('/logout') }}">登出系統</a></div>
	</div>
</div>
@yield('content')
</body>
</html>
<script type="text/javascript" src="{{ URL::asset('/js/html5media.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/jquery.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
	    $("ul#navigation > li:has(ul) > a").append('<div class="arrow-bottom"><img src="{{ URL::asset('img/open.png') }}" height="20"></div>');
	    $("ul#navigation > li ul li:has(ul) > div a").append('<div class="arrow-right"><img src="{{ URL::asset('img/close.png') }}" height="20"></div>');
	});
	function gb(v){
		return document.getElementById(v);
	}
	function trim(value){
	    return value.replace(/^\s+|\s+$/g, '');
	}
</script>
@yield('script')