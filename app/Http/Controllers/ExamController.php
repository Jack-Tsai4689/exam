<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sets;
use App\Setsque;
use App\Exams;
use App\ExamDetail;
use DB;
use URL;

class ExamController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->login_status)return redirect('/login');
        $sets = Sets::where('s_finish',1)->get()->all();
        foreach ($sets as $k => $v) {
            $sets[$k]->days = (!empty($v->s_begtime)) ? $v->s_begtime.' - '.$v->s_endtime:'不限';
            $lime = explode(":", $v->s_limtime);
            $sets[$k]->lim = (int)$lime[0].'時'.(int)$lime[1].'分'.(int)$lime[2].'秒';

        }
        
        return view('exam.index', [
            'menu_user' => $this->menu_user,
            'title' => '測驗',
            'Data' => $sets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //存檔 & 讀取下一頁
    public function store(Request $req)
    {
        // dd($req->all());
        $sid = ($req->has('setid') && (int)$req->input('setid')>0) ? (int)$req->input('setid'):0;
        $part = ($req->has('epart') && (int)$req->input('epart')>0) ? (int)$req->input('epart'):0;
        $qno = ($req->has('current_qno') && (int)$req->input('current_qno')>0) ? (int)$req->input('current_qno'):0;
        $qtype = ($req->has('qtype') && !empty($req->input('qtype'))) ? trim($req->input('qtype')):'';
        $qnum = ($req->has('qnum') && (int)$req->input('qnum')>0) ? (int)$req->input('qnum'):0;
        $act = ($req->has('next_qa') && !empty($req->input('next_qa'))) ? trim($req->input('next_qa')):'';

        //下個大題
        if ($act==="part"){
            $eid = $req->input('exam');

            return;
        }
        if ($sid<=0 || $part<=0 || $qno<=0)abort(400);
        $ans = '';
        switch ($qtype) {
            case 'M':
                $n = 1;
                $ans_arr = array();
                while($n<=$qnum){
                    $ans_arr[] = $req->input('ans'.$qno.'_'.$n);
                    $n++;
                }
                $ans = implode(",", $ans_arr);
                break;
            case 'S':
            case 'R':
                $ans = $req->input('ans'.$qno);
                break;
            case 'D':
                $ans_arr = $req->input('ans'.$qno);
                $ans = implode(",", $ans_arr);
                break;
        }
        ExamDetail::where('ed_eid', $part)
                  ->where('ed_sort', $qno)
                  ->update(['ed_ans'=> $ans]);
        $finish = false;
        //讀取題目
        switch ($act) {
            case 'n': //下一題
                $qno++;
                break;
            case 'p': //上一題
                $qno--;
                break;
            case 'q': //指定題號
                $qno = $req->input('next_qno');
                break;
            case 'f': //交卷
                $finish = true;
                Exams::where('e_id', $part)->update([
                    'e_status'=> "Y",
                    'e_endtime_at' => time()
                ]);
                echo 1;
                return;
                break;
            default:
                abort(400);
                break;
        }
        $que = ExamDetail::select('ed_qid')
                         ->where('ed_eid', $part)
                         ->where('ed_sort', $qno)->first();
        $quedata = $this->_Ques_Info($que->que()->first(), $qno);
        echo json_encode($quedata);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function init_check(Request $req){
        $sets = ($req->has('sets') && (int)$req->input('sets')>0) ? (int)$req->input('sets'):0;
        if ($sets===0)abort(400);
        session()->put('token', uniqid());
        session()->put('type', 'sets');
        session()->put('sets', $req->input('sets'));
        echo '1';
    }
    public function goexam(){
        //試卷方式
        $can_exam = true;
        if (session('type')==="sets"){
            $exam_type = 'sets';
            $s_id = session('sets');
            session()->forget('token');
            session()->forget('type');
            session()->forget('sets');
            $sets = Sets::find($s_id);
            if ($sets->s_sub){
                $sub = $sets->sub()->get()->all();
                foreach ($sub as $k => $v) {
                    $sub[$k]->back = ($v->s_page==='N') ? '不':'';
                }
            }else{
                $sub = array();
            }
            $time = ($sets->s_again) ? '可重複考':'僅限一次';
            $exam_name = $sets->s_name;
            $lime = explode(":", $sets->s_limtime);
            $limetime = '';
            if ($lime[0]>0) $limetime.= (int)$lime[0].'小時';
            if ($lime[1]>0) $limetime.= (int)$lime[1].'分';
            if ($lime[2]>0) $limetime.= (int)$lime[2].'秒';
            return view('exam.info', [
                'title' => $exam_name,
                'type' => 'sets',
                'exnum' => '',
                'gra' => '',
                'subj' => '',
                'chap' => '',
                'degree' => '',
                'sets' => $s_id,
                'lime' => $sets->s_limtime,
                'score_open' => '',
                'Sum' => $sets->s_sum,
                'Limetime' => $limetime,
                'Sub_info' => $sub,
                'Pass_core' => $sets->s_pass_score,
                'Times' => $time
            ]);
        }
    }
    public function examing(Request $req){
        $type = ($req->has('type') && !empty($req->input('type'))) ? trim($req->input('type')):'';
        $exnum = ($req->has('exnum') && !empty($req->input('exnum'))) ? trim($req->input('exnum')):'';
        $gra = ($req->has('gra') && (int)$req->input('gra')>0) ? (int)$req->input('gra'):0;
        $subj = ($req->has('subj') && (int)$req->input('subj')>0) ? (int)$req->input('subj'):0;
        $chap = ($req->has('chap') && (int)$req->input('chap')>0) ? (int)$req->input('chap'):0;
        $degree = ($req->has('degree') && !empty($req->input('degree'))) ? trim($req->input('degree')):'';
        $sid = ($req->has('sets') && (int)$req->input('sets')>0) ? (int)$req->input('sets'):0;
        $lime = ($req->has('lime') && !empty($req->input('lime'))) ? trim($req->input('lime')):'';

        if ($type==="sets"){
            if ($sid<=0)abort(400);
            //$this->_exam_sets($sets);
            $sets_data = Sets::find($sid);
            $_exam = $this->_Exam_Status_Check($sid);
            if (!$sets_data->s_again){
                //不能重覆考
                if ($_exam->status==="ed")die('已考過');
            }
            $time = time();
            $lime = explode(":", $sets_data->s_limtime);
            $already_ans = 0;
            switch ($_exam->status) {
                case '': //第一次考
                    //主記錄先存
                    $edata = new Exams;
                    $edata->fill([
                        'e_stu' => session('epno'),
                        's_id' => $sid,
                        'e_pid' => 0,
                        'e_sub' => $sets_data->s_sub,
                        'e_begtime_at' => $time
                    ]);
                    $edata->save();
                    $eid = $edata->e_id;
                    $qno = array();
                    $part_show = new \stdclass;
                    $first_que = new \stdclass;
                    if ($sets_data->s_sub){
                        //找大題 -> 找題目，放進exams 依大題順序
                        $part_que = Sets::where('s_pid', $sid)->orderby('s_part')->get()->all();
                        foreach ($part_que as $pk => $pv) {
                            //新增大題
                            $e_part = new Exams;
                            $e_part->fill([
                                'e_stu' => session('epno'),
                                's_id' => $pv->s_id,
                                'e_pid' => $eid,
                                'e_sub' => 0,
                                'e_sort' => ($pk+1),
                                'e_begtime_at' => $time
                                ]);
                            $e_part->save();
                            //把題目寫一份到學生卷 exam_details
                            DB::insert("INSERT INTO exam_details(s_id, ed_eid, ed_sort, ed_qid) 
                                SELECT ?, ?, sq_sort, sq_qid 
                                FROM setsque 
                                WHERE sq_sid=? AND sq_part =? 
                                ORDER BY sq_sort", [$pv->s_id, $e_part->e_id, $sid, $pv->s_id]);
                            if ($pk===0)$part_show->eid = $e_part->e_id;
                        }
                        /*
                        loading第一大題設定
                        可否回上頁
                        題數
                        配分
                        */
                        $part_show->control = $part_que[0]->s_page;
                        $part_show->intro = nl2br(trim($part_que[0]->s_intro));
                        $part_show->no = 1;
                        $part_show->sub = true;
                        $part_show->score = $part_que[0]->s_percen;
                        
                        $first_quedata = Setsque::select('sq_qid')->where('sq_sid', $sid)
                                                ->where('sq_part', $part_que[0]->s_id)
                                                ->orderby('sq_sort')->get()->all();
                    }else{
                        $part_show->control = $sets_data->s_page;
                        $part_show->sub = false;
                        
                        $first_quedata = Setsque::select('sq_qid')->where('sq_sid', $sid)
                                                ->where('sq_part', $sid)
                                                ->orderby('sq_sort')->get()->all();

                    }
                    $part_show->nums = count($first_quedata);
                    foreach ($first_quedata as $v) {
                        $qno[] = $v->sq_qid;
                    }
                    $qno_act = ($part_show->control==="Y") ? 'onclick=go(1)':'';
                    $qno_html = '<div class="current" id="go1" '.$qno_act.'>'.str_pad(1,2,0,STR_PAD_LEFT).'</div>';
                    $i=2;
                    while ($i<=$part_show->nums) {//題號的部份，以顏色區分題號的狀況
                        if ($part_show->control==="Y"){
                            $qno_html.= '<div id="go'.$i.'" onclick=go('.$i.')>'.str_pad($i,2,0,STR_PAD_LEFT).'</div>';
                        }else{
                            $qno_html.= '<div id="go'.$i.'" >'.str_pad($i,2,0,STR_PAD_LEFT).'</div>';
                        }
                        $i++;
                    }
                    //loading第一題資料
                    $que = $this->_Ques_Info($first_quedata[0]->que()->first(), 1);
                    break;
                case 'yet': //中離，從未完成的大題進來
                    $eid = $_exam->eid;
                    $qno = array();
                    $part_show = new \stdclass;
                    $first_que = new \stdclass;
                    $qno_html = '';
                    $start_q = 0;
                    $start_q_chk = false;
                    $curr_q_chk = false;
                    if ($sets_data->s_sub){
                        $part_show->eid = $_exam->esid;
                        $part_que = Sets::find($_exam->ssid);
                        /*
                        loading第一大題設定
                        可否回上頁
                        題數
                        配分
                        */
                        $part_show->control = $part_que->s_page;
                        $part_show->intro = nl2br(trim($part_que->s_intro));
                        $part_show->no = 1;
                        $part_show->sub = true;
                        $first_quedata = ExamDetail::where('ed_eid', $_exam->esid)
                                                   ->orderby('ed_sort')->get()->all();
                        $part_show->nums = count($first_quedata);
                        $part_show->score = $part_que->s_percen;
                    }else{
                        $part_show->control = $sets_data->s_page;
                        $part_show->sub = false;
                        $first_quedata = ExamDetail::where('ed_eid', $_exam->eid)
                                                     ->where('s_id', $sid)
                                                     ->orderby('ed_sort')->get()->all();
                        $part_show->nums = count($first_quedata);
                    }
                    foreach ($first_quedata as $i => $v) {
                        $qno[] = $v->ed_qid;
                        if (empty($v->ed_ans) && !$start_q_chk){
                            $start_q = $i;
                            $part_show->no = ($i+1);
                            $start_q_chk = true;
                        }
                        if (!empty($v->ed_ans)){
                            $already_ans++;
                            $qno_act = ($part_show->control==="Y") ? 'onclick=go('.($i+1).')':'';
                            $qno_html.= '<div class="finish" id="go'.($i+1).'" '.$qno_act.'>'.str_pad(($i+1),2,0,STR_PAD_LEFT).'</div>';    
                        }else{
                            $curr_class = "";
                            if (!$curr_q_chk){
                                $curr_class = 'class="current"';
                                $curr_q_chk = true;
                            }
                            $qno_act = ($part_show->control==="Y") ? 'onclick=go('.($i+1).')':'';
                            $qno_html.= '<div '.$curr_class.' id="go'.($i+1).'" '.$qno_act.'>'.str_pad(($i+1),2,0,STR_PAD_LEFT).'</div>';    
                        }
                    }
                    //loading第一題資料
                    $que = $this->_Ques_Info($first_quedata[$start_q]->que()->first(), ($start_q+1));
                    break;
                case 'ing': //正在考
                    die('您已在測驗中');
                    break;
            }
            $data = [
                'sets_name' => $sets_data->s_name,
                'type' => 'sets',
                'sets' => $sid,
                'exam' => $eid,
                'y' => $already_ans,
                'hour' => $lime[0],
                'min' => $lime[1],
                'sec' => $lime[2],
                'end_date' => '',
                'first_part' => $part_show,
                'qno_html' => $qno_html,
                'curr' => ($start_q+1),
                'que' => $que,
                'qno' => $qno
            ];
            if ($part_show->control==="Y"){
                return view('exam.ying', $data);
            }else{
                return view('exam.ying', $data);
            }
            
        }
    }
    /*
    考試確認
    如果沒考完，還可以接續考，用e_status判斷 (N, O)
    照順序判斷，從主體->大題
    @int 考卷id sid
    return 
        status ed(考過) ing(進行中) yet(中離)
        eid 學生卷id
    */
    private function _Exam_Status_Check($sid){
        $record = Exams::where('s_id', $sid)
                           ->where('e_stu', session('epno'))
                           ->where('e_sort',0)->first();
        $json = new \stdclass;
        $json->status = '';
        $json->eid = 0; //學生卷主id
        $json->esid = 0; //學生卷大題id
        $json->sid = 0; //考卷主id
        $json->ssid = 0; //考卷大題id
        if (!empty($record)){
            switch ($record->e_status) {
                case 'N'://主體未完成
                    if (!$record->e_sub){
                        $json->status = 'ing';
                        $json->eid = $record->e_id;
                        $json->esid = $record->e_id;
                        $json->sid = $sid;
                        $json->ssid = $sid;
                        return $json;
                    }
                    $sub_record = Exams::where('e_pid', $record->e_id)->orderby('e_sort')->get()->all();
                    //如果有大題
                    foreach ($sub_record as $sv) {
                        //if ($sv->e_status==="O")$json->status = 'yet';
                        //if ($sv->e_status==="N")$json->status = 'ing';
                        if ($sv->e_status==="O" || $sv->e_status==="N"){
                            $json->status = 'yet';
                            $json->eid = $record->e_id;
                            $json->esid = $sv->e_id;
                            $json->sid = $sid;
                            $json->ssid = $sv->s_id;
                            break;
                        }
                    }
                    break;
                case 'O': 
                case 'Y':
                    if ($record->e_status==="O"){
                        $json->status = 'yet';
                    }else{
                        $json->status = 'ed';
                    }
                    $json->eid = $record->e_id;
                    $json->esid = $record->e_id;
                    $json->sid = $sid;
                    $json->ssid = $sid;
                    break;
            }
        }
        return $json;
    }
    public function examtest($sid){
        $this->_exam_sets($sid);
    }
    // 格式化顯示題目
    private function _Ques_Info($que, $no){
        $data = new \stdclass;
        $data->ans = '';
        $data->qid = $que->q_id;
        $data->qtype = $que->q_quetype;
        $data->qnum = 0;
        $qcont = array();
        if (!empty($que->q_quetxt))$qcont[] = nl2br(trim($que->q_quetxt));
        if (!empty($que->q_qm_src)){
            if (is_file($que->q_qm_src))$qcont[] = '<img class="pic" src="'.URL::asset($que->q_qm_src).'">';
        }
        if (!empty($que->q_qs_src)){
            if (is_file($que->q_qs_src))$qcont[] = '<span class="qs" data-id="S'.$no.'" onclick="QS(this)">播放</span><audio id="S'.$no.'" preload>
                        <source src="'.URL::asset($que->q_qs_src).'" type="audio/mpeg">
                        <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                      </audio>';
        }
        $data->qcont = implode("<br>", $qcont);
        switch ($que->q_quetype) {
            case 'S': 
            case 'D': 
                $ans_i = 1;
                $ans_html = '';
                $qtype = ($que->q_quetype==="S") ? 'radio':'checkbox';
                $qname = ($que->q_quetype==="S") ? $no:$no.'[]';
                while ($ans_i<=$que->q_num) {
                    $ans_html.= '<label><input name="ans'.$qname.'" type="'.$qtype.'" value="'.$ans_i.'"><font id="ans_'.$ans_i.'">'.chr($ans_i+64).'</font></label>';
                    $ans_i++;
                }
                $data->ans = $ans_html;
                break;
            case 'R': 
                $ans_html = '<label><input type="radio" name="ans'.$no.'" value="1">O</label>  ';
                $ans_html.= '<label><input type="radio" name="ans'.$no.'" value="2">X</label>';
                $data->ans = $ans_html;
                break;
            case 'M': 
                $ans = explode(',', $que->q_ans);
                $ans_math = '';
                $data->qnum = count($ans);
                foreach ($ans as $i => $o) {
                    $ans_math.= '<div id="a'.($i+1).'"><span>No.'.($i+1).'</span>';

                    if (preg_match("/^[0-9]*$/", $o)){
                        $now = (int)$o;
                    }else{
                        if ($o==="a")$now = 10;
                        if ($o==="b")$now = 11;
                    }
                    $each = 1;
                    while($each<=9){
                        $ans_math.= '<label><input type="radio" name="ans'.$no.'_'.($i+1).'" value="'.$each.'">'.$each.'</label>';
                        $each++;
                    }
                    $ans_math.= '<label><input type="radio" name="ans'.$no.'_'.($i+1).'" value="0">0</label>';
                    $ans_math.= '<label><input type="radio" name="ans'.$no.'_'.($i+1).'" value="a">-</label>';
                    $ans_math.= '<label><input type="radio" name="ans'.$no.'_'.($i+1).'" value="b">±</label>';
                    $ans_math.= '</div>';
                }
                $data->ans = $ans_math;
                break;
        }
        return $data;
    }
}
