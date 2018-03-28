<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('layout.sub')
	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		width: 1152px;
    	}
    	.cen {
    		margin: 0 auto;
    		padding: 20px 0px 10px 0px;
    		margin: 0px 20px 0px 20px;
    	}
    	#sets_title {
    		height: 30px;
    		line-height: 30px;
    		margin-bottom: 5px;
    		background-color: white;
    		border-bottom: 1px #B4B5B5 solid;
    		border-right: 1px #B4B5B5 solid;
    		border-left: 1px #B4B5B5 solid;
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
    	#sets_title label{
    		margin-left: 20px;
    	}
    	.title label {
    		margin-left: 20px;
            float: left;
    	}
    	.input_field {
    		margin:0px;
            width: 500px;
    	}
    	.btn {
    		font-size: 14px;
    	}
    	.f14{
    		margin-left: 5px;
    		margin-right: 5px;
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
    	#duty td {
    		padding-bottom: 0px;
    	}
    	.list tr td{
    		margin-bottom: 10px;
    		height: 25px;
    		line-height: 25px;
    		padding-left: 10px;
    		vertical-align: top;
    	}
    	.list label {
    		margin-right: 5px;
    	}
    	.list input {
    		margin-right: 5px;
    	}
    	.table_last {
    		margin-bottom: 20px;
    	}
    	.oans {
    		display: none;
    	}
    	.oans_control, .oans_control label {
    		cursor: pointer;
            float: left;
    	}
        .oans_control label {
            float: left;
        }
        .oans_pic {
            float: right;
        }
        .title_pic {
            float: left;
            width: 25px;
            height: 25px;
            margin-top: 3px;
        }
        /*#oans_pic {
            float: right;
            margin-top: 2px;
        }*/
    	select {
    		margin-right: 5px;
    	}
    	textarea {
    		width: 500px;
    		height: 60px;
    		margin: 5px 0px 5px 0px;
            border: 1px #EED6B4 solid;
    	}
        .custom-combobox {
            position: relative;
            display: inline-block;
        }
        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
        }
        .custom-combobox-input {
            margin: 0 !important;
            padding: 5px 10px;
        }
        .set_all {
            margin: 2% auto;
            width: 960px;
            text-align: center;
        }
        #more_btn {
            text-align: center;
            background-color: #F8CDB9;
        }
        #more_btn:hover {
            background-color: #FCE3CE;
            cursor: pointer;
        }
        .move {
            display: inline-block;
            margin-left: 20px;
        }
        #ans_group {
            display: inline-block;
        }
        .error_msg {
            color: red;
        }
        #que_pic {
            display: none;
        }
        #loading_status {
            width: 48px;
        }
        #correct_ans_math span{
            width: 40px;
            display: inline-block;
        }
        #correct_ans_math div:last-child {
            border: 0.5px #E6E6E6 solid;
            display: inline-block;
            padding: 0px 5px 0px 5px;
        }
        #correct_ans_math div {
            border: 0.5px #E6E6E6 solid;
            display: inline-block;
            padding: 0px 5px 0px 5px;
            border-bottom: none;
        }
        .audio {
            /*width: 300px;*/
        }
        /*#partd {
            display: none;
        }*/
        .math {
            display: none;
        }
        .hiden {
            display: none;
        }
        .show {
            display: block;
        }
        #qimg_content, .qimg_content {
            display: inline-block;
        }
        .remove_sub {
            float: right;
            margin-right: 5px;
            font-size: 30px;
            height: 27px;
            line-height: 27px;
            cursor: pointer;
        }
	</style>
