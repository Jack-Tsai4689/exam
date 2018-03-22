<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	@include('layout.sub')
	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		width: 90%;
    	}
    	.cen {
    		margin: 0 auto;
    		padding: 20px 0px 10px 0px;
    		margin: 0px 20px 0px 20px;
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
    	.last {
    		margin-bottom: 20px;
    	}
    	.oans {
    		display: none;
    	}
        .oans_control, .oans_control label {
            cursor: pointer;
        }
        .oans_control label {
            float: left;
        }
        #oans_pic {
            float: left;
            margin-top: 5px;
        }
    	select {
    		margin-right: 5px;
    	}
        video {
            width: 80%;
        }
        .pic {
            width: 80%;
        }
	</style>
</head>
<body>
<div id="all">
	<div class="title"><label class="f17">題目資訊-第{{ $qid }}題</label></div>
    <div class="content">
		<div class="cen">
			<table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr class="deep">
                    <td align="right">建立者</td>
                    <td width="80%">{{ $Owner }}</td>
                </tr>
				<tr>
                    <td><label class="f17">{{ $Quetype }}</label></td>
                    <td></td>
                </tr>
                <tr class="deep">
                    <td align="right">題目</td>
                    <td>{!! $Que_content !!}</td>
                </tr>
                <tr class="shallow">
                    <td align="right">解答</td>
                    <td width="80%">{{ $Ans }}</td>
                </tr>
                <tr class="deep">
                    <td align="right">知識點</td>
                    <td>{!! $Know_content !!}</td>
                </tr>
                <tr class="shallow">
                    <td align="right">關鍵字</td>
                    <td>{{ $Keyword }}</td>
                </tr>
                <tr class="deep">
                    <td align="right">範圍</td>
                    <td width="80%">【{{ $Grade }}】【{{ $Subject }}】【{{ $Chapter }}】【{{ $Degree }}】</td>
                </tr>
                <tr>
                    <td><label class="f17 oans_control" onclick="show_oans('oans')"><img id="oans_pic" src="{{ URL::asset('img/close.png')}}" height="20">詳解</label></td>
                    <td></td>
                </tr>
            </table>
            <table class="list oans last" border="0" width="100%" cellpadding="0" cellspacing="0" id="oans">
                <tr class="deep">
                    <td>{!! $Ans_content !!}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="content" style="margin-bottom:50px;">
        <div class="cen" style="padding-bottom:50px;">
            <div style="text-align:left;">
                @if ($Owner===session('epno'))
                    <input type="button" class="btn w150 h30" value="編輯" name="save_next" id="save_next" onclick="redire()">
                @endif
                <input type="submit" class="btn w150 h30" value="關閉" name="save_close" id="save_close" onclick="window.close();">
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
function show_oans(obj){
    var ans = $('#'+obj);
	if (ans.css('display')=='none'){
		ans.css('display','table');
		$('#'+obj+'_pic').prop('src','{{ URL::asset('img/open.png') }}');
	}else{
		ans.css('display','none');
		$('#'+obj+'_pic').prop('src','{{ URL::asset('img/close.png') }}');
	}
}
function redire(){
    location.href="{{ url('ques/'.$qid.'/edit') }}";
}
window.moveTo((screen.width)*0.2,0);window.resizeTo((screen.width)*0.8,screen.height);window.focus();
</SCRIPT>