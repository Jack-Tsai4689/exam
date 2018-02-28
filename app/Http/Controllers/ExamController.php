<?php

namespace App\Http\Controllers;

//use Event;
// use App\Events\PushNotification;
use Redis;
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
    private $encrypt_hash = "JerryTsai";
    private $hash = null;
    private $aes_key = null;
    private $aes_iv = null;

    public function __construct(){
        parent::__construct();
    }
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
    // aes初始化
    private function _aes_init(){
        $this->hash = hash('SHA384', $this->encrypt_hash, true);
        $this->aes_key = substr($this->hash, 0, 32);
        $this->aes_iv = substr($this->hash, 32, 16);
    }
    //加密
    private function _encrypt($data){
        $padding = 16 - (strlen($data) % 16);
        $data.= str_repeat(chr($padding), $padding);
        $encrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->aes_key, $data, MCRYPT_MODE_CBC, $this->aes_iv);
        return base64_encode($encrypt);
    }
    //解密
    private function _decrypt($source){
        $encrypt = base64_decode($source);
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->aes_key, $encrypt, MCRYPT_MODE_CBC, $this->aes_iv);
        $padding = ord($data[strlen($data) - 1]);
        return substr($data, 0, -$padding);
    }
    //中離記錄，接收至socket.js
    public function quit(Request $req){
        $token = $req->input('token');
        //$time = $req->input('time');
        $this->_aes_init();
        $user = $this->_decrypt($token);
        $io = Redis::get($token);
        Redis::del($token);
        Redis::del($io);
        $exam_data = explode("|", $user);
        Exams::where('e_id', $exam_data[1])
             ->where('e_stu', $exam_data[0])
             ->where('e_status', 'N')
             ->update(['e_status' => 'O', 'e_endtime_at' => time() ]);
        //Event::fire(new PushNotification($this->login_user, '123'));
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
        $utime = ($req->has('utime') && !empty($req->input('utime'))) ? trim($req->input('utime')):0;
        $eid = $req->input('exam');
        //下個大題
        if ($act==="part"){
            //學生卷id
            $hour = $req->input('hour');
            $min = $req->input('min');
            $sec = $req->input('sec');
            $sets_data = Sets::find($sid);
            $part = Exams::where('e_pid', $eid)->where('e_status','N')->orderby('e_sort')->first();
            if ($part===null){
                //加總題數進行更新
                $sql = "UPDATE exams INNER JOIN (
                    SELECT e_pid, SUM(e_rnum) as r, SUM(e_wnum) as w, SUM(e_nnum) as n FROM exams WHERE e_pid=?
                ) a
                ON exams.e_id=a.e_pid
                SET e_rnum=a.r, e_wnum=a.w, e_nnum=a.n, e_status='Y'";
                DB::update($sql, [$eid]);
                die('考試結束');
            }
            $lime = $hour.":".$min.":".$sec;
            return view('exam.nextpart', [
                'title' => $sets_data->s_name,
                'type' => 'sets',
                'exam' => $eid,
                'exnum' => '',
                'gra' => '',
                'subj' => '',
                'chap' => '',
                'degree' => '',
                'sets' => $sid,
                'lime' => $lime,
                'epart' => $part->e_id,
                'spart' => $part->s_id
            ]);
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
                  ->update(['ed_ans'=> $ans])
                  ->increment('ed_times', $utime);

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
                Exams::where('e_id', $eid)->update([
                    'e_endtime_at' => time()
                ]);

                $exam_sub = Exams::find($part);
                $ans = Redis::get('s'.$sid.'|p'.$exam_sub->s_id);
                //答案
                $right_ans = explode("|", $ans);
                $all_rows = count($right_ans);
                $right_rows = 0;
                //核對答案
                foreach ($right_ans as $i => $v) {
                    //會回傳筆數
                    $right = ExamDetail::where('ed_eid', $part)
                                       ->where('ed_ans', $v)
                                       ->where('ed_sort', ($i+1))
                                       ->update(['ed_right'=>1]);
                    if ($right>0)$right_rows++;
                }
                // 算未答的題目
                $no_ans = ExamDetail::where('ed_eid', $part)->where('ed_ans','')->count();
                $exam_sub->e_rnum = $right_rows;
                $exam_sub->e_nnum = $no_ans;
                $exam_sub->e_wnum = $all_rows-$right_rows - $no_ans;
                $exam_sub->e_status = 'Y';
                $exam_sub->e_endtime_at = time();
                $exam_sub->save();
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
        if (Redis::exists('q_info:'.$que->ed_qid)){
            $quedata = Redis::get('q_info:'.$que->ed_qid);
        }else{
            $quedata = $this->_Ques_Info($que->que()->first(), $qno);
            Redis::set('q_info:'.$que->ed_qid, $quedata);
        }
        echo json_encode($quedata);
    }
    //session 傳值
    public function init_check(Request $req){
        $sets = ($req->has('sets') && (int)$req->input('sets')>0) ? (int)$req->input('sets'):0;
        if ($sets===0)abort(400);
        session()->put('token', uniqid());
        session()->put('type', 'sets');
        session()->put('sets', $req->input('sets'));
        echo '1';
    }
    //開始測驗
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
                //大題
                foreach ($sub as $k => $v) {
                    $sub[$k]->back = ($v->s_page==='N') ? '不':'';
                    //答案存redis
                    $key = 's'.$s_id.'|p'.$v->s_id;
                    if (!Redis::exists($key)){
                        $subq = Setsque::where('sq_sid', $s_id)
                                       ->where('sq_part', $v->s_id)
                                       ->join('ques','ques.q_id','=','setsque.sq_qid')
                                       ->select('q_ans')
                                       ->orderby('sq_sort')
                                       ->get()->all();
                        $ans = array();
                        foreach ($subq as $sk => $sv) {
                            $ans[] = $sv->q_ans;
                        }
                        Redis::set('s'.$s_id.'|p'.$v->s_id, implode("|", $ans));
                    }
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
    //進行測驗
    public function examing(Request $req){
        $type = ($req->has('type') && !empty($req->input('type'))) ? trim($req->input('type')):'';
        $exnum = ($req->has('exnum') && !empty($req->input('exnum'))) ? trim($req->input('exnum')):'';
        $gra = ($req->has('gra') && (int)$req->input('gra')>0) ? (int)$req->input('gra'):0;
        $subj = ($req->has('subj') && (int)$req->input('subj')>0) ? (int)$req->input('subj'):0;
        $chap = ($req->has('chap') && (int)$req->input('chap')>0) ? (int)$req->input('chap'):0;
        $degree = ($req->has('degree') && !empty($req->input('degree'))) ? trim($req->input('degree')):'';
        $sid = ($req->has('sets') && (int)$req->input('sets')>0) ? (int)$req->input('sets'):0;
        $lime = ($req->has('lime') && !empty($req->input('lime'))) ? trim($req->input('lime')):'';
        $exam = ($req->has('exam') && !empty($req->input('exam'))) ? (int)$req->input('exam'):0;
        $epart = ($req->has('epart') && !empty($req->input('epart'))) ? (int)$req->input('epart'):0;
        $spart = ($req->has('spart') && !empty($req->input('spart'))) ? (int)$req->input('spart'):0;
        if ($type==="sets"){
            if ($sid<=0)abort(400);
            //$this->_exam_sets($sets);
            $sets_data = Sets::find($sid);
            //直接下個大題
            if ($exam>0 && $epart>0 && $spart>0){
                $_exam = new \stdclass;
                $_exam->status = 'part';
                $_exam->eid = $exam;
                $_exam->esid = $epart;
                $_exam->sid = $sid;
                $_exam->ssid = $spart;
                $data = $this->_next_part($_exam, $sets_data, $lime);
                Exams::where('e_id', $epart)->update(['e_begtime_at'=> time()]);
                return view('exam.ying', $data);
                return;
            }
            $_exam = $this->_Exam_Status_Check($sid, $epart, $spart);
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
                    $start_q = 0;
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
                    break;
                case 'yet': //中離，從未完成的大題進來
                    //切回測驗中
                    Exams::where('e_id', $_exam->esid)->update(['e_status'=>'N']);
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
                        $part_show->no = $part_que->s_part;
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
                            //$part_show->no = ($i+1);
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
                    break;
                case 'ing': //正在考
                    die('您已在測驗中');
                    break;
            }
            if ($_exam->status==="" || $_exam->status==="yet"){
                //loading第一題資料
                if (Redis::exists('q_info:'.$first_quedata[$start_q]->ed_qid)){
                    $que = Redis::get('q_info:'.$first_quedata[$start_q]->ed_qid);
                }else{
                    $que = $this->_Ques_Info($first_quedata[$start_q]->que()->first(), ($start_q+1));
                    Redis::set('q_info:'.$first_quedata[$start_q]->ed_qid, $que);   
                }
            }

            $this->_aes_init();
            $token = $this->_encrypt($this->login_user.'|'.$part_show->eid);
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
                'qno' => $qno,
                'token' => $token
            ];
            if ($part_show->control==="Y"){
                return view('exam.ying', $data);
            }else{
                return view('exam.ying', $data);
            }
            
        }
    }
    //看成績結果
    public function score($eid){
        //學生卷主id
        $eid = (int)$eid;
        if ($eid<1)abort(400);
        $exam = Exams::find($eid);
        $sets = Sets::find($exam->s_id);
        
        $Sets_name = $sets->s_name;
        $que = array();
        if ($exam->e_sub){
            $sub_exam = Exams::where('e_pid', $eid)->get()->all();
        }

        $uses_time = $exam->e_endtime_at - $exam->e_begtime_at;
        return view('exam.result', [
            'menu_user' => $this->menu_user,
            'title' => '成績',
            'Data' => $sub_exam,
            'exam' => $exam
        ]);
    }
    //下個大題
    private function _next_part($exam, $sets_data, $limetime){
        $start_q = 0;
        $qno = array();
        $part_show = new \stdclass;
        $first_que = new \stdclass;
        $part_show->eid = $exam->esid;
        if ($sets_data->s_sub){
            //找大題 -> 找題目，放進exams 依大題順序
            $part_que = Sets::find($exam->ssid);
            /*
            loading第一大題設定
            可否回上頁
            題數
            配分
            */
            $part_show->control = $part_que->s_page;
            $part_show->intro = nl2br(trim($part_que->s_intro));
            $part_show->no = $part_que->s_part;
            $part_show->sub = true;
            $part_show->score = $part_que->s_percen;
            
            $first_quedata = Setsque::select('sq_qid')->where('sq_sid', $exam->sid)
                                    ->where('sq_part', $exam->ssid)
                                    ->orderby('sq_sort')->get()->all();
        }else{
            $part_show->control = $sets_data->s_page;
            $part_show->sub = false;
            
            $first_quedata = Setsque::select('sq_qid')->where('sq_sid', $exam->sid)
                                    ->where('sq_part', $exam->sid)
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
        $que = $this->_Ques_Info($first_quedata[$start_q]->que()->first(), ($start_q+1));
        $lime = explode(":", $limetime);
        $this->_aes_init();
        $token = $this->_encrypt($this->login_user.'|'.$part_show->eid);
        $data = [
                'sets_name' => $sets_data->s_name,
                'type' => 'sets',
                'sets' => $exam->sid,
                'exam' => $exam->eid,
                'y' => 0,
                'hour' => $lime[0],
                'min' => $lime[1],
                'sec' => $lime[2],
                'end_date' => '',
                'first_part' => $part_show,
                'qno_html' => $qno_html,
                'curr' => ($start_q+1),
                'que' => $que,
                'qno' => $qno,
                'token' => $token
            ];
        return $data;
    }
    /*
    考試確認
    如果沒考完，還可以接續考，用e_status判斷 (N, O)
    照順序判斷，從主體->大題
    @int 考卷id=>sid，學生卷大題=>epart，考卷大題=>spart
    return 
        status ed(考過) ing(進行中) yet(中離)
        eid 學生卷id
    */
    private function _Exam_Status_Check($sid, $epart, $spart){
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
    // public function examtest($sid){
    //     $this->_exam_sets($sid);
    // }
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
