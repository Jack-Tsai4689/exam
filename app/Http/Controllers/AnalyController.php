<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Exams;
use App\ExamDetail;
use App\Pubs;
use App\Ques;
use App\Gscs;
use DB;

class AnalyController extends TopController
{
    public function __construct(){
        parent::__construct();
    }
    /*
    考題來源表
    */
    public function source($eid){
        if (!$this->login_status)return redirect('/login');
        $eid = (int)$eid;
        $exam = Exams::find($eid);
        $sets_set = Pubs::find($exam->s_id);
        if ($exam->e_sub){
            $sub_exam = Exams::where('e_pid', $eid)->get()->all();
            $sub_sets = Pubs::where('p_pid', $exam->s_id)->get()->all();
            $part = array();
            foreach ($sub_exam as $si => $se) {
                $ques = ExamDetail::select('ed_ans','ed_sort','ed_right','ed_qid')
                                 ->where('ed_eid', $se->e_id)
                                 ->orderby('ed_sort')->get()->all();
                $info = new \stdclass;
                $info->score = $se->e_score;
                $info->rnum = $se->e_rnum;
                $info->wnum = $se->e_wnum;
                $info->nnum = $se->e_nnum;
                $info->percen = $sub_sets[$si]->p_percen;
                $qdata = array();
                foreach ($ques as $v) {
                    $oq = $v->ques_source();
                    $chap = Ques::find($oq->q_id)->chap()->first();
                    $data = $this->_que_ans_format($oq, $v);

                    $tmp = new \stdclass;
                    $tmp->sort = $v->ed_sort;
                    $tmp->chap = $chap->name;
                    $tmp->ed_ans = $data->ed_ans;
                    $tmp->q_ans = $data->q_ans;
                    $tmp->right = ($data->ed_right) ? true:false;

                    switch ($oq->q_degree) {
                        case "M": $tmp->degree = "中等"; break;
                        case "H": $tmp->degree = "困難"; break;
                        case "E": $tmp->degree = "容易"; break;
                        default: $tmp->degree = "容易"; break;
                    }
                    $sql = "SELECT COUNT(*) AS row, SUM(ed_right) as correct FROM exam_details WHERE ed_qid=?";
                    $c = DB::select($sql, [$v->ed_qid])[0];
                    $tmp->percen = round($c->correct / $c->row, 2)*100;
                    array_push($qdata, $tmp);
                }
                $info->qdata = $qdata;
                array_push($part, $info);
            }
        }else{
            $ques = ExamDetail::select('ed_ans','ed_sort','ed_right','ed_qid')
                                 ->where('ed_eid', $eid)
                                 ->orderby('ed_sort')->get()->all();
            $part = new \stdclass;
            $part->score = $exam->e_score;
            $part->rnum = $exam->e_rnum;
            $part->wnum = $exam->e_wnum;
            $part->nnum = $exam->e_nnum;
            $part->percen = $sets_set->p_sum;
            $qdata = array();
            foreach ($ques as $v) {
                $oq = $v->ques_source();
                $chap = Ques::find($oq->q_id)->chap()->first();
                $data = $this->_que_ans_format($oq, $v);

                $tmp = new \stdclass;
                $tmp->sort = $v->ed_sort;
                $tmp->chap = $chap->name;
                $tmp->ed_ans = $data->ed_ans;
                $tmp->q_ans = $data->q_ans;
                $tmp->right = ($data->ed_right) ? true:false;

                switch ($oq->q_degree) {
                    case "M": $tmp->degree = "中等"; break;
                    case "H": $tmp->degree = "困難"; break;
                    case "E": $tmp->degree = "容易"; break;
                    default: $tmp->degree = "容易"; break;
                }
                $sql = "SELECT COUNT(*) AS row, SUM(ed_right) as correct FROM exam_details WHERE ed_qid=?";
                $c = DB::select($sql, [$v->ed_qid])[0];
                $tmp->percen = round($c->correct / $c->row, 2)*100;
                array_push($qdata, $tmp);
            }
            $part->qdata = $qdata;
        }
        return view('analy.concept', [
            'menu_user' => $this->menu_user,
            'title' => $sets_set->p_name.'- 考題概念表',
            'Part' => $part,
            'Eid' => $eid,
            'Have_sub' => $exam->e_sub
        ]);
    }
    /*
    觀念答對比率圖
    */
    public function radar($eid){
        if (!$this->login_status)return redirect('/login');
        $eid = (int)$eid;
        $exam = Exams::find($eid);
        $sets_set = Pubs::find($exam->s_id);
        $q = array();
        $chap_id = array();
        $no = 0;
        if ($exam->e_sub){
            $sub_exam = Exams::where('e_pid', $eid)->get()->all();
            foreach ($sub_exam as $si => $se) {
                $ques = ExamDetail::select('ed_ans','ed_sort','ed_right','ed_qid')
                                 ->where('ed_eid', $se->e_id)
                                 ->orderby('ed_sort')->get()->all();
                foreach ($ques as $v) {
                    $no++;
                    $tmp = new \stdclass;
                    $tmp->qno = $no;//$v->ed_sort;
                    $tmp->right = $v->ed_right;
                    $oq = $v->ques_source();
                    $chap_id[] = $oq->q_chap;
                    $tmp->id = $oq->q_chap;
                    array_push($q, $tmp);
                }
            }
        }else{
            $ques = ExamDetail::select('ed_ans','ed_sort','ed_right','ed_qid')
                              ->where('ed_eid', $eid)
                              ->orderby('ed_sort')->get()->all();
            foreach ($ques as $v) {
                $no++;
                $tmp = new \stdclass;
                $tmp->qno = $no;//$v->ed_sort;
                $tmp->right = $v->ed_right;
                $oq = $v->ques_source();
                $chap_id[] = $oq->q_chap;
                $tmp->id = $oq->q_chap;
                array_push($q, $tmp);
            }
        }
        //唯一
        $chap = array_values(array_unique($chap_id));
        $data = array();
        $chap_name = array();
        $q_right = array();
        $q_all = array();
        foreach ($chap as $c) {
            $tmp = Gscs::find($c);
            $d = new \stdclass;
            $d->name = $tmp->g_name;
            $chap_name[] = $tmp->g_name;
            $right = array();
            $wrong = array();
            $all = 0;
            foreach ($q as $i => $v) {
                if ($v->id===$c){
                    if ($v->right){
                        $right[] = '('.$v->qno.')';
                    }else{
                        $wrong[] = '('.$v->qno.')';
                    }
                    $all++;
                    unset($q[$i]);
                }
            }
            $q_right[] = count($right);
            $q_all[] = $all;
            // $d->all = $all;
            $d->right = implode(" ", $right);
            $d->wrong = implode(" ", $wrong);
            array_push($data, $d);
        }
        return view('analy.radar', [
            'menu_user' => $this->menu_user,
            'title' => $sets_set->p_name.'- 觀念答對比率圖',
            'Data' => $data,
            'Eid' => $eid,
            'Con_type' => implode(",", $chap_name),
            'Con_right' => implode(",", $q_right),
            'Con_all' => implode(",", $q_all),
            'Graph_id' => $exam->e_stu.'_'.$eid
        ]);
    }
    private function _que_ans_format($que, $e_que){
        $data = new \stdclass;
        $data->ed_ans = '';
        switch ($que->q_quetype) {
            case "S": 
                $data->q_ans = chr($que->q_ans+64);
                if (!empty($e_que->ed_ans)) $data->ed_ans = chr($e_que->ed_ans+64);
                break;
            case "D": 
                $ans = array();
                $ans = explode(",", $que->q_ans);
                $ans_html = array();
                foreach ($ans as $o) {
                    $ans_html[] = chr($o+64);
                }
                $data->q_ans = implode(", ", $ans_html);
                if (!empty($e_que->ed_ans)){
                    $ans = array();
                    $ans = explode(",", $e_que->ed_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        $ans_html[] = chr($o+64);
                    }
                    $data->ed_ans = implode(", ", $ans_html);
                }
                break;
            case "R": 
                $data->q_ans = ($que->q_ans==="1") ? "O":"X";
                if (!empty($e_que->ed_ans)) $data->ed_ans = ($e_que->ed_ans==="1") ? "O":"X";
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
                $data->q_ans = implode(", ", $ans_html);

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
                    $data->ed_ans = implode(", ", $ans_html);
                }
                break;
        }
        $data->ed_right = $e_que->ed_right;
        return $data;
    }
}
