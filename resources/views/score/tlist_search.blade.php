@extends('layout.default')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('cssfunc/ex_set.css') }}">
	<style type="text/css">
    	#all {
            height: auto !important;
    	}
    	#cen {
    		/*margin: 0 auto;
    		padding: 20px 0px 50px 0px;
    		margin: 0px 20px 0px 20px;*/
            width: 100%;
    	}
    	.input_field {
    		margin:0px;
    	}
    	.btn:active {
    		border: 0.5px gray dashed;
    	}
    	.f14{
    		margin-left: 5px;
    		margin-right: 5px;
    	}
        /*#duty td {
    		padding-bottom: 0px;
    	}*/
    	.list tr td{
    		/*margin-bottom: 10px;*/
    		height: 50px;
    		line-height: 25px;
    		/*padding-left: 10px;*/
    	}
        .list tr th {
            height: 40px
        }
        .list tr td,
        .list tr th {
            vertical-align: middle;
            text-align: center;
            border-right: 1px #b4b5b5 solid
        }
        .list .deep {
            background-color: #efefee
        }
        .list .shallow {
            background-color: #fcfcfc
        }
        .list tr.shallow:hover,
        .list tr.deep:hover {
            background-color: #fce3ce
        }
        .list th.last {
            text-align: center;
            border-right: 0
        }
        .list td.last {
            /*text-align: left;*/
            border-right: 0
        }
    	.list label {
    		margin-right: 5px;
    	}
    	.list input {
    		margin-right: 5px;
    	}
    	.list {
    		/*margin-bottom: 20px;*/
    	}
    	select {
    		margin-right: 5px;
            font-family: "微軟正黑體";
    	}
    	textarea {
    		width: 500px;
    		height: 65px;
    		margin: 5px 0px 5px 0px;
    	}
        #intro_open {
            top: 0px;
            bottom: 0px;
            left: 0px;
            right: 0px;
            position: fixed;
            opacity: 0.8;
            z-index: 3;
            background:-moz-radial-gradient(center,ellipse cover,rgba(0,0,0,0.4) 0,rgba(0,0,0,0.9) 100%);
            background: -ms-radial-gradient(center,ellipse cover,rgba(0,0,0,0.4) 0,rgba(0,0,0,0.9) 100%);
            background: -webkit-radial-gradient(center,ellipse cover,rgba(0,0,0,0.4) 0,rgba(0,0,0,0.9) 100%);
            filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr='#66000000',endColorstr='#e6000000',GradientType=1)";
            -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
            display: none;
        }
        #intro_all {
            width: 100%;
            position: fixed;
            z-index: 5;
            top: 0px;
            margin: 7% auto;
            display: none;
        }
        #intro_content {
            width: 500px;
            margin: 0% auto;
            position: relative;
            float: none;
            height: 150px;
            border-radius: 10px;
            height: 80px;
        }
        #intro_title {
            font-size: 20px;
            text-align: center;
            line-height: 80px;
        }
        .see_rs {
            cursor: pointer;
        }
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
    <form id="findform" onsubmit="return gofind()">
    <div class="title_intro condition">
        <div>　　學號　<input type="text" name="stuno" id="stuno" value="{{ $Stuno }}">　姓名　<input type="text" name="stuname" id="stuname" value="{{ $Stuname }}">　　<input type="submit" name="find" id="find" value="搜尋"></div>
    </div>
    </form>
	<div class="content">
		<div id="cen">
            <table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="120">學號</th>
                        <th width="120">姓名</th>                            
                        <th width="100">得分</th>
                        <th width="160">交卷時間</th>
                        <th width="180" class="last">診斷報告</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($Data as $i => $v)    <tr align="center" class="{{ ($i%2==0) ? 'deep':'shallow' }}">
                        <td {!! $v->can_see !!}>{{ $v->e_stu }}</td>
                        <td>{{ $v->stu()->name }}</td>
                        <td>{{ (float)$v->e_score }}</td>
                        <td>{{ $v->e_end }}</td>
                        <td class="last">@if ($v->e_status==="Y")
                            <span style="float:left; margin-right: 1em;"><a href="{{ url('/analy/'.$v->e_id) }}" target="_blank">考題概念表</a></span><span style="float:left;"><a href="{{ url('/analy/'.$v->e_id.'/concept') }}" target="_blank">觀念答對比例圖</a></span><span style="float:left;"><a href="{{ url('/analy/'.$v->e_id.'/report') }}" target="_blank">完整報告</a></span>
                            @endif</td>
                    </tr>
                @endforeach</tbody>
			</table>
		</div>
	</div>
</div>
<div id="intro_open"></div>
<div id="intro_all">
    <div id="intro_content">
        <div id="intro_title"><img src="{{ URL::asset('img/tenor.gif') }}" width="60"></div>
    </div>
</div>
@stop
@section('script')
<script>
$(".see_rs").on('click', function(){
    location.href = "{{ url('/score').'/' }}"+this.id;
});
function gofind(){
    let stuno = gb('stuno').value;
    let stuname = gb('stuname').value;
    if (stuno==="" && stuname==="")return false;
    location.href = "{{ url('/score/search') }}?"+$("#findform").serialize();
    return false;
};
</script>
@stop