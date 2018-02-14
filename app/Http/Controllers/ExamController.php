<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sets;
use App\Setsque;
use App\Exams;
use DB;
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
    public function store(Request $request)
    {
        //
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
        $sets = ($req->has('sets') && (int)$req->input('sets')>0) ? (int)$req->input('sets'):0;
        $lime = ($req->has('lime') && !empty($req->input('lime'))) ? trim($req->input('lime')):'';

        if ($type==="sets"){
            if ($sets<=0)abort(400);
            $this->_exam_sets($sets);
        }
    }
    public function examtest($sid){
        $this->_exam_sets($sid);
    }
    private function _exam_sets($sid){
        $sets_data = Sets::find($sid);
        if (!$sets_data->s_again){
            $record = Exams::where('s_id', $sid)->where('e_stu', session('epno'))->first();
            if (!empty($record)){
                echo'已考過';
                return;
            }
        }
        $lime = explode(":", $sets_data->s_limtime);
        //主記錄先放
        $edata = new Exams;
        $edata->fill([
            'e_stu' => session('epno'),
            's_id' => $sid,
            'e_pid' => 0,
            'e_sub' => $sets_data->s_sub,
            'e_begtime_at' => time()
        ]);
        $edata->save();
        $eid = $edata->e_id;

        $first_part = null;
        $first_que = new \stdclass;
        if ($sets_data->s_sub){
            //找大題 -> 找題目，放進exam_details
            $part_que = Sets::where('s_pid', $sid)->orderby('s_part')->get()->all();
            foreach ($part_que as $pv) {
                //把考卷寫一份到學生卷
                DB::insert("INSERT INTO exam_details(s_id, ed_eid, ed_sort, ed_qid) SELECT ?, ?, sq_sort, sq_qid FROM setsque WHERE sq_sid=? AND sq_part =? ORDER BY sq_sort", [$pv->s_id, $eid, $sid, $pv->s_id]);
            }
            //loading第一題資料
            $first_quedata = Setsque::where('sq_sid', $sid)
                                    ->where('sq_part', $part_que[0]->s_id)
                                    ->where('sq_sort',1)->first();
        }
        
        $data = [
            'sets_name' => $sets_data->s_name,
            'hour' => $lime[0],
            'min' => $lime[1],
            'sec' => $lime[2],

        ];
    }
}
