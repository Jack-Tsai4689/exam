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
		}
		.btn {
			height: 25px;
			margin-bottom: 5px;
			border: 0.5px #EED6B4 solid;
		}
		.btn_page {
			height: 20px;
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
		.list tr th {
			height: 40px;
		}
		.list tr td, .list tr th{
			vertical-align: middle;
			text-align: center;
			border-right: 1px #B4B5B5 solid;
			height: 40px;
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
			border-right: 0px;
		}
		.list div.que {
			margin: 5px;
			background-color: gray;
			height: 30px;

		}
		.btn:active {
			border: 0.5px gray dashed;
		}
		.ps label {
			margin-left: 20px;
			color: #D35E69;
		}
		.part {
			background-color: #F2D9B6;
		}
		.part {
			padding-left: 1em;
			font-size: 18px;
		}
		.part > span {
			margin: 0px 5px;
			font-weight: bolder;
		}
		.part > .right {
			color: blue;
		}
		.part > .wrong {
			color: red;
		}
		.part > .none {
			color: gray;
		}
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<INPUT type="hidden" name="f_sid" id="f_sid" value="">
    <INPUT type="hidden" name="f_minutes" id="f_minutes" value="">
    <INPUT type="hidden" name="f_seconds" id="f_seconds" value="">
    <INPUT type="hidden" name="f_exnumr" id="f_exnumr" value="">
    <INPUT type="hidden" name="f_subject" id="f_subject" value="">
    <INPUT type="hidden" name="f_bmenuname" id="f_bmenuname" value="">
	<div class="title_intro">
		<input type="button" class="btn w100" id="see_result" value="成績結果">
		<input type="button" class="btn w150" id="see_concept" value="觀念答對比率圖">
		<input type="button" class="btn w150" name="" id="" value="列印" onclick="print();">
		{{-- <label class="f15" id="end"><a href="javascript:void(0)" onclick="if(confirm('您確定要關閉?')) window.close();">關閉</a></label> --}}
	</div>
	@foreach($Part as $pi => $p)
	<div class="title_intro">
		<div class="part">第{{ ($pi+1) }}大題 {{ (float)$p->score }}分 ({{ $p->percen }}%)　答對<span class="right">{{ $p->rnum }}</span>題　答錯<span class="wrong">{{ $p->wnum }}</span>題　未答<span class="none">{{ $p->nnum }}</span>題</div>
	</div>
	<div class="content">
		<div id="cen">
			<table width="100%" class="list">
				<thead>
					<tr class="shallow">
						<th style="min-width:70px;">題號</th>
						<th style="width:100%;">章節概念</th>
						<th style="min-width:70px;">對錯</th>
						<th style="min-width:70px;">答對率</th>
						<th style="min-width:70px;">難易度</th>
						<th style="min-width:80px;">作答</th>
						<th class="last" style="min-width:80px;">答案</th>
					</tr>
				</thead>
				@foreach($p->qdata as $qi => $q)
				<tr class="{{ ($qi%2===0) ? 'deep':'shallow' }}">
					<td>{{ $q->sort }}</td>
					<td align="left">{{ $q->chap }}</td>
					<td><img src="{{ ($q->right) ? URL::asset('img/icon_op_t.png'):URL::asset('img/icon_op_f.png') }}" height="20"></td>
					<td>{{ $q->percen }}%</td>
					<td>{{ $q->degree }}</td>
					<td>{{ $q->ed_ans }}</td>
					<td class="last">{{ $q->q_ans }}</td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
	@endforeach
</div>
@stop
@section('script')
<script>
	$("#see_result").on('click', function(){
		location.href = "{{ url('/score/'.$Eid) }}";
	});
	$("#see_concept").on('click', function(){
		location.href = "{{ url('/analy/'.$Eid.'/concept') }}";
	});
</script>
@stop