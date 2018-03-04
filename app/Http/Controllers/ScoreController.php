<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exams;
use App\Sets;

class ScoreController extends TopController
{
    
    public function __construct(){
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 成績列表
    public function index()
    {
        //學生只看自己的
        if (session('ident')==="S"){
            $data = Exams::where('e_stu', session('epno'))
                         ->where('e_pid', 0)
                         ->where('e_status', 'Y')
                         ->get()->all();
            foreach ($data as $k => $v) {
                $s = Sets::find($v->s_id);
                $data[$k]->sets = $s->s_name;
                $data[$k]->gra = $s->gra->name;
                $data[$k]->subj = $s->subj->name;
            }
            return view('exam.score_list', [
                'menu_user' => $this->menu_user,
                'title' => '成績',
                'Data' => $data
            ]);
            return;
        }
        //老師依班級看
        if (session('ident')==="T"){

        }
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
    //看成績結果
    public function show($id)
    {
        //學生卷主id
        $eid = (int)$id;
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
            'title' => $sets->s_name.' 測驗結果',
            'Setsname' => $sets->s_name,
            'Data' => $sub_exam,
            'exam' => $exam,
            'Eid' => $eid
        ]);
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
}