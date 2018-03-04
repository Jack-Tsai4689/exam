<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use URL;

class Exams extends Model
{
	public $timestamps = false;
	protected $table = 'exams';
	protected $primaryKey = 'e_id';
	protected $fillable = [
		'e_stu',
        's_id',
        'e_pid',
        'e_sub',
        'e_sort',
        'e_begtime_at',
        'e_endtime_at',
        'e_used_time',
        'e_rnum',
        'e_wnum',
        'e_nnum',
        'e_score',
        'e_status'
	];
    //關聯並格式化
    public function ques_ans(){
        $ques = $this->hasMany(ExamDetail::class, 'ed_eid','e_id')
                ->join('ques','ques.q_id','=','exam_details.ed_qid')
                ->select('ed_ans','ed_right','ed_sort','ques.*')
                ->orderby('ed_sort')->get()->all();
        $data = array();
        foreach ($ques as $q) {
                $tmp = new \stdclass;
                $tmp->qno = $q->ed_sort;
                //題目
                if (!empty($q->q_quetxt))$qcont[] = nl2br(trim($q->q_quetxt));
                if (!empty($q->q_qm_src)){
                    if (is_file($q->q_qm_src))$qcont[] = '<img class="pic" src="'.URL::asset($q->q_qm_src).'">';
                }
                if (!empty($q->q_qs_src)){
                    if (is_file($q->q_qs_src))$qcont[] = '<audio preload>
                                <source src="'.URL::asset($q->q_qs_src).'" type="audio/mpeg">
                                <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                              </audio>';
                }
                $tmp->qcont = implode("<br>", $qcont);
                $ans_html = '';
                $my_ans = '';
                //題型、答案
                switch ($q->q_quetype) {
                        case "S": 
                            $ans_html = chr($q->q_ans+64);
                            if (!empty($q->ed_ans))$my_ans = chr($q->ed_ans+64);
                            break;
                        case "D": 
                            $ans = array();
                            $ans = explode(",", $q->q_ans);
                            $qans = array();
                            foreach ($ans as $o) {
                                $qans[] = chr($o+64);
                            }
                            $ans_html = implode(", ", $qans);
                            if (!empty($q->ed_ans)){
                                    $ans = array();
                                    $ans = explode(",", $q->ed_ans);
                                    $qans = array();
                                    foreach ($ans as $o) {
                                        $qans[] = chr($o+64);
                                    }
                                    $my_ans = implode(", ", $qans);
                                }
                            break;
                        case "R": 
                            $ans_html = ($q->q_ans==="1") ? "O":"X";
                            if (!empty($q->ed_ans))$my_ans = ($q->ed_ans==="1") ? "O":"X";
                            break;
                        case "M": 
                            $ans = array();
                            $ans = explode(",", $q->q_ans);
                            $qans = array();
                            foreach ($ans as $o) {
                                if (!preg_match("/^[0-9]*$/", $o)){
                                    $qans[] = ($o==="a") ? '-':'±';
                                }else{
                                    $qans[] = $o;
                                }
                            }
                            $ans_html = implode(", ", $qans);
                            if (!empty($q->ed_ans)){
                                    $ans = array();
                                    $ans = explode(",", $q->ed_ans);
                                    $qans = array();
                                    foreach ($ans as $o) {
                                        if (!preg_match("/^[0-9]*$/", $o)){
                                            $qans[] = ($o==="a") ? '-':'±';
                                        }else{
                                            $qans[] = $o;
                                        }
                                    }
                                    $my_ans = implode(", ", $qans);
                                }
                            break;
                }
                $tmp->q_ans = $ans_html;
                $tmp->myans = $my_ans;
                //如果錯的話，顯示我的答案
                $tmp->right_pic = (!$q->ed_right) ? '/img/icon_op_f.png':'/img/icon_op_t.png';
                
                //詳解
                $acont = array();
                //詳解文字
                if (!empty($q->q_anstxt)) $acont[] = nl2br(trim($q->q_anstxt));
                //詳解圖檔
                if(!empty($q->q_am_src)){
                        if (is_file($q->q_am_src))$acont[] = '<IMG class="pic"  src="'.URL::asset($q->q_am_src).'">';
                }
                $amedia = array();
                //詳解聲音檔
                if(!empty($q->q_as_src)){
                if(is_file($q->q_as_src)){
                    $amedia[] = '<font color="green">詳解音訊 O</font>';
                }else{
                    $amedia[] = '<font color="red">詳解音訊遺失 X</font>';
                }
                }
                //詳解影片檔
                if(!empty($q->q_av_src)){
                if(is_file($q->q_av_src)){
                    $amedia[] = '<font color="green">詳解視訊 O</font>';
                }else{
                    $amedia[] = '<font color="red">詳解視訊遺失 X</font>';
                }
                }
                $acont[] = implode(' | ', $amedia);
                $tmp->acont = '詳解<br>'.implode("<br>", $acont);
                array_push($data, $tmp);
        }
        return $data;
    }
    //關聯大題設定
    public function sets_info(){
        return $this->hasone(Sets::class, 's_id','s_id')->first();
    }
}
