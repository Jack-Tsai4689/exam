<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('layout.sub')
	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		max-width: 1152px;
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
            /*width: 500px;*/
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
        .math, .write, .match {
            display: none;
        }
        .hiden {
            display: none;
        }
        .show {
            display: block;
        }
        #opt_range > div {
            display: inline-block;
        }
	</style>
</head>
<body>
<div id="all">
    {{ $Sets_msg }}
	<div class="title"><label class="f17">{{ $title }}</label></div>
	<FORM name="form1" id="form1" method="POST" enctype="multipart/form-data" action="{{ url('ques') }}" onsubmit="return form_check(this);">
    <div class="content" id="first">
		<div class="cen">
			<table class="list" id="que_main" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left"><label class="f17">題目</label></td>
                    <td></td>
                </tr>
				<tr class="deep">
                    <td align="right">題型<font color="red">＊</font></td>
                    <td width="80%">
                    	<label><input type="radio" name="f_qus_type" id="typeS" checked value="S" onchange="change_type(this.value)">單選題</label>
                        <label><input type="radio" name="f_qus_type" id="typeD" value="D" onchange="change_type(this.value)">複選題</label>
                        <label><input type="radio" name="f_qus_type" id="typeR" value="R" onchange="change_type(this.value)">是非題</label>
                        <label><input type="radio" name="f_qus_type" id="typeM" value="M" onchange="change_type(this.value)">選填題</label>
                        <label><input type="radio" name="f_qus_type" id="typeC" value="C" onchange="change_type(this.value)">配合題</label>
                        <label><input type="radio" name="f_qus_type" id="typeG" value="G" onchange="change_type(this.value)">題組</label>
                    </td>
                </tr>
                <tr class="shallow">
                    <td align="right">題目文字說明</td>
                    <td><textarea  name="f_quetxt" id="f_quetxt" cols="50" rows="4" onkeydown="done()" value=""></textarea>
                    <br><font class="f12">*題目文字說明或圖檔不可空白</font>
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">題目圖檔</td>
                    <td>
                        <IMG id="qimg" src="{{ $Qimg }}" width="98%"><br>
                        <div id="qimg_content">{!! $Qimg_html !!}</div>
                        <input type="hidden" id="f_qimg" name="f_qimg" value="">
                    	<input type="file" name="qpic" id="qpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG
                    </td>
                </tr>
                <tr class="shallow">
                    <td align="right">題目聲音檔</td>
                    <td><input type="file" name="qsound" id="qsound" accept="audio/mp3">格式：MP3</td>
                </TR>
                <tr class="deep">
                    <td align="right">關鍵字</td>
                    <td>(每個最多10個字)<br>
                        <input type="text" class="input_field w150" name="fk[]" maxlength="10">
                        <input type="text" class="input_field w150" name="fk[]" maxlength="10">
                        <input type="text" class="input_field w150" name="fk[]" maxlength="10">
                        <input type="text" class="input_field w150" name="fk[]" maxlength="10">
                        <input type="text" class="input_field w150" name="fk[]" maxlength="10">
                    </td>
                </tr>
                <tr class="shallow">
                    <td align="right">知識點</td>
                    <td id="know_div">
                        <input type="button" name="" id="addpoint" value="選擇知識點">
                        <input type="hidden" id="f_pid" name="f_pid" value=""/>
                        <div style="display: inline-block;" id="pid_name"></div>
                        <div style="display: inline-block;" id="pid_cancell"></div>
                        {{-- <font color="green">*「知識點」有助於學生在看診斷報告時，對題目的解答較易於融會貫通噢~</font> --}}
                    </td>
                </tr>
                <tr>
                    <td align="left" colspan="2"><label class="f17">範圍</label><font color="green">*確實設定範圍，在學生的診斷報告中，較可以準確分析學生「較強」或「較弱」是哪些</font></td>
                </tr>
                <tr class="deep">
                    <td align="right">年級<font color="red">＊</font></td>
                    <td width="80%">
                        <select name="f_grade" id="f_grade" onchange="subj_c(this)">{!! $Q_Grade !!}</select>
                    </td>
                </tr>
                <tr class="shallow">
                    <td align="right">科目<font color="red">＊</font></td>
                    <td id="subj">
                        <select name="f_subject" id="f_subject" onchange="chap_c(this)">{!! $Q_Subject !!}</select>
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">章節<font color="red">＊</font></td>
                    <td>
                        <div class="ui-widget">
                            <select id="f_chapterui" name="f_chapterui">
                                <option value=""></option>
                                {!! $Q_Chapter !!}
                            </select>
                            <label id="chapter_error" class="error_msg" style="margin-left:40px;"></label>
                        </div>
                    </td>
                </TR>
                <tr class="deep">
                    <td align="right">開放留言</td>
                    <td>
                        <label><input type="radio" name="f_stuoans" value="Y">開放</label>
                        <label><input type="radio" name="f_stuoans" value="N">停用</label>
                    </td>
                </tr>
            </table>
            @php if ($que_type!='G'): @endphp
            <table class="list" border="0" width="100%" cellpadding="0" cellspacing="0" id="notgp">
                <tr>
                    <td align="left"><label class="f17">選項</label></td>
                    <td></td>
                </tr>
                <tr class="deep" name="ans_type">
                    <td align="right">選項個數<font color="red">＊</font></td>
                    <td>
                        <select name="option_num" id="option_num" onchange="optnum(this.value)">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </td>
                </tr>
                <tr class="shallow" id="simple">
                    <td align="right">正確答案<font color="red">＊</font></td>
                    <td width="80%">
                        <div id="ans_group"></div>
                        <label id="ans_group_error" class="error_msg"></label>
                    </td>
                </tr>
                <tr class="deep math">
                    <td align="right">選項題數<font color="red">＊</font></td>
                    <td width="80%">
                        <select name="num" id="num" onchange="num_change(this.value)">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </td>
                </tr>
                <tr class="shallow math">
                    <td align="right">正確解答<font color="red">＊</font></td>
                    <td id="correct_ans_math">
                        <div id="a1"><span>No.1</span><label><input type="radio" name="ans1" value="1">1</label><label><input type="radio" name="ans1" value="2">2</label><label><input type="radio" name="ans1" value="3">3</label><label><input type="radio" name="ans1" value="4">4</label><label><input type="radio" name="ans1" value="5">5</label><label><input type="radio" name="ans1" value="6">6</label><label><input type="radio" name="ans1" value="7">7</label><label><input type="radio" name="ans1" value="8">8</label><label><input type="radio" name="ans1" value="9">9</label><label><input type="radio" name="ans1" value="0">0</label><label><input type="radio" name="ans1" value="a">-</label><label><input type="radio" name="ans1" value="b">±</label>
                        </div>
                    </td>
                </tr>
                <tr class="deep match">
                    <td align="right">配合方式</td>
                    <td width="80%"><label><input type="radio" name="gmatch" checked class="gtype" value="1">1 v.s. 1 (1組 對應 1個選項)</label>　
                        <label><input type="radio" name="gmatch" class="gtype" value="2">1 v.s. 多 (1組 對應 多個選項)</label>
                    </td>
                </tr>
                <tr class="shallow match">
                    <td align="right">選項群</td>
                    <td>
                        <input type="button" id="more_opt" value="增加選項">　<input type="button" id="remove_opt" value="減少選項">
                        <div id="opt_range"><div><input type="checkbox" class="opt"><label class="opt_no">1. </label><input type="text" class="opt_txt" name="opttxt[]"></div></div>
                    </td>
                </tr>
                <tr class="deep match">
                    <td align="right">對應組別</td>
                    <td>
                        <input type="button" id="more_cgroup" value="增加組別">　<input type="button" id="remove_cgroup" value="減少組別">
                        <div id="cgroup_range">
                            <div style="display: inline-block;">
                                <div><input type="text" name="cg[]" class="cgroup" placeholder="組別1"></div>
                                <div><input type="button" name="joino" class="btn_joino" data-id="1" value="加入">　<input type="button" name="removeo" class="btn_removeo" data-id="1" value="移除"></div>
                                <div>
                                    <select multiple class="cg_ans" name="cg_ans1[]"></select>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="deep write">
                    <td align="right">標準答案<font color="red">＊</font></td>
                    <td width="80%">
                        <label><input type="radio" name="write_correct" checked id="0">無</label>
                        <label><input type="radio" name="write_correct" id="1">有</label>
                        <input type="text" name="" id="" class="input_field">
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">難易度</td>
                    <td>
                        <label><input type="radio" name="f_degree" value="E" {{ $Degree->E }} >容易</label>
                        <label><input type="radio" name="f_degree" value="M" {{ $Degree->M }} >中等</label>
                        <label><input type="radio" name="f_degree" value="H" {{ $Degree->H }} >困難</label>
                    </td>
                </TR>
                <tr>
                    <td><label class="f17 oans_control" id="oans_control" onclick="show_oans('oans')">詳解<img class="oans_pic" id="pic_oans" src="{{ URL::asset('img/close.png') }}" height="20"></label></td>
                    <td></td>
                </tr>
            </table>
			<table class="list table_last oans" border="0" width="100%" cellpadding="0" cellspacing="0" id="oans">
				<tr class="deep">
                    <td align="right">文字說明</td>
                    <td width="80%">
                    	<textarea  name="f_anstxt" cols="50" rows="4" value=""></textarea>
                    </td>
                </tr>
                <tr class="shallow">
                    <td align="right">圖片檔</td>
                    <td>
                        <IMG id="aimg" src="{{ $Aimg }}" width="98%"><br>
                        <div id="aimg_content">{!! $Aimg_html !!}</div>
                        <input type="hidden" id="f_aimg" name="f_aimg" value="">
                        <input type="file" name="apic" id="apic" accept=".jpg,.jpeg,.png">格式：JPG/PNG
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">聲音檔</td>
                    <td><input type="file" name="asound" id="asound" accept="audio/mp3">格式：MP3</td>
                </TR>
                <tr class="shallow">
                    <td align="right">影片檔</td>
                    <td><input type="file" name="avideo" id="avideo" accept="video/mp4">格式：MP4</td>
                </tr>
            </table>
            @php endif; @endphp
        </div>
    </div>
    <div class="content" style="margin-bottom:50px;">
        <div class="cen" style="padding-bottom:50px;">
            <div style="text-align:left;">
                    {{ csrf_field() }}
                    <input type="submit" class="btn w150 h30" value="存檔，出下一題" name="save_next" id="save_next">
                    <input type="submit" class="btn w150 h30" value="存檔，離開" name="save_close" id="save_close">
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
</body>
</html>
<script type="text/javascript">
//var  past = '';
change_type('S');
function gb(v){
    return document.getElementById(v);
}
function show_oans(elem){
    let oans = $('#'+elem);
    if (oans.css('display')=='none'){
        oans.css('display','table');
        $('#pic_'+elem).prop('src','{{ URL::asset('img/open.png') }}');
    }else{
        oans.css('display','none');
        $('#pic_'+elem).prop('src','{{ URL::asset('img/close.png') }}');
    }
}
window.moveTo(0,0);
window.resizeTo(screen.width,screen.height);
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
            let selected = this.element.children( ":selected" ),
                value = selected.val() ? selected.text() : "";
            if(''!=$( "#f_chapter" ).val()&&''==value) value=$( "#f_chapter" ).val();
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
            let input = this.input,
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
            let matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
            response( this.element.children( "option" ).map(function() {
                let text = $( this ).text();
                if ( this.value && ( !request.term || matcher.test(text) ) )
                return {
                    label: text,
                    value: text,
                    option: this
                };
            }) );
        },
        _removeIfInvalid: function( event, ui ) {
            $( "#f_chapter" ).val(this.input.val());
        },
        _destroy: function() {
           this.wrapper.remove();
           this.element.show();
        }
    });
})( jQuery );
    
