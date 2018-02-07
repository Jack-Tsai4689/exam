@extends('layout.default')
@section('style')
	<style type="text/css">
		#all {
			width: 90%;
		}
		#title {
			height: 30px;
			line-height: 30px;
		}
		#cen {
			width: 100%;
		}
		.hover {
			background-color: #FCE3CE;
		}
		.select {
			background-color: #F8CD89;
		}
		.content {
			float: left;
			width: 100%;
		}
		.btn {
			height: 25px;
			margin-bottom: 5px;
			border: 0.5px #EED6B4 solid;
		}
		.content:hover .eror {
			visibility: visible;
		}
		.each_bottom {
			width: 100%;
			float: left;
			padding-bottom: 20px;
			margin-left: 5px;
		}
		
		#page {
			margin-bottom: 60px;
			height: 40px;
			line-height: 40px;
			padding: 10px 0px 10px 0px;
			position: relative;
		}
		#page .each {
			padding: 0px;
			text-align: center;
			position: absolute;
			width: 100%;
		}
		.btn_page {
			height: 20px;
		}
		#prev {
			margin-right: 20px;
		}
		#next {
			margin-left: 20px;
		}
		.title_intro {
			position: relative;
			width: 100%;
			height: 35px;
			line-height: 35px;
		}
		.title_intro div {
			position: absolute;
			text-align: center;
			width: 100%;
		}
		.title_intro.btn{
			height: 25px;
		}
		#choice_fie {
			float: right;
			margin-right: 10px;
		}
		#keyword_search {
			text-align: left !important;
			margin-left: 5px;
		}
		#glass {
			width: 50px;
			background-color: #F2D9B6;
			float: left;
			height: 29px;
			display: inline-block;
			margin-top: 12px;
		}
		#glass_img {
			background-image: {{ url('img/icon_op_glass.png') }};
			height: 25px;
			position: relative;
			z-index: 2;
		}
		.list tr th {
			height: 40px;
		}
		.list tr td, .list tr th{
			vertical-align: middle;
			text-align: center;
			border-right: 1px #B4B5B5 solid;
			padding: 1px;
		}
		.list .deep {
			background-color: #EFEFEE;
		}
		.list .shallow {
			background-color: #FCFCFC;
		}
		.list tr.shallow:hover, .list tr.deep:hover{
			background-color: #FCE3CE;
		}
		.list th.last{
			text-align: center;
			border-right: 0px;
		}
		.list td.last{
			text-align: center;
			border-right: 0px;
		}
		.all_rows {
			position: absolute;
			margin-left: 20px;
		}
		.show {
			display: block;
		}
		#keyword {
			height: 25px;
			line-height: 25px;
			position: relative;
			z-index: 2;
		}
		.condition {
			height: 40px;
			line-height: 40px;
		}
		.condition div{
			text-align: left !important;
		}
		.condition label, .condition select {
			margin-left: 5px;
			/*font-size: 16px;*/
		}
		.chk_select {
			width: 20px;
			height: 20px;
			cursor: pointer;
		}
		.person_pic{
			width: 60px;
			height: 60px;
			margin: 5px 0px 5px 0px;
			margin-bottom: 5px;
		}
		.person_name {
			margin-bottom: 5px;
		}
		.point {
			padding: 5px !important;
			text-align: left !important;
			vertical-align: top !important;
		}
		.btn_edit{
			margin-bottom: 10px;
			font-size: 14px;
		}
		#point_check{
			position: fixed;
			width: 80px;
			float: left;
			margin-left: -92px;
		}
		#check_btn, #check_tip {
			position: relative;
			background-color: white;
			padding: 5px;
			border-bottom: 1px #B4B4B5 solid;
			border-left: 1px #B4B4B5 solid;
			border-right: 1px #B4B4B5 solid;
			width: 70px;
		}
		#check_btn {
			height: 30px;
		}
		#check_btn .btn {
			height: 30px;
			font-size: 15px;
		}
		#check_tip {
			margin-top: 5px;
			font-size: 11px;
		}
		.point_m {
			width: 15px;
			height: 15px;
		}
		.list > tbody > tr > td.kcont {
			text-align: left;
		}
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<form name="form1" id="form1">
	<div class="title_intro">
		<div class="top_search"><label style="margin-left:5px;">關鍵字搜尋</label><input type="text" class="input_field" name="f_search" id="f_search" value=""><div class="glass_div" onclick="search_confirm()"><img src="{{ URL::asset('img/icon_op_glass.png') }}"></div><a href="{{ url('/know') }}" style="margin-left:55px;">瀏覽全部</a></div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增知識點" onclick="location.href='{{ url('/know/create') }}'"></div>
	</div>
	<div class="title_intro condition">
		<div>
			<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">篩選條件</div>
				類別：
				<select name="gra" onchange="getsubj(this.value)">
					<option value="0">全部</option>{!! $Grade !!}
				</select>
				科目：
				<select name="subj" id="subj" onchange="getchap(this.value)">
					<option value="0">全部</option>{!! $Subject !!}
				</select>
				章節：
				<select name="chap" id="chap">
					<option value="0">全部</option>{!! $Chapter !!}
				</select>
				<input type="button" id="cond" value="篩選">
			<input type="hidden" name="page" id="urlpage" value="">
		</div>
	</div>
	</form>
	<div class="content" id="choice">
		<div id="cen">
			<input type="hidden" name="act" id="act" value="">
			<input type="hidden" name="pid" id="pid" value="">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th style="width:99px;">發表者</th>
						<th style="min-width:550px; width:60%;">知識點</th>
						<th style="width:99px;">類別</th>
						<th style="width:99px;">科目</th>
						<th style="width:99px;">章節</th>
						<th class="last" style="width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($Data as $i => $v)
					@php $class = ($i%2==0) ? 'deep' : 'shallow'; @endphp
					<tr class="{{ $class }}">
						<td>{{ $v->k_owner }}</td>
						<td class="kcont">{!! $v->k_content !!}</td>
						<td>{{ $v->gra->name }}</td>
						<td>{{ $v->subj->name }}</td>
						<td>{{ $v->chap->name }}</td>
						<td class="last"><input type="button" class="btn w50" name="" id="" value="編輯" onclick="location.href='{{ url('/know/'.$v->k_id.'/edit') }}'"></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div id="page" class="content">
		<label class="all_rows">共{{ $Num }}筆資料</label>
		<div class="each">
			{!! $Page->prev !!}
			<select id="pagegroup" onchange="page(this.value)">{!! $Page->pg !!}</select>
			{!! $Page->next !!}
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
function search_confirm(){
  var search = $('#f_search').val();
  if (trim(search)!=''){form1.submit();}
}
let g = 0;
function getsubj(v){
	g = v;
	gb("subj").innerHTML = '<option value="">搜尋中</option>';
	if (v==="0"){
		gb("subj").innerHTML = '<option value="0">全部</option>';
		gb("chap").innerHTML = '<option value="0">全部</option>';
		return;
	}
	$.ajax({
		type:"GET",
		url:"{{ url('basic/detail') }}",
		data:{'type':'subj', g:v},
		dataType:"JSON",
		success: function(rs){
			let html = '<option value="0">全部</option>';
			for (let i in rs){
				html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
			}
			gb('subj').innerHTML = html;
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			if (rs.status==400)gb('subj').innerHTML = '<option value="">無資料</option>';
		}
	});
}
function getchap(v){
	gb("chap").innerHTML = '<option value="">搜尋中</option>';
	if (v==="0"){
		gb("chap").innerHTML = '<option value="0">全部</option>';
		return;
	}
	$.ajax({
		type:"GET",
		url:"{{ url('basic/detail') }}",
		data:{'type':'chap', 'g':g, 's':v},
		dataType:"JSON",
		success: function(rs){
			let html = '<option value="0">全部</option>';
			for (let i in rs){
				html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
			}
			gb('chap').innerHTML = html;
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			if (rs.status==400)gb('chap').innerHTML = '<option value="">無資料</option>';
		}
	});
}
$("#cond").on('click', function(){
	knows_find();
});
function gp(p){
	gb('urlpage').value = p;
	knows_find();
}
function knows_find(){
	location.href = '{{ url('/know') }}?'+$("#form1").serialize();
}
</SCRIPT>
@stop