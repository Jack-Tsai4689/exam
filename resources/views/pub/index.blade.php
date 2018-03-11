@extends('layout.default')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('cssfunc/ex_sets.css') }}">
    <style type="text/css">
		.left {
			text-align: left !important;
			/*vertical-align: top !important;*/
			padding-left: 5px !important;
		}
		/*.sets_title {
			height: 30px;
			line-height: 30px;
			font-size: 16px;
		}*/
		.condition {
			text-align: left !important;
		}
		.list tr td {
			height: auto !important;
		}
		.list tr td {
			text-align: center;
		}
/*		#again {
			width: 100px;
		}
		#exam_time {
			width: 320px;
		}
		#createtime {
			width: 150px;
		}
		#lime {
			width: 100px;
		}
		#sets_view {
			width: 80px;
		}*/
		.edit_func div {
			height: auto;
		}
		.del {
			background-color: red;
			color: white;
		}
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<div class="title_intro">
		<div><input type="button" class="btn f16 w150" name="" id="" value="發佈測驗" onclick="location.href='{{ url('pub/create') }}'"></div>
	</div>
	<form id="search">
	<div class="title_intro condition">
		<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">篩選條件</div>
		類別：
		<select name="gra" onchange="getsubj(this.value)">
			<option value="0">全部</option>
			@foreach($Grade as $g)
			<option value="{{ $g->g_id }}">{{ $g->g_name}}</option>
			@endforeach
		</select>
		科目：
		<select name="subj" id="subj">
			<option value="0">全部</option>
			@foreach($Subject as $s)
			<option value="{{ $s->g_id }}">{{ $s->g_name}}</option>
			@endforeach
		</select>
		<input type="button" id="cond" value="篩選">
		<input type="hidden" name="page" id="urlpage" value="">
	</div>
	</form>
	<div class="content">
		<div id="cen">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th>派卷名稱</th>
						<th>發佈者</th>
						<th>類別</th>
						<th>科目</th>
						<th>班級</th>
						<th>班別</th>
						<th>考卷</th>
						<th width="80">重覆考</th>
						<th width="180">考試期間</th>
						<th width="180">發表時間</th>
						<th width="100">考試限時</th>
						<th width="60">狀態</th>
						<th width="100">題目預覽</th>
						<th class="last">動作</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($Data as $i => $v)
				<tr class="{{ ($i%2==0) ? 'deep':'shallow' }}">
					<td name="setsname" class="left">{{ $v->p_name }}</td>
					<td>{{ $v->p_owner }}</td>
					<td>{{ $v->gra->name }}</td>
					<td>{{ $v->subj->name }}</td>
	                <td></td>
					<td></td>
					<td></td>
					<td>{{ ($v->p_again) ? "O":"X" }}</td>
					<td>{!! $v->exam_day !!}</td>
					<td>{{ date('Y/m/d H:i:s', $v->p_created_at) }}</td>
					<td>{{ $v->p_limtime }}</td>
					<td>開放中</td>
					<td><a href="{{ url('/pub/'.$v->p_id) }}">瀏覽</a></td>
					<td><input type="button" value="暫停"></td>
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
			<select id="pagegroup" onchange="gp(this.value)">{!! $Page->pg !!}</select>
			{!! $Page->next !!}
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
function close_stu(){$('#sets_stulist').hide();}
function chk_all(){
	$('input:checkbox[name=choice_f]').prop('checked',true);
}
function notchk_all(){
	$('input:checkbox[name=choice_f]').prop('checked',false);	
}
function page(p){
	location.href = "/sets?p="+p;
}
function change_finish(value){
	var chk = $("#set_finish_"+value).prop("checked") ;
	var func = $('#edit_func_'+value);
	$('div[name=edit_group]').removeClass('show');
	if (chk){
		success(value);
	}else{
		failed(value);
	}
}
function failed(value){//已完成，切成未
    $.getJSON("sets_class.php", {set_id:value, f:'N'}, function(data){
        if (data.msg!='OK'){
            $("#set_finish_"+value).prop("checked",true);
            alert(data.msg);
        }else{
        	location.reload();
        }
    });
}
function success(value){//未完成，切成已
	$.getJSON("sets_class.php", {set_id:value, f:'Y'}, function(data){
        if (data.msg=='OK'){
			location.reload();
        }else{
        	$("#set_finish_"+value).prop("checked",false);
        	alert(data.msg);
        }
    });
}
function copyfrom(value){
	location.href = "ex_sets.php?action=copy&sets="+value;
}
function open_edit(value){
	var func = $('#edit_func_'+value);
	if (func.hasClass('show')){
		func.removeClass('show');
	}else{
		$('div[name=edit_group]').removeClass('show');
		func.addClass('show');
	}
}
// function open_class(){
// 	$('#sets_class').css('display','block');
// }
// function close_class(){
// 	$('#sets_class').css('display','none');
// }
function open_field(){
	$('#sets_filed').css('display','block');
}
function close_field(){
	$('#sets_filed').css('display','none');
	document.getElementById('field_msg').innerHTML = '';
}
$('#tiphelp').mouseover(function() {
  $('.tip').css('display','block');
});
$('#tiphelp').mouseout(function() {
  $('.tip').css('display','none');
});
function show_icon(){ $('#intro_icon').show()}
function hide_icon(){ $('#intro_icon').hide();}

function updcheck(){
	if (!confirm("定案後將無法變更，確定?")){
		return false;
	}
}
function delcheck(){
	if (!confirm("確定刪除?")){
		return false;
	}
}
function getsubj(v){
	gb("subj").innerHTML = '<option value="">搜尋中</option>';
	if (v==="0"){
		gb("subj").innerHTML = '<option value="0">全部</option>';
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
$("#cond").on('click', function(){
	sets_find();
});
function gp(p){
	gb('urlpage').value = p;
	sets_find();
}
function sets_find(){
	location.href = '{{ url('/sets')}}?'+$("#search").serialize();	
}
</script>
@stop