$(function() {
    $( "#f_chapterui" ).combobox();
});
// function delete_que(){//刪除
//     if (confirm('刪除後以上您輸入的資料都會消失，您確定要刪除嗎?')){
//         location.href="ex_md.php?act=delete";
//     }
// }
let rows_m = 1;

function subj_c(obj){
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'type':'subj', 'g':obj.value},
        success: function(rs){
            $("#f_subject").html('');
            let html = '';
            for(let i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            $("#f_subject").html(html);
            chap_c(gb('f_subject').value);
        },
        error: function(rs){
            switch(rs.status){
                case 400: alert('例外錯誤'); break;
                case 401: alert('登入逾時，請重新登入'); break;
                case 406:
                    gb('f_subject').innerHTML = '<option value="0">無科目</optoin>';
                    gb('f_chapterui').innerHTML = '<option value="0">無章節</optoin>'; 
                    break;
            }
        }
    });
}
function chap_c(obj){
    $('.custom-combobox-input').val('');
    $('#f_chapterui').empty();
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'type':'chap', 'g':gb('f_grade').value, 's':obj.value},
        success: function(rs){
            let html = '';
            for(let i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            $("#f_chapterui").html(html);
        },
        error: function(rs){
            switch(rs.status){
                case 400: alert('例外錯誤'); break;
                case 401: alert('登入逾時，請重新登入'); break;
                case 406: gb('f_chapterui').innerHTML = '<option value="0">無章節</optoin>'; break;
            }
        }
    });
}

