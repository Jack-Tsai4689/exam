<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="{{ URL::asset('/js/html5media.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/js/jquery.min.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/main.css') }}">
	<style type="text/css">
        body {
            width: 100%;
            float: left;
            position: relative;
        }
    	#all {
    		margin: 20px auto;
    		width: 1152px;
    	}
    	#title {
    		height: 30px;
    		line-height: 30px;
    	}
    	.title_intro{
    		height: 40px;
    		line-height: 40px;
    	}
    	.title_intro label {
    		margin-right: 5px;
    	}
        .ans {
            height: auto;
            line-height: 30px;
        }
    	.result_times {
    		text-align: center;
    		font-size: 16px;
    	}
    	.result_times.qno {
    		height: auto;
    	}
    	.result_times.qno div {
    		display: inline-block;
    		height: 25px;
    		line-height: 25px;
    		margin-right: 10px;
    		width: 25px;
    		background-color: #B4B4B5;
    		color: white;
            cursor: pointer;
    	}
    	.result_times div {
    		width: 15px;
    		display: inline-block;
    	}
    	.result_times.qno div.finish {
            background-color: #3EAC4A;
        }
        .result_times.qno div.chk {
    		background-color: #B7282C;
    	}
    	.result_times.qno div.current {
            background-color: #0071BC;
        }
    	.cen {
    		margin: 5px 0px 5px 0px;
    		min-height: 300px;
            max-height: 500px;
    		position: relative;
    		overflow: auto;
    	}
    	.que_main {
    		width: 1000px;
            min-height: 300px;
            max-height: 500px;
    		margin: 0px auto;
    		position: relative;
            overflow-y: auto;
    	}
    	#chk_choice {
    		position: absolute;
    		width: auto;
    		margin-left: 70px;
    	}
    	.qno_btn {
    		text-align: center;
    	}
    	#btn_left {
    		position: absolute;
    		z-index: 2;
    		width: 100%;
    		text-align: left;
    	}
    	.btn_center {
    		position: relative;
    		z-index: 2
    	}
    	.btn_right {
    		float: right;
    		margin-top: 8px;
    		margin-right: 5px;
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
        .sm_no {
            float: left;
            position: relative;
            display: inline-block;
            padding-right: 5px;
        }
        .sm_cont {
            width: 960px;
            display: inline-block;
            overflow-wrap: break-word;
        }

        .ans > span {
            width: 500px;
            display: inline-block;
        }
        .ans > span > div {
            display: inline-block;
            padding: 0px 5px 0px 5px;
            width: 100%;
        }
        .pic {
            width: 100%;
            max-width: 1000px;
        }
        .qs {
            cursor: pointer;
        }
	</style>
	<title>線上測驗</title>
</head>
<body>
<div id="all">
    <form name="exam_form" id="exam_form" method="POST" action="{{ url('/exam') }}">
	<div id="title"><label class="f17">【{{ $sets_name }}】</label></div>
	<div class="title_intro result_times">
        <INPUT type="hidden" name="hour" id="hour" value="{{ $hour }}">
        <INPUT type="hidden" name="min" id="min" value="{{ $min }}">
        <INPUT type="hidden" name="sec" id="sec" value="{{ $sec }}">
        <INPUT type="hidden" name="end_date" id="end_date" value="">
		<label>時間到後自動交卷</label><label>&nbsp;&nbsp;剩餘</label>
		<font color="red" id="h">{{ str_pad($hour,2,0,STR_PAD_LEFT) }}</font>時
		<font color="red" id="m">{{ str_pad($min,2,0,STR_PAD_LEFT) }}</font>分
		<font color="red" id="s">{{ str_pad($sec,2,0,STR_PAD_LEFT) }}</font>秒
	</div>
    @if ($first_part->sub)
    <div class="title_intro result_times">
        第{{ $first_part->no }}大題 ({{ $first_part->score }}%)
        <div>{!! $first_part->intro !!}</div>
    </div>
    @endif
	<div class="title_intro result_times">
		第<font id="current">{{ str_pad(1,2,0,STR_PAD_LEFT) }}</font>題/共{{ str_pad($first_part->nums,2,0,STR_PAD_LEFT) }}題&nbsp;&nbsp;未作答
        <font id="n">{{ $first_part->nums }}</font> 
        <font color="#3EAC4A">已作答</font>
        <font id="y">0</font> 
        <font color="#B7282C">再檢查</font>
        <font id="r">0</font>
		<input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="setid" value="{{ $sets }}">
	</div>
	<div class="title_intro result_times qno">
        {!! $qno_html !!}
	</div>
	<input type="hidden" name="current_qno" id="current_qno" value="{{ $curr }}">
    <input type="hidden" name="stu_ans" id="stu_ans" value="">
    <input type="hidden" name="exam" id="exam" value="{{ $exam }}">
    <input type="hidden" name="qtype" id="qtype" value="{{ $que->qtype }}">
    <input type="hidden" name="qnum" id="qnum" value="{{ $que->qnum }}">
    <input type="hidden" name="epart" id="epart" value="{{ $first_part->eid }}">
    <input type="hidden" name="spart" id="spart" value="{{ $first_part->sid }}">
    <input type="hidden" name="utime" id="utime">
    <div class="content" id="Q1">
        <div class="cen">
            <input type="hidden" id="Q1_no" value="{{ $que->qid }}">
            <div class="que_main" id="Q1_main">
                {!! $que->qcont !!}
            </div>
        </div>
    </div>
    <div class="title_intro result_times ans" id="A1">
        <span id="A1_main">
            {!! $que->ans !!}
        </span>
        <div id="chk_choice"><label><input type="checkbox" name="dou_check" id="dou_check1" value="1">再檢查</label></div>
    </div>
    <input type="hidden" name="next_qtxt" id="next_qtxt" value="">
    <input type="hidden" name="next_qa" id="next_qa" value="">
    <input type="hidden" name="next_qno" id="next_qno" value="">    
    @foreach ($qno as $i => $v)
	<div class="content" id="Q{{ ($i+1) }}" style="display:none;">
		<div class="cen">
            <input type="hidden" id="Q{{ ($i+1) }}_no" value="{{ $v }}">
			<div class="que_main" id="Q{{ ($i+1) }}_main"></div>
		</div>
	</div>
	<div class="title_intro result_times ans" id="A{{ ($i+1) }}" style="display:none;">
        <span id="A{{ ($i+1) }}_main"></span>
		<div id="chk_choice"><label><input type="checkbox" name="dou_check" id="dou_check{{ ($i+1) }}" value="1">再檢查</label></div>
	</div>
    @endforeach
	<div class="title_intro qno_btn">
        {{ csrf_field() }}
		<div id="btn_left"><input type="button" class="btn w150 f14" style="margin-left:5px; display:none;" id="perious" name="perious" value="上一題"><input type="button" class="btn w150 f14 btn_right" id="finish" name="finish" value="交卷"></div>
		<input type="button" class="btn w150 f14 btn_center" id="next" name="next" value="下一題">
	</div>
    </form>
</div>
</body>
</html>
<script language="javascript">
 window.resizeTo(screen.width,screen.height);
$(document).mouseleave(function(e){
  window.onbeforeunload = function (e) {
    return '確定放棄?';
  }
});
var token = "{{ $token }}";
var hours = {{ $hour }};
var minutes = {{ $min }};
var seconds = {{ $sec }};
var cache = 59;
var utime = 0;
$(document).ready(function (){
    var exam_times;
    setTimeout("count()", 1000);
    //count();
});
    function count(){
        if (hours===0 && minutes===0 && seconds===0){
            window.onbeforeunload = null;
            //exam_form.submit();
        }else{
            exam_times = setTimeout("count()", 1000);
            if (seconds === 0){
                if (minutes === 0){
                    hours -= 1;
                    minutes = 59;
                    seconds = cache;
                }else{
                    seconds = cache;
                    minutes = minutes-1;
                }
            }else{
                seconds = seconds-1;
            }
            utime++;
            gb('min').value = minutes;
            gb('sec').value = seconds;
            gb('hour').value = hours;
            var hour, min, sec;
            (hours<10) ? (hour = '0'+hours) : (hour = hours);
            (minutes<10) ? (min = '0'+minutes) : (min = minutes);
            (seconds<10) ? (sec = '0'+seconds) : (sec = seconds);
            gb('h').innerHTML = hour;
            gb('m').innerHTML = min;
            gb('s').innerHTML = sec;
            gb('utime').value = utime;
        }
    }
// document.onkeydown = function(event){
// //鎖特定按鍵 116 F5  123 F12
//     if (event.keyCode == 116 || event.keyCode == 123 ||  
//         event.keyCode == 17 || event.keyCode == 18 || 
//         event.keyCode == 82 || event.keyCode == 85 || 
//         event.keyCode == 73){
//         event.keyCode = 0;
//         event.returnValue = false;
//     }
// }
// document.oncontextmenu = function(){ //鎖右鍵 chrome可破解
//     event.returnValue = false;
// }
var current = {{ $curr }},
    n={{ $first_part->nums-$y }},
    y={{ $y }},
    r=0,
    all = {{ $first_part->nums }},
    stu_ans = Array({{ $first_part->nums }}),
    du_check = Array({{ $first_part->nums }});
    for (var i = 0; i < {{ $first_part->nums }}; i++) {
        stu_ans[i] = '';
        du_check[i] = '';
    }
function gb(v){
    return document.getElementById(v);
}
gb('n').innerHTML = n;
gb('y').innerHTML = y;
function but_change(){
    $('audio').each(function(){
        this.pause(); // Stop playing
        this.currentTime = 0; // Reset time
    }); 
    $('#go'+current).addClass('current');
    $('#go'+current).removeClass('other');
    if (current==1){
        //$('#finish').css('display','none');
        $('#perious').css('display','none');
        $('#next').css('display','inline-block');
    }else if (current==all){
        $('#next').css('display','none');
        //$('#finish').css('display','block');
        $('#perious').css('display','inline-block');

    }else{
        //$('#finish').css('display','none');
        $('#next').css('display','inline-block');
        $('#perious').css('display','inline-block');
    }
    if (current<10){
        $('#current').html('0'+current);    
    }else{
        $('#current').html(current);    
    }
}
function storeans(){
    var oans = Array();
    var qtype = gb("qtype").value;
    switch(qtype){
        case 'S':
        case 'R':
            $('input[name="ans'+current+'"]:checked').each(function(){
                oans.push(this.value);
            }); 
            break;
        case 'M':
            var qnum = gb('qnum').value;
            var i = 1;
            while(i<=qnum){
                let ans = $('input[name="ans'+current+'_'+i+'"]:checked').val();
                if (typeof ans !=="undefined")oans.push(ans);
                i++;
            }
            break;
        case 'D':
            $('input[name="ans'+current+'[]"]:checked').each(function(){
                oans.push(this.value);
            });
            break;
    }
    var ans = oans.join('.');
    if (ans!=''){
        $('#go'+current).addClass('finish');
        if (stu_ans[current-1]==''){
            n--;    y++;
            gb('n').innerHTML = n;
            gb('y').innerHTML = y;
        }
        stu_ans[current-1] = ans;
    } 
    var check = $('#dou_check'+current).prop('checked');
    if (check){//mark
        $('#go'+current).addClass('chk');
        if (du_check[current-1]==''){
            du_check[current-1] = 1;
            r++;
        }
    }else{
        if (du_check[current-1]==1){
            du_check[current-1] = '';
            r--;
            $('#go'+current).removeClass('chk');
        }
    }
    gb('r').innerHTML = r;
    $('#go'+current).removeClass('current');
    gb('current_qno').value = current;
}
$('#next').click(function(){
    storeans();
    var nqno = current+1;
    var txt = ($('#Q'+nqno+'_main').html()=='')?'n':'y';
    gb('next_qtxt').value = txt;
    gb('next_qa').value = 'n';
    if (txt=='n'){ clearTimeout(exam_times); }
    $.ajax({
        type:'POST',
        url:'{{ url('/exam') }}',
        dataType: 'JSON',
        data: $('#exam_form').serialize(),
        success: function (data, textStatus, jqXHR){
            utime = 0;
            gb("qtype").value = data.qtype;
            gb("qnum").value = data.qnum;
            if (txt=='n'){
                gb('Q'+nqno+'_main').innerHTML = data.qcont;
                gb('A'+nqno+'_main').innerHTML = data.ans;
            }
        }
    });

    if (txt=='n'){ count(); }
    $('#Q'+current).css('display','none');
    $('#A'+current).css('display','none');
    current+=1;
    $('#Q'+nqno).css('display','block');
    $('#A'+nqno).css('display','block');
    but_change();
});
$('#perious').click(function(){
    storeans();
    var pqno = current-1;
    var txt = ($('#Q'+pqno+'_main').html()=='')?'n':'y';
    gb('next_qtxt').value = txt;
    gb('next_qa').value = 'p';
    if (txt=='n'){ clearTimeout(exam_times); }
    $.ajax({
        type:'POST',
        url:'{{ url('/exam') }}',
        dataType: 'JSON',
        data: $('#exam_form').serialize(),
        success: function (data, textStatus, jqXHR){
            utime = 0;
            gb("qtype").value = data.qtype;
            gb("qnum").value = data.qnum;
            if (txt=='n'){
                gb('Q'+current+'_main').innerHTML = data.qcont;
                gb('A'+current+'_main').innerHTML = data.ans;
            }
        }
    });
    if (txt=='n'){ count(); }
    $('#Q'+current).css('display','none');
    $('#A'+current).css('display','none');
    current-=1;
    $('#Q'+pqno).css('display','block');
    $('#A'+pqno).css('display','block');
    but_change();
});
function go(qno){
    $('#go'+current).removeClass('current');
    storeans();
    var txt = ($('#Q'+qno+'_main').html()=='')?'n':'y';
    gb('next_qtxt').value = txt;
    gb('next_qa').value = 'q';
    gb('next_qno').value = qno;
    if (txt=='n'){ clearTimeout(exam_times); }
    $.ajax({
        type:'POST',
        url:'{{ url('/exam') }}',
        dataType: 'JSON',
        data: $('#exam_form').serialize(),
        success: function (data, textStatus, jqXHR){
            utime = 0;
            gb("qtype").value = data.qtype;
            gb("qnum").value = data.qnum;
            if (txt=='n'){
                gb('Q'+qno+'_main').innerHTML = data.qcont;
                gb('A'+qno+'_main').innerHTML = data.ans;
            }
        }
    });
    if (txt=='n'){ count(); }
    $('#Q'+current).css('display','none');
    $('#A'+current).css('display','none');
    current = qno;
    $('#Q'+qno).css('display','block');
    $('#A'+qno).css('display','block');
    //window.onbeforeunload = '';
    but_change();
}
$('#finish').click(function(){
    storeans();
    var len = stu_ans.length;
    var no_ans = Array();
    var no = 0;
    for (var i = 0; i < len; i++) {
        //console.log(stu_ans[i]);
        if (stu_ans[i]==''){
            no_ans[no] = (i+1);
            no++;
        }
    }
    gb('next_qa').value = 'f';
    if (no==0){
        if (confirm("確定要交卷?"))finish_done();
    }else{
        var all_no = no_ans.join(',');
        if (confirm("您還有第"+all_no+"題未作答，確定要交卷？"))finish_done();
    }
});
function finish_done(){
    window.onbeforeunload = null;
    $.ajax({
        type:'POST',
        url:'{{ url('/exam') }}',
        dataType: 'JSON',
        data: $('#exam_form').serialize(),
        success: function (data, textStatus, jqXHR){
            gb('next_qa').value = 'part';
            exam_form.submit();
        }
    });
}
function QS(obj){
    var s = $(obj).data('id');
    $(obj).text('播放中');
    var nqs = document.getElementById(s);
    nqs.addEventListener('timeupdate', getmusic, false);
    nqs.play();
}
function getmusic(e){
    console.log(e.srcElement.currentTime);
}
</script>
<script src="{{ URL::asset('js/app.js')}}"></script>