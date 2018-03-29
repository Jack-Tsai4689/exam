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
            float: left;
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
	<FORM name="form_top" id="form_top">
    <div class="content">
		<div class="cen">
			<table class="list" id="que_main" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left"><label class="f17">題組題目</label></td>
                    <td><a href="{{ url('/ques/create') }}"><input type="button" value="放棄並以非題組新增"></a><input type="hidden" name="serial" value="0"></td>
                </tr>
                <tr class="shallow">
                    <td align="right">題組說明</td>
                    <td width="80%">
                        <textarea  name="gpcontent" id="gpcontent" cols="50" rows="4" value="" placeholder="文字、圖片、音訊，最少擇一"></textarea>
                        <br>
                        圖片　<input type="file" name="qpic" id="qpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG
                        <br>
                        音訊　<input type="file" name="qsound" id="qsound" accept="audio/mp3">格式：MP3
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
                    <td><label><input type="checkbox" name="allrange" value="all" onclick="all_set(this)">統一設定(不能獨立更動小題範圍)</label></td>
                </tr>
                <tr class="deep allgsc hiden">
                    <td align="right">類別<font color="red">＊</font></td>
                    <td>
                        <select name="grade_al" id="grade_al" onchange="subj_c('top', this)">{!! $Q_Grade !!}</select>
                    </td>
                </tr>
                <tr class="shallow allgsc hiden">
                    <td align="right">科目<font color="red">＊</font></td>
                    <td id="subj">
                        <select name="subject_al" id="subject_al" onchange="chap_c('top', this)">{!! $Q_Subject !!}</select>
                    </td>
                </tr>
                <tr class="deep allgsc hiden">
                    <td align="right">章節<font color="red">＊</font></td>
                    <td>
                        <div class="ui-widget">
                            <select name="chapter_al" id="chapter_al">
                                <option value=""></option>{!! $Q_Chapter !!}
                            </select>
                            <label id="chapter_al_msg" style="margin-left:40px;"></label>
                        </div>
                    </td>
                </TR>
                    </td>
                </tr>
            </table>
            {{ csrf_field() }}
        </div>
    </div>
    </FORM>
    <div id="more">
    <form id="form1">
        <div class="qpart" id="q1">
            <div class="title"><label class="subno f17 no">第1小題</label><span class="remove_sub">&times;</span></div>
            <div class="content">
                <div class="cen">
                    <table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="left"><label class="f17">題目</label></td>
                            <td><input type="hidden" name="serial" value="1"><input type="hidden" class="pserial" name="pserial" value="40"></td>
                        </tr>
                        <tr class="deep">
                            <td align="right">題型<font color="red">＊</font></td>
                            <td width="80%">
                                <label><input type="radio" class="qus_type" name="qus_type" checked value="S" onchange="change_type(1, this)">單選題</label>
                                <label><input type="radio" class="qus_type" name="qus_type" value="D" onchange="change_type(1, this)">複選題</label>
                                <label><input type="radio" class="qus_type" name="qus_type" value="R" onchange="change_type(1, this)">是非題</label>
                            </td>
                        </tr>
                        <tr class="shallow">
                            <td align="right">題目說明</td>
                            <td>
                                <textarea  name="gpcontent" class="gpcontent" cols="50" rows="4" placeholder="文字、圖片、音訊，最少擇一"></textarea>
                                <br>
                                圖片　<input type="file" class="qpic" name="qpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG
                                <br>
                                音訊　<input type="file" class="qsound" name="qsound" accept="audio/mp3">格式：MP3
                            </td>
                        </tr>
                        <tr>
                            <td align="left"><label class="f17">選項</label></td>
                            <td></td>
                        </tr>
                        <tr class="shallow ans_type">
                            <td align="right">選項個數<font color="red">＊</font></td>
                            <td>
                                <select name="option_num" class="option_num" onchange="optnum(1, this)">
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option selected value="4">4</option>
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
                        <tr class="deep">
                            <td align="right">正確答案<font color="red">＊</font></td>
                            <td width="80%">
                                <div class="ans_group">
                                    <label><input type="radio" name="ans1[]" value="1">A</label>
                                    <label><input type="radio" name="ans1[]" value="2">B</label>
                                    <label><input type="radio" name="ans1[]" value="3">C</label>
                                    <label><input type="radio" name="ans1[]" value="4">D</label>
                                </div>
                                <label id="ans_group_error" class="error_msg"></label>
                            </td>
                        </tr>
                        <tr class="gsc">
                            <td align="left" colspan="2"><label class="f17">範圍</label></td>
                        </tr>
                        <tr class="deep gsc">
                            <td align="right">類別<font color="red">＊</font></td>
                            <td width="80%">
                                <select class="grade" name="grade" onchange="subj_c(1, this)">{!! $Q_Grade !!}</select>
                            </td>
                        </tr>
                        <tr class="shallow gsc">
                            <td align="right">科目<font color="red">＊</font></td>
                            <td>
                                <select class="subject" name="subject" onchange="chap_c(1, this)">{!! $Q_Subject !!}</select>
                            </td>
                        </tr>
                        <tr class="deep gsc">
                            <td align="right">章節<font color="red">＊</font></td>
                            <td>
                                <div class="ui-widget">
                                    <select class="chapter" id="chapter1" name="chapter">
                                        <option value=""></option>{!! $Q_Chapter !!}
                                    </select>
                                    <label class="chapter_msg" style="margin-left:40px;"></label>
                                </div>
                            </td>
                        </TR>
                        <tr class="shallow">
                            <TD align="right">難易度</TD>
                            <td>
                                <label><input type="radio" name="degree1" value="E">容易</label>
                                <label><input type="radio" name="degree1" value="M">中等</label>
                                <label><input type="radio" name="degree1" value="H">困難</label>
                            </TD>
                        </TR>
                        <tr>
                            <td>
                                <label class="f17 oans_control" id="oans_control1" onclick="show_oans('oans1')">詳解<img class="oans_pic" id="pic_oans1" src="{{ URL::asset('img/close.png') }}" height="20"></label>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <table class="list oans" border="0" width="100%" cellpadding="0" cellspacing="0" id="oans1">
                        <tr class="deep">
                            <td align="right">詳解說明</td>
                            <td width="80%">
                                <textarea  name="f_anstxt" cols="50" rows="4" value=""></textarea>
                                <div>圖片　<input type="file" name="apic" class="apic" accept=".jpg,.jpeg,.png">格式：JPG/PNG</div>
                                <div>音訊　<input type="file" name="asound" class="asound" accept="audio/mp3">格式：MP3</div>
                                <div>視訊　<input type="file" name="avideo" class="avideo" accept="video/mp4">格式：MP4</div>
                            </td>
                        </tr>
                    </table>
                    {{ csrf_field() }}
                </div>
            </div>
        </div>
    </form>
    </div>
    <div class="title" id="more_btn" onclick="more_one()"><font class="f17">增加小題</font></div>
    <div class="content" style="margin-bottom:50px;">
        <div class="cen" style="padding-bottom:50px;">
            <div style="text-align:left;">
                <input type="button" class="btn w150 h30" value="存檔，出下一題" name="save_next" id="save_next" onclick="create_check('n')">
                <input type="button" class="btn w150 h30" value="存檔，離開" name="save_close" id="save_close" onclick="create_check('c')">
            </div>
        </div>
    </div>
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
                      .attr( "title", "顯示此類別、科目下所有章節" )
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

