@extends('layout.default')
@section('style') 
	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		min-width: 1152px;
    	}
    	#title {
    		height: 30px;
    		line-height: 30px;
    	}
    	.title_intro{
    		height: 40px;
    		line-height: 40px;
    	}
    	.title_intro input {
    		margin-left: 5px;
    	}
    	.title_intro label {
    		margin-right: 5px;
    		font-size: 16px;
    	}
    	.result_times{
    		text-align: center;
    	}
    	#cen {
    		padding: 20px 5px 15px 5px;
    	}
    	.qno {
    		width: 45px;
    		vertical-align: middle;
    		font-size: 18px;
    	}
    	.qno_c {
    		width: 50px;
    		vertical-align: middle;
    	}
    	.qno_ans {
    		font-size: 16px;
    		vertical-align: middle;
            width: 50px;
    	}
    	.qno_ans div {
    		margin-bottom: 5px;
    	}
    	.qno_ans input {
    		margin-right: 5px;
    	}
    	.qno_intro {
    		width: 1000px;
    	}
        .que {
            width: 1000px;
        }
        .que img {
            width: 1000px;
        }
    	.list td {
    		padding-bottom: 5px;
    	}
    	.list {
    		margin-bottom: 15px;
    		margin-left: 5px;
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
        .eror {
            color: #B3B3B3;
        }
        .part_info {
            text-align: center;
            font-size: 16px;
            font-weight: bolder;
        }
        .part_info > .right {
            color: blue;
        }
        .part_info > .wrong {
            color: red;
        }
        .part_info > .none {
            color: gray;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<div class="title_intro result_times">
		<label>費時</label><label>時分秒</label>
		<label>答對</label><label style="color:#076AAF">{{ $exam->e_rnum }}</label><label>題<label>
		<label>答錯</label><label style="color:#B7282C">{{ $exam->e_wnum }}</label><label>題</label>
		<label>未答<label>{{ $exam->e_nnum }}</label><label>題</label>
		<label>得分<label><font color="#C1272D">{{ $exam->e_score }}</font></label>分
	</div>
	<div class="title_intro">
		<input type="button" class="btn w150 f14" value="考題來源表" onclick="">
		<input type="button" class="btn w150 f14" value="觀念答對比率圖" onclick="">
		<input type="button" class="btn w150 f14" value="列印診斷報告" onclick="">
		<label>診斷報告封面標題</label>
		<input type="text" class="input_field w250" name="f_reporttitle" id="f_reporttitle">
		<INPUT type="hidden" name="f_sid" id="f_sid"  value="">
        <INPUT type="hidden" name="p_sids" id="p_sids"  value="">
        <INPUT type="hidden" name="f_subject" id="f_subject" value="">
        <INPUT type="hidden" name="fkey" id="fkey" value="">
	</div>
    @foreach($Data as $i => $p)
    <div class="title_intro">
        <div class="part_info">第 {{ ($i+1) }} 大題({{ $p->sets_info()->s_percen }}%)　<span class="right">答對 {{ $p->e_rnum }} 題</span>　<span class="wrong">答錯 {{ $p->e_wnum }} 題</span>　<span class="none"> {{ $p->e_nnum }} 題未答</span></div>
    </div>
	<div class="content">
		<div id="cen">
			<table class="list" cellpadding="0" cellspacing="0" width="100%">
                @foreach($p->ques_ans() as $q)
				<tr align="center">
					<td class="qno">{{ $q->qno }}.</td>
					<td class="qno_c"><img src="{{ URL::asset($q->right_pic) }}"></td>
					<td class="qno_ans">{{ $q->myans }}</td>
					<td class="que" align="left">{!! $q->qcont !!}</td>
                </tr>
                <tr align="center">
                    <td class="qno">解答</td>
                    <td class="qno_c"></td>
                    <td class="qno_ans">{{ $q->q_ans }}</td>
                    <td class="que" align="left">{!! $q->acont !!}</td>
                </tr>
                <tr>
                    <td colspan="5"><hr></td>
                </tr>
                @endforeach
			</table>
		</div>
	</div>
    @endforeach
	<div class="title_intro">
		<input type="button" class="btn w150 f14" name="" id="" value="考題來源表">
		<input type="button" class="btn w150 f14" name="" id="" value="觀念答對比率圖">
		<input type="button" class="btn w150 f14" name="" id="" value="列印診斷報告">
		<label>診斷報告封面標題</label>
		<input type="text" class="input_field w250" name="" id="">
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
    window.moveTo(0,0);window.resizeTo(screen.width,screen.height);
</script>  
@stop