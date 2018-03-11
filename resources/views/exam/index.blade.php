@extends('layout.default')
@section('style')
	<style type="text/css">
    	#all {
    		width: 1152px;
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
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
		<div class="content">
			<div id="cen">
                <table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>考卷名稱</th>
                            <th width="120">年級</th>
                            <th width="120">科目</th>
                            <th width="150">期間</th>
                            <th width="120">限時</th>
                            <th width="120" class="last"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Data as $i => $v)
                        @php $class = ($i%2==0) ? 'deep':'shallow'; @endphp
                        <tr align="center" class="{{ $class }}">
                            <td>{{ $v->p_name }}</td>
                            <td>{{ $v->gra->name }}</td>
                            <td>{{ $v->subj->name }}</td>
                            <td>{{ $v->days }}</td>
                            <td>{{ $v->lim }}</td>
                            <td class="last">
                                <form onsubmit="return examinit(this)">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="sets" class="sets" value="{{ $v->s_id }}">
                                    <input type="submit" class="btn w100" name="goin" value="進入">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
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
    function examinit(obj){
        act_start();
        $.ajax({
            type: "POST",
            url: "{{ url('/exam/init') }}",
            data: $(obj).serialize(),
            dataType: "JSON",
            success: function(){
                window.open("{{ url('/exam/info') }}","result","width="+screen.width+",height="+screen.height+",resizable=yes,scrollbars=yes,location=no");
                act_end();
            },
            error: function(){
                act_end();
            }
        });
        return false;
    }
    function act_start(){
        $('#intro_open').show();
        $('#intro_all').show();
    }
    function act_end(){
        $('#intro_open').hide();
        $('#intro_all').hide();
    }
</script>
@stop