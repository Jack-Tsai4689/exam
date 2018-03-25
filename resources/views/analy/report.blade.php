<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('layout.sub')
    <style type="text/css">
        #all {
            margin: 20px auto;
        }
        #title {
            height: 30px;
            line-height: 30px;
        }
        #cen {
            width: auto;
            padding: 20px 5px 15px 5px;
        }
        .hover {
            background-color: #FCE3CE;
        }
        .select {
            background-color: #F8CD89;
        }
        .content {
            float: left;
            width: 99%;
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
            width: 99%;
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
        .list_concept tr td, .list_concept tr th {
            text-align: center;
        }
        .list tr td, .list tr th{
            vertical-align: middle;
            border-right: 1px #B4B5B5 solid;
            height: 35px;
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
            text-align: center;
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

        .list {
            margin-top: 5px;
            margin-bottom: 15px;
            margin-left: 5px;
        }
        .list td.last{
            text-align: center;
            border-right: 0px;
            width: 150px;
        }
        .list label {
            margin-left: 5px;
        }
        .currect, .right {
            color: #29ABE2;
        }
        .wrong {
            color: #B7282C;
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
        .graph {
            width: 100%;
            max-width: 700px;
        }

                .title_intro label {
            margin-right: 5px;
            font-size: 16px;
        }
        .result_times{
            text-align: center;
            font-size: 18px;
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
        .btn {
            height: 25px;
            border: 1px #EED6B4 solid;
        }
        .input_field {
            height: 25px;
        }
        .eror {
            color: #B3B3B3;
        }
        .right, .wrong, .none {
            margin: 0px 2px;
            font-weight: bolder;
        }
        .none {
            color: gray;
        }
        .hiden {
            display: none;
        }
        .pic {
            width: 50%;
        }
    </style>
</head>
<body>
    <div style="text-align: center">
        <div>診斷報告</div>
        <div>考卷：{{ $Setsname }}</div>
        <div>學號：{{ $Stu }}</div>
    </div>
    <!-- 分頁 -->
	<p class="MsoNormal" align="center" style="page-break-before:always">&nbsp;</p>
	<!-- 考題來源表 -->
    @if($Have_sub)
        @foreach($Part as $pi => $p)
        <div class="title_intro">
            <div class="part">第{{ ($pi+1) }}大題 {{ (float)$p->score }}分 ({{ $p->percen }}%)　答對<span class="right">{{ $p->rnum }}</span>題　答錯<span class="wrong">{{ $p->wnum }}</span>題　未答<span class="none">{{ $p->nnum }}</span>題</div>
        </div>
        <div class="content">
            <div id="cen">
                <table width="100%" class="list list_concept">
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
    @else
        <div class="title_intro">
            <div class="part">得分 {{ (float)$Part->score }} / {{ $Part->percen }}　答對<span class="right">{{ $Part->rnum }}</span>題　答錯<span class="wrong">{{ $Part->wnum }}</span>題　未答<span class="none">{{ $Part->nnum }}</span>題</div>
        </div>
        <div class="content">
            <div id="cen">
                <table width="100%" class="list list_concept">
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
                    @foreach($Part->qdata as $qi => $q)
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
    @endif
    <!-- 分頁 -->
    <p class="MsoNormal" align="center" style="page-break-before:always">&nbsp;</p>
    <!-- 觀念答對比率圖 -->
    <div class="content">
        <div id="cen">
        @if (is_file('concept/'.$Graph_id.'.jpg'))
            <img class="graph" id="concept" src="{{ URL::asset('/concept/'.$Graph_id.'.jpg').'?'.rand() }}">
        @else
            @if(count($CData)>2)
                <img class="graph" id="concept" src="{{ URL::asset('jpgraph/pattern/radarmarkex1.php?f_sid='.$Graph_id.'&str_scrtype='.urlencode($Con_type).'&str_rightcnt='.urlencode($Con_right).'&str_allcnt='.urlencode($Con_all).'&title='.urlencode('觀念答對比率圖')) }}">
            @elseif(count($CData)===2)
                <img class="graph" id="concept" src="{{ URL::asset('jpgraph/pattern/pieex5_2.php?f_sid='.$Graph_id.'&str_rightcnt='.urlencode($Con_right).'&str_allcnt='.urlencode($Con_all).'&title='.urlencode('觀念答對比率圖'))}}">
            @else
                <img class="graph" id="concept" src="{{ URL::asset('jpgraph/pattern/pieex5.php?f_sid='.$Graph_id.'&str_rightcnt='.urlencode($Con_right).'&str_allcnt='.urlencode($Con_all).'&title='.urlencode('觀念答對比率圖')) }}">
            @endif
        @endif
        <table cellpadding="0" cellspacing="0" width="100%" class="list">
        @foreach($CData as $k => $v)
            <tr class="{{ ($k%2===0) ? 'deep':'shallow' }}">
                <td width="200" rowspan="2">
                    <label>{{ $v->name }}</label>
                </td>
                <td >
                    <label class="currect">對</label><label class="currect">{{ $v->right }}</label>
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
    <p class="MsoNormal" align="center" style="page-break-before:always">&nbsp;</p><!-- 分頁 -->
	<!-- 詳解 -->
	<div align="center">
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
                    <tr align="center" class="ans">
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
	</div>
</body>
</html>
<script type="text/javascript">
	//print();
    var sum = 950//27.7;
    var small = 0;
    var big = false;
    var pages = '<p class="MsoNormal" align="center" style="page-break-before:always">&nbsp;</p>';
    // $(function() {
    //     $('div[name=q]').each(function(i){
    //         var one = $(this).innerHeight();
    //         if (big){
    //             $(pages).insertBefore($(this));                
    //         }
    //         if (one<sum){
    //             small+= one;
    //             console.log(small);
    //             if (small>sum){
    //                 console.log(small+','+i);
    //             //    var tmp = small-(one+5)/38;//太近就不切分頁，讓他跳頁
    //             //    if ((sum-tmp)<2){}else{
    //                     $(pages).insertBefore($(this));    
    //             //    }
    //                 small = 0;
    //                 small+= one;
    //             }
    //         }else{
    //             if (i>0){
    //                $(pages).insertBefore($(this));                
    //             }
    //             big = true;
    //             small = 0;
    //         }
    //     });
    // });
</script>