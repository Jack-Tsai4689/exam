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
	</style>
<body>
<div id="all">
	<div id="title"><label class="f17"><?=$title?></label></div>
	<div class="title_intro">
		<a class="f14" id="tiphelp">考卷設定Help？</a>
		<div class="tip">
			※點擊<font color="#C84146">未完成</font>/<font color="#29ABE2">已完成</font>可以做狀態切換<br>
			※當考卷<font color="#C84146">未完成</font>，至試卷預覽可調整題目順序。<br>
			※考卷為<font color="#29ABE2">已完成</font>，學生才看得到喔。<br>
			※如分享對象為「限班別」需至編輯「設定班級」才能切換。<br>
			※<font color="#29ABE2">已完成</font>的考卷如果有考試記錄將無法編輯，但可複製。
		</div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增考卷" onclick="location.href='<?=site_url('/sets/create')?>'"></div>
	</div>
	<div class="title_intro condition">
		<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">篩選條件</div>
		年級：
		<select name="f_grade">
			<option value="">全部</option>
			<?=$Grade?>
		</select>
		科目：
		<select name="f_subject">
			<option value="">全部</option>
			<?=$Subject?>
		</select>
	</div>
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
						<th id="exam_time" name="exam_time">考試時間</th>
						<th id="createtime" name="createtime">發表時間</th>
						<th id="lime" name="lime">考試限時</th>
						<th id="sets_view">試卷預覽</th>
						<th class="last" style="width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($Data as $v):?>
				<tr class="">
					<td name="setsname" class="left"><?=$v->SETS_NAME?></td>
					<td><?=$v->OWNER?></td>
					<td><?=$v->GRA?></td>
					<td name="subj"><?=$v->SUBJ?></td>
	                <td><?=$v->cname?></td>
					<td name="times"><?=($v->AGAIN) ? 'O':'X'?></td>
					<td name="exam_time"><?=(!empty($v->BEGTIME) && !empty($v->ENDTIME)) ? $v->BEGTIME.' ~ '.$v->ENDTIME:'不限';?></td>
					<td name="createtime"><?=$v->UPDATETIME?></td>
					<td name="lime"><?=$v->LIMTIME?></td>
					<td><a id="sets_link" href="<?=site_url('/sets/review').'/'.$v->ID?>">試卷預覽</a></td>
					<td class="last">
						<div class="edit_group btn" onclick="open_edit(<?=$v->ID?>)">編輯</div>
						<div id="edit_func_<?=$v->ID?>" class="edit_func" name="edit_group">
							<div><a href="<?=site_url('/sets/edit').'/'.$v->ID?>">修改</a></div>
                            <div><a href="javascript:void(0)" >刪除</a></div>
                            <div><a href="javascript:void(0)" >新增題目</a></div>
                            <div><a href="upload_md.php?f_sets=" target="_blank">Excel匯入</a></div>
    						<div><a href="javascript:void(0)" onclick="window.open('<?=site_url('/sets/vclass').'/'.$v->ID?>','result','width=900,height=600,resizable=yes,scrollbars=yes,location=no,status=yes');">設定班級</a></div>
                            <div><a href="javascript:void(0)" onclick="copyfrom()">複製</a></div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="page" class="content">
		<label class="all_rows">共筆資料</label>
		<div class="each">
			<?=$Prev?>
			<select id="pagegroup" onchange="page(this.value)"><?=$Pg?></select>
			<?=$Next?>
		</div>
	</div>
</div>
<div id="sets_filed" class="list_set">
	<div class="set_all">
		<div class="set_title"><label class="f17">選擇欄位</label></div>
		<div class="set_content">
			<div class="set_cen">
				<div class="set_btn">
					<input type="button" class="btn w75 f14" name="allchk" onclick="chk_all()" value="全部選取">
					<input type="button" class="btn w75 f14" name="allnotchk" onclick="notchk_all()" value="全部不選">
				</div>
				<div class="set_chk">
					<label><input type="checkbox" name="choice_f" checked value="setsname">考卷名稱</label>
					<label><input type="checkbox" name="choice_f" checked value="duty">考卷說明</label>
					<!-- <label><input type="checkbox" name="choice_f" checked>擁有者</label> -->
					<label><input type="checkbox" name="choice_f" checked value="subj">科目</label>
					<label><input type="checkbox" name="choice_f" checked value="times">次數</label>
					<label><input type="checkbox" name="choice_f" checked value="que_nums">題數</label>
					<label><input type="checkbox" name="choice_f" checked value="share">分享對象</label>
					<label><input type="checkbox" name="choice_f" checked value="exam_time">考試時間</label><br>
					<label><input type="checkbox" name="choice_f" checked value="createtime">發表時間</label>
					<label><input type="checkbox" name="choice_f" checked value="lime">考試限時</label>
				</div>
				<div>
                	<div style="text-align:left; float:left;"><input type="button" class="btn w80 f16" value="確定" name="sure" id="sure" onclick="field_change()">&nbsp;&nbsp;<font id="field_msg" color="red"></font></div>
					<div style="text-align:right; height:30px; line-height:30px;"><a href=""><font class="f15"><a href="javascript:void(0)" onclick="close_field()">取消更改</a></font></a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="sets_stulist" class="list_set">
    <div class="set_all">
        <div class="set_title"><label class="f17">班級學生名單</label></div>
        <div class="set_content">
            <div class="set_cen">
<!--                 <div class="set_btn">
                    <input type="button" class="btn w75 f14" name="" id="" value="全部選取">
                    <input type="button" class="btn w75 f14" name="" id="" value="全部不選">
                </div> -->
                <div class="set_main">
	                <div class="eachone" onclick="">
	                    <div class="person_pic"><img src="http://<?=$_SERVER['HTTP_HOST'].'/'.$s_pic?>"></div>
	                    <div class="person_dep">
	                        <div class="f16"></div>
	                        <div class="f12"></div>
	                    </div>
	                </div>
                </div>
                <div>
                    <!-- <div style="text-align:left; float:left;"><input type="submit" class="btn w80 f16" value="確定" name="sure" id="sure"></div> -->
                    <div style="text-align:right; height:30px; line-height:30px;"><a href=""><font class="f15"><a href="javascript:void(0)" onclick="close_stu()">關閉</a></font></a></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
function close_stu(){$('#sets_stulist').hide();}
function chk_all(){
	$('input:checkbox[name=choice_f]').prop('checked',true);
}
function notchk_all(){
	$('input:checkbox[name=choice_f]').prop('checked',false);	
}
function field_change(){
	var chk,attribute;
	var real = $('input:checkbox[name=choice_f]:checked').val();
	if (real==null){
		document.getElementById('field_msg').innerHTML = '至少選一個';
	}else{
		$('input:checkbox[name=choice_f]').each(function(){
			chk = $(this).prop('checked');
			attribute = $(this).val();
			if (chk){
				$('th[name="'+attribute+'"]').css('display','table-cell');
				$('td[name="'+attribute+'"]').css('display','table-cell');
			}else{
				$('th[name="'+attribute+'"]').css('display','none');
				$('td[name="'+attribute+'"]').css('display','none');
			}
		});
		close_field();
	}
}
function page(p){
	location.href = '<?=site_url("/sets")?>?p='+p;
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
function delete_que(value){//刪試卷
    if (confirm('確定刪除此考卷?')){
        location.href="ex_sets.php?action=delete&sets="+value;
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
</script>