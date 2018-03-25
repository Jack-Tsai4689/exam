<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Exams;
use App\Pubs;

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
        if (!$this->login_status)return redirect('/login');
        //學生只看自己的
        if (session('ident')==="S"){
            $data = Exams::where('e_stu', session('epno'))
                         ->where('e_pid', 0)
                         ->where('e_status', 'Y')
                         ->orwhere('e_status','O')
                         ->get()->all();
            foreach ($data as $k => $v) {
                $p = Pubs::find($v->s_id);
                $data[$k]->sets = $p->p_name;
                $data[$k]->gra = $p->gra->name;
                $data[$k]->subj = $p->subj->name;                
            }
            return view('score.score_slist', [
                'menu_user' => $this->menu_user,
                'title' => '成績',
                'Data' => $data
            ]);
            return;
        }
        //老師依班級看
        if (session('ident')==="T"){
            $data = Exams::where('e_pid', 0)
                         ->get()->all();
            foreach ($data as $k => $v) {
                $data[$k]->can_see = '';
                switch($v->e_status){
                    case 'Y':
                        $data[$k]->e_end = date('Y/m/d H:i:s', $v->e_endtime_at);
                        $data[$k]->can_see = 'class="see_rs" id="'.$v->e_id.'"';
                        break;
                    case 'N':
                        $data[$k]->e_end = '進行中';
                        break;
                    case 'O':
                        $data[$k]->e_end = '中離';
                        break;
                }
            }
            return view('score.score_tlist', [
                'menu_user' => $this->menu_user,
                'title' => '成績-班級查詢',
                'Data' => $data
            ]);
            return;
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
        if (!preg_match("/^[0-9]*$/", $id))abort(400);
        //學生卷主id
        $eid = (int)$id;
        if ($eid<1)abort(400);
        $exam = Exams::find($eid);
        $sets = Pubs::find($exam->s_id);
        
        $uses_time = $exam->e_endtime_at - $exam->e_begtime_at;
        $times = new \stdclass;
        $times->hour = floor($uses_time/3600);
        $times->min = floor(($uses_time%3600)/60);
        $times->sec = floor($uses_time%60);

        if ($exam->e_sub){
            $sub_exam = Exams::where('e_pid', $eid)->orderby('e_sort')->get()->all();
        }else{
            $sub_exam = $exam->sub_ques_ans();
        }
        return view('exam.result', [
            'menu_user' => $this->menu_user,
            'title' => $sets->p_name.' 測驗結果',
            'Setsname' => $sets->p_name,
            'Data' => $sub_exam,
            'exam' => $exam,
            'Time' => $times,
            'Eid' => $eid,
            'Have_sub' => $sets->p_sub,
            'Sum' => $sets->p_sum
        ]);
    }
    // 成績搜尋頁
    public function find_stu(){
        $_get = request()->all();
        $Stuno = '';
        $Stuname = '';
        $open_search = false;
        if (!empty($_get)){
            $no = request()->input('stuno');
            $name = request()->input('stuname');
            if (preg_match("/^[0-9]*$/", $no)){
                $open_search = true;
                $Stuno = (string)$no;
            }
            if (!empty($name)){
                $open_search = true;
                $Stuname = trim($name);
            }
        }
        $data = array();
        if ($open_search){
            $data = Exams::select('*');
            if (!empty($Stuno))$data = $data->where('e_stu', $Stuno);
            if (!empty($Stuname)){
                $data = $data->join('stus','stus.st_no','=','exams.e_stu')
                             ->where('st_name','like','%'.$Stuname.'%');
            }
            $data = $data->where('e_pid', 0)
                         // ->where('e_status', 'Y')
                         // ->orwhere('e_status','O')
                         ->get()->all();
            foreach ($data as $k => $v) {
                $data[$k]->can_see = '';
                switch($v->e_status){
                    case 'Y':
                        $data[$k]->e_end = date('Y/m/d H:i:s', $v->e_endtime_at);
                        $data[$k]->can_see = 'class="see_rs" id="'.$v->e_id.'"';
                        break;
                    case 'N':
                        $data[$k]->e_end = '進行中';
                        break;
                    case 'O':
                        $data[$k]->e_end = '中離';
                        break;
                }
            }
        }
        return view('score.tlist_search', [
            'menu_user' => $this->menu_user,
            'title' => '成績-個人查詢',
            'Data' => $data,
            'Stuno' => $Stuno,
            'Stuname' => $Stuname
        ]);
    }
}