function num_change(v){//選填用
    let math = $('#form1 #correct_ans_math');
    let newone = Number(v);
    let oldone = Number(rows_m);
    if (newone>oldone){
        let start = oldone+1;
        for(let i=start; i<=v;i++){
            html = '<div id="a'+i+'"><span>No.'+i+'</span>';
            let j = 1;
            while (j<=9){
                html+= '<label><input type="radio" name="ans'+i+'" value="'+j+'">'+j+'</label>';
                j++;
            }
            html+= '<label><input type="radio" name="ans'+i+'" value="0">0</label>';
            html+= '<label><input type="radio" name="ans'+i+'" value="a">-</label>';
            html+= '<label><input type="radio" name="ans'+i+'" value="b">±</label>';
            html+= '</div>';
            math.append(html);
        }
        rows_m = newone;
    }else if (newone<oldone){
        let j = oldone;
        while(j>newone){
            math.find('#a'+j).remove();
            j--;
        }
        rows_m = newone;
    }
        //correct_ans_math
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

// function select_point(){//知識點
//     document.getElementById('que_pic').src="";
//     document.getElementById('que_pic').src="ex_point.php?fkey=6";
//     $('#que_pic').attr('width','100%');
//     $('#que_pic').attr('height',screen.height*0.8);
//     $('#sets_filed .set_all').css('width','90%');
//     $('#sets_filed').show();
//     //let point = window.open("ex_point.php?fkey=6","ex_point","width=1240px,height=600px,resizable=yes,scrollbars=yes,status=yes");
// }
// document.onkeydown = function(event){//鎖特定按鍵 116 F15  123 F12
//     if (event.keyCode == 116){
//         if (confirm('確定要重新整理?未存檔資料將可能遺失!')){
//         }else{
//             event.keyCode = 0;
//             event.returnValue = false;
//         }
//     }
// }
let action = false;
function done(){
    action = true;
}
function form_check(obj){
    // if (gb('f_grade').value=="" || gb('f_subject').value=="" || gb('f_chapterui').value==""){
    //     alert("注意題目範圍");
    //     return false;
    // }
    let error = false;
    if (gb("typeC").checked){
        let gcrows = Number($(".gtype:checked").val());
        $(".cg_ans").each(function(){
            let grows = this.options.length;
            if (grows===0){
                error = true;
                alert("設定錯誤");
                return false;
            }
            if (gcrows===1){
                if (grows!=1){
                    error = true;
                    alert("設定錯誤");
                    return false;
                }
            }
        });
        if (error)return false;
        $(".cg_ans").find("option").each(function(){
            this.selected = true;
        });
    }
    // if (data_check()){
    //     alert('請確認無誤');
    //     return false;
    // }
}
function data_check(){
    let no = '';
    let error = false;
    let i = 0;
    let correct_ans = $('input[name="ans[]"]:checked').val();
    if (correct_ans==null){
        document.getElementById('ans_group_error').innerHTML = '(X) 設定答案';
    }else{
        document.getElementById('ans_group_error').innerHTML = '';
    }
    let chapter = document.getElementById('f_chapter').value;
    if (trim(chapter)==''){
        error = true;
        document.getElementById('chapter_error').innerHTML = '(X) 章節勿空白';
    }else{
        document.getElementById('chapter_error').innerHTML = '';
    }
    return error;
}
let originurl = opener.location.href;
// function check(act){
//     let q=0;
//     let error = false;//data_check();
//     if (!error){
//         console.log(gb("typeC").checked);
//         return;
//         //if (gb("typeC").checked)
//         let gcrows = Number($(".gtype").val());
//         $(".cg_ans").each(function(){
//             let grows = this.options.length;
//             if (grows===0){
//                 error = true;
//                 alert("設定錯誤");
//                 return false;
//             }
//             if (gcrows===1){
//                 if (grows!=1){
//                     error = true;
//                     alert("設定錯誤");
//                     return false;
//                 }
//             }
//         });
//         if (error)return false;
//         $(".cg_ans").find("option").each(function(){
//             this.selected = true;
//         });
//         //背景post
//         $('#posting').show();
//         let type = 'a';
//         if (type=='feedback'){ $('#handle_type').val(act); }
//         $.ajax({
//             type:"POST",
//             url:"{{ url('/ques') }}",
//             data:new FormData(gb('form1')),
//             contentType: false,
//             processData: false,
//             cache: false,
//             dataType:"JSON",
//             success: function(rs){
//                 opener.location.href = '{{ url('/ques') }}';
//                 if (act==="c"){
//                     window.close();
//                 }else{
//                     window.location.reload();
//                 }
//             }
//         });
//     }
// }
function no_display(num){//編號切換
    let j ='';
    for (let i=0; i <num; i++) {
        j = String.fromCharCode(i+65);
        $('#ans_'+(i+1)).html(j);
    }
}
function change_type(ans_t){//選項設定
    if (ans_t==="G"){
        if (confirm("設定將遺失，確定變更？")){
            location.href = "{{ url('ques/createg') }}";
        }else{
            $("#type"+past).prop('checked', true);
            return;
        }
    }
    if (ans_t!=="M"){
        $(gb('simple')).show();
        $('tr[name="ans_type"]').show();
        $(".math").hide();
    }
    if (ans_t!=="C")$(".match").hide();
    let num, html = '';
    switch(ans_t){
        case 'S'://單選
            $('#form1 tr[name=ans_type]').css('display','table-row');
            num = gb('option_num').value;
            for (let i =1; i <=num; i++) {
                j = String.fromCharCode(i+64);
                html+= '<label><input name="ans[]" type="radio" value="'+i+'"><font id="ans_'+i+'">'+j+'</font></label>';
            }
            $('#form1  #ans_group').html(html);
            break;
        case 'D'://複選
            $('#form1 tr[name=ans_type]').css('display','table-row');
            num = gb('option_num').value;
            for (let i =1; i <=num; i++) {
                j = String.fromCharCode(i+64);
                html+= '<label><input name="ans[]" type="checkbox" value="'+i+'"><font id="ans_'+i+'">'+j+'</font></label>';
            }
            $('#form1  #ans_group').html(html);
            break;
        case 'R'://是非
            html+= '<label><input type="radio" name="ans[]" value="1" checked>O</label>  <label><input type="radio" name="ans[]" value="2">X</label>';
            $('#form1 tr[name=ans_type]').css('display','none');
            $('#form1 #ans_group').html(html);
            break;
        case 'M'://選填
        case 'C':
            $('tr[name="ans_type"]').hide();
            $(gb('simple')).hide();
            if (ans_t==="M")$(".math").show();
            if (ans_t==="C")$(".match").show();
            break;
    }
    past = ans_t;

}
function optnum(v){//選項數擷取
    let type = $('input[name="f_qus_type"]:checked').val();
    change_type(type);
}
function ao_display(n, v){//編號切換
    let j ='';
    let l =n.length;
    let newn = n.substring(8,l);
    let num = $('#form1 > #option_num'+newn).val();
    for (let i=0; i <num; i++) {
        if (v==0)j = i+1;
        if (v==1)j = String.fromCharCode(i+65);
        //alert(j);
        $('#form1 > #ans'+newn+'_'+(i+1)).html(j);
    }
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

function openframe(){
    gb("que_pic").style.width = '100%';
    gb("que_pic").style.height = screen.height*0.8;
    // $('#que_pic').attr('width','100%');
    // $('#que_pic').attr('height',screen.height*0.8);
    $('#sets_filed .set_all').css('width','90%');
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
    gb('que_pic').src = '';
}
// function remove_point(){
//     let point = document.getElementById('point_content');
//     point.innerHTML = '<input type="button" value="選擇知識點" class="btn w100 h25" name="f_btn" onClick="select_point()">';
    // let point = $('#point_content');
    // point.html('');
    // point.append($('<input>').attr({type:'button',value:'選擇知識點',class:'btn w100 h25',name:'f_btn',onClick:'select_point()'}));
    // document.getElementById('f_pid').value = 't';
    // document.forms[0].submit();
//}
function rem(elem,no){
    if (confirm('檔案無法復原，確定?')){
        let obj = $('#f_'+elem+no).val();
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
    let cont = $('#'+id+'content');
    if (cont.css('display')=='block'){
        cont.css('display','none');
        $('#'+id+'oc_pic').prop('src','close.png');
    }else{
        cont.css('display','block');
        $('#'+id+'oc_pic').prop('src','open.png');
    }
}
$("#more_opt").on('click', function(){
    let rows = document.querySelectorAll(".opt").length;
    let html = '<div id="opt'+(rows+1)+'"><input type="checkbox" class="opt"><label class="opt_no">'+(rows+1)+'. </label><input type="text" class="opt_txt" name="opttxt[]"></div>';
    $("#opt_range").append(html);
});
$("#remove_opt").on('click', function(){
    let rows = document.querySelectorAll(".opt").length;
    document.getElementById('opt'+rows).remove();
    if (rows===1)return;
    let cg_ans = document.querySelectorAll(".cg_ans");
    cg_ans.forEach(function(ele){
        for (i = ele.options.length-1; i>=0; i--){
            let optdel = ele.options[i];
            if (Number(optdel.value)===(rows-1)){
                ele.remove(i);
                continue;
            }
        }
    });
});
$("#more_cgroup").on('click', function(){
    let rows = document.querySelectorAll(".cgroup").length;
    let html = '<div id="cg'+(rows+1)+'" style="display: inline-block;"><div><input type="text" name="cg[]" class="cgroup" placeholder="組別'+(rows+1)+'"></div><div><input type="button" name="joino" class="btn_joino" data-id="'+(rows+1)+'" value="加入">　<input type="button" name="removeo" class="btn_removeo" data-id="'+(rows+1)+'" value="移除"></div><div><select multiple class="cg_ans" name="cg_ans'+(rows+1)+'[]"></select></div></div>';
    $("#cgroup_range").append(html);
});
$("#remove_cgroup").on('click', function(){
    let rows = document.querySelectorAll(".cgroup").length;
    document.getElementById('cg'+rows).remove();
});

$("#cgroup_range").on('click', ".btn_joino", function(){
    let id = $(this).data('id');
    let ans = $('select[name="cg_ans'+id+'[]"]');
    // let ans = $('#cg_ans'+id);
    $(".opt:checked").each(function(){
        let i = $(".opt").index(this);
        let v = $(".opt_no")[i].innerHTML+$(".opt_txt")[i].value;
        if (v=="")return;
        let have = false;
        ans.find("option").each(function(){
            let nv = Number($(this).val());
            if (nv===i){
                have = true;
                return false;
            }
        });
        if (!have)ans.append(new Option(v, i));
    });
    let cg = ans.find('option');
    cg.detach().sort(function(a,b){
        let av = Number(a.value);
        let bv = Number(b.value);
        return (av > bv)?1:((av < bv)?-1:0);
    });
    cg.appendTo(ans);
});
$("#cgroup_range").on('click', ".btn_removeo", function(){
    let id = $(this).data('id');
    $('select[name="cg_ans'+id+'[]"]').find(":selected").remove();
    // $('#cg_ans'+id).find(":selected").remove();
});
$("#opt_range").on('blur', ".opt_txt", function(){
    let obj = this;
    let gans = [];
    let id = $(".opt_txt").index(obj);
    $(".cg_ans").each(function(){
        $(this).find("option").each(function(){
            if (this.value==id){
                let no = $(".opt_no")[id].innerHTML;
                $(this).text(no+obj.value);
                return false;
            }
        });
    });
});
</SCRIPT>