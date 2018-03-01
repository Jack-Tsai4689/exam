<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Exams;
use App\ExamDetail;
use App\Ques;
use DB;

class AnalyController extends Controller
{
    public function index()
    {
        //
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
    /*
    考題來源表
    */
    public function source($eid){
        $eid = (int)$eid;
        $exam = Exams::find($eid);
        if ($exam->e_sub){
            $sub_exam = Exams::where('e_pid', $eid)->get()->all();
            $qdata = array();
            foreach ($sub_exam as $se) {
                $ques = ExamDetail::select('ed_ans','ed_right','ed_qid')
                                 ->where('ed_eid', $se->e_id)
                                 ->orderby('ed_sort')->get()->all();
                foreach ($ques as $v) {
                    $sql = "SELECT COUNT(*) AS row, SUM(ed_right) as correct FROM exam_details WHERE ed_qid=?";
                    $c = DB::select($sql, [$v->ed_qid])[0];
                    $percen = round($c->correct / $c->row, 2)*100;
                    echo $percen.',';
                    // $oq = $v->ques_source();
                    // $data = $this->_que_ans_format($oq, $v);
                    // $chap = Ques::find($oq->q_id)->chap()->first();
                    // $data->chap = $chap->name;
                    // $c = $data->que()->first();
                    // dd($c);
                    // array_push($qdata, $data);
                }
            }
            // dd($qdata);
        }
    }
    /*
    觀念答對比率圖
    */
    public function radar($eid){

    }
    private function _que_ans_format($que, $e_que){
        switch ($que->q_quetype) {
            case "S": 
                $que->q_ans = chr($que->q_ans+64);
                if (!empty($e_que->ed_ans)) $que->ed_ans = chr($e_que->ed_ans+64);
                break;
            case "D": 
                $ans = array();
                $ans = explode(",", $que->q_ans);
                $ans_html = array();
                foreach ($ans as $o) {
                    $ans_html[] = chr($o+64);
                }
                $que->q_ans = implode(", ", $ans_html);

                $ans = array();
                $ans = explode(",", $e_que->ed_ans);
                $ans_html = array();
                foreach ($ans as $o) {
                    $ans_html[] = chr($o+64);
                }
                $que->ed_ans = implode(", ", $ans_html);
                break;
            case "R": 
                $que->q_ans = ($que->q_ans==="1") ? "O":"X";
                if (!empty($e_que->ed_ans)) $que->ed_ans = ($e_que->ed_ans==="1") ? "O":"X";
                break;
            case "M": 
                $ans = array();
                $ans = explode(",", $que->q_ans);
                $ans_html = array();
                foreach ($ans as $o) {
                    if (!preg_match("/^[0-9]*$/", $o)){
                        $ans_html[] = ($o==="a") ? '-':'±';
                    }else{
                        $ans_html[] = $o;
                    }
                }
                $que->q_ans = implode(", ", $ans_html);

                if (!empty($e_que->ed_ans)){
                    $ans = array();
                    $ans = explode(",", $e_que->ed_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        if (!preg_match("/^[0-9]*$/", $o)){
                            $ans_html[] = ($o==="a") ? '-':'±';
                        }else{
                            $ans_html[] = $o;
                        }
                    }
                    $que->ed_ans = implode(", ", $ans_html);
                }
                break;
        }
        $que->ed_right = $e_que->ed_right;
        return $que;
    }
}
