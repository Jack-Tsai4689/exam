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
		#again {
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
		}
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
		<a class="f14" id="tiphelp">考卷設定Help？</a>
		<div class="tip">
			※點擊<font color="#C84146">未完成</font>/<font color="#29ABE2">已完成</font>可以做狀態切換<br>
			※當考卷<font color="#C84146">未完成</font>，至試卷預覽可調整題目順序。<br>
			※考卷為<font color="#29ABE2">已完成</font>，學生才看得到喔。<br>
			※如分享對象為「限班別」需至編輯「設定班級」才能切換。<br>
			※<font color="#29ABE2">已完成</font>的考卷如果有考試記錄將無法編輯，但可複製。
		</div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增考卷" onclick="location.href='{{ url('sets/create') }}'"></div>
	</div>
	<form id="search">
	<div class="title_intro condition">
		<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">篩選條件</div>
		類別：
		<select name="f_grade" onchange="cond()">
			<option value="">全部</option>
			{!! $Grade !!}
		</select>
		科目：
		<select name="f_subject" onchange="cond()">
			<option value="">全部</option>
			{!! $Subject !!}
		</select>
	</div>
	</form>
	<div class="content" data-step="1" data-intro="我建立的所有考卷" data-position="top">
		<div id="cen">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th id="setsname" name="setsname">考卷</th>
						<th id="owner">擁有者</th>
						<th>年級</th>
						<th id="subj" name="subj">科目</th>
						<th >班級<br>
							<font class="f12">應測人數(<font class="class_examed">已測</font>/<font class="class_notexam">未測</font>/<font class="class_examing">測驗中</font>)</font>
						</th>
						<th id="again" name="times">重覆考</th>
						<th id="exam_time" name="exam_time">考試期間</th>
						<th id="createtime" name="createtime">發表時間</th>
						<th id="lime" name="lime">考試限時</th>
						<th width="60">狀態</th>
						<th id="sets_view">題目預覽</th>
						<th class="last" style="width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($Data as $i => $v)
					@php $class = ($i%2==0) ? 'deep':'shallow'; @endphp
				<tr class="{{ $class }}">
					<td name="setsname" class="left">{{ $v->s_name }}</td>
					<td>{{ $v->s_owner }}</td>
					<td>{{ $v->gra->name }}</td>
					<td name="subj">{{ $v->subj->name }}</td>
	                <td></td>
					<td name="times">{{ $v->s_again }}</td>
					<td name="exam_time">{{ $v->time }}</td>
					<td name="createtime">{{ $v->updated_at }}</td>
					<td name="lime">{{ $v->s_limtime }}</td>
					<td>{{ $v->finish }}</td>
					<td><a id="sets_link" href="sets/{{ $v->s_id }}/show">題目預覽</a></td>
					<td class="last">
						<div class="edit_group btn" onclick="open_edit({{ $v->s_id }})">編輯</div>
						@if (!$v->s_finish)
						<div id="edit_func_{{ $v->s_id }}" class="edit_func" name="edit_group">
							<div><input type="button" onclick="location.href='{{ url('sets/'.$v->s_id.'/edit') }}'" value="修改"></div>
							<div>
								<form action="{{ url('/sets/'.$v->s_id.'/finish') }}" method="post" onsubmit="return updcheck()">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="PUT">
									<input type="hidden" name="status" value="open">
                            		<input type="submit" value="開放">
                            	</form>
							</div>
							<div>
                            	<form action="{{ url('/sets/'.$v->s_id) }}" method="post" onsubmit="return delcheck()">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="DELETE">
                            		<input type="submit" class="del" value="刪除">
                            	</form>
                            </div>
    						<div><a href="javascript:void(0)" onclick="">設定班級</a></div>
{{--                             <div><a href="javascript:void(0)" onclick="copyfrom()">複製</a></div>
 --}}					
						</div>
						@endif
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div id="page" class="content">
		<label class="all_rows">共筆資料</label>
		<div class="each">
			{{ $Prev }}
			<select id="pagegroup" onchange="page(this.value)">{{ $Pg }}</select>
			{{ $Next }}
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
	if (!confirm("開放後無法提前關閉，確定開放?")){
		return false;
	}
}
function delcheck(){
	if (!confirm("確定刪除?")){
		return false;
	}
}
function cond(){
	location.href = '{{ url('/sets')}}?'+$("#search").serialize();
}
</script>
@stop