</head>
<body>
<div id="all">
	<div class="title"><label class="f17">{{ $title }}</label></div>
	<FORM name="form1" id="form1" method="POST" enctype="multipart/form-data">
    <div class="content" id="first">
		<div class="cen">
			<table class="list" id="que_main" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left"><label class="f17">題組題目</label></td>
                    <td><a href="{{ url('/ques/create') }}"><input type="button" value="題型改為非題組"></a></td>
                </tr>
                <tr class="shallow">
                    <td align="right">題組說明</td>
                    <td width="80%"><textarea  name="gpcontent" id="gpcontent" cols="50" rows="4" value=""></textarea>
                    <br>
                        <input type="file" name="qpic" id="qpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG
                    <br>
                    <input type="file" name="qsound" id="qsound" accept="audio/mp3">格式：MP3
                    <br>
                    <font class="f12">*題目文字說明或圖檔不可空白</font>
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">知識點</td>
                    <td>
                        <input type="hidden" id="f_pid" name="f_pid" value=""/>
                        <div><font color="green">*「知識點」有助於學生在看診斷報告時，對題目的解答較易於融會貫通噢~</font></div>
                        <div><input type="button" id="addpoint" value="選擇知識點"><div id="point_content" style="display: inline-block;"></div></div>
                    </td>
                </tr>
                <tr>
                    <td align="left"><label class="f17">範圍</label></td>
                    <td><label><input type="checkbox" onclick="all_set(this)">統一設定</label></td>
                </tr>
                <tr class="deep allgsc hiden">
                    <td align="right">年級<font color="red">＊</font></td>
                    <td>
                        <select class="grade" name="grade" id="grade0" onchange="subj_c(this)">{!! $Q_Grade !!}</select>
                    </td>
                </tr>
                <tr class="shallow allgsc hiden">
                    <td align="right">科目<font color="red">＊</font></td>
                    <td id="subj">
                        <select class="subject" name="subject" id="subject0" onchange="chap_c(this)">{!! $Q_Subject !!}</select>
                    </td>
                </tr>
                <tr class="deep allgsc hiden">
                    <td align="right">章節<font color="red">＊</font></td>
                    <td>
                        <div class="ui-widget">
                            <select class="chapter" name="f_chapterui" id="chapter0">
                                <option value=""></option>{!! $Q_Chapter !!}
                            </select>
                            <label class="chapter_msg" style="margin-left:40px;"></label>
                        </div>
                    </td>
                </TR>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div id="more">
    </div>
    <div class="title" id="more_btn" onclick="more_one()"><font class="f17">增加小題</font></div>
    <div class="content" style="margin-bottom:50px;">
        <div class="cen" style="padding-bottom:50px;">
            <div style="text-align:left;">
                {{ csrf_field() }}
                <input type="submit" class="btn w150 h30" value="存檔，出下一題" name="save_next" id="save_next" onclick="check('n')">
                <input type="button" class="btn w150 h30" value="存檔，離開" name="save_close" id="save_close" onclick="check('c')">
            </div>
        </div>
    </div>
    </form>
</div>
<div id="sets_filed" class="list_set">
    <div class="set_all">
        <img src="{{ URL::asset('img/loading.gif') }}" id="loading_status">
        <iframe width="1500" height="920" id="que_pic"></iframe>
        <input type="button" style="float:right;" name="" id="" value="關閉" class="btn w100" onclick="close_pic()">
    </div>
</div>
<div id="posting" class="list_set">
    <div class="set_all">
        <div class="set_content">
            <div class="set_cen">
            處理中...
            </div>
        </div>
    </div>
</div>
<div id="que_know" class="list_set">
    <div class="set_all">
        <img src="{{ URL::asset('img/loading.gif') }}" id="loading_kstatus">
        <iframe width="1500" height="100%" id="qknows"></iframe>
        <input type="button" style="float:right;" name="" id="" value="關閉" class="btn w100" onclick="close_know()">
    </div>
