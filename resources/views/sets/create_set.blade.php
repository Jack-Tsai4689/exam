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
    	#duty td {
    		padding-bottom: 0px;
    	}
    	.list_edit tr td{
    		margin-bottom: 10px;
    		/*height: 25px;
    		line-height: 25px;*/
    		padding-left: 10px;
    		vertical-align: top;
    	}
    	.list_edit label {
    		margin-right: 5px;
    	}
    	.list_edit input {
    		margin-right: 5px;
    	}
    	.list_edit {
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
            /*vertical-align: top;*/
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
        .sub {
            margin-top: 5px;
            border-bottom: 0.5px #D2D5D5 solid;
            padding: 5px;
        }
        #ad {
            top: 0px;
            left: 0px;
            display: none;
            width: 0px;
            height: 0px;
        }
        .hiden {
            display: none;
        }
        #big_title div input, #big_title div select {
            margin-top: 3px;
        }
        #divsc {
            display: inline-block;
        }
        .csub_intro {
            float: left;
            margin-top: 10px;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div class="title"><label class="f17">{{ $title }}</label></div>
    <form name="form1" id="form1" method="post" action="{{ url('sets') }}" onsubmit="return check_data()">
    	<div class="content">
    		<div class="cen last">
    			<table class="list_edit" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr class="deep">
                        <td width="250" align="center">考卷名稱</td>
                        <td width="80%"><INPUT type="text" class="input_field w250" id="setsname" name="setsname" size="15" maxlength="20" value="" ></td>
                    </tr>
    				<tr class="shallow" id="duty">
                        <td align="center">考卷說明</td>
                        <td><textarea name="intro" id="intro" cols="50" rows="4" placeholder="範例：1-20題是非題。21-40題選擇題共40題" value=""></textarea></td>
                    </tr>
                    <tr class="deep">
                        <td align="center">年級</td>
                        <td>
                            <select name="grade" id="grade" onchange="subj_c(this.value)">
                                {!! $Grade !!}
                            </select>
                        </td>
                    </tr>
                    <tr class="shallow">
                        <td align="center">科目</td>
                        <td id="subj">
                            <select name="subject" id="subject">
                                {!! $Subject !!}
                            </select>
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
                        <td align="center">大題</td>
                        <td>
                            <label><input type="checkbox" name="have_sub" id="have_sub" value="1" onclick="need_sub(this.checked)">需要大題</label>
                            <div id="divsc">
                                <select name="control" id="control">
                                    <option value="Y">可回上題修改</option>
                                    <option value="N">不可回上題修改</option>
                                </select>
                            </div>
                            　<input type="button" id="moresub" value="新增大題" onclick="add_title()" class="btn hiden">
                            <div id="big_title"></div>
                        </td>
                    </tr>
    			</table>
                <div>
                    {{ csrf_field() }}
                	<div style="text-align:left; float:left;"><INPUT type="submit" class="btn w150 f16" value="儲存" name="save" id="save"></div>
    				<div style="text-align:right; height:30px; line-height:30px;"><a href="{{ url('sets') }}"><font class="f15">返回上一層</font></a></div>
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
    $( ".date" ).datepicker({
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
function del(obj){
    var sub = obj.parentElement.parentElement;
    $(sub).remove();
}
function add_title(){
    var html = '<div class="sub"><div><label>大題</label><input type="text" style="width:40px; text-align: center;" class="input_field subsort" name="sub_sort[]" placeholder="順序"><label>　分數比重</label><input type="text" style="width: 40px; text-align: center;" class="input_field subscore" name="sub_score[]" maxlength="4"><label>%　</label><select name="sub_control[]"><option value="Y">可回上題修改</option><option value="N">不可回上題修改</option></select><span title="移除" onclick="del(this)">&times;</span></div><div><label class="csub_intro">大題說明</label><textarea name="sub_intro[]" class="subintro"></textarea></div></div>';
    $('#big_title').append(html);
}
function need_sub(v){
    if (v){
        $("#moresub").removeClass('hiden');
        $("#big_title").removeClass('hiden');
        $("#divsc").css('display','none');
    }else{
        $("#moresub").addClass('hiden');
        $("#big_title").addClass('hiden');
        $("#divsc").css('display','inline-block');
    }
}
function check_data(){
    var setsname = trim(gb('setsname').value);//名稱
    var error = false;
    var percen = 0;
    if ($("#have_sub").prop('checked')){
        $(".subsort").each(function(){
            if (isNaN(this.value)){
                error = true; alert('大題順序只能數字'); return false;
            }
            if (this.value=='' || this.value<1){
                error = true; alert('順序至少為1'); return false;
            }
        });
        if (error)return false;
        $(".subscore").each(function(){
            if (isNaN(this.value)){
                error = true; alert('分數比例只能數字'); return false;
            }
            if (this.value=='' || this.value<1){
                error = true; alert('分數比例至少為1'); return false;
            }
            percen+=Number(this.value);
        });
        if (percen!=100){
            alert('分數比例總和需為100'); return false;
        }
        // $(".subintro").each(function(){
        //     if (this.value==''){
        //         error = true;
        //         alert('大題說明請確實填寫');
        //         return false;
        //     }
        // });
        if (error)return false;
    }
    if (setsname==''){
        alert('考試名稱不得空值'); return false;
    }else if (!isNaN(setsname)){
        alert('考卷名稱不能都是數字'); return false;
    }
    var date = $('input[name=chk_date]:checked').val();
    if (date==0){
        if (gb('begdate').value > gb('enddate').value){
            alert('考試結束時間需大於等於考試起始時間!'); return false;
        }
    }
    // var limTimeH = gb('limTimeH').value;
    // var limTimeM = gb('limTimeM').value;
    // var limTimeS = gb('limTimeS').value;
    // if (limTimeH==0 && limTimeM==0 && limTimeS==0){
    //     alert('考試限時不可以都是0!'); return false;
    // }
    var passscore = gb('passscore').value;
    if (passscore<=0 || isNaN(passscore)){
        alert('及格分數有誤'); return false;
    }
    passscore = Number(passscore);
    var sum_score = gb('sum').value;
    if (sum_score<=0 || isNaN(sum_score)){
        alert('總分有誤'); return false;
    }
    sum_score = Number(sum_score);
    if (sum_score<passscore){
        alert('分數錯誤'); return false;
    }
}
function subj_c(v){
    $.ajax({
        type:"GET",
        url:"{{ url('basic/detail')}}",
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