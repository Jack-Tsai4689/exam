@extends('layout.default')
@section('style') 
	<style type="text/css">
    	#all {
    		margin: 20px auto;
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
            font-size: 18px;
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
/*        .que img {
            width: 1000px;
        }*/
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
        .part {
            background-color: #F2D9B6;
        }
        .part {
            padding-left: 1em;
            font-size: 18px;
        }
        .right, .wrong, .none {
            margin: 0px 2px;
            font-weight: bolder;
        }
        .right {
            color: green;
        }
        .wrong {
            color: red;
        }
        .none {
            color: gray;
        }
        .hiden {
            display: none;
        }
        .pic {
            width: 80%;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<div class="title_intro result_times">
		{{ $Setsname }}　測驗費時 {{ $Time->hour }}小時 {{ $Time->min }}分 {{ $Time->sec}}秒　
        答對<span class="right">{{ $exam->e_rnum }}</span>題　
        答錯<span class="wrong">{{ $exam->e_wnum }}</span>題　
        未答<span class="none">{{ $exam->e_nnum }}</span>題　
		得分<span class="wrong">{{ (float)$exam->e_score }}</span> / {{ $Sum }}
	</div>
	<div class="title_intro">
        <input type="button" class="btn w150 f14 oans" value="顯示解答/詳解">
		<input type="button" class="btn w100 f14 analy" value="考題概念表">
		<input type="button" class="btn w150 f14 concept" value="觀念答對比率圖">
		<input type="button" class="btn w150 f14 report" value="診斷報告">
		<label>診斷報告封面標題</label>
		<input type="text" class="input_field w250" name="f_reporttitle" id="f_reporttitle">　{{ date('Y/m/d H:i:s', $exam->e_endtime_at) }} 交卷
		<INPUT type="hidden" name="f_sid" id="f_sid"  value="">
        <INPUT type="hidden" name="p_sids" id="p_sids"  value="">
        <INPUT type="hidden" name="f_subject" id="f_subject" value="">
        <INPUT type="hidden" name="fkey" id="fkey" value="">
	</div>
    @if ($Have_sub)
    @foreach($Data as $p)
        <div class="title_intro">
            <div class="part">第 {{ $p->e_sort }} 大題 {{ (float)$p->e_score }}分({{ $p->sets_info()->p_percen }}%)　答對<span class="right">{{ $p->e_rnum }}</span>題　答錯<span class="wrong">{{ $p->e_wnum }}</span>題　未答<span class="none">{{ $p->e_nnum }}</span>題</div>
        </div>
    	<div class="content">
    		<div id="cen">
    			<table class="list" cellpadding="0" cellspacing="0" width="100%">
                    @foreach($p->sub_ques_ans() as $q)
    				<tr align="center">
    					<td class="qno">{{ $q->qno }}</td>
    					<td class="qno_c"><img src="{{ URL::asset($q->right_pic) }}"></td>
    					<td class="qno_ans">{{ $q->myans }}</td>
    					<td class="que" align="left">{!! $q->qcont !!}</td>
                    </tr>
                    <tr align="center" class="ans hiden">
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
    @else
        <div class="content">
            <div id="cen">
                <table class="list" cellpadding="0" cellspacing="0" width="100%">
                    @foreach($Data as $q)
                    <tr align="center">
                        <td class="qno">{{ $q->qno }}</td>
                        <td class="qno_c"><img src="{{ URL::asset($q->right_pic) }}"></td>
                        <td class="qno_ans">{{ $q->myans }}</td>
                        <td class="que" align="left">{!! $q->qcont !!}</td>
                    </tr>
                    <tr align="center" class="ans hiden">
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
    @endif
	<div class="title_intro">
        <input type="button" class="btn w150 f14 oans" value="顯示解答/詳解">
		<input type="button" class="btn w100 f14 analy" value="考題概念表">
		<input type="button" class="btn w150 f14 concept" value="觀念答對比率圖">
		<input type="button" class="btn w150 f14 report" value="診斷報告">
		<label>診斷報告封面標題</label>
		<input type="text" class="input_field w250" name="" id="">
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
    let hiden_ans = true;
    window.moveTo(0,0);window.resizeTo(screen.width,screen.height);
    const analy = document.getElementsByClassName("analy");
    Array.prototype.forEach.call(analy, function(i){
        i.onclick = function(){
            location.href = "{{ url('/analy/'.$Eid) }}";    
        };
    });
    const concept = document.getElementsByClassName("concept");
    Array.prototype.forEach.call(concept, function(i){
        i.onclick = function(){
            location.href = "{{ url('/analy/'.$Eid.'/concept') }}";
        };
    });
    const report = document.getElementsByClassName("report");
    Array.prototype.forEach.call(report, function(i){
        i.onclick = function(){
            location.href = "{{ url('/analy/'.$Eid.'/report') }}";
        };
    });
    $(".oans").on('click', function(){
        if (hiden_ans){
            $(".oans").val('隱藏解答/詳解');
            $(".ans").removeClass("hiden");
            hiden_ans = false;
        }else{
            $(".oans").val('顯示解答/詳解');
            $(".ans").addClass("hiden");
            hiden_ans = true;
        }
    });
</script>  
@stop