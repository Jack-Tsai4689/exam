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
    <form name="form1" id="form1" method="post" action="{{ url('sets') }}" onsubmit="return check_data()">
    	<div class="content">
    		<div class="cen last">
    			<table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="left"><label class="f17">適用範圍</label></td>
                        <td></td>
                    </tr>
                    <tr class="deep">
                        <td width="250" align="center">班級</td>
                        <td width="80%">
                            <select name="ca" id="ca">
                                <option></option>
                            </select>
                        </td>
                    </tr>
    				<tr class="shallow">
                        <td align="center">班別</td>
                        <td>
                            <select name="cla" id="cla">
                                <option value="0">全部</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="deep">
                        <td align="center">考卷</td>
                        <td>
                        	<select name="sets" id="sets">
                                <option></option>   
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="left"><label class="f17">使用試卷</label></td>
                        <td></td>
                    </tr>
                    <tr class="deep">
                        <td align="center">年級</td>
                        <td>
                            <select name="grade" id="grade" onchange="subj_c(this.value)">
                                @foreach($Grade as $g)
                                <option value="{{ $g->g_id }}">{{ $g->g_name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="shallow">
                        <td align="center">科目</td>
                        <td>
                            <select name="subj" id="subj" onchange="sets_c(this.value)">
                                @foreach($Subject as $su)
                                <option value="{{ $su->g_id }}">{{ $su->g_name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="deep">
                        <td align="center">派卷</td>
                        <td>
                            <select name="sets" id="sets">
                                @foreach($Sets as $s)
                                <option value="{{ $s->s_id }}">{{ $s->s_name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="left"><label class="f17">參數設定</label></td>
                        <td></td>
                    </tr>
                    <tr class="shallow">
                        <td align="center">測驗時間</td>
                        <td>
                            <label><input type="radio" name="chk_date" value="2" checked>不限</label>
                            <label><input type="radio" name="chk_date" value="1">期間內</label>
                            <INPUT type="text" class="input_field date" name="begdate" id="begdate" size="10" maxlength="10" readonly value="{{ $Time->begdate }}">
                            <select name="begTimeH">{!! $Time->begTimeH !!}</select>時 　～　
                            <INPUT type="text" class="input_field date" name="enddate" id="enddate" size="10"  maxlength="10" readonly value="{{ $Time->enddate }}">
                            <select name="endTimeH">{!! $Time->endTimeH !!}</select>時
                        </td>
                    </tr>
                    <tr class="deep">
                        <TD align="center">測驗限時</TD>
                        <td>
                            <select name='limTimeH' id='limTimeH'>{!! $Lim->limTimeH !!}</select>時  
                    		<select name='limTimeM' id='limTimeM'>{!! $Lim->limTimeM !!}</select>分 
                    		<select name='limTimeS' id='limTimeS'>{!! $Lim->limTimeS !!}</select>秒
                        </TD>
                    </TR>
                    <tr class="shallow">
                        <TD align="center">重覆測試</TD>
                        <td>
                    		<label><input type="radio" name="f_times" value="2">不行</label>
                    		<label><input type="radio" name="f_times" value="1" checked>可以</label>
                        </TD>
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
                            <INPUT type="text" class="input_field date" name="score_date" id="score_date" size="10" maxlength="10" readonly value="" onchange="">
                            <select name="scoreTimeH"></select>時
                            </div>
                        </td>
                    </tr>
    			</table>
                <div>
                    {{ csrf_field() }}
                	<div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="發佈" name="save" id="save">　<font color="red">*發佈後，如有學生已進行考試，將無法調整</font></div>
    				<div style="text-align:right; height:30px; line-height:30px;"><a href="{{ url('pub') }}"><font class="f15">返回上一層</font></a></div>
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
    $(".date").datepicker({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
    });
});
function publish(v){
    if (v=='t'){
        gb('score_view').style.display = 'inline-block';
    }else{
        gb('score_view').style.display = 'none';
    }
}
function check_data(){
    var setsname = trim(gb('setsname').value);//名稱
    var error = false;
    var percen = 0;
    var date = $('input[name=chk_date]:checked').val();
    if (date==0){
        if (gb('begdate').value > gb('enddate').value){
            alert('測驗時間錯誤'); return false;
        }
    }
    var limTimeH = gb('limTimeH').value;
    var limTimeM = gb('limTimeM').value;
    var limTimeS = gb('limTimeS').value;
    if (limTimeH==0 && limTimeM==0 && limTimeS==0){
        alert('測驗限時不得為0'); return false;
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
        url:"{{ url('basic/detail')}}",
        dataType:"JSON",
        data:{'type':'subj', 'g':v},
        success: function(rs){
            $("#subj").html('');
            var html = '';
            for(var i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            $("#subj").html(html);
        }
    });
}
function sets_c(v){
    if (v===0){
        $("#sets").html('<option value="0">無試卷</option>');
        return;
    }
    $.ajax({
        type:"GET",
        url:"{{ url('sets/pfetch') }}",
        dataType:"JSON",
        data:{'g':gb('grade').value, 's':gb('subj').value},
        success: function(rs){
            $("#sets").html('');
            var html = '';
            for(var i in rs){
                html+= '<option value="'+rs[i].ID+'">'.rs[i].NAME+'</option>';
            }
            $("#sets").html(html);
        }
    })
}
</script>
@stop