<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sets;

class ExamController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->login_status())return redirect('/login');
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
                'exam_type' => 'sets',
                'exam_exnum' => '',
                'exam_grade' => '',
                'exam_subject' => '',
                'exam_chapter' => '',
                'exam_degree' => '',
                'exam_listseq' => $s_id,
                'exam_limtime' => $sets->s_limtime,
                'exam_cram' => '',
                'exam_fkey' => '',
                'score_open' => '',
                'Sum' => $sets->s_sum,
                'Limetime' => $limetime,
                'Sub_info' => $sub,
                'Pass_core' => $sets->s_pass_score,
                'Times' => $time
            ]);
        }
    }
}