</div>
</body>
</html>
<script type="text/javascript">
//change_type('S');
let now_range;
let set_only = false;
function gbc(v){
    return document.querySelectorAll(v);
}
function show_oans(elem){
    var oans = $('#'+elem);
    if (oans.css('display')=='none'){
        oans.css('display','table');
        $('#pic_'+elem).prop('src','{{ URL::asset('img/open.png') }}');
    }else{
        oans.css('display','none');
        $('#pic_'+elem).prop('src','{{ URL::asset('img/close.png') }}');
    }
}
window.moveTo(0,0);
//window.resizeTo(screen.width,screen.height);
window.focus();
// function get_data2(f_qid,f_type) {
//     window.open("upvs_2.php?f_qid="+f_qid+"&f_type="+f_type,null,'width=700px,height=500px,resizable=yes,scrollbars=yes,status=yes');
// }
(function( $ ) {
    $.widget( "custom.combobox", {
        _create: function() {
           this.wrapper = $( "<span>" )
               .addClass( "custom-combobox" )
               .insertAfter( this.element );
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },
        _createAutocomplete: function() {
            var selected = this.element.children( ":selected" ),
                value = selected.val() ? selected.text() : "";
            //if(''!=$( "#f_chapter" ).val()&&''==value) value=$( "#f_chapter" ).val();
            this.input = $( "<input>" )
                .appendTo( this.wrapper )
                .val( value )
                .attr( "title", "" )
                .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy( this, "_source" )
                })
                .tooltip({
                    tooltipClass: "ui-state-highlight"
               });
    
            this._on( this.input, {
                autocompleteselect: function( event, ui ) {
                    ui.item.option.selected = true;
                    this._trigger( "select", event, {
                        item: ui.item.option
                    });
                },
                autocompletechange: "_removeIfInvalid"
            });
        },
        _createShowAllButton: function() {
            var input = this.input,
            wasOpen = false;
            $( "<a>" ).attr( "tabIndex", -1 )
                      .attr( "title", "顯示此年級、科目下所有章節" )
                      .tooltip()
                      .appendTo( this.wrapper )
                      .button({
                        icons: {
                           primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                      })
                      .removeClass( "ui-corner-all" )
                      .addClass( "custom-combobox-toggle ui-corner-right" )
                      .mousedown(function() {
                        wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                      })
            .click(function() {
               input.focus();
    // Close if already visible
                   if ( wasOpen ) { return; }
    // Pass empty string as value to search for, displaying all results
               input.autocomplete( "search", "" );
            });
        },
        _source: function( request, response ) {
            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
            response( this.element.children( "option" ).map(function() {
                var text = $( this ).text();
                if ( this.value && ( !request.term || matcher.test(text) ) )
                return {
                    label: text,
                    value: text,
                    option: this
                };
            }) );
        },
        _removeIfInvalid: function( event, ui ) {
           // $( "#f_chapter" ).val(this.input.val());
        },
        _destroy: function() {
           this.wrapper.remove();
           this.element.show();
        }
    });
})( jQuery );
// $("#more").ready(function(){
//     $(".chapter").combobox();
// });

$("#more").ready(function() {
    $(".chapter").combobox();
    // $( ".f_grade" ).combobox();    
});
// function delete_que(){//刪除
//     if (confirm('刪除後以上您輸入的資料都會消失，您確定要刪除嗎?')){
//         location.href="ex_md.php?act=delete";
//     }
// }
function all_set(obj){
    set_only = obj.checked;
    range_set();
}
function range_set(){
    if (set_only){
        $(".gsc").addClass('hiden');
        $(".allgsc").removeClass('hiden');
    }else{
        $(".gsc").removeClass('hiden');
        $(".allgsc").addClass('hiden');
    }
}
var rows_m = 1;
function subj_c(obj){
    let ind = $(".grade").index(obj);
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'alt':'info', 'type':'subj', 'g':obj.value},
        success: function(rs){
            gbc(".subject")[ind].innerHTML = '';
            // $(".subject")[ind].innerHTML = '';
            // gb('subject'+ind).innerHTML = '';
            let html = '';
            for(let i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            gbc(".subject")[ind].innerHTML = html;
            // $(".subject")[ind].innerHTML = html;
            // gb('subject'+ind).innerHTML = html;
            chap_c(gbc(".subject")[ind]);
        },
        error: function(rs){
            switch(rs.status){
                case 400: alert('例外錯誤'); break;
                case 401: alert('登入逾時，請重新登入'); break;
                case 406: 
                    gbc(".subject")[ind].innerHTML = '<option value="">無資料</option>'; 
                    gbc(".chapter")[ind].innerHTML = '';
                    $('.custom-combobox-input')[ind].value = '';
                    gbc(".chapter_msg")[ind].innerHTML = '無資料'; 
                    break;
            }
        }
    });
}
function chap_c(obj){
    let ind = $(".subject").index(obj);
    $('.custom-combobox-input')[ind].value = '';
    // $('#f_chapterui').empty();
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'alt':'info', 'type':'chap', 'g':gb('grade'+ind).value, 's':obj.value},
        success: function(rs){
            gbc(".chapter")[ind].innerHTML = '';
            // gb('chapter'+ind).innerHTML = '';
            let html = '';
            for(let i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            gbc(".chapter")[ind].innerHTML = html;
            gbc(".chapter_msg")[ind].innerHTML = '';
            // gb('chapter'+ind).innerHTML = html;
            // gb('chapter'+ind+'_msg').innerHTML = ''; 
        },
        error: function(rs){
            switch(rs.status){
                case 400: alert('例外錯誤'); break;
                case 401: alert('登入逾時，請重新登入'); break;
                case 406: 
                    gbc(".chapter")[ind].innerHTML = '';
                    // gb('chapter'+ind).innerHTML = ''; 
                    $('.custom-combobox-input')[ind].value = '';
                    gbc(".chapter_msg")[ind].innerHTML = '無資料'; 
                    // gb('chapter'+ind+'_msg').innerHTML = '無資料'; 
                    break;
            }
        }
    });
}

