@extends('layout.default')
@section('style')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('cssfunc/ex_set.css') }}">
	<style type="text/css">
		.show{
			display: block;
		}
		.hiden {
			display: none;
		}
		.list > tbody > tr > td.qcont {
			text-align: left;
		}
		.pic {
			width: 500px;
		}
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<form name="form1" id="form1">
	<div class="title_intro">
		<div class="top_search"><label style="margin-left:5px;">關鍵字搜尋</label><input type="text" class="input_field" name="q" id="q" value="{{ $Qkeyword }}"><div class="glass_div" onclick="search_confirm()"><img src="{{ URL::asset('img/icon_op_glass.png') }}"></div><a href="{{ url('/ques') }}" style="margin-left:55px;">瀏覽全部</a></div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增題目" onclick='window.open("{{ url('/ques/create') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' >&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn f16 w150" name="" id="" value="Excel匯入(敬請期待)"></div>
		{{-- <label class="f16" id="choice_fie"><a href="javascript:void(0)" onclick="open_field();">選擇欄位</a></label> --}}
	</div>
	<div class="title_intro condition">
		<div>
			<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">條件</div>
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
			難度：
			<select name="degree">
				<option value=""  {{ $Degree->A}} >全部</option>
				<option value="E" {{ $Degree->E}} >容易</option>
				<option value="M" {{ $Degree->M}} >中等</option>
				<option value="H" {{ $Degree->H}} >困難</option>
				</select>
			　<input type="button" id="cond" value="篩選">
			<input type="hidden" name="page" id="urlpage" value="">
		</div>
	</div>
	</form>
	<div class="content">
		<div id="cen">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th name="qno" style="width:4%; min-width:39px;">序號</th>
						<th name="que">題目</th>
						<th style="width:80px;">題型</th>
						<th name="ans" style="width:5%; min-width:49px;">答案</th>
						<th name="gra" style="width:6%; min-width:59px;">類別</th>
						<th name="sub" style="width:5%; min-width:49px;">科目</th>
						<th name="chp" style="width:9.5%; min-width:99px;">章節</th>
						<th name="deg" style="width:4%; min-width:39px;">難度</th>
						<th style="width:100px;">Qrcode</th>
						<th name="pub" style="width:10%; min-width:109px;">發表時間</th>
						<th class="last" style="width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($Data as $k => $v)
					@php $class = (($k+1)%2==0) ? 'shallow':'deep' @endphp
					<tr class="{{ $class }}">
						<td name="qno">{{ $v->q_id }}</td>
						<td class="qcont" name="que">{!! $v->q_qcont.'<br>'.$v->q_acont !!}<br>{{ $v->q_know }}</td>
						<td>{{ $v->q_quetype }}</td>
						<td name="ans">{{ $v->q_ans }}</td>
						<td name="gra">{{ $v->q_gra }}</td>
						<td name="sub">{{ $v->q_subj }}</td>
						<td name="chp">{{ $v->q_chap }}</td>
						<td name="deg">{{ $v->q_degree }}</td>
						<td></td>
						<td>{{ $v->q_update }}</td>
						<td class="last"><input type="button" class="btn w80" onclick='window.open("{{ url('ques/'.$v->q_id.'/') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' value="明細"><br><input type="button" class="btn w80" onclick='window.open("{{ url('ques/'.$v->q_id.'/edit') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' value="編輯"></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div id="page" class="content">
		<label class="all_rows">共 {{ $Num }} 筆資料</label>
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
function open_edit(value){
	var func = $('#edit_func_'+value);
	if (func.hasClass('show')){
		func.removeClass('show');
	}else{
		$('div[name=edit_group]').removeClass('show');
		func.addClass('show');
	}
}
var i='';
function check_all(obj,cName){
    var checkboxs = document.getElementsByName(cName);
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;}
}
function search_confirm(){
  var search = $('#q').val();
  var pattern = new RegExp("[`~!@#$^&()=|{}':;'-+,\\[\\].<>/?~！@#￥……&*（）——|{}【】『；：」「'。，、？]");
  var rs = "";
  for (var i = 0; i < search.length; i++) { 
      rs += search.substr(i, 1).replace(pattern, ''); 
  } 
  if (search.trim()!='')ques_find();
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
	ques_find();
});
function gp(p){
	gb('urlpage').value = p;
	ques_find();
}
function ques_find(){
	location.href = '{{ url('/ques') }}?'+$("#form1").serialize();
}
</script>
@stop