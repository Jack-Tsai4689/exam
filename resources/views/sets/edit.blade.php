@extends('layout.default')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/cssfunc/ex_sets.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<style type="text/css">
    	#all {
    		width: 1280px;
    	}
    	.cen {
    		margin: 0 auto;
    		padding: 20px 0px 20px 0px;
    		margin: 0px 20px 0px 20px;
    	}
    	.last {
    		padding: 20px 0px 50px 0px;
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
    	.input_field {
    		margin:0px;
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
    	.list {
    		margin-bottom: 20px;
    	}
    	#begdate, #enddate {
    		margin-right: 0px;
    	}
    	select {
    		margin-right: 5px;
    	}
    	textarea {
    		width: 500px;
    		height: 65px;
    		margin: 5px 0px 5px 0px;
    		border: 1px #EED6B4 solid;
    	}
        .sub_title {
            width: 300px;
        }
        .sub label, .sub input, .sub select {
            vertical-align: top;
        }
        .sub span {
            vertical-align: top;
            font-size: 18px;
            cursor: pointer;
        }
        .sub span:hover {
            background-color: #DDD;
            border: 0.5px #DDD solid;
            border-radius: 5px;
        }
        #score_view {
            display: none;
        }
        #big_title div {
            margin-top: 5px;
            border: 0.5px #D2D5D5 solid;
            padding: 5px;
        }
        #ad {
            top: 0px;
            left: 0px;
            display: none;
            width: 0px;
            height: 0px;
        }
	</style>
@stop
@section('content')
<div id="all">
		<div class="title"><label class="f17">{{ $title }}</label></div>
		<form name="form1" id="form1" method="post" action="{{ url('/sets/'.$Sid) }}" onsubmit="return check_data()">
		<div class="content">
			<div class="cen last">
				<table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
	                <tr class="deep">
	                    <td width="250" align="center">考卷名稱</td>
	                    <td width="80%"><INPUT type="text" class="input_field w250" id="setsname" name="setsname" size="15" maxlength="20" value="{{ $Setsname}}"></td>
	                </tr>
					<tr class="shallow" id="duty">
	                    <td align="center">考卷說明</td>
	                    <td><textarea name="intro" id="intro" cols="50" rows="4" placeholder="範例：1-20題是非題。21-40題選擇題共40題" value="{{ $Intro }}">{{ $Intro }}</textarea></td>
	                </tr>
	                <tr class="deep">
	                    <td align="center">考試時間</td>
	                    <td>
	                    	<label><input type="radio" name="chk_date" {{ $Time->date_N }} value="2">不限</label>
	                    	<label><input type="radio" name="chk_date" {{ $Time->date_Y }} value="1">期間內</label>
	                    	<INPUT type="text" class="input_field" name="begdate" id="begdate" size="10" maxlength="10" readonly value="{{ $Time->begdate }}">
                    		<select name="begTimeH">{!! $Time->begTimeH !!}</select>時 　～　
                    		<INPUT type="text" class="input_field" name="enddate" id="enddate" size="10"  maxlength="10" readonly value="{{ $Time->enddate }}">
                    		<select name="endTimeH">{!! $Time->endTimeH !!}</select>時
	                    </td>
	                </tr>
	                <tr class="shallow">
	                    <TD align="center">考試限時</TD>
	                    <td>
                            <select name='limTimeH' id='limTimeH'>{!! $Lim->limTimeH !!}</select>時  
                    		<select name='limTimeM' id='limTimeM'>{!! $Lim->limTimeM !!}</select>分 
                    		<select name='limTimeS' id='limTimeS'>{!! $Lim->limTimeS !!}</select>秒
	                    </TD>
	                </TR>
	                <tr class="deep">
	                    <TD align="center">重覆考</TD>
	                    <td>
                    		<label><input type="radio" name="f_times" {{ $Again->N }} value="2">不行</label>
                    		<label><input type="radio" name="f_times" {{ $Again->Y }} value="1">可以</label>
	                    </TD>
	                </tr>
                    <tr class="shallow">
                        <td align="center">年級</td>
                        <td>
                            <select name="grade" id="grade" onchange="subj_c(this.value)">{!! $Grade !!}</select>
                        </td>
                    </tr>
                    <tr class="deep">
                        <td align="center">科目</td>
                        <td id="subj">
                            <select name="subject" id="subject">{!! $Subject !!}</select>
                    </td>
                    </tr>
                    <tr class="deep">
                        <TD align="center">總分</TD>
                        <TD><INPUT type="text" class="input_field w50" id="sum" name="sum" size="3" maxlength="3" value="{{ $Sum }}"></TD>
                    </TR>
                    <tr class="shallow">
                        <TD align="center">及格分數</TD>
                        <TD><INPUT type="text" class="input_field w50" id="passscore" name="passscore" size="3" maxlength="3" value="{{ $Pass }}"></TD>
                    </TR>
                    <tr class="deep">
                        <TD align="center">公佈答案</TD>
                        <td>
                            <select name="score_out" id="score_out" onchange="publish(this.value)">
                                <option selected value="n">作答完公佈</option>
                                <option value="t">時間到公佈</option>
                            </select>
                            <div id="score_view">
                            <INPUT type="text" class="input_field" name="score_date" id="score_date" size="10" maxlength="10" readonly value="" onchange="">
                            <select name="scoreTimeH"></select>時
                            </div>
                        </td>
                    </tr>
				</table>
                <div>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                	<div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="儲存" name="save" id="save"></div>
					<div style="text-align:right; height:30px; line-height:30px;"><a href="{{ url('/sets') }}"><font class="f15">返回上一層</font></a></div>
				</div>
            </div>
		</div>
    </form>
