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
    public function sub_ques_ans(){
        $ques = $this->hasMany(ExamDetail::class, 'ed_eid','e_id')
                     ->join('pubsque', function($join){
                        $join->on('pubsque.pq_part','=','exam_details.s_id')
                             ->on('pubsque.pq_qid','=','exam_details.ed_qid')
                             ->on('pubsque.pq_sort','=','exam_details.ed_sort');
                     })
                     ->select('ed_eid','ed_qid','ed_ans','ed_right','ed_sort','pubsque.*')
                     ->orderby('ed_sort')->get()->all();
        $data = array();
        foreach ($ques as $q) {
                $tmp = new \stdclass;
                $tmp->qno = $q->ed_sort;
                //題目
                $qcont = array();
                if (!empty($q->pq_quetxt))$qcont[] = nl2br(trim($q->pq_quetxt));
                if (!empty($q->pq_qm_src)){
                    if (is_file($q->pq_qm_src))$qcont[] = '<img class="pic" src="'.URL::asset($q->pq_qm_src).'">';
                }
                if (!empty($q->pq_qs_src)){
                    if (is_file($q->pq_qs_src))$qcont[] = '<audio preload>
                                <source src="'.URL::asset($q->pq_qs_src).'" type="audio/mpeg">
                                <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                              </audio>';
                }
                $tmp->qcont = implode("<br>", $qcont);
                $ans_html = '';
                $my_ans = '';
                //題型、答案
                switch ($q->pq_quetype) {
                    case "S": 
                        $ans_html = chr($q->pq_ans+64);
                        if (!empty($q->ed_ans))$my_ans = chr($q->ed_ans+64);
                        break;
                    case "D": 
                        $ans = array();
                        $ans = explode(",", $q->pq_ans);
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
                        $ans_html = ($q->pq_ans==="1") ? "O":"X";
                        if (!empty($q->ed_ans))$my_ans = ($q->ed_ans==="1") ? "O":"X";
                        break;
                    case "M": 
                        $ans = array();
                        $ans = explode(",", $q->pq_ans);
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
                if (!empty($q->pq_anstxt)) $acont[] = nl2br(trim($q->pq_anstxt));
                //詳解圖檔
                if(!empty($q->pq_am_src)){
                        if (is_file($q->pq_am_src))$acont[] = '<IMG class="pic"  src="'.URL::asset($q->pq_am_src).'">';
                }
                $amedia = array();
                //詳解聲音檔
                if(!empty($q->pq_as_src)){
                    if(is_file($q->pq_as_src)){
                        $amedia[] = '<audio controls preload oncontextmenu="return false;">
                        <source src="'.URL::asset($q->pq_as_src).'" type="audio/mpeg">
                        <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                      </audio>';
                    }else{
                        $amedia[] = '<font color="red">詳解音訊遺失</font>';
                    }
                }
                //詳解影片檔
                if(!empty($q->pq_av_src)){
                    if(is_file($q->pq_av_src)){
                        $amedia[] = '<video controls preload oncontextmenu="return false;">
                        <source src="'.URL::asset($q->pq_av_src).'" type="video/mpeg">
                        <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                      </video>';
                    }else{
                        $amedia[] = '<font color="red">詳解視訊遺失</font>';
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
        return $this->hasone(Pubs::class, 'p_id','s_id')->first();
    }
    //關聯學生
    public function stu(){
        return $this->hasone(Stus::class, 'st_no', 'e_stu')->select('st_name as name')->first();
    }
}
