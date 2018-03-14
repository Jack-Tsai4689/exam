<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('layout.sub')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('cssfunc/ex_set.css') }}">
	<style type="text/css">
		.show{
			display: block;
		}
		#all {
			margin: 20px auto;
		}
		.hiden {
			display: none;
		}
		.list > tbody > tr > td.qcont {
			text-align: left;
		}
		#qchk_all, .qchk {
			width: 18px;
			height: 18px;
		}
		.pic {
			max-width: 100%;
		}
	</style>
</head>
<body>
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<form name="form1" id="form1" method="POST" action="{{ url('/ques') }}">
	<div class="title_intro">
		<div class="top_search"><label style="margin-left:5px;">關鍵字搜尋</label><input type="text" class="input_field" name="f_search" id="f_search" value=""><div class="glass_div" onclick="search_confirm()"><img src="{{ URL::asset('img/icon_op_glass.png') }}"></div><a href="{{ url('/ques') }}" style="margin-left:55px;">瀏覽全部</a></div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增題目" onclick='window.open("{{ url('/ques/create') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' >&nbsp;&nbsp;&nbsp;&nbsp;<a href=""><input type="button" class="btn f16 w150" name="" id="" value="Excel匯入" onclick="location.href='upload_md.php'"></a></div>
		<label class="f16" id="choice_fie"><a href="javascript:void(0)" onclick="open_field();">選擇欄位</a></label>
	</div>
	<div class="title_intro condition">
		<div>
			<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">條件</div>
			類別：
			<select name="gra" onchange="getsubj(this.value)">
				<option value="">全部</option>{!! $Grade !!}
			</select>
			科目：
			<select name="subj" id="subj" onchange="getchap(this.value)">
				<option value="">全部</option>{!! $Subject !!}
			</select>
			章節：
			<select name="chap" id="chap">
				<option value="">全部</option>{!! $Chapter !!}
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
			<input type="button" id="sel_que" value="加入至考卷">
		</div>
	</div>
	<div class="content">
		<div id="cen">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th width="50"><input type="checkbox" id="qchk_all" onclick="check_all(this, 'qchk[]')"></th>
						<th name="qno" style="width:4%; min-width:39px;">序號</th>
						<th name="que">題目</th>
						<th style="width:80px;">題型</th>
						<th name="ans" style="width:5%; min-width:49px;">答案</th>
						<th name="gra" style="width:6%; min-width:59px;">類別</th>
						<th name="sub" style="width:5%; min-width:49px;">科目</th>
						<th name="chp" style="width:9.5%; min-width:99px;">章節</th>
						<th name="deg" style="width:4%; min-width:39px;">難度</th>
						<th style="width:100px;">Qrcode</th>
						<th class="last" name="pub" style="width:10%; min-width:109px;">發表時間</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($Data as $k => $v)
					@php $class = (($k+1)%2==0) ? 'shallow':'deep' @endphp
					<tr class="{{ $class }}">
						<td><input type="checkbox" name="qchk[]" class="qchk" value="{{ $v->q_id }}"></td>
						<td name="qno">{{ $v->q_id }}</td>
						<td class="qcont" name="que">{!! $v->q_qcont.'<br>'.$v->q_acont !!}<br>{{ $v->q_know }}</td>
						<td>{{ $v->q_quetype }}</td>
						<td name="ans">{{ $v->q_ans }}</td>
						<td name="gra">{{ $v->q_gra }}</td>
						<td name="sub">{{ $v->q_subj }}</td>
						<td name="chp">{{ $v->q_chap }}</td>
						<td name="deg">{{ $v->q_degree }}</td>
						<td></td>
						<td class="last">{{ $v->q_update }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	</form>
	<div id="page" class="content">
		<label class="all_rows">共 {{ $Num }} 筆資料</label>
		<div class="each">
			{{ $Page->prev }}
			<select id="pagegroup" onchange="gp(this.value)">{!! $Page->pg !!}</select>
			{{ $Page->next }}
		</div>
	</div>
</div>
<script type="text/javascript">
// function chk_all(){
// 	$('input:checkbox[name=choice_f]').prop('checked',true);
// }
// function notchk_all(){
// 	$('input:checkbox[name=choice_f]').prop('checked',false);	
// }
// function field_change(){
// 	var chk,attribute;
// 	var real = $('input:checkbox[name=choice_f]:checked').val();
// 	if (real==null){
// 		document.getElementById('field_msg').innerHTML = '至少選一個';
// 	}else{
// 		$('input:checkbox[name=choice_f]').each(function(){
// 			chk = $(this).prop('checked');
// 			attribute = $(this).val();
// 			if (chk){
// 				$('th[name="'+attribute+'"]').css('display','table-cell');
// 				$('td[name="'+attribute+'"]').css('display','table-cell');
// 			}else{
// 				$('th[name="'+attribute+'"]').css('display','none');
// 				$('td[name="'+attribute+'"]').css('display','none');
// 			}
// 		});
// 		close_field();
// 	}
// }
function page(p){
	form1.action='ex_set.php?p='+p;
	form1.submit();
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
function open_field(){
	$('#sets_filed').css('display','block');
}
function close_field(){
	$('#sets_filed').css('display','none');
	document.getElementById('field_msg').innerHTML = '';
}
var i='';
function check_all(obj,cName){
    var checkboxs = document.getElementsByName(cName);
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;}
}
// function delete_one(value){
//   if (confirm('您確定要刪除此題目？')){
//     location.href="ex_set.php?action=delete&qid="+value+"&p="+document.getElementById('p').value;
//   }
// }
// function chk(value){//选择确认 (删题目/改分享)
//   var que = new Array();
//   var choice = 0;
//   $('input:checkbox:checked[name="chkbox[]"]').each(function(i) { 
//     if ($(this).val()!=''){
//       choice = 1;
//       return false;
//     }
//   });
//   if (!choice){
//     alert('您尚未勾選題目');
//   }else{
//     if (value=='delete'){
//       if (confirm('您確定要刪除所勾選的題目？')){
//         $('#action').val('deletenums');
//         form1.submit();
//       }
//     }else if (value=='change'){
//       $('#action').val(value);
//       form1.submit();
//     }
//   }
// }
function search_confirm(){
  var search = $('#f_search').val();
  var pattern = new RegExp("[`~!@#$^&()=|{}':;'-+,\\[\\].<>/?~！@#￥……&*（）——|{}【】『；：」「'。，、？]");
  var rs = "";
  for (var i = 0; i < search.length; i++) { 
      rs += search.substr(i, 1).replace(pattern, ''); 
  } 
  if (search.trim()!=''){form1.submit();}
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
	location.href = '{{ url('/ques/imp') }}?'+$("#form1").serialize();
}
$("#sel_que").on('click', function(){
	let que_range = [];
	$(".qchk:checked").each(function(){
		que_range.push(Number(this.value));
	});
	parent.document.getElementById('ques').value = que_range.join(",");
	parent.document.getElementById('sets_filed').style.display = 'none';
	parent.document.getElementById('que_pic').style.display = 'none';
	parent.importque();
});
</script>