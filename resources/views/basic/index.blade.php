@extends('layout.default')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/cssfunc/ex_sets.css') }}">
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
		.basic_sub {
			height: 25px;
			line-height: 25px;
			padding: 5px 0px 5px 20px;
			font-size: 16px;
			display: inline-block;
			width: 100px;
			background-color: #F2D9B6;
		}
		.addbtn {
			/*float: right;
			margin: 5px 5px 0 0;*/
			margin: 5px 0 0 5px;
		}
		.btn {
			border: .5px gray inset;
		}
		tr.select {
			background-color: #fce3ce;
		}
		.hiden {
			display: none;
		}
		.time {
			width: 300px;
		}
		table .btn {
			margin: 0px;
		}
		.list > thead > tr > th.name, .list > tbody > tr > td.name {
			text-align: left;
			padding-left: 5px;
		}
		#intro_open {
            top: 0px;
            bottom: 0px;
            left: 0px;
            right: 0px;
            position: fixed;
            opacity: 0.8;
            z-index: 3;
            background:-moz-radial-gradient(center,ellipse cover,rgba(0,0,0,0.4) 0,rgba(0,0,0,0.9) 100%);
            background: -ms-radial-gradient(center,ellipse cover,rgba(0,0,0,0.4) 0,rgba(0,0,0,0.9) 100%);
            background: -webkit-radial-gradient(center,ellipse cover,rgba(0,0,0,0.4) 0,rgba(0,0,0,0.9) 100%);
            filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr='#66000000',endColorstr='#e6000000',GradientType=1)";
            -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
            display: none;
        }
        #intro_all {
            width: 100%;
            position: fixed;
            z-index: 5;
            top: 0px;
            margin: 7% auto;
            display: none;
        }
        #intro_content {
            width: 500px;
            margin: 0% auto;
            position: relative;
            float: none;
            height: 150px;
            border-radius: 10px;
            height: 80px;
        }
        #intro_title {
            font-size: 20px;
            text-align: center;
            line-height: 80px;
        }
        .last {
        	width: 300px;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div class="title"><label class="f17">{{ $title }}</label></div>
	<div class="content">
		<div id="cen">
			<div class="basic_sub">類別</div>
			<form onsubmit="return ngra(this)">
			<div class="addbtn">
				{{ csrf_field() }}
				<input type="hidden" name="type" value="gra">
				新類別：<input type="text" name="graname" id="graname">　<input type="submit" class="btn f16 w100" value="確定"></div>
			</form>
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th class="name">名稱</th>
						<th class="time">更新者</th>
						<th class="time">更新時間</th>
						<th class="time" style="width:150px;">更名</th>
						<th class="last" >新增科目</th>
					</tr>
				</thead>
				<tbody id="gralist">
				@foreach ($Grade as $v)
					<tr>
						<td class="name"><a href="javascript:void(0)" class="gc" data-id="{{ $v->g_id }}">{{ $v->g_name }}</a></td>
						<td>{{ $v->g_owner }}</td>
						<td>{{ date('Y/m/d H:i:s', $v->updated_at)}}</td>
						<td><input type="button" class="gedit" data-id="{{ $v->g_id }}" value="更名"></td>
						<td>
							<form onsubmit="return nsubj(this)">
								<input type="text" name="subjname">
								<input type="hidden" name="type" value="subj">
								<input type="hidden" name="g" value="{{ $v->g_id }}">
								<input type="submit" class="btn w70" value="確定">
							</form>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="content">
		<div id="cen">
			<div class="basic_sub">科目</div>
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th class="name">名稱</th>
						<th class="time">更新者</th>
						<th class="time">更新時間</th>
						<th class="time" style="width:150px;">更名</th>
						<th class="last" >新增章節</th>
					</tr>
				</thead>
				<tbody id="subjlist"></tbody>
			</table>
		</div>
	</div>
	<div class="content">
		<div id="cen">
			<div class="basic_sub">章節</div>
				<input type="hidden" name="graid" id="cgraid" value="">
				<input type="hidden" name="subjid" id="subjid" value="">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th class="name">名稱</th>
						<th class="time">更新者</th>
						<th class="time">更新時間</th>
						<th class="last" style="width:150px;">維護</th>
					</tr>
				</thead>
				<tbody id="chaplist"></tbody>
			</table>
		</div>
	</div>
<!-- 	<div id="page" class="content">
		<label class="all_rows">共筆資料</label>
		<div class="each">
			<select id="pagegroup" onchange="page(this.value)"></select>
		</div>
	</div> -->
</div>
<div id="intro_open"></div>
<div id="intro_all">
    <div id="intro_content">
        <div id="intro_title"><img src="{{ URL::asset('img/tenor.gif') }}" width="60"></div>
    </div>
</div>
<div id="updateg" class="list_set">
    <div class="set_all">
        <div class="title"><label class="f17">變更類別</label></div>
        <div class="set_content">
            <div class="set_cen">
                <div class="cen last">
                    <form method="post" onsubmit="return gcheck(this)">
                    類別：<input type="text" name="ugraname" id="ugraname">	
                    <div>
                        <div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="更新"></div>
                        <div style="text-align:right; height:30px; line-height:30px;"><a href="javascript:void(0)" id="ugcancel"><font class="f15">取消</font></a></div>
                        <input type="hidden" name="ugraid" id="ugraid">
                        <input type="hidden" name="type" value="ugra">
                        {{ csrf_field() }}
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updates" class="list_set">
    <div class="set_all">
        <div class="title"><label class="f17">變更科目</label></div>
        <div class="set_content">
            <div class="set_cen">
                <div class="cen last">
                    <form method="post" onsubmit="return scheck(this)">
                    科目：<input type="text" name="usubjname" id="usubjname">	
                    <div>
                        <div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="更新"></div>
                        <div style="text-align:right; height:30px; line-height:30px;"><a href="javascript:void(0)" id="uscancel"><font class="f15">取消</font></a></div>
                        <input type="hidden" name="usubjid" id="usubjid">
                        <input type="hidden" name="usg" id="usg">
                        <input type="hidden" name="type" value="usubj">
                        {{ csrf_field() }}
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updatec" class="list_set">
    <div class="set_all">
        <div class="title"><label class="f17">變更章節</label></div>
        <div class="set_content">
            <div class="set_cen">
                <div class="cen last">
                    <form method="post" onsubmit="return ccheck(this)">
                    章節：<input type="text" name="uchapname" id="uchapname">	
                    <div>
                        <div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="更新"></div>
                        <div style="text-align:right; height:30px; line-height:30px;"><a href="javascript:void(0)" id="uccancel"><font class="f15">取消</font></a></div>
                        <input type="hidden" name="uchapid" id="uchapid">
                        <input type="hidden" name="ucg" id="ucg">
                        <input type="hidden" name="ucs" id="ucs">
                        <input type="hidden" name="type" value="uchap">
                        {{ csrf_field() }}
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script type="text/javascript">
var g = 0;
var s = 0;
function ngra(obj){
	if (gb('graname').value=="")return false;
	act_start();
	$.ajax({
		type:"POST",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		url:"{{ url('basic') }}",
		data:$(obj).serialize(),
		dataType:"JSON",
		success:function(rs){
			gb('gralist').innerHTML = '';
			var html = '';
			var g_sel = null;
			for (var i in rs){
				g_sel = (rs[i].ID===g) ? 'class="select"':'';
				html+= '<tr '+g_sel+'><td class="name"><a href="javascript:void(0)" class="gc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="gedit" data-id="'+rs[i].ID+'" value="更名"></td><td><form onsubmit="return nsubj(this)"><input type="text" name="subjname"><input type="hidden" name="type" value="subj"><input type="hidden" name="g" value="'+rs[i].ID+'"><input type="submit" class="btn w70" value="確定"></form></td></tr>';
			}
			gb('gralist').innerHTML = html;
			gb('graname').value = '';
			act_end();
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			act_end();
		}
	});
	return false;
}
function nsubj(obj){
	if (obj.subjname.value=="")return false;
	act_start();
	$.ajax({
		type:"POST",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		url:"{{ url('basic') }}",
		data:$(obj).serialize(),
		dataType:"JSON",
		success:function(rs){
			if (obj.g.value==g){
				var html = '';
				var s_sel = null;
				for (var i in rs){
					s_sel = (rs[i].ID===s) ? 'class="select"':'';
					html+= '<tr '+s_sel+'><td class="name"><a href="javascript:void(0)" class="sc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="sedit" data-id="'+rs[i].ID+'" value="更名"></td><td><form onsubmit="return nchap(this)"><input type="text" name="chapname"><input type="hidden" name="type" value="chap"><input type="hidden" name="g" value="'+g+'"><input type="hidden" name="s" value="'+rs[i].ID+'"><input type="submit" class="btn w70" value="確定"></form></td></tr>';
				}
				gb('subjlist').innerHTML = html;
			}
			obj.subjname.value = '';
			alert('新增成功');
			act_end();
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			act_end();
		}
	});
	return false;
}
function nchap(obj){
	if (obj.chapname.value=="")return false;
	act_start();
	$.ajax({
		type:"POST",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		url:"{{ url('basic') }}",
		data:$(obj).serialize(),
		dataType:"JSON",
		success:function(rs){
			if (obj.s.value==s){
				var html = '';
				for (var i in rs){
					html+= '<tr><td class="name"><a href="javascript:void(0)" class="cc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="cedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
				}
				gb('chaplist').innerHTML = html;
			}
			alert('新增成功');
			obj.chapname.value = '';
			act_end();
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			act_end();
		}
	});
	return false;
}
$("#gralist").on("click", ".gc", function(){
	$('#gralist').find('tr').removeClass('select');
	var tr = this.parentElement.parentElement;
	$(tr).addClass('select');
	g = $(this).data("id");
	gb('usg').value = g;
	gb('ucg').value = g;
	act_start();
	$.ajax({
		type:"GET",
		url:"{{ url('basic/detail') }}",
		data:{'type':'subj', g:g},
		dataType:"JSON",
		success: function(rs){
			var html = '';
			var s_sel = null;
			for (var i in rs){
				s_sel = (rs[i].ID===s) ? 'class="select"':'';
				html+= '<tr '+s_sel+'><td class="name"><a href="javascript:void(0)" class="sc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="sedit" data-id="'+rs[i].ID+'" value="更名"></td><td><form onsubmit="return nchap(this)"><input type="hidden" name="g" value="'+g+'"><input type="hidden" name="s" value="'+rs[i].ID+'"><input type="hidden" name="type" value="chap"><input type="text" name="chapname"><input type="submit" class="btn w70" value="確定"></form></td></tr>';
			}
			gb('subjlist').innerHTML = html;
			gb('chaplist').innerHTML = '';
			act_end();
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			if (rs.status==400){
				gb('subjlist').innerHTML = '';
				gb('chaplist').innerHTML = '';
			}
			act_end();
		}
	});
});
$("#subjlist").on("click", ".sc", function(){
	$('#subjlist').find('tr').removeClass('select');
	var tr = this.parentElement.parentElement;
	$(tr).addClass('select');
	s = $(this).data("id");
	gb('ucs').value = s;
	act_start();
	$.ajax({
		type:"GET",
		url:"{{ url('basic/detail') }}",
		data:{'type':'chap', 'g':g, 's':s},
		dataType:"JSON",
		success: function(rs){
			gb('chaplist').innerHTML = '';
			var html = '';
			for (var i in rs){
				html+= '<tr><td class="name"><a class="cc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="cedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('chaplist').innerHTML = html;
			act_end();
		},
		error: function(rs){
			if (rs.status==401)alert('登入逾時，請重新登入');
			if (rs.status==400)gb('chaplist').innerHTML = '';
			act_end();
		}
	});
});
$("#gralist").on("click", ".gedit", function(){
	var tr = this.parentElement.parentElement;
	var ac = $(tr).find('td > a.gc');
	gb('ugraname').value = $(ac).text();
	gb('ugraid').value = $(ac).data("id");
	$(gb('updateg')).show();
});
$("#subjlist").on("click", ".sedit", function(){
	var tr = this.parentElement.parentElement;
	var ac = $(tr).find('td > a.sc');
	gb('usubjname').value = $(ac).text();
	gb('usubjid').value = $(ac).data("id");
	$(gb('updates')).show();
	gb('usubjname').focus();
});
$("#chaplist").on("click", ".cedit", function(){
	var tr = this.parentElement.parentElement;
	var cc = $(tr).find('td > a.cc');
	gb('uchapname').value = cc.text();
	gb('uchapid').value = $(cc).data("id");
	$(gb('updatec')).show();
	gb('uchapname').focus();
});
$("#ugcancel").on('click', function(){
	$(gb("updateg")).hide();
});
$("#uscancel").on('click', function(){
	$(gb("updates")).hide();
});
$("#uccancel").on('click', function(){
	$(gb("updatec")).hide();
});
function gcheck(v){
	if (gb("ugraname").value=="")return false;
	if (gb("ugraid").value=="0")return false;
	$.ajax({
		type:"POST",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		url:"{{ url('/basic')}}",
		data:$(v).serialize(),
		dataType:"JSON",
		success: function(rs){
			gb('gralist').innerHTML = '';
			var html = '';
			var g_sel = null;
			for (var i in rs){
				g_sel = (rs[i].ID===g) ? 'class="select"':'';
				html+= '<tr '+g_sel+'><td class="name"><a href="javascript:void(0)" class="gc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="gedit" data-id="'+rs[i].ID+'" value="更名"></td><td><form onsubmit="return nsubj(this)"><input type="text" name="subjname"><input type="hidden" name="type" value="subj"><input type="hidden" name="g" value="'+rs[i].ID+'"><input type="submit" class="btn w70" value="確定"></form></td></tr>';
			}
			gb('gralist').innerHTML = html;
			gb('graname').value = '';
		}
	});
	$(gb("updateg")).hide();
	return false;
}
function scheck(v){
	if (gb("usubjname").value=="")return false;
	if (gb("usubjid").value=="0")return false;
	$.ajax({
		type:"POST",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		url:"{{ url('/basic')}}",
		data:$(v).serialize(),
		dataType:"JSON",
		success: function(rs){
			gb('subjlist').innerHTML = '';
			var html = '';
			var s_sel = null;
			for (var i in rs){
				s_sel = (rs[i].ID===s) ? 'class="select"':'';
				html+= '<tr '+s_sel+'><td class="name"><a href="javascript:void(0)" class="sc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="sedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('subjlist').innerHTML = html;
			gb('chaplist').innerHTML = '';
		}
	});
	$(gb("updates")).hide();
	return false;
}
function ccheck(v){
	if (gb("uchapname").value=="")return false;
	if (gb("uchapid").value=="0")return false;
	$.ajax({
		type:"POST",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		url:"{{ url('/basic')}}",
		data:$(v).serialize(),
		dataType:"JSON",
		success: function(rs){
			gb('chaplist').innerHTML = '';
			var html = '';
			for (var i in rs){
				html+= '<tr><td class="name"><a class="cc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="cedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('chaplist').innerHTML = html;
		}
	});
	$(gb("updatec")).hide();
	return false;
}
function act_start(){
	$('#intro_open').show();
    $('#intro_all').show();
}
function act_end(){
	$('#intro_open').hide();
    $('#intro_all').hide();
}
</script>
@stop