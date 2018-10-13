@extends('layout.default')
@section('style')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('cssfunc/ex_set.css') }}">
	<style type="text/css">
		.show{
			display: block;
		}
		.hiden {
			display: none;
		}
		.list > tbody > tr > td.cont {
			text-align: left;
		}
		.cont > div {
			margin-bottom: 5px;
		}
		.pic {
			width: 500px;
		}
	</style>
@stop
@section('content')
<div id="all">
	<div id="title"><label class="f17">{{ $title }}</label></div>
	<form name="form1" id="form1">
	<div class="title_intro">
		<div class="top_search"><label style="margin-left:5px;">關鍵字搜尋</label><input type="text" class="input_field" name="q" id="q" value="{{ $Qkeyword }}"><div class="glass_div" onclick="search_confirm()"><img src="{{ URL::asset('img/icon_op_glass.png') }}"></div><a href="{{ url('/ques') }}" style="margin-left:55px;">瀏覽全部</a></div>
		<div><input type="button" class="btn f16 w150" name="" id="" value="新增題目" onclick='window.open("{{ url('/ques/create') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' >&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn f16 w150" name="" id="" value="Excel匯入(敬請期待)"></div>
		{{-- <label class="f16" id="choice_fie"><a href="javascript:void(0)" onclick="open_field();">選擇欄位</a></label> --}}
	</div>
	<div id="app"></div>
	<div class="title_intro condition">
		<div id="condit">
			<div style="width:80px; display:inline-block; position: relative; margin-left:5px;">範圍條件</div>
			{{-- 類別：
			<select name="gra" @change="g_subject" v-model="grade">
				<option v-for="grade in grades" :value="grade.ID">@{{grade.NAME}}</option>
			</select>
			科目：
			<select name="subj" @change="g_chap" v-model="subject">
				<option v-for="subj in subjects" :value="subj.ID">@{{subj.NAME}}</option>
			</select>　
			章節：
			<select name="chap" v-model="chapter">
				<option v-for="chap in chapters" :value="chap.ID">@{{chap.NAME}}</option>
			</select>　 --}}
			難度：
			<select name="degree">
				<option value=""  {{ $Degree->A}} >全部</option>
				<option value="E" {{ $Degree->E}} >容易</option>
				<option value="M" {{ $Degree->M}} >中等</option>
				<option value="H" {{ $Degree->H}} >困難</option>
				</select>
			　<input type="button" value="篩選" @click="query">
			<input type="hidden" name="page" value="@{{pg}}">
		</div>
	</div>
	</form>
	<div class="content">
		<div id="cen">
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
				<thead>
					<tr>
						<th name="qno" style="width:4%; min-width:39px;">序號</th>
						<th name="que">題目/詳解</th>
						<th style="width:80px;">題型</th>
						<th name="ans" style="width:5%; min-width:49px;">答案</th>
						<th style="width:100px;">知識點</th>
						<th name="pub" style="width:10%; min-width:109px;">發表時間</th>
						<th class="last" style="width:82px;">編輯</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($Data as $k => $v)
					<tr class="{{ ($k%2==0) ? 'deep':'shallow' }}">
						<td name="qno">{{ $v->q_id }}</td>
						<td class="cont" name="que"><div>範圍 【{{ $v->q_gra }}】【{{ $v->q_subj }}】【{{ $v->q_chap }}】【{{ $v->q_degree }}】</div>{!! $v->cont !!}</td>
						<td>{{ $v->q_quetype }}</td>
						<td name="ans">{{ $v->q_ans }}</td>
						<td>{{ $v->q_know }}</td>
						<td>{{ $v->q_update }}</td>
						<td class="last"><input type="button" class="btn w80" onclick='window.open("{{ url('ques/'.$v->q_id.'/') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' value="明細"><br><input type="button" class="btn w80" onclick='window.open("{{ url('ques/'.$v->q_id.'/edit') }}","_blank","width=800,height=600,resizable=yes,scrollbars=yes,location=no");' value="編輯"></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div id="page" class="content">
		<label class="all_rows">共 {{ $Num }} 筆資料</label>
		<div class="each">
			{!! $Page->prev !!}
			<select id="pagegroup" @change="query" v-model="pg">{!! $Page->pg !!}</select>
			{!! $Page->next !!}
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
// function open_edit(value){
// 	var func = $('#edit_func_'+value);
// 	if (func.hasClass('show')){
// 		func.removeClass('show');
// 	}else{
// 		$('div[name=edit_group]').removeClass('show');
// 		func.addClass('show');
// 	}
// }
// var i='';
// function check_all(obj,cName){
//     var checkboxs = document.getElementsByName(cName);
//     for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;}
// }
// function search_confirm(){
//   var search = $('#q').val();
//   var pattern = new RegExp("[`~!@#$^&()=|{}':;'-+,\\[\\].<>/?~！@#￥……&*（）——|{}【】『；：」「'。，、？]");
//   var rs = "";
//   for (var i = 0; i < search.length; i++) { 
//       rs += search.substr(i, 1).replace(pattern, ''); 
//   } 
//   if (search.trim()!='')ques_find();
// }

let g = 0;
Vue.component('Select', {
  props: ['value', 'options'],
  computed:{
    index: {
      get(){
        return this.value;
      },
      set(val){
        this.$emit('input', val);
      },
    },
  },
  template: `
    <select v-model="index">
      <option v-for="item in options" :value="item.ID">
        @{{item.NAME}}
      </option>
    </select>
  `,
});

new Vue({
	el: "#app",
	data: {
		all: [{ID:0, NAME:'nodata'}],
		grades: [],
		gradeidx: 0,
		subjidx: 0,
		chapidx: 0,
	},
	asyncCcomputed:{
		subjects(){
			return new Promise(resolve => {
				axios.get("{{ url('basic/detail')}}", {
					params: {type:"subj", g:this.gradeidx}
				}).then(res => {
					return res.data;
				}).catch(res => {
					return this.all;
				});
			});
		},
		chap(){
			if (this.subjidx===0)return this.all;
			axios.get("{{ url('basic/detail')}}", {
				params: {type:"chap", g:this.gradeidx, s:this.subjidx}
			}).then((res) => {
				return res.data;
			}).catch(res => {
				return this.all;
			});
		},
	},
	watch: {
		gradeidx(){
			this.subjidx = 0;
		},
		subjidx(){
			this.chapidx = 0;
		},
	},
	template: `
	<div>
		<Select v-model="gradeidx" :options="grades"></Select>
		<Select v-model="subjidx" :options="subjects"></Select>
		<Select v-model="chapidx" :options="chap"></Select>
	</div>
	`,
	mounted(){
		axios.get("{{ url('basic/detail')}}", {
				params: {type:"grade"}
			}).then(grade => {
				this.grades = grade.data;
			}).catch(res => {
				this.grades = this.all;
			});
	}
});
</script>
@stop