$("#more").ready(function() {
    $("#chapter_al").combobox();
    $(".chapter").combobox();
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
function subj_c(ind, obj){
    if (ind!=="top"){
        gbc("#q"+ind+" .subject")[0].innerHTML = '';
    }else{
        gb("subject_al").innerHTML = '';
    }    
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'alt':'info', 'type':'subj', 'g':obj.value},
        success: function(rs){
            let html = '';
            for(let i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            if (ind!=="top"){
                gbc("#q"+ind+" .subject")[0].innerHTML = html;
                chap_c(ind, gbc("#q"+ind+" .subject")[0]);
            }else{
                gb("subject_al").innerHTML = html;
                chap_c("top", gb("subject_al"));
            }
        },
        error: function(rs){
            switch(rs.status){
                case 400: alert('例外錯誤'); break;
                case 401: alert('登入逾時，請重新登入'); break;
                case 406: 
                    if (ind!=="top"){
                        gbc("#q"+ind+" .subject")[0].innerHTML = '<option value="">無資料</option>'; 
                        gbc("#q"+ind+" .chapter")[0].innerHTML = '';
                        gbc('#q'+ind+' input.custom-combobox-input')[0].value = '';
                        gbc("#q"+ind+" .chapter_msg")[0].innerHTML = '無資料';
                    }else{
                        gb("subject_al").innerHTML = '<option value="">無資料</option>'; 
                        gb("chapter_al").innerHTML = '';
                        gb("chapter_al_msg").innerHTML = '無資料';
                    }                    
                    break;
            }
        }
    });
}
function chap_c(ind, obj){
    let wdata;
    if (ind!=="top"){
        gbc('#q'+ind+' input.custom-combobox-input')[0].value = '';
        wdata = {'alt':'info', 'type':'chap', 'g':gbc("#q"+ind+" .grade")[0].value, 's':obj.value};
        gbc('#q'+ind+' .chapter')[0].innerHTML = '';
    }else{
        gbc('#form_top input.custom-combobox-input')[0].value = '';
        gb("chapter_al").innerHTML = '';
        wdata = {'alt':'info', 'type':'chap', 'g':gb("grade_al").value, 's':obj.value};
    }    
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data: wdata,
        success: function(rs){
            let html = '';
            for(let i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            if (ind!=="top"){
                gbc("#q"+ind+" .chapter")[0].innerHTML = html;
                gbc("#q"+ind+" .chapter_msg")[0].innerHTML = '';
            }else{
                gb("chapter_al").innerHTML = html;
                gb("chapter_al_msg").innerHTML = '';
            }
        },
        error: function(rs){
            switch(rs.status){
                case 400: alert('例外錯誤'); break;
                case 401: alert('登入逾時，請重新登入'); break;
                case 406: 
                    if (ind!=="top"){
                        gbc("#q"+ind+" .chapter")[0].innerHTML = '';
                        gbc('#q'+ind+' input.custom-combobox-input')[0].value = '';
                        gbc("#q"+ind+" .chapter_msg")[0].innerHTML = '無資料';
                    }else{
                        gb("chapter_al").innerHTML = '';    
                        gb("chapter_al_msg").innerHTML = '無資料'; 
                    }
                    break;
            }
        }
    });
}

