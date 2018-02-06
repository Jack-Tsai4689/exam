<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	@include('layout.sub')	
	<style type="text/css">
    	#all {
    		width: 800px;
    		margin: 50px auto;
    	}
    	#cen {
            font-size: 20px;
            margin: 20px 20px 0px 20px;
            position: relative;
    	}
    	#cen > div {
    		line-height: 25px;
    	}
    	div.sub {
    		margin-left: 2em;
    		font-size: 16px;
    	}
    	div.sub_intro {
    		/*margin-left: 4em;*/
    		font-size: 16px;	
    	}
    	#go {
    		position: relative;
    		text-align: center;
    		margin-bottom: 20px;
    	}
    	#go input {
    		font-size: 20px;
    	}
    	#tip {
    		font-size: 14px;
    		line-height: 16px !important;
    		margin-top: 5px;
    	}
    	.text{

    	}
	</style>
</head>
<body>
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<form name="form1" id="form1" method="post" action="exam.php">
		<div class="content">
			<div id="cen">
				<table cellpadding="0" cellspacing="0" class="list" width="100%">
					<tr>
						<td align="center">總分</td>
						<td><strong><font color="blue">{{ $Sum }}</font></strong>分</td>
					</tr>
					@foreach ($Sub_info as $key => $v)
	                <tr>
						<td></td>
						<td>第{{ ($key+1) }}大題&nbsp;-&nbsp;配分&nbsp;<strong><font color="blue">{{ $v->s_percen }}</font></strong>%&nbsp;　{{ $v->back }}可回上題修改</td>
					</tr>
					<tr>
						<td></td>
						<td>{{ $v->intro }}</td>
					</tr>
					@endforeach
					<tr>
						<td align="center">及格</td>
						<td><strong><font color="blue">{{ $Pass_core }}</font></strong>分</td>
					</tr>
					<tr>
						<td align="center">限時</td>
						<td>{{ $Limetime }}</td>
					</tr>
					<tr>
						<td align="center">成績公佈</td>
						<td>{{ $score_open }}</td>
					</tr>
				</table>
                <div>總分　<strong><font color="blue">{{ $Sum }}</font></strong>分</div>
                @foreach ($Sub_info as $key => $v)
                	<div class="sub">第{{ ($key+1) }}大題&nbsp;-&nbsp;配分&nbsp;<strong><font color="blue">{{ $v->s_percen }}</font></strong>%&nbsp;　{{ $v->back }}可回上題修改</div>
{{--                 	<div class="sub_intro">{{ $v->s_intro }}</div> --}}
                @endforeach
                <div>及格　<strong><font color="blue">{{ $Pass_core }}</font></strong>分</div>
                <div>限時　{{ $Limetime }}</div>
				<div id="tip"><font color="red">※&nbsp;{{ $Times }}<br>※&nbsp;請再次確認您選擇的考卷是否正確<br>※&nbsp;點擊「開始測驗」進行考試<br>※&nbsp;考試時，請勿重整網頁，以免影響您的考試權益</font></div>
			</div>
            <div id="go"><input type="submit" class="btn w100" value="開始測驗"></div>
		</div>
		<input type="hidden" name="exam_type" value="{{ $exam_type }}">
		<input type="hidden" name="exam_exnum" value="{{ $exam_exnum }}">
		<input type="hidden" name="exam_grade" value="{{ $exam_grade }}">
		<input type="hidden" name="exam_subject" value="{{ $exam_subject }}">
		<input type="hidden" name="exam_chapter" value="{{ $exam_chapter }}">
		<input type="hidden" name="exam_degree" value="{{ $exam_degree }}">
		<input type="hidden" name="exam_listseq" value="{{ $exam_listseq }}">
		<input type="hidden" name="exam_limtime" value="{{ $exam_limtime }}">
		<input type="hidden" name="exam_cram" value="{{ $exam_cram }}">
		<input type="hidden" name="exam_fkey" value="{{ $exam_fkey }}">
	</FORM>
</body>
</html>
<script type="text/javascript">
document.onkeydown = function(event){
//F5->116  F12->123 shift->16 ctrl->17 alt->18 R->82 U->85 I->73 S->83 P->80
	var key_array = [116,123,16,17,18,80,82,83,85,73];
	key_array.forEach(function(key){
		if (event.keyCode==key){
			event.keyCode = 0;
        	event.returnValue = false;
        	return false;
		}
	});
}
//window.moveTo((screen.width)/4,(screen.height)/5);
</script>