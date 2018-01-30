@extends('layout.default')
@section('style')
    <script type="text/javascript" src="{{ URL::asset('/js/jquery-ui.js') }}"></script>
	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		width: 1152px;
    	}
        .set_all {
            margin: 5% auto;
        }
    	#title {
    		height: 30px;
    		line-height: 30px;
    	}
        .title {
            height: 30px;
            line-height: 30px;
            margin-bottom: 5px;
            background-color: #F2D9B6;
            border-bottom: 1px #B4B5B5 solid;
            border-right: 1px #B4B5B5 solid;
            border-left: 1px #B4B5B5 solid;
            float: left;
            width: 100%;
        }
        .title label {
            margin-left: 20px;
        }
    	.title_intro{
    		line-height: 40px;
    	}
    	.title_intro div {
    		margin-left: 5px;
    	}
        .title_intro input {
            margin-left: 5px
        }
    	.title_intro label {
    		margin-right: 5px;
    		font-size: 16px;
    	}
        .title_intro label {
            padding-left: 20px;
            margin-right: 5px;
            font-size: 14px;
        }
        .sub_intro {
            line-height: 0;
            padding-left: 20px;
            margin-right: 5px;
            font-size: 14px;
            margin-bottom: 10px;
            /*display: inline-block;*/
        }
    	.result_times{
    		text-align: center;
    	}
    	#cen {
    		padding: 20px 10px 15px 10px;
    		margin: 0 auto;
    	}
    	.qno {
    		width: 45px;
    		vertical-align: middle;
    		font-size: 18px;
    	}
    	.qno_c {
    		width: 50px;
    		vertical-align: middle;
    	}
    	.qno_ans {
    		width: 55px;
    		font-size: 16px;
    		vertical-align: middle;
    	}
    	.qno_ans div {
    		margin-bottom: 5px;
    	}
    	.qno_ans input {
    		margin-right: 5px;
    	}
    	.qno_intro {
    		width: 1000px;
    	}
    	#form1 .list td:not(.handle) {
    		padding-bottom: 5px;
            border: #B4B4B5 solid thin;
    	}
        .list th {
            padding-bottom: 5px;
        }
    	.list {
    		margin-bottom: 15px;
    	}
    	.btn {
    		height: 25px;
    		border: 1px #EED6B4 solid;
    	}
    	.btn:active {
    		border: 1px gray dashed;
    	}
    	.input_field {
    		height: 25px;
    	}
        .hidden {
            display: none;
        }
        .list tr td.que {
            padding: 5px;
        }
        .last .list tr td{
            margin-bottom: 10px;
            height: 25px;
            line-height: 25px;
            padding-left: 10px;
            vertical-align: top;
        }
        .que_title {
            font-size: 15px;
            font-weight: bold;
        }
        .tip {
            padding: 5px;
            border: 1px #B4B4B5 solid;
            color: #1A1A1A !important;
            width: auto !important;
            position: absolute;
            text-align: left !important;
            margin-top: -9px;
            line-height: 15px;
            background-color: #F7F3E5;
            z-index: 2;
            display: none;
        }
        #tip_esort {
            margin-left: 20px;
        }
        #tip_csort {
            margin-left: 35px;
        }
        #tip_usort {
            margin-left: 193px;
        }
        #save_div {
            display: none;
        }
        .allans {
            display: none;
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
        #quick_no div {
            display: inline-block;
            font-size: 20px;
            border: 1px gray solid;
            line-height: 20px;
            color: white;
            background-color: gray;
            padding: 2px;
            position: relative;
            margin-right: 5px;
        }
        .now {
            background-color: #F8CD89;
            border-color: #FBCD89;
        }
        .bpart_div {
            text-align: center;
            display: inline-block;
        }
        #quick_no {
            margin: 0px 10px 0px 10px;
        }
        textarea {
            width: 500px;
            height: 65px;
            margin: 5px 0px 5px 0px;
            border: 1px #EED6B4 solid;
        }
        .deep {
            background-color: #F5F5F4;
        }
        .shallow {
            background-color: #FCFCFC;
        }
        .shallow td{
            padding: 10px 0px 10px 0px;
        }
        .part_sort {
            display: inline-block;
            visibility: hidden;
            cursor: move;
        }
        .sub_del {
            margin-right: 10px;
            /*float: right;*/
            margin-top: 5px;
            cursor: pointer;
            right: 0;
            position: absolute;
        }
        .sub_update_del {
            margin-right: 10px;
            margin-top: 5px;
            cursor: pointer;
        }
        #part_func {
            display: none;
        }
        .handle {
            vertical-align: middle;
            visibility: hidden;
            cursor: move;
        }
        .show_handle {
            visibility: visible;
        }
        #sets_filed > .set_all {
            width: 90%;
            height: 80%;
        }
        .partq {
            background-color: white;
            border-width: 2px;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<div class="title"><label class="f17">{{ $Set_name }} 摘要</label></div>
    <div class="title_intro">
        <label>總分</label>{{ $Sum }}
        <label>及格分數</label>{{ $Pass }}
	</div>
    <div class="title"><label class="f17" style="float:left;" onclick="zoom()">大題</label><img style="float:left;margin-top:5px;" id="part_img" src="{{ URL::asset('img/open.png') }}" width="20" height="20"></div>
    <div id="part_div" class="title_intro" style="padding-bottom:10px;">
        <!--<input type="button" name="" id="" onclick="moreone()" class="btn w100 h25" value="增加"> -->
        <input type="button" name="" id="" onclick="edit_sub()" class="btn w100 h25" value="編輯">　
        <input type="button" onclick="open_part()" class="btn w100 h25" name="" id="start_part" value="開啟排序">
        <span id="part_func"><input type="button" onclick="close_part()" class="btn w100 h25" name="" id="" value="關閉排序">　
        <input type="button" onclick="save_part()" class="btn w100 h25" name="" id="" value="儲存排序">　
        </span>
        <font color="red">*刪除大題，底下的題目將移至其他大題內</font>
        <br>
        <div id="part_section">{{ $Part_cont }}</div>
    </div>

    <div name="part" id="part">
        <div class="title"><label class="f17">題目</label></div>
        <div class="title_intro">
            <div id="part_que">{{ $Part_btn}}</div>
            <div><input type="button" class="btn w100" value="新增題目" onclick='window.open("{{ url("/que/create") }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");'>　<input type="button" class="btn w100 ware" value="從題庫加入"></div>
            <!-- <input type="button" class="btn w150" onclick="open_s(0)" name="esort" id="esort0" value="開啟排序">
            <div class="tip" id="tip_esort0">※開啟排序，按住每題題號可以拖曳喔</div>
            <input type="button" class="btn w150 hidden" onclick="close_s(0)" name="csort" id="csort0" value="關閉排序">
            <div class="tip" id="tip_csort0">※關閉排序，但不儲存</div>
            <input type="button" class="btn w150 hidden" onclick="save_s(0)" name="usort" id="usort0" value="儲存排序">
            <div class="tip" id="tip_usort0">※儲存並關閉排序</div>
            <input type="button" class="btn w150 hidden" name="rsort" id="rsort0" onclick="rand_sort(0)" value="隨機排序">
            <input type="button" class="btn w150 hidden" name="nsort" id="nsort0" onclick="recover_sort(0);" value="回復排序"> -->
        </div>
        <form id="form1" name="form1" method="post">
        	<div class="content">
        		<div id="cen">
                    <label id="nowpart">{{ $Part_now }}</label>
        			<table class="list" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>題號</th>
                                <th>答案</th>
                                <th>題目</th>
                            </tr>
                        </thead>
                        <tbody id="sort0">
                        @foreach ($Qdata as $k => $v)
                            <tr align="center" name="node" id="{{ $v->SQ_SORT }}">
                                <td class="handle">: :</td>
                                <td class="qno">{{ $v->SQ_SORT }}</td>
                                <td class="qno_ans">{{ $v->ANS }}</td>
                                <td width="1000" align="left" class="que">{{ $v->QCONT }}</td>
            				</tr>
                        @endforeach
                        </tbody>
        			</table>
        		</div>
        	</div>
        </form>
    </div>
</div>
<div id="sub_title" class="list_set">
    <div class="set_all">
        <div class="title"><label class="f17">編輯大題</label></div>
        <div class="set_content">
            <div class="set_cen">
                <div class="cen last">
                    <form name="form2" id="form2" method="post">
                    <input type="button" name="" id="" onclick="moreone()" class="btn w100 h25" value="增加">
                    <div style="max-height:490px; overflow:auto; overflow-x:hidden; margin:10px 0px 10px 0px;">
                        <input type="hidden" name="setid" value="{{ $SETID }}">
                        <table class="list" id="more" border="0" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="left"><label class="f17">大題</label></td>
                                <td><input type="hidden" name="sub[]" value=""></td>
                            </tr>
                            <tr class="deep">
                                <td align="right">分數比重</td>
                                <td><input style="width:40px; text-align:center;" value="" type="text" class="input_field" name="sub_score[]" maxlength="4">%</td>
                            </tr>
                            <tr class="shallow">
                                <td align="right">翻頁控制</td>
                                <td>
                                    <select name="sub_control[]">
                                        <option value="Y">可回上題修改</option>
                                        <option value="N">不可回上題修改</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="deep">
                                <td align="right" style="vertical-align:top;">大題說明</td>
                                <td>
                                    <textarea name="sub_intro[]" placeholder="範例：1-20題是非題，21-40題選擇題，共40題。" value=""></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <div style="text-align:left; float:left;"><INPUT type="button" class="btn w150 f16" value="儲存" onclick="check_data()" name="update" id="update"></div>
                        <div style="text-align:right; height:30px; line-height:30px;"><a href="javascript:void(0)" onclick="cancel('u')"><font class="f15">取消</font></a></div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="intro_open"></div>
<div id="intro_all">
    <div id="intro_content" class="set_content">
        <div id="intro_title">更新中...</div>
    </div>
</div>
<form id="setsort" name="setsort">
    <input type="hidden" name="node" id="node">
    <input type="hidden" name="s" id="s" value="{{ $SETID }}">
    <input type="hidden" name="t" id="t" value="p">
</form>
<div id="sets_filed" class="list_set">
    <div class="set_all">
        <img src="{{ URL::asset('img/loading.gif') }}" id="loading_status">
        <iframe width="100%" height="100%" id="que_pic"></iframe>
        <div><input type="button" style="float:right;" name="" id="" value="關閉" class="btn w100" onclick="close_pic()"></div>        
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="{{ URL::asset('/jsfunc/sets_review.js') }}"></script>
<script type="text/javascript">

window.moveTo(0,0);window.resizeTo(screen.width,screen.height);
// When the document is ready set up our sortable with it's inherant function(s)
var n=2;
var b=3,c=4; 
eval('a'+n+'=b*c');

for (var i = 1; i<=1; i++) {
    eval('original_sort'+i+' = new Array()');
    $("#sort"+i+" > tr[name=node]").each(function(j){
        eval('original_sort'+i+'[j] = '+$(this).attr('id'));
    });
    eval('original_len'+i+' = original_sort'+i+'.length-1');
    eval('newsort'+i+' = new Array()');
    for(var j=0;j<=original_len;j++){
        eval('newsort'+i+'[j] = original_sort'+i+'[j]');
    }
};
<?php 
for($i=1;$i<=1;$i++){
    //echo 'var original_sort'.$i.' = new Array();';
}
?>
// var original_sort = new Array();
// $(".list tr[name=node]").each(function(i){
//     original_sort[i] = $(this).attr('id');
// });
// var original_len = original_sort.length-1;
// var newsort = new Array();//給隨機用的
// for(var i=0;i<=original_len;i++){
//     newsort[i]=original_sort[i];
// }
$('document').ready(function() {
    for(var i=1;i<=1;i++){
      var sort = gb('sort'+i);
      $(sort).sortable({
        handle: '.handle',
        opacity: 0.6,
        //拖曳時透明
        cursor: 'move',
        //游標設定
        axis:'y',
        //只能垂直拖曳
        update : function () { 
        } 
      });
      $(sort).sortable('disable');
    }
    var ps = gb('part_section');
      $(ps).sortable({
        handle: '.part_sort',
        opacity: 0.6,
        //拖曳時透明
        cursor: 'move',
        //游標設定
        axis:'y',
        //只能垂直拖曳
        update : function () { 
        } 
      });
      $(ps).sortable('disable');
});
function open_part(){
    var sort = gb('part_section');
    $(sort).sortable('enable');
    $(sort).find('.part_sort').css('visibility','visible');
    var start = gb('start_part');
    $(start).hide();
    var func = gb('part_func');
    $(func).show();
}
function close_part(){
    var sort = gb('part_section');
    $(sort).sortable('disable');
    $(sort).find('.part_sort').css('visibility','hidden');
    var start = gb('start_part');
    $(start).show();
    var func = gb('part_func');
    $(func).hide();
}
function save_part(){
    var d=Array();
    var f=0;
    $("div[name=node]").each(function(){
        d[f]=$(this).attr('id');
        f++
    });
    gb('node').value = JSON.stringify(d);
    $('#intro_open').show();
    $('#intro_all').show();
    $.ajax({
        type:"POST",
        url:'{{ url('/sets/update_setsort') }}',
        dataType:'json',
        data: $('#setsort').serialize(),
        success: function(data){
            location.reload();
        }
    });
}
function del_ask(s,v,i){
    if (confirm('確定刪除第'+i+'大題?')){
        var getdata = {set:s,v:v,i:i};
        $.ajax({
            type:'POST',
            url:'ex_sets_update.php',
            dataType:'json',
            data:getdata,
            success: function(data, textStatus, jqXHR){
                if (data.code==7){
                    //alert('變更成功');
                    location.reload();
                }
            }
        });
    }
}
function open_s(i){
    var sort = gb('sort'+i);
    $(sort).sortable('enable');
    $(sort).find('.handle').addClass('show_handle');
    gb('esort'+i).style.display='none';
    gb('csort'+i).style.display='inline-block';
    gb('usort'+i).style.display='inline-block';
    gb('nsort'+i).style.display='inline-block';
    gb('rsort'+i).style.display='inline-block';
}
function close_s(i){
    var sort = gb('sort'+i);
    $(sort).sortable('disable');
    $(sort).find('.handle').removeClass('show_handle');
    gb('esort'+i).style.display='inline-block';
    gb('csort'+i).style.display='none';
    gb('usort'+i).style.display='none';
    gb('nsort'+i).style.display='none';
    gb('rsort'+i).style.display='none';
}

function recover_sort(i){
    eval('ori_len = original_len'+i);
    eval('ori = original_sort'+i);
        for (var j = ori_len; j>=0; j--) {
        $('#'+ori[j]).insertBefore($('#'+ori[j+1]));
    }
}
function rand_sort(i){
    eval('ori_len = original_len'+i);
    eval('newsort = newsort'+i);
    newsort.sort(shuffle);
    for (var j = 0; j <ori_len; j++) {
        $('#'+newsort[j]).insertAfter($('#'+newsort[j+1]));
    }
}
function shuffle(a,b) {
  var num = Math.random() > 0.5 ? -1:1;
  return num;
}
function save_s(id){
    $('.sort').sortable('disable');
    var d=Array();
    var f=0;
    $(".list tr[name=node]").each(function(){
        d[f]=$(this).attr('id');
        f++
    });
    var c=JSON.stringify(d);
    $('#intro_open').show();
    $('#intro_all').show();
    $.post("update_setsort.php",{node:c,s:id,t:'q'},function(g){
        if(g.success){
            location.reload();
        }
    },"json");
}
// $('#esort').mouseover(function() { $('#tip_esort').css('display','block'); });
// $('#esort').mouseout(function() { $('#tip_esort').css('display','none'); });
// $('#csort').mouseover(function() { $('#tip_csort').css('display','block'); });
// $('#csort').mouseout(function() { $('#tip_csort').css('display','none'); });
// $('#usort').mouseover(function() { $('#tip_usort').css('display','block'); });
// $('#usort').mouseout(function() { $('#tip_usort').css('display','none'); });
// function open_ans(){
//     $('.allans').show();
//     document.getElementById('enab_update_ans').classList.add('hidden');
//     document.getElementById('save_div').style.display='inline-block';
//     document.getElementById('cancel_ans').classList.remove('hidden');
// }
// function close_ans(){
//     $('.allans').hide();
//     document.getElementById('enab_update_ans').classList.remove('hidden');
//     document.getElementById('save_div').style.display='none';
//     document.getElementById('cancel_ans').classList.add('hidden');
// }
// function not_free(q){
//     document.getElementById('free'+q).checked = false;
// }
// function free_chk(q){
//     if (document.getElementById('free'+q).checked){
//         $('input[name="correct_ans'+q+'[]"]:checked').attr('checked',false);
//     }
// }
function save_newans(){

}
function view(i){
    //every
    // var ipart = document.getElementsByName('intro');
    // $(ipart).hide();
    var bpart = document.getElementsByName('bpart');
    $(bpart).removeClass('now');
    var part = document.getElementsByName('part');
    $(part).hide();
    //now
    // var ipart_now = document.getElementById('intro'+i);
    // $(ipart_now).show();
    var bpart_now = document.getElementById('bpart'+i);
    $(bpart_now).addClass('now');
    var part_now = document.getElementById('part'+i);
    $(part_now).show();
}
var add = 0;
function update_recover(i){
    var sub = document.getElementsByName('nsub'+i);
    $(sub).remove();
}
function moreone(){
    add++;
    //var more = document.getElementById('more');
    // $(more).append(
    //     $('<tr>').attr('name','sub'+add).append(
    //         $('<td>').attr('align','left').append( $('<label>').attr('class','f17').text('新大題') ),
    //         $('<td>').append($('<img>').attr({src:'images/icon_op_f.png', onclick:'update_recover('+add+')',width:'15',height:'15',class:'sub_update_del'}))
    //     ),
    //     $('<tr>').attr({name:'sub'+add,class:'deep'}).append(
    //         $('<td>').attr('align','right').text('分數比重'),
    //         $('<td>').append( $('<input>').attr({style:'width:40px; text-align:center;',type:'text',class:'input_field',name:'sub_score[]',maxlength:4}),'%' )
    //     ),
    //     $('<tr>').attr({name:'sub'+add,class:'shallow'}).append(
    //         $('<td>').attr('align','right').text('翻頁控制'),
    //         $('<td>').append(
    //             $('<select>').attr('name','sub_control[]').append(
    //                 $('<option>').attr('value','Y').text('可回上題修改'),
    //                 $('<option>').attr('value','N').text('不可回上題修改')
    //     ))),
    //     $('<tr>').attr({name:'sub'+add,class:'deep'}).append(
    //         $('<td>').attr({align:'right',style:'vertical-align:top;'}).text('大題說明'),
    //         $('<td>').append( $('<textarea>').attr({name:'sub_intro[]',placeholder:'選擇題，'}) )
    //     )
    // );
    var html = '<tr name="nsub'+add+'"><td align="left"><label class="f17">新大題</label></td><td><img src="{{ URL::asset('img/icon_op_f.png') }}" onclick="update_recover('+add+')" width="15" height="15" class="sub_update_del"><input type="hidden" name="sub[]" value=""></td></tr><tr name="nsub'+add+'" class="deep"><td align="right">分數比重</td><td><input type="text" name="sub_score[]" maxlength="4" style="width:40px; text-align:center;" class="input_field">%</td></tr><tr name="nsub'+add+'" class="shallow"><td align="right">翻頁控制</td><td><select name="sub_control[]"><option value="Y">可回上題修改</option><option value="N">不可回上題修改</option></select></td></tr><tr name="nsub'+add+'" class="deep"><td align="right" style="vertical-align:top;">大題說明</td><td><textarea name="sub_intro[]" placeholder="選擇題"></textarea></td></tr>';
    $(gb('more')).append(html);
}
function edit_sub(){
    $(gb('more')).html('');
    $.ajax({
        type:'GET',
        url:'{{ url('sets/ajsub'.'/'.$SETID) }}',
        dataType:'json',
        success: function(data, textStatus, jqXHR){
            var html = '';
            for (var i in data){
                var con_Y = (data[i].control==="Y") ? 'selected':'';
                var con_N = (data[i].control==="N") ? 'selected':'';
                var j = Number(i)+1;
                html+= '<tr name="sub'+data[i].sid+'"><td align="left"><label class="f17">大題'+j+'</label></td><td><img src="{{ URL::asset('img/icon_op_f.png') }}" onclick="update_recover('+data[i].sid+')" width="15" height="15" class="sub_update_del"><input type="hidden" name="sub[]" value="'+data[i].sid+'"></td></tr><tr name="sub'+data[i].sid+'" class="deep"><td align="right">分數比重</td><td><input type="text" name="sub_score[]" value="'+data[i].percen+'" maxlength="4" style="width:40px; text-align:center;" class="input_field">%</td></tr><tr name="sub'+data[i].sid+'" class="shallow"><td align="right">翻頁控制</td><td><select name="sub_control[]"><option '+con_Y+' value="Y">可回上題修改</option><option '+con_N+' value="N">不可回上題修改</option></select></td></tr><tr name="sub'+data[i].sid+'" class="deep"><td align="right" style="vertical-align:top;">大題說明</td><td><textarea name="sub_intro[]" placeholder="選擇題" value='+data[i].intro+'>'+data[i].intro+'</textarea></td></tr>';
            };
            $(gb('more')).html(html);
        }
    });
    $(gb('sub_title')).show();   
}
function cancel(c){
    var more;
    if (c=='c'){
        more = gb('create');
    }else{
        more = gb('sub_title');
    }
    $(more).hide();
    
}

function check_data(){
    var error = false;
    var percen = 0;
    $('input[name="sub_score[]"]').each(function(){
        if (isNaN(this.value)){
            error = true; alert('分數比重只能數字'); return false;
        }
        if (this.value=='' || this.value<1){
            error = true; alert('分數比重至少為1'); return false;
        }
        percen+=Number(this.value);
    });
    if (error)return false;
    if (percen!=100){
        alert('分數比重總和需為100'); return false;
    }
    $('textarea[name="sub_intro[]"]').each(function(){
        if (this.value==''){
            error = true;
            alert('大題說明請確實填寫');
            return false;
        }
    });
    if (error)return false;
    $('#intro_open').show();
    $('#intro_all').show();
    $.ajax({
        type:'POST',
        url:'{{ url('/sets/subu') }}',
        dataType:'json',
        data:$('#form2').serialize(),
        success: function(){
            location.reload();
        }
    });
}
function zoom(){
    if ($(gb('part_div')).css('display')=='block'){
        gb('part_img').src = '{{ URL::asset('img/close.png') }}';
        $(gb('part_div')).hide();
    }else{
        gb('part_img').src = '{{ URL::asset('img/open.png') }}';
        $(gb('part_div')).show();
    }
}
$(".ware").on('click', function(){
    document.getElementById('que_pic').src="{{ url('/question/join') }}";
    $('#sets_filed').show();
    $('#loading_status').show();
    $("#que_pic").load(function(){
        $('#loading_status').hide();
        $('#que_pic').show();
    });
});

function close_pic(){
    $('#sets_filed').hide();
    $('#que_pic').hide();
}
</script>