// function uque(v){
//     if (v==="dque"){
//         $.ajax({
//             type:"POST",
//             url:"{{ url('/ques/rmpic') }}",
//             data:{'type':v},
//             dataType:"JSON",
//             success: function(rs){
//                 gb('qimg_content').innerHTML = rs.html;
//                 gb('qimg').src = '';
//                 gb('f_qimg').value = '';
//             }
//         });
//         return;
//     }
//     document.getElementById('que_pic').src="{{ url('/ques/qupload') }}?type="+v;
//     $('#sets_filed').show();
//     $('#loading_status').show();
//     $("#que_pic").load(function(){
//         $('#loading_status').hide();
//         $('#que_pic').show();
//     });
// }
// function uans(v){
//     if (v==="dans"){
//         $.ajax({
//             type:"POST",
//             url:"{{ url('/ques/rmpic') }}",
//             data:{'type':v},
//             dataType:"JSON",
//             success: function(rs){
//                 gb('aimg_content').innerHTML = rs.html;
//                 gb('aimg').src = '';
//                 gb('f_aimg').value = '';
//             }
//         });
//         return;
//     }
//     document.getElementById('que_pic').src="{{ url('/ques/qupload') }}?type="+v;
//     $('#sets_filed').show();
//     $('#loading_status').show();
//     $("#que_pic").load(function(){
//         $('#loading_status').hide();
//         $('#que_pic').show();
//     });
// }
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
    // $('#form1 input[name="f_imgsrc[]"]').each(function(){
    //     no = this.id;
    //     no = no.substring(8);
    //     var quetxt = $('#f_quetxt'+no).val();
    //     var img = $(this).val();
    //     i++;
    //     if (quetxt=='' && img==''){
    //         error = true;
    //     }
    // });
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
    // if ($('input[name=f_qus_type]:checked').val()==4){
    //     if (i<2){
    //         error = true;
    //         alert('請增加小題');
    //     }
    // }
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
function change_type(ind, obj){//選項設定
    switch(obj.value){
        case 'S'://單選
        case 'D'://複選
            let num = gbc("#q"+ind+" .option_num")[0].value;
            out_ans(obj.value, ind, num);
            gbc("#q"+ind+" .ans_type")[0].classList.remove('hiden');
            break;
        case 'R'://是非
            const html = '<label><input type="radio" name="ans'+ind+'[]" value="1" checked>O</label>  <label><input type="radio" name="ans'+ind+'[]" value="2">X</label>';
            gbc("#q"+ind+" .ans_type")[0].classList.add('hiden');
            gbc("#q"+ind+" .ans_group")[0].innerHTML = html;
            break;
    }
}
function out_ans(type, ind, num){
    let html = '';
    switch(type){
        case 'S'://單選
            for (let r =1; r <=num; r++) {
                j = String.fromCharCode(r+64);
                html+= '<label><input name="ans'+ind+'[]" type="radio" value="'+r+'">'+j+'</label> ';
            }
            break;
        case 'D'://複選
            for (let r =1; r <=num; r++) {
                j = String.fromCharCode(r+64);
                html+= '<label><input name="ans'+ind+'[]" type="checkbox" value="'+r+'">'+j+'</label> ';
            }
            break;
    }
    gbc("#q"+ind+" .ans_group")[0].innerHTML = html;
}
function optnum(ind, obj){//選項數擷取
    let type = $('#q'+ind+' .qus_type:checked').val();
    out_ans(type, ind, obj.value);
}
let tasks = [];
function create_check(g){
    $("form").each(function(){
        var tfid = this.id;
        tasks.push(function upq(){
            return up(gb(tfid));
        });
    });
    main().then(function(value){
        opener.location.reload();
        if (g==="n"){location.reload();}
        if (g==="c"){window.close();}
        //closeBlock();
    }).catch(function(error){
        console.log(error);
        //gb('msg'+error.id).innerHTML = error.msg;
        //closeBlock();
    });
    tasks = [];
}
function main(){
    // function recordValue(results, value){
    //     results.push(value);
    //     return results;
    // }
    // var pushValue = recordValue.bind(null, []);
    var promise = Promise.resolve();
    for (var i=0; i < tasks.length;i++){
        var task = tasks[i];
        promise = promise.then(task);
    }
    return promise;
}
function up(obj){
    return new Promise(function(resolve, reject){
        let formdata = new FormData(obj);
        let uajax = new XMLHttpRequest();
        uajax.upload.onprogress = function(event){
            // var id = obj.id.substr(1, obj.length);
            // var percent = 0;
            // percent = (event.loaded / event.total) * 100;
            // qmpercent.innerHTML = "第"+id+"題，上傳進度："+Math.round(percent)+"%";
        }
        uajax.onloadend = function(event){
            let status = JSON.parse(event.target.status);
            switch(status){
                case 400: alert('例外錯誤'); reject(status); break;
                case 401: alert('逾時操作，請重登入'); reject(status); break;
                case 200:
                    let rs = JSON.parse(event.target.responseText);
                    if (rs.type==="G"){
                        let ps = gbc(".pserial");
                        ps.forEach(function(ele){
                            ele.value = rs.serial;
                        });
                    }
                    resolve(status);
                    break;
            }
        };
        uajax.onerror = function(rs){
            var status = JSON.parse(rs.target.status);
            reject(status);
            // var id = obj.id.substr(1, obj.length);
            // reject({'msg':"上傳錯誤，請確認檔案是否完整",'id':id});
        };
        uajax.open("POST", "{{ url('/ques/createg') }}", true);
        uajax.send(formdata);    
    });
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
let sub_nums = 2;
let sub_no = 1;
function more_one(){
    sub_no = $("#more > form > .qpart").length+1;
    $("#more").append('<form id="form'+sub_nums+'"><div class="qpart" id="q'+sub_nums+'"><div class="title"><label class="subno f17 no">第'+sub_no+'小題</label><span class="remove_sub">&times;</span></div><div class="content"><div class="cen"><table class="list" border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td align="left"><label class="f17">題目</label></td><td><input type="hidden" name="serial" value="'+sub_nums+'"><input type="hidden" class="pserial" name="pserial"></td></tr><tr class="deep"><td align="right">題型<font color="red">＊</font></td><td width="80%"><label><input type="radio" class="qus_type" name="qus_type" checked value="S" onchange="change_type('+sub_nums+', this)">單選題</label><label><input type="radio" class="qus_type" name="qus_type" value="D" onchange="change_type('+sub_nums+', this)">複選題</label><label><input type="radio" class="qus_type" name="qus_type" value="R" onchange="change_type('+sub_nums+', this)">是非題</label></td></tr><tr class="deep"><td align="right">題目說明</td><td><textarea  name="gpcontent" id="gpcontent" cols="50" rows="4" value="" placeholder="文字說明、圖片、音訊，最少擇一"></textarea><br>圖片　<input type="file" name="qpic" id="qpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG<br>音訊　<input type="file" name="qsound" id="qsound" accept="audio/mp3">格式：MP3</td></tr><tr><td align="left"><label class="f17">選項</label></td><td></td></tr><tr class="shallow ans_type"><td align="right">選項個數<font color="red">＊</font></td><td><select name="option_num" class="option_num" onchange="optnum('+sub_nums+', this)"><option value="2">2</option><option value="3">3</option><option selected value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></td></tr><tr class="deep" class="simple"><td align="right">正確答案<font color="red">＊</font></td><td width="80%"><div class="ans_group"><label><input type="radio" name="ans'+sub_nums+'[]" value="1">A</label><label><input type="radio" name="ans'+sub_nums+'[]" value="2">B</label><label><input type="radio" name="ans'+sub_nums+'[]" value="3">C</label><label><input type="radio" name="ans'+sub_nums+'[]" value="4">D</label></div><label id="ans_group_error" class="error_msg"></label></td></tr><tr class="gsc"><td align="left" colspan="2"><label class="f17">範圍</label></td></tr><tr class="deep gsc"><td align="right">類別<font color="red">＊</font></td><td width="80%"><select class="grade" name="grade" id="grade'+sub_nums+'" onchange="subj_c('+sub_nums+', this)">{!! $Q_Grade !!}</select></td></tr><tr class="shallow gsc"><td align="right">科目<font color="red">＊</font></td><td><select class="subject" name="subject" id="subject'+sub_nums+'" onchange="chap_c('+sub_nums+', this)">{!! $Q_Subject !!}</select></td></tr><tr class="deep gsc"><td align="right">章節<font color="red">＊</font></td><td><div class="ui-widget"><select class="chapter" id="chapter'+sub_nums+'" name="chapter"><option value=""></option>{!! $Q_Chapter !!}</select><label class="chapter_msg" style="margin-left:40px;"></label></div></td></TR><tr class="shallow"><TD align="right">難易度</TD><td><label><input type="radio" name="degree'+sub_nums+'" value="E">容易</label><label><input type="radio" name="degree'+sub_nums+'" value="M">中等</label><label><input type="radio" name="degree'+sub_nums+'" value="H">困難</label></TD></TR><tr><td><label class="f17 oans_control" id="oans_control'+sub_nums+'" onclick="show_oans(\'oans'+sub_nums+'\')">詳解<img class="oans_pic" id="pic_oans'+sub_nums+'" src="{{ URL::asset('img/close.png') }}" height="20"></label></td><td></td></tr></table><table class="list oans" border="0" width="100%" cellpadding="0" cellspacing="0" id="oans'+sub_nums+'"><tr class="deep"><td align="right">詳解說明</td><td width="80%"><textarea  name="f_anstxt" cols="50" rows="4" value=""></textarea><div>圖片　<input type="file" name="apic" id="apic" accept=".jpg,.jpeg,.png">格式：JPG/PNG</div><div>音訊　<input type="file" name="asound" id="asound" accept="audio/mp3">格式：MP3</div><div>視訊　<input type="file" name="avideo" id="avideo" accept="video/mp4">格式：MP4</div></td></tr></table>{{ csrf_field() }}</div></div></div></form>');
    $(function() {
        $('#chapter'+sub_nums).combobox();
        range_set();
    });    
    if (sub_no===10)$('#more_btn').hide();
    sub_nums++;

}
$("#more").on('click', ".remove_sub", function(){
    if (confirm('確定移除？')){
        let qpart = this.parentElement.parentElement.parentElement;
        sub_no--;
        $(qpart).remove();
        $(".subno").each(function(i){
            this.innerHTML = '第'+(i+1)+'小題';
        });
    }
});
</SCRIPT>