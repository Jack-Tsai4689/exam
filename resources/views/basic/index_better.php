    <link rel="stylesheet" type="text/css" href="<?=base_url('/cssfunc/ex_sets.css')?>">
    <style type="text/css">
		.left {
			text-align: left !important;
			/*vertical-align: top !important;*/
			padding-left: 5px !important;
		}
/*		.sets_title {
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
<body>
<div id="all">
	<div class="title"><label class="f17"><?=$title?></label></div>
	<div class="content">
		<div id="cen">
			<div class="basic_sub">年級</div>
			<form id="newgra" onsubmit="return ngra()">
			<div class="addbtn">
				<input type="hidden" name="type" value="gra">
				新年級：<input type="text" name="graname" id="graname">　<input type="submit" class="btn f16 w100" value="新增年級"></div>
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
				<?php foreach ($Grade as $v):?>
					<tr>
						<td class="name"><a href="javascript:void(0)" class="gc" data-id="<?=$v->ID?>"><?=$v->NAME?></a></td>
						<td><?=$v->OWNER?></td>
						<td><?=$v->UPDATETIME?></td>
						<td><input type="button" class="gedit" data-id="<?=$v->ID?>" value="更名"></td>
						<td><input type="text" name="nsubj"><input type="button" class="btn w70" value="確定"></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="content">
		<div id="cen">
			<div class="basic_sub">科目</div>
			<!-- <form id="newsubj" onsubmit="return nsubj()">
			<div class="addbtn">
				<input type="hidden" name="type" value="subj"> -->
				<input type="hidden" name="graid" id="sgraid" value="">
				<!-- 新科目：<input type="text" name="subjname" id="subjname">　<input type="submit" class="btn f16 w100" value="新增科目"></div>
			</form> -->
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
			<!-- <form id="newchap" onsubmit="return nchap()">
			<div class="addbtn"> -->
				<!-- <input type="hidden" name="type" value="chap"> -->
				<input type="hidden" name="graid" id="cgraid" value="">
				<input type="hidden" name="subjid" id="subjid" value="">
				<!-- 新章節：<input type="text" name="chapname" id="chapname">　<input type="submit" class="btn f16 w100" value="新增章節"></div>
			</form> -->
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
    <div id="intro_content" class="set_content">
        <div id="intro_title">更新中...</div>
    </div>
</div>
<div id="updateg" class="list_set">
    <div class="set_all">
        <div class="title"><label class="f17">變更年級</label></div>
        <div class="set_content">
            <div class="set_cen">
                <div class="cen last">
                    <form method="post" onsubmit="return gcheck(this)">
                    年級：<input type="text" name="ugraname" id="ugraname">	
                    <div>
                        <div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="更新"></div>
                        <div style="text-align:right; height:30px; line-height:30px;"><a href="javascript:void(0)" id="ugcancel"><font class="f15">取消</font></a></div>
                        <input type="hidden" name="ugraid" id="ugraid">
                        <input type="hidden" name="type" value="gra">
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
                        <input type="hidden" name="subjgra" id="subjgra">
                        <input type="hidden" name="usubjid" id="usubjid">
                        <input type="hidden" name="type" value="subj">
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
                        <input type="hidden" name="chapgra" id="chapgra">
                        <input type="hidden" name="chapsubj" id="chapsubj">
                        <input type="hidden" name="uchapid" id="uchapid">
                        <input type="hidden" name="type" value="chap">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
function gb(v){
	return document.getElementById(v);
}
var g = 0;
var s = 0;
function ngra(){
	if (gb('graname').value=="")return false;
	$.ajax({
		type:"POST",
		url:"<?=site_url('/basic/cont')?>",
		data:$('#newgra').serialize(),
		dataType:"JSON",
		success:function(rs){
			gb('gralist').innerHTML = '';
			var html = '';
			var g_sel = null;
			for (var i in rs){
				g_sel = (rs[i].ID===g) ? 'class="select"':'';
				html+= '<tr '+g_sel+'><td class="name"><a href="javascript:void(0)" class="gc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="gedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('gralist').innerHTML = html;
			gb('graname').value = '';
			$(".gc").on("click", function(){
				getsubj(this);
			});
		}
	});
	return false;
}
function nsubj(){
	if (gb('subjname').value=="")return false;
	if (gb('sgraid').value=="0")return false;
	$.ajax({
		type:"POST",
		url:"<?=site_url('/basic/cont')?>",
		data:$('#newsubj').serialize(),
		dataType:"JSON",
		success:function(rs){
			gb('subjlist').innerHTML = '';
			var html = '';
			var s_sel = null;
			for (var i in rs){
				s_sel = (rs[i].ID===s) ? 'class="select"':'';
				html+= '<tr '+s_sel+'><td class="name"><a href="javascript:void(0)" class="sc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="sedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			alert('新增成功');
			gb('subjlist').innerHTML = html;
			gb('subjname').value = "";
			$(".sc").on("click", function(){
				getsubj(this);
			});
		}
	});
	return false;
}
function nchap(){
	if (gb('chapname').value=="")return false;
	if (gb('cgraid').value=="0")return false;
	if (gb('subjid').value=="0")return false;
	$.ajax({
		type:"POST",
		url:"<?=site_url('/basic/cont')?>",
		data:$('#newchap').serialize(),
		dataType:"JSON",
		success:function(rs){
			gb('chaplist').innerHTML = '';
			var html = '';
			for (var i in rs){
				html+= '<tr><td class="name"><a href="javascript:void(0)" class="cc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="cedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			alert('新增成功');
			gb('chaplist').innerHTML = html;
			gb('chapname').value = "";
		}
	});
	return false;
}
$(".gc").on('click', function(){
	s = 0;
	getsubj(this);
});
function getsubj(v){
	$('#gralist').find('tr').removeClass('select');
	var tr = v.parentElement.parentElement;
	$(tr).addClass('select');
	g = $(v).data("id");
	gb('sgraid').value = g;
	gb('cgraid').value = g;
	gb('subjgra').value = g;
	gb('chapgra').value = g;
	$.ajax({
		type:"GET",
		url:"<?=site_url('/basic/gsclist')?>",
		data:{'type':'subj', g:g},
		dataType:"JSON",
		success: function(rs){
			gb('subjlist').innerHTML = '';
			var html = '';
			var s_sel = null;
			for (var i in rs){
				s_sel = (rs[i].ID===s) ? 'class="select"':'';
				html+= '<tr '+s_sel+'><td class="name"><a href="javascript:void(0)" class="sc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="sedit" data-id="'+rs[i].ID+'" value="更名"></td><td><input type="text" name="nsubj"><input type="button" class="btn w70" value="確定"></td></tr>';
			}
			gb('subjlist').innerHTML = html;
			gb('chaplist').innerHTML = '';
			$(".sc").on("click", function(){
				getchap(this);
			});
			$(".sedit").on('click', function(){
				sed(this);
			});
		}
	});
}
function getchap(v){
	$('#subjlist').find('tr').removeClass('select');
	var tr = v.parentElement.parentElement;
	$(tr).addClass('select');
	s = $(v).data("id");
	gb('subjid').value = s;
	gb('chapsubj').value = s;
	$.ajax({
		type:"GET",
		url:"<?=site_url('/basic/gsclist')?>",
		data:{'type':'chap', 'g':g, 's':s},
		dataType:"JSON",
		success: function(rs){
			gb('chaplist').innerHTML = '';
			var html = '';
			for (var i in rs){
				html+= '<tr><td class="name"><a class="cc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="cedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('chaplist').innerHTML = html;
			$(".cedit").on('click', function(){
				ced(this);
			});
		}
	});
}
function ged(v){
	var tr = v.parentElement.parentElement;
	var ac = $(tr).find('td > a.gc');
	gb('ugraname').value = $(ac).text();
	gb('ugraid').value = $(ac).data("id");
	$(gb('updateg')).show();
}
function sed(v){
	var tr = v.parentElement.parentElement;
	var ac = $(tr).find('td > a.sc');
	gb('usubjname').value = $(ac).text();
	gb('usubjid').value = $(ac).data("id");
	$(gb('updates')).show();
	gb('usubjname').focus();
}
function ced(v){
	var tr = v.parentElement.parentElement;
	var cc = $(tr).find('td > a.cc');
	gb('uchapname').value = cc.text();
	gb('uchapid').value = $(cc).data("id");
	$(gb('updatec')).show();
	gb('uchapname').focus();
}
$(".gedit").on('click', function(){
	var tr = this.parentElement.parentElement;
	var gc = $(tr).find('td > a.gc');
	gb('ugraname').value = $(gc).text();
	gb('ugraid').value = $(gc).data("id");
	$(gb('updateg')).show();
	gb('ugraname').focus();	
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
		url:"<?=site_url('/basic/ucont')?>",
		data:$(v).serialize(),
		dataType:"JSON",
		success: function(rs){
			gb('gralist').innerHTML = '';
			var html = '';
			var g_sel = null;
			for (var i in rs){
				g_sel = (rs[i].ID===g) ? 'class="select"':'';
				html+= '<tr '+g_sel+'><td class="name"><a href="javascript:void(0)" class="gc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="gedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('gralist').innerHTML = html;
			gb('graname').value = '';
			$(".gc").on("click", function(){
				getsubj(this);
			});
			$(".gedit").on('click', function(){
				ged(this);
			});
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
		url:"<?=site_url('/basic/ucont')?>",
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
			$(".sc").on("click", function(){
				getchap(this);
			});
			$(".sedit").on('click', function(){
				sed(this);
			});
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
		url:"<?=site_url('/basic/ucont')?>",
		data:$(v).serialize(),
		dataType:"JSON",
		success: function(rs){
			gb('chaplist').innerHTML = '';
			var html = '';
			for (var i in rs){
				html+= '<tr><td class="name"><a class="cc" data-id="'+rs[i].ID+'">'+rs[i].NAME+'</a></td><td>'+rs[i].OWNER+'</td><td>'+rs[i].UPDATETIME+'</td><td><input type="button" class="cedit" data-id="'+rs[i].ID+'" value="更名"></td></tr>';
			}
			gb('chaplist').innerHTML = html;
			$(".cedit").on('click', function(){
				ced(this);
			});
		}
	});
	$(gb("updatec")).hide();
	return false;
}
</script>