	<link rel="stylesheet" type="text/css" href="<?=base_url('/cssfunc/ex_set.css')?>">
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
	</style>
</head>
<body>
<div id="all">
	<div id="title"><label class="f17"><?=$title?></label></div>
	<form name="form1" id="form1" method="POST" action="<?=site_url('/question')?>">
	<div class="title_intro">
		<div class="top_search"><label style="margin-left:5px;">關鍵字搜尋</label><input type="text" class="input_field" name="f_search" id="f_search" value=""><div class="glass_div" onclick="search_confirm()"><img src="<?=base_url('images/icon_op_glass.png')?>"></div><a href="<?=site_url('/question')?>" style="margin-left:55px;">瀏覽全部</a></div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增題目" onclick='window.open("<?=site_url('/question/create')?>","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' >&nbsp;&nbsp;&nbsp;&nbsp;<a href=""><input type="button" class="btn f16 w150" name="" id="" value="Excel匯入" onclick="location.href='upload_md.php'"></a></div>
		<label class="f16" id="choice_fie"><a href="javascript:void(0)" onclick="open_field();">選擇欄位</a></label>
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
			難度：
			<select name="f_degree" onChange="submit();">
				<option value=""  <?=$Degree->A?> >全部</option>
				<option value="E" <?=$Degree->E?> >容易</option>
				<option value="M" <?=$Degree->M?> >中等</option>
				<option value="H" <?=$Degree->H?> >困難</option>
				</select>
			<input type="hidden" name="p" id="p" value="">
			<input type="hidden" name="action" id="action" value="">
		</div>
	</div>
	<div class="content">
		<div id="cen">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th name="qno" style="width:4%; min-width:39px;">序號</th>
						<th name="que">題目</th>
						<th style="width:80px;">題型</th>
						<th name="ans" style="width:5%; min-width:49px;">答案</th>
						<th name="gra" style="width:6%; min-width:59px;">年級</th>
						<th name="sub" style="width:5%; min-width:49px;">科目</th>
						<th name="chp" style="width:9.5%; min-width:99px;">章節</th>
						<th name="deg" style="width:4%; min-width:39px;">難度</th>
						<th style="width:100px;">Qrcode</th>
						<th name="pub" style="width:10%; min-width:109px;">發表時間</th>
						<th class="last" style="max-width:82px; min-width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($Data as $k => $v):
				$class = (($k+1)%2==0) ? 'shallow':'deep';
				?>
					<tr class="<?=$class?>">
						<td name="qno"><?=$v->QID?></td>
						<td class="qcont" name="que"><?=$v->QCONT.'<br>'.$v->ACONT?></td>
						<td><?=$v->QUE_TYPE?></td>
						<td name="ans"><?=$v->ANS?></td>
						<td name="gra"><?=$v->GRA?></td>
						<td name="sub"><?=$v->SUBJ?></td>
						<td name="chp"><?=$v->CHAP?></td>
						<td name="deg"><?=$v->DEGREE?></td>
						<td></td>
						<td><?=$v->UPDATETIME?></td>
						<td class="last"><input type="button" class="btn w80" onclick="editq(<?=$v->QID?>)" value="編輯no"></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	</form>
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
					<label><input type="checkbox" name="choice_f" checked value="qno">題號</label>
					<label><input type="checkbox" name="choice_f" checked value="que">題目</label>
					<label><input type="checkbox" name="choice_f" checked value="ans">答案</label>
					<label><input type="checkbox" name="choice_f" checked value="deg">難度</label>
					<label><input type="checkbox" name="choice_f" checked value="sub">科目</label>
					<label><input type="checkbox" name="choice_f" checked value="gra">年級</label>
					<label><input type="checkbox" name="choice_f" checked value="chp">章節</label>
					<label><input type="checkbox" name="choice_f" checked value="sets">考卷</label>
					<label><input type="checkbox" name="choice_f" checked value="sh">分享對象</label><br>
					<label><input type="checkbox" name="choice_f" checked value="oans">學生提供其他詳解</label>
					<label><input type="checkbox" name="choice_f" checked value="pub">發表時間</label>
				</div>
				<div>
                	<div style="text-align:left; float:left;"><input type="button" class="btn w80 f16" value="確定" name="sure" id="sure" onclick="field_change()">&nbsp;&nbsp;<font id="field_msg" color="red"></font></div>
					<div style="text-align:right; height:30px; line-height:30px;"><a href=""><font class="f15"><a href="javascript:void(0)" onclick="close_field()">取消更改</a></font></a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="status_list" class="list_set">
	<div id="list_all">
		<div class="set_title"><label class="f17" id="list_title"></label></div>
		<div class="set_content">
			<div id="list_cen">
				<div class="set_main" id="list_main"></div>
				<div id="list_bottom">
					<div style="text-align:right; height:30px; line-height:30px;"><font class="f15"><a href="javascript:void(0)" onclick="close_list()">關閉</a></font></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="oans_list" class="list_set">
	<div id="oans_all">
		<div class="set_title"><label class="f17">其他詳解</label></div>
		<div class="set_content">
			<div id="list_cen">
				<div class="set_main" id="list_oans"></div>
				<div id="list_bottom">
					<div style="text-align:right; height:30px; line-height:30px;"><font class="f15"><a href="javascript:void(0)" onclick="close_oans()">關閉</a></font></div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
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
function goo2(epno,f_fields) {
  var rtn_data = window.open("fieldlist.php?f_pno=ex_set&self=true&epno="+epno+"&f_fields="+f_fields,"result","width=1026,height=768,resizable=yes,scrollbars=yes,location=no");
}
function delete_one(value){
  if (confirm('您確定要刪除此題目？')){
    location.href="ex_set.php?action=delete&qid="+value+"&p="+document.getElementById('p').value;
  }
}
function chk(value){//选择确认 (删题目/改分享)
  var que = new Array();
  var choice = 0;
  $('input:checkbox:checked[name="chkbox[]"]').each(function(i) { 
    if ($(this).val()!=''){
      choice = 1;
      return false;
    }
  });
  if (!choice){
    alert('您尚未勾選題目');
  }else{
    if (value=='delete'){
      if (confirm('您確定要刪除所勾選的題目？')){
        $('#action').val('deletenums');
        form1.submit();
      }
    }else if (value=='change'){
      $('#action').val(value);
      form1.submit();
    }
  }
}
function search_confirm(){
  var search = $('#f_search').val();
  var pattern = new RegExp("[`~!@#$^&()=|{}':;'-+,\\[\\].<>/?~！@#￥……&*（）——|{}【】『；：」「'。，、？]");
  var rs = "";
  for (var i = 0; i < search.length; i++) { 
      rs += search.substr(i, 1).replace(pattern, ''); 
  } 
  if (search.trim()!=''){form1.submit();}
}
function ans(value){//其他详解
    $('#oans_list').css('display','block');
    var c = $('#list_oans');
    c.html('讀取中...');
    $.getJSON("queans.php", {'value':value, 'me':false}, function(json){
        c.html('');
        var all = json.length;
        for (var i = 0; i <= all; i++) {
            c.append(
                $('<a>').attr({title:json[i].u, onclick:'window.open ("oanswer.php?oqid='+value+'&owner='+json[i].link+'","_blank","width=1100,height=700,top=300,left=100,resizable=yes,scrollbars=yes,location=no");'})
                		.append(
                    $('<img>').attr({class:'oans_pic',src:'profilepics/'+json[i].p+'.jpg'})
                ),
                $('<img>').attr({class:'oans_gold',src:'images/icon_op_gold.png'}),
                $('<font>').attr({class:'oans_num'}).append(
                	$('<a>').attr({name:value+json[i].link,onclick:"ans_g("+value+",'"+json[i].link+"')",title:'金牌數'})
                			.text(json[i].c)
                )
            );
        }
    });
}
function close_oans(){ $('#oans_list').css('display','none');}
function like(no){//按赞
    var url = "goldlike.php";
    var data = {'type': 'Q','no':no};
    $('#list_title').text('按讚');
    showdialog(url, data);
}
function showdialog(url, data){
    $('#status_list').css('display','block');
    var c = $('#list_main');
    c.html('讀取中...');
    $.getJSON( url, data, function(json){
      c.html('');
      var all = json.length;
      for (var i = 0; i <= all; i++) {
        c.append(
          $('<div>').addClass('eachone').append(
            $('<div>').addClass('person_pic').append(
              $('<a>').attr('href','profile.php?f_userid='+json[i].link).append(
                $('<img>').attr({src:'profilepics/'+json[i].p+'.jpg', title: json[i].uname})
              )
            ),
            $('<div>').addClass('person_dep').append(
              $('<div>').addClass('f16').append(
                $('<a>').attr('href','profile.php?f_userid='+json[i].link).text(json[i].uname)
              )
              ,$('<div>').addClass('f12').text(json[i].sch_name)
            )
          )
        );
      }
    });
}
function close_list(){ $('#status_list').css('display','none'); }
  function open_point(ele){
  	var elem = document.getElementById(ele);
  	var p_elem = document.getElementById('p'+ele);
  	if ($(elem).hasClass('hiden')){
  		$(elem).addClass('show');
  		$(elem).removeClass('hiden');
  		p_elem.src = 'open.png';
  	}else{
  		$(elem).removeClass('show');
  		$(elem).addClass('hiden');
  		p_elem.src = 'close.png';
  	}
    // if ($('#'+ele).css('display')=='none'){
    //   $('#'+ele).css('display','block');
    //   $('#p'+ele).attr('src','open.png');
    // }else{
    //   $('#'+ele).css('display','none');
    //   $('#p'+ele).attr('src','close.png');
    // }
  }
  function open_dans(ele){
    if ($('#'+ele).css('display')=='none'){
      $('#'+ele).css('display','block');
      $('#p'+ele).attr('src','open.png');
    }else{
      $('#'+ele).css('display','none');
      $('#p'+ele).attr('src','close.png');
    }
  }

  function editq(q){
  	//window.open("<?=site_url('/question/edit').'/'?>"+q,"_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");
  }
</script>