</div>
@stop
@section('script')
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="{{ URL::asset('/js/jquery-ui.js') }}"></script>
<script type="text/javascript">
$(function() {
    $( "#begdate" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
    });
    $( "#enddate" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
    });
    $( "#score_date" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
    });
});
var sub = 1;
function publish(v){
    if (v=='t'){
        gb('score_view').style.display = 'inline-block';
    }else{
        gb('score_view').style.display = 'none';
    }
}
function del(v){
    $('#sub'+v).remove();
}
function page_control(v){
    if (v=='S'){
        $('select[name="sub_control[]"]').each(function(){
            this.disabled = false;
        });
    }else{
        $('select[name="sub_control[]"]').each(function(){
            this.disabled = true;
            this.value = v;
        });
    }
}
function add_title(){
    var control = true;
    var select_y = false;
    var select_n = false;
    if ($('#control').val()=='S'){
        control = false;
    }else{
        if ($('#control').val()=='Y'){select_y = true;}else{select_n = true;}
    }
    $('#big_title').append(
        $('<div>').attr({class:'sub',id:'sub'+sub}).append(
            $('<label>').text('大題'),
            $('<input>').attr({style:'width:40px; text-align:center', type:'text', class:'input_field', name:'sub_sort[]',id:'sub_sort'+sub}),
            $('<label>').text(' 分數比重'),
            $('<input>').attr({style:'width:40px; text-align:center;', type:'text', class:'input_field', name:'sub_score[]',maxlength:'4'}),
            $('<label>').text('% 翻頁控制'),
            $('<select>').attr({name:'sub_control[]',id:'sub_control'+sub,disabled:control}).append(
                $('<option>').attr({value:'Y', selected:select_y}).text('可回上題修改'),
                $('<option>').attr({value:'N', selected:select_n}).text('不可回上題修改')
            ),
            $('<span>').attr({title:'移除', onclick:'del('+sub+')'}).html('&times;'),'<br>',
            $('<label>').text('大題說明'),
            $('<textarea>').attr({name:'sub_intro[]'}),'&nbsp;&nbsp;'
        )
    );
    sub++;
}
// function check_all(obj,cName)
//     {
//         var checkboxs = document.getElementsByName(cName);
//         for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;}
//     }
function check_data(){
    var setsname = trim(gb('setsname').value);//名稱
    var error = false;
    var percen = 0;
    // $('input[name="sub_sort[]"]').each(function(){
    //     if (isNaN(this.value)){
    //         error = true; alert('大題順序只能數字'); return false;
    //     }
    //     if (this.value=='' || this.value<1){
    //         error = true; alert('順序至少為1'); return false;
    //     }
    // });
    // if (error)return false;
    // $('input[name="sub_score[]"]').each(function(){
    //     if (isNaN(this.value)){
    //         error = true; alert('分數比例只能數字'); return false;
    //     }
    //     if (this.value=='' || this.value<1){
    //         error = true; alert('分數比例至少為1'); return false;
    //     }
    //     percen+=Number(this.value);
    // });
    // if (percen!=100){
    //     alert('分數比例總和需為100'); return false;
    // }
    // $('textarea[name="sub_intro[]"]').each(function(){
    //     if (this.value==''){
    //         error = true;
    //         alert('大題說明請確實填寫');
    //         return false;
    //     }
    // });
    //if (error)return false;
    if (setsname==''){
        alert('考試名稱有誤'); return false;
    }else if (!isNaN(setsname)){
        alert('[考卷名稱]不能都是數字喔'); return false;
    }
    var date = $('input[name=chk_date]:checked').val();
    if (date==0){
        if (gb('begdate').value > gb('enddate').value){
            alert('考試結束時間需大於等於考試起始時間!'); return false;
        }
    }
    var limTimeH = gb('limTimeH').value;
    var limTimeM = gb('limTimeM').value;
    var limTimeS = gb('limTimeS').value;
    if (limTimeH==0 && limTimeM==0 && limTimeS==0){
        alert('考試限時不可以都是0!'); return false;
    }
    var passscore = gb('passscore').value;
    if (passscore<=0 || isNaN(passscore)){
        alert('及格分數有誤'); return false;
    }
    var sum_score = gb('sum').value;
    if (sum_score<=0 || isNaN(sum_score)){
        alert('總分有誤'); return false;
    }
}
function subj_c(v){
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'type':'subj', 'g':v},
        success: function(rs){
            $("#subject").html('');
            var html = '';
            for(var i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            $("#subject").html(html);
        }
    });
}
</script>
@stop