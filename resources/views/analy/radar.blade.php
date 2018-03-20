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
    	#cen {
    		width: 100%;
    		text-align: center;
    	}
    	.hover {
    		background-color: #FCE3CE;
    	}
    	.select {
    		background-color: #F8CD89;
    	}
    	.content {
    		float: left;
    		width: 100%;
    		margin-bottom: 50px;
    	}
    	.btn {
    		height: 25px;
    		margin-bottom: 5px;
    		border: 0.5px #EED6B4 solid;
    	}
    	.title_intro {
    		position: relative;
    		height: 40px;
    		line-height: 40px;
    	}
    	.title_intro input {
    		margin-left: 5px;
    	}
    	.title_intro.btn{
    		height: 25px;
    	}
    	#end {
    		float: right;
    		margin-right: 10px;
    	}
    	.list {
    		margin-top: 5px;
    	}
    	.list tr td {
    		vertical-align: middle;
    		text-align: left;
    		border-right: 1px #B4B5B5 solid;
    		height: 25px;
    	}
    	.list .deep {
    		background-color: #EFEFEE;
    		font-weight: bold;
    	}
    	.list .shallow {
    		background-color: #FCFCFC;
    		font-weight: bold;
    	}
    	.list th.last{
    		text-align: center;
    		border-right: 0px;
    	}
    	.list td.last{
    		text-align: center;
    		border-right: 0px;
    		width: 150px;
    	}
    	.list label {
    		margin-left: 5px;
    	}
    	.currect {
    		color: #29ABE2;
    	}
    	.wrong {
    		color: #B7282C;
    	}
    	.btn:active {
    		border: 0.5px gray dashed;
    	}
    	.ps label {
    		margin-left: 20px;
    		color: #D35E69;
    	}
    	.exam_again {
    		border: #B4B4B5 solid thin;
    		background-color: white;
    		position: absolute;
    		width: 70px;
    		margin-left: 150px;
    		margin-top: -30px;
    		display: none;
    	}
    	.again li {
    		margin: 2px 0px 2px 0px;
    	}
    	.again li:hover {
    		background-color: #EFEFEE;
    	}
        .graph {
            width: 100%;
            max-width: 700px;
        }
	</style>
@stop
@section("content")
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<div class="title_intro">
		<input type="button" class="btn w100" id="see_result" value="成績結果">
		<input type="button" class="btn w100" id="see_analy" value="考題概念表" >
		{{-- <input type="button" class="btn w150" value="列印" onclick="print();"> --}}
		{{-- <label class="f15" id="end"><a href="javascript:void(0)" onclick="if(confirm('您確定要關閉?'))window.close();">關閉</a></label> --}}
	</div>
	<div class="content">
		<div id="cen">
        @if (is_file('concept/'.$Graph_id.'.jpg'))
            <img class="graph" id="concept" src="{{ URL::asset('/concept/'.$Graph_id.'.jpg').'?'.rand() }}">
        @else
    		@if(count($Data)>2)
                <img class="graph" id="concept" src="{{ URL::asset('jpgraph/pattern/radarmarkex1.php?f_sid='.$Graph_id.'&str_scrtype='.urlencode($Con_type).'&str_rightcnt='.urlencode($Con_right).'&str_allcnt='.urlencode($Con_all).'&title='.urlencode('觀念答對比率圖')) }}">
            @elseif(count($Data)===2)
                <img class="graph" id="concept" src="{{ URL::asset('jpgraph/pattern/pieex5_2.php?f_sid='.$Graph_id.'&str_rightcnt='.urlencode($Con_right).'&str_allcnt='.urlencode($Con_all).'&title='.urlencode('觀念答對比率圖'))}}">
            @else
                <img class="graph" id="concept" src="{{ URL::asset('jpgraph/pattern/pieex5.php?f_sid='.$Graph_id.'&str_rightcnt='.urlencode($Con_right).'&str_allcnt='.urlencode($Con_all).'&title='.urlencode('觀念答對比率圖')) }}">
            @endif
        @endif
		<table cellpadding="0" cellspacing="0" width="100%" class="list">
        @foreach($Data as $k => $v)
			<tr class="{{ ($k%2===0) ? 'deep':'shallow' }}">
				<td width="400" rowspan="2">
					<label>{{ $v->name }}</label>
				</td>
				<td >
					<label class="currect">對</label><label class="currect">{{ $v->right }}</label>
				</td>
				<td rowspan="2" class="last">
					<input type="button" class="btn w150 f14" name="" id="" value="再考一次" onclick="aga({{ $k }})">
					<div class="exam_again" id="ag_{{ $k }}">
                        <ul class="again">
                            <a href="javascript:void(0);" onclick="go_ex('10','','','')"><li>10 題</li></a>
                            <a href="javascript:void(0);" onclick="go_ex('20','','','')"><li>20 題</li></a>
                            <a href="javascript:void(0);" onclick="go_ex('60','','','')"><li>60 題</li></a>
                        </ul>
                    </div>
				</td>
			</tr>
			<tr class="{{ ($k%2===0) ? 'deep':'shallow' }}">
				<td>
					<label class="wrong">錯</label><label class="wrong">{{ $v->wrong }}</label>
				</td>
			</tr>
        @endforeach
		</table>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
var pnum = '';
function aga(num){
    if (pnum!=''){
        $('#ag_'+pnum).css('display','none');
    }
    if (pnum==num){
        $('#ag_'+num).css('display','none');
        pnum ='';    
    }else{
        $('#ag_'+num).css('display','block');
        pnum = num;
    }
}
function go_ex(n,s,g,v){
    var getdata = {'n':n,'g':g,'s':s,'c':v};
    $.ajax({
        type:'get',
        url:'validate_exam.php',
        dataType: 'json',
        data: getdata,
        success: function (data, textStatus, jqXHR){
            if (data.no){
                if (data.msg!='')alert(data.msg);
                window.open('startexam.php?n='+n+'&s='+s+'&g='+g+'&c='+v+'&key=7','result',"width=800,height=600,resizable=yes,scrollbars=yes,location=no");
            }else{
                alert(data.msg);
            }
        }
    });
}
$("#see_result").on('click', function(){
    location.href = "{{ url('/score/'.$Eid) }}";
});
$("#see_analy").on('click', function(){
    location.href = "{{ url('/analy/'.$Eid) }}";
});
</script>
@stop