function uque(v){
    if (v==="dque"){
        $.ajax({
            type:"POST",
            url:"{{ url('/ques/rmpic') }}",
            data:{'type':v},
            dataType:"JSON",
            success: function(rs){
                gb('qimg_content').innerHTML = rs.html;
                gb('qimg').src = '';
                gb('f_qimg').value = '';
            }
        });
        return;
    }
    document.getElementById('que_pic').src="{{ url('/ques/qupload') }}?type="+v;
    $('#sets_filed').show();
    $('#loading_status').show();
    $("#que_pic").load(function(){
        $('#loading_status').hide();
        $('#que_pic').show();
    });
}
function uans(v){
    if (v==="dans"){
        $.ajax({
            type:"POST",
            url:"{{ url('/ques/rmpic') }}",
            data:{'type':v},
            dataType:"JSON",
            success: function(rs){
                gb('aimg_content').innerHTML = rs.html;
                gb('aimg').src = '';
                gb('f_aimg').value = '';
            }
        });
        return;
    }
    document.getElementById('que_pic').src="{{ url('/ques/qupload') }}?type="+v;
    $('#sets_filed').show();
    $('#loading_status').show();
    $("#que_pic").load(function(){
        $('#loading_status').hide();
        $('#que_pic').show();
    });
}
function close_pic(){
    $('#sets_filed').hide();
    $('#que_pic').hide();
}

$("#addpoint").on('click', function(){
    document.getElementById('que_pic').src="{{ url('/know/join') }}";
    openframe();
});
$("#know_div").on("click", "#pcancell", function(){
    gb('f_pid').value = '';
    gb('pid_name').innerHTML = '';
    gb('pid_cancell').innerHTML = '';
});

