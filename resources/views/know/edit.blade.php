@extends('layout.default')
@section('style')
	<style type="text/css">
    	#all {
    		width: 1152px;
    	}
    	#cen {
    		margin: 0 auto;
    		padding: 20px 0px 50px 0px;
    		margin: 0px 20px 0px 20px;
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
    		font-size: 14px;
    	}
    	.list {
    		margin-bottom: 20px;
    	}
    	select {
    		margin-right: 5px;
    	}
    	textarea {
    		width: 500px;
    		height: 60px;
    		margin: 5px 0px 5px 0px;
    		border: 1px #EED684 solid;
    	}
    	.tip {
    		font-size: 11px;
    	}
        #loading_status {
            width: 48px;
        }
        .set_all {
            margin: 2% auto;
            width: 960px;
            text-align: center;
        }
        .hiden {
            display: none;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<FORM name="form1" method="POST" action="{{ url('/know/'.$Kid) }}" onsubmit="return check(this)" enctype="multipart/form-data">
	<div class="content">
		<div id="cen">
			<table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr class="deep">
                    <td align="right">發表者</td>
                    <td width="80%">{{ $Owner }}</td>
                </tr>
                <tr class="shallow">
                    <td width="250" align="right">名稱</td>
                    <td width="80%">
                    	<input type="text" class="input_field" name="f_kname" id="f_kname" cols="50" rows="4" value="{{ $Kname }}">
                    </td>
                </tr>
				<tr class="deep" id="duty">
                    <td align="right">文字說明</td>
                    <td><textarea  name="f_kcont" id="f_kcont" cols="50" rows="4" value="{{ $Kcontent }}">{{ $Kcontent }}</textarea></td>
                </tr>
                
                <tr class="shallow">
                    <td align="right">圖檔</td>
                    <td>
                        <div id="kmold" {!! $Kmsold !!}><IMG id="kimg" src="{{ $Kimg }}" width="98%"><br>{!! $Kimg_html !!}</div>
                        <div id="kmup" {!! $Km_upload !!}><input type="file" name="kpic" id="kpic" accept=".jpg,.jpeg,.png">格式：JPG/PNG
                        <input type="hidden" id="km_src" name="km_src" value="{{ $Kimgsrc }}"></div>
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">關鍵字</td>
                    <td>(每個最多10個字)<br>
                        @foreach($Kkeword as $k)
                        <input type="text" class="input_field w150" name="fk[]" maxlength="10" value="{{ $k }}">
                        @endforeach
                    </td>
                </tr>
                <tr class="shallow">
                    <td align="right">類別</td>
                    <td>
                    	<select name="f_grade" id="f_grade" onchange="subj_c(this.value)">{!! $Grade !!}</select>
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">科目</td>
                    <td>
                        <select name="f_subject" id="f_subject" onchange="chap_c(this.value)">{!! $Subject !!}</select>
                    </td>
                </tr>
                <tr class="shallow">
                    <TD align="right">章節</TD>
                    <td>
                        <select name="f_chapter" id="f_chapter">{!! $Chapter !!}</select>
                    </TD>
                </TR>
			</table>
            <div>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
            	<div style="text-align:left; float:left;"><input type="submit" class="btn w150 h30" value="確定" name="sure" id="sure"></div>
                <div style="text-align:right; height:30px; line-height:30px;"><a href="register1.html"><font class="f15"><a href="{{ url('/know') }}">回上頁</a></font></a></div>
			</div>
		</div>
	</div>
	</form>
</div>
@stop
@section('script')
<script type="text/javascript">
function rem(v){
    if (confirm('確定刪除?')){
        gb('km_src').value = '';
        $("#kmup").removeClass("hiden");
        gb("kmold").innerHTML = '';
        $("#kmold").hide();
    }
}
function subj_c(v){
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'type':'subj', 'g':v},
        success: function(rs){
            $("#f_subject").html('');
            var html = '';
            for(var i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            $("#f_subject").html(html);
            chap_c(gb('f_subject').value);
        },
        error: function(){
            gb('f_subject').innerHTML = '<option value="0">無科目</optoin>';
            gb('f_chapter').innerHTML = '<option value="0">無章節</optoin>';
        }
    });
}
function chap_c(v){
    $('#f_chapter').empty();
    $.ajax({
        type:"GET",
        url:"{{ url('/basic/detail') }}",
        dataType:"JSON",
        data:{'type':'chap', 'g':gb('f_grade').value, 's':v},
        success: function(rs){
            var html = '';
            for(var i in rs){
                html+= '<option value="'+rs[i].ID+'">'+rs[i].NAME+'</option>';
            }
            $("#f_chapter").html(html);
        },
        error: function(){
            gb('f_chapter').innerHTML = '<option value="0">無章節</optoin>';
        }
    });
}
function check(obj){//onsubmit data_chect
    var potname = gb('f_kname').value;
    var keyword = gb('f_kw').value;
    if (trim(potname)==''){
        alert('資料不完整');
        return false;
    }
}
function uknow(v){
    if (v==="dknow" || v==="deknow"){
        $.ajax({
            type:"POST",
            url:"{{ url('/know/rmpic') }}",
            data:{'type':v},
            dataType:"JSON",
            success: function(rs){
                gb('kimg_content').innerHTML = rs.html;
                gb('kimg').src = '';
                gb('f_kimg').value = '';
            }
        });
        return;
    }
    document.getElementById('que_pic').src="{{ url('/know/qupload') }}?type="+v;
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
</script>
@stop