<?php 
/**********************************************************
/*  程式名稱：選擇知識點
/*  作者：Jerry
/*  版本：$Id: ex_point.php,v3 2016/06/04 18:09
/*  更新日期：$Date: v1 2016/04/22 16:47
					 v2 2016/04/24 01:49
					 v3 2016/06/04 18:09
/*  程式目的/問題描述：
	v2->知識點選擇debug
	v3->增加知識點刪除機制，有跟題目關聯就不能刪
/*  其它說明：
/*********************************************************/
?>
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
			background-image: url('images/images/images/icon_op_glass.png');
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
			font-size: 16px;
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
		@media screen and (max-width: 1500px) {
			#all {
				min-width: 1152px;
			}
		}
	</style>
</head>
<body>
<form name="form1" method="POST" action="<?=site_url('/knowledge')?>">
<div id="all">
	<div id="title"><label class="f17"><?=$title?></label></div>
	<div class="title_intro">
		<div class="top_search"><label style="margin-left:5px;">關鍵字搜尋</label><input type="text" class="input_field" name="f_search" id="f_search" value=""><div class="glass_div" onclick="search_confirm()"><img src="images/icon_op_glass.png"></div><a href="<?=site_url('/knowledge')?>" style="margin-left:55px;">瀏覽全部</a></div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增知識點" onclick="location.href='<?=site_url('/knowledge/create')?>'"></div>
	</div>
	<div class="title_intro condition">
		<div>
			<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">篩選條件</div>
				年級：
				<select name="f_grade" onchange="submit();">
					<option value="">全部</option><?=$Grade?>
				</select>
				科目：
				<select name="f_subject" onChange="submit();">
					<option value="">全部</option><?=$Subject?>
				</select>
				章節：
				<select name="f_chapter" onChange="submit();">
					<option value="">全部</option><?=$Chapter?>
				</select>
		</div>
	</div>
	<div class="content" id="choice">
		<!-- <div id="point_check">
			<div id="check_btn"><input type="button" class="btn w70 h30" name="" id="" onclick="check_choice()" value="確定"></div>
			<div id="check_tip"><center>小提醒</center>勾選右方知識點，並點擊確定回題目畫面。</div>
		</div> -->
		<div id="cen">
			<input type="hidden" name="act" id="act" value="">
			<input type="hidden" name="pid" id="pid" value="">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th style="width:99px;">發表者</th>
						<th style="min-width:550px; width:60%;">知識點</th>
						<th style="width:99px;">年級</th>
						<th style="width:99px;">科目</th>
						<th style="width:99px;">章節</th>
						<th class="last" style="width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				foreach ($Data as $i => $v):
					$class = ($i%2==0) ? 'deep' : 'shallow';
				?>
					<tr class="<?=$class?>">
						<td><?=$v->K_OWNER?></td>
						<td class="kcont"><?=$v->K_CONTENT?></td>
						<td><?=$v->K_GRA?></td>
						<td><?=$v->K_SUBJ?></td>
						<td><?=$v->K_CHAP?></td>
						<td><input type="button" class="btn w50" name="" id="" value="編輯" onclick="location.href='<?=site_url('/knowledge/edit').'/'.$v->KID?>'"></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="page" class="content">
		<label class="all_rows">共<?=$Num?>筆資料</label>
		<div class="each">
			<?=$Prev?>
			<select id="pagegroup" onchange="page(this.value)">
				<?=$Pg?>
			</select>
			<?=$Next?>
		</div>
	</div>
</div>
</form>
</body>
</html>
<script>
function page(p){
	form1.action='ex_point?p='+p;
	form1.submit();
}
function edit(value){
    location.href='ex_pointA.php?f_pno=ex_point&w_add=F&f_pid='+value;
}
function del(value){
    if(confirm("確定刪除此知識點?")){
        $('#pid').val(value);
        $('#act').val('del');
        form1.submit();
    }
}
function trim(value){
    return value.replace(/^\s+|\s+$/g, '');
}
function search_confirm(){
  var search = $('#f_search').val();
  if (trim(search)!=''){form1.submit();}
}
function point_m(value){
	var mp = $('#mp'+value);
	if (mp.css('display')=='none'){
		mp.css('display','block');
		$('#m'+value).attr('src','open.png');
	}else{
		mp.css('display','none');
		$('#m'+value).attr('src','close.png');
	}
}
var no_choice = true;
$(document).mouseleave(function(e){
	if (!no_choice){
		window.onbeforeunload = function (e) {
			return '尚未儲存，確定離開?';
		}	
	}else{
		window.onbeforeunload = null;
	}
  
});
function point_chk(){
	no_choice = false;
}
function check_choice(){//知識點編號回傳給題目
    var check = $('input[name="radbox"]:checked').val();
    if (check==null){
    	alert('尚未選擇');
    }else{
    	// self.opener.document.getElementById('f_pid').value=check;//編號
	    // self.opener.document.forms[0].submit();//全部都能用
	    //self.opener.document.getElementById('pid_check').innerHTML="已選擇";//編號
	    //opener.window.document.getElementById('pid_pic').innerHTML=check;
	    // window.close();
	    var point = $('#point_content', parent.document);
	    point.html('');
		point.append(
			$('<div>').html(document.getElementById('np_'+check).innerHTML),
			$('<input>').attr({type:'button',value:'重選知識點',class:'btn w160 h25',name:'upd_point',onClick:'select_point()'}),
			$('<input>').attr({type:'button',value:'取消知識點',class:'btn w160 h25',name:'upd_point',onClick:'remove_point()'}),
			$('<input>').attr({type:'hidden',name:'f_pid',id:'f_pid',value:check})
    	);
    	$('#sets_filed', parent.document).hide();
	}
}
</SCRIPT>