function close_know(){
    $('#que_know').hide();
    $('#qknows').hide();
}
function openframe(){
    gb("qknows").style.width = '100%';
    gb("qknows").style.height = screen.height*0.7+'px';
    // $('#que_pic').attr('width','100%');
    // $('#que_pic').attr('height',screen.height*0.8);
    $('#que_know .set_all').css('width','90%');
    $('#que_know').show();
    $('#loading_kstatus').show();
    $("#qknows").load(function(){
        $('#loading_kstatus').hide();
        $('#qknows').show();
    });
}
function trim(value){
    return value.replace(/^\s+|\s+$/g, '');
}
var action = false;
function done(){
    action = true;
}
function form_check(obj){
    if (data_check()){
        alert('請確認無誤');
        return false;
    }
}
function data_check(){
    var no = '';
    var error = false;
    var i = 0;
    $('#form1 input[name="f_imgsrc[]"]').each(function(){
        no = this.id;
        no = no.substring(8);
        var quetxt = $('#f_quetxt'+no).val();
        var img = $(this).val();
        i++;
        if (quetxt=='' && img==''){
            error = true;
        }
    });
    var correct_ans = $('input[name="ans[]"]:checked').val();
    if (correct_ans==null){
        document.getElementById('ans_group_error').innerHTML = '(X) 設定答案';
    }else{
        document.getElementById('ans_group_error').innerHTML = '';
    }
    var chapter = document.getElementById('f_chapter').value;
    if (trim(chapter)==''){
        error = true;
        document.getElementById('chapter_error').innerHTML = '(X) 章節勿空白';
    }else{
        document.getElementById('chapter_error').innerHTML = '';
    }
    if ($('input[name=f_qus_type]:checked').val()==4){
        if (i<2){
            error = true;
            alert('請增加小題');
        }
    }
    return error;
}
//var originurl = opener.location.href;
function check(act){
    var q=0;
    var error = false;//data_check();
    if (!error){
        //背景post
        $('#posting').show();
        var type = 'a';
        if (type=='feedback'){ $('#handle_type').val(act); }
        $.ajax({
            type:"POST",
            url:"{{ url('/ques') }}",
            data:new FormData(gb('form1')),
            contentType: false,
            processData: false,
            cache: false,
            dataType:"JSON",
            success: function(rs){
                opener.location.href = '{{ url('/ques') }}';
                if (act==="c"){
                    window.close();
                }else{
                    window.location.reload();
                }
            }
        });
    }
}
function change_type(ans_t, obj = null){//選項設定
    let num, html = '';
    switch(ans_t){
        case 'S'://單選
            $(".ans_type").css('display','table-row');
            if (obj!==null){
                let id = $(".option_num").index(obj);
                num = obj.value;
                for (let r =1; r <=num; r++) {
                    j = String.fromCharCode(r+64);
                    html+= '<label><input name="ans'+(id+1)+'[]" type="radio" value="'+r+'"><font id="ans_'+r+'">'+j+'</font></label>';
                }
                $(".ans_group")[id].innerHTML = html;
                return;
            }
            $(".option_num").each(function(i){
                num = this.value;
                for (let r =1; r <=num; r++) {
                    j = String.fromCharCode(r+64);
                    html+= '<label><input name="ans'+(i+1)+'[]" type="radio" value="'+r+'"><font id="ans_'+r+'">'+j+'</font></label>';
                }
                $(".ans_group")[i].innerHTML = html;
            });
            break;
        case 'D'://複選
            $(".ans_type").css('display','table-row');
            $(".option_num").each(function(i){
                num = this.value;
                for (let i =1; i <=num; i++) {
                    j = String.fromCharCode(i+64);
                    html+= '<label><input name="ans[]" type="checkbox" value="'+i+'"><font id="ans_'+i+'">'+j+'</font></label>';
                }
                $(".ans_group")[i].innerHTML = html;
            });
            // var num = gb('option_num').value;
            // var html = '';
            // for (var i =1; i <=num; i++) {
            //     j = String.fromCharCode(i+64);
            //     html+= '<label><input name="ans[]" type="checkbox" value="'+i+'"><font id="ans_'+i+'">'+j+'</font></label>';
            // }
            // $('#form1  #ans_group').html(html);
            break;
        case 'R'://是非
            html+= '<label><input type="radio" name="ans[]" value="1" checked>O</label>  <label><input type="radio" name="ans[]" value="2">X</label>';
            $('#form1 tr[name=ans_type]').css('display','none');
            // $('#form1 #ans_group').html(html);
            $(".ans_group").innerHTML = html;
            break;
    }
}
function optnum(obj){//選項數擷取
    var type = $(".ans_type").val();
    change_type(type, obj);
}
function rem(elem,no){
    if (confirm('檔案無法復原，確定?')){
        var obj = $('#f_'+elem+no).val();
        $.getJSON("rem_file.php", {type:elem,file:obj,no:no}, function(data){
            if (no==''){
                $('#'+elem+'_content').html(data);
            }else{
                $('#q'+no+'_'+elem+'_content').html(data);
            }
        });
    }
}
function oc(id){
    var cont = $('#'+id+'content');
    if (cont.css('display')=='block'){
        cont.css('display','none');
        $('#'+id+'oc_pic').prop('src',"{{ URL::asset('img/close.png') }}");
    }else{
        cont.css('display','block');
        $('#'+id+'oc_pic').prop('src',"{{ URL::asset('img/open.png') }}");
    }
}
let sub_nums = 1;
let sub_no = 1;
function more_one(){
    sub_no = $("#more > .qpart").length;
    $("#more").append('<div class="qpart" id="q'+sub_nums+'"><div class="title"><label class="subno f17 no">第'+sub_no+'小題</label><span class="remove_sub">&times;</span></div><div class="content"><div class="cen"><table class="list" border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td align="left"><label class="f17">題目</label></td><td></td></tr><tr class="deep"><td align="right">題型<font color="red">＊</font></td><td width="80%"><label><input type="radio" name="f_qus_type" checked value="S" onchange="change_type(this.value)">單選題</label><label><input type="radio" name="f_qus_type" value="D" onchange="change_type(this.value)">複選題</label><label><input type="radio" name="f_qus_type" value="R" onchange="change_type(this.value)">是非題</label></td></tr><tr class="deep"><td align="right">題目說明</td><td><textarea  name="gpcontent" id="gpcontent" cols="50" rows="4" value="" placeholder="文字說明、圖片、音訊，最少擇一"></textarea><br><input type="file" name="qpic" id="qpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG<br><input type="file" name="qsound" id="qsound" accept="audio/mp3">格式：MP3</td></tr><tr><td align="left"><label class="f17">選項</label></td><td></td></tr><tr class="shallow" class="ans_type"><td align="right">選項個數<font color="red">＊</font></td><td><select name="option_num" class="option_num" onchange="optnum(this)"><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></td></tr><tr class="deep" class="simple"><td align="right">正確答案<font color="red">＊</font></td><td width="80%"><div class="ans_group"><label><input type="radio" name="ans'+sub_nums+'[]" value="1"><font id="ans_1">A</font></label><label><input type="radio" name="ans'+sub_nums+'[]" value="2"><font id="ans_2">B</font></label></div><label id="ans_group_error" class="error_msg"></label></td></tr><tr class="deep math"><td align="right">選項題數<font color="red">＊</font></td><td width="80%"><select name="num" id="num" onchange="num_change(this.value)"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></td></tr><tr class="shallow math"><td align="right">正確解答<font color="red">＊</font></td><td class="correct_ans_math"><div id="a1"><span>No.1</span><label><input type="radio" name="ans1" value="1">1</label><label><input type="radio" name="ans1" value="2">2</label><label><input type="radio" name="ans1" value="3">3</label><label><input type="radio" name="ans1" value="4">4</label><label><input type="radio" name="ans1" value="5">5</label><label><input type="radio" name="ans1" value="6">6</label><label><input type="radio" name="ans1" value="7">7</label><label><input type="radio" name="ans1" value="8">8</label><label><input type="radio" name="ans1" value="9">9</label><label><input type="radio" name="ans1" value="0">0</label><label><input type="radio" name="ans1" value="a">-</label><label><input type="radio" name="ans1" value="b">±</label></div></td></tr><tr class="gsc"><td align="left" colspan="2"><label class="f17">範圍</label></td></tr><tr class="deep gsc"><td align="right">年級<font color="red">＊</font></td><td width="80%"><select class="grade" name="grade" id="grade'+sub_nums+'" onchange="subj_c(this)">{!! $Q_Grade !!}</select></td></tr><tr class="shallow gsc"><td align="right">科目<font color="red">＊</font></td><td><select class="subject" name="f_subject" id="subject'+sub_nums+'" onchange="chap_c(this)">{!! $Q_Subject !!}</select></td></tr><tr class="deep gsc"><td align="right">章節<font color="red">＊</font></td><td><div class="ui-widget"><select class="chapter" id="chapter'+sub_nums+'" name="f_chapterui"><option value=""></option>{!! $Q_Chapter !!}</select><label class="chapter_msg" style="margin-left:40px;"></label></div></td></TR><tr class="shallow"><TD align="right">難易度</TD><td><label><input type="radio" name="f_degree1[]" value="E">容易</label><label><input type="radio" name="f_degree1[]" value="M">中等</label><label><input type="radio" name="f_degree1[]" value="H">困難</label></TD></TR><tr><td><label class="f17 oans_control" id="oans_control'+sub_nums+'" onclick="show_oans(\'oans'+sub_nums+'\')">詳解<img class="oans_pic" id="pic_oans'+sub_nums+'" src="{{ URL::asset('img/close.png') }}" height="20"></label></td><td></td></tr></table><table class="list oans" border="0" width="100%" cellpadding="0" cellspacing="0" id="oans'+sub_nums+'"><tr class="deep"><td align="right">文字說明</td><td width="80%"><textarea  name="f_anstxt" cols="50" rows="4" value=""></textarea></td></tr><tr class="shallow"><td align="right">圖片檔</td><td><input type="hidden" id="f_imgsol'+sub_nums+'" name="f_imgsol[]" value="">格式：JPG/PNG</td></tr><tr class="deep"><TD align="right">聲音檔</TD><td><input type="hidden" id="f_imgsols'+sub_nums+'" name="f_imgsols[]" value="">格式：MP3</TD></TR><tr class="shallow"><td align="right">影片檔</td><td><input type="hidden" id="f_imgsolv'+sub_nums+'" name="f_imgsolv[]" value="">格式：MP4</td></tr></table></div></div></div>');
    $(function() {
        $('#chapter'+sub_nums).combobox();
        range_set();
    });    
    if (sub_no===10)$('#more_btn').hide();
    sub_nums++;

}
$("#more").on('click', ".remove_sub", function(){
    if (confirm('確定移除？')){
        let qpart = this.parentElement.parentElement;
        sub_no--;
        $(qpart).remove();
        $(".subno").each(function(i){
            this.innerHTML = '第'+(i+1)+'小題';
        });
    }
});
</SCRIPT>