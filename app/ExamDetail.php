<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use URL;

class ExamDetail extends Model
{
	public $timestamps = false;
	protected $table = 'exam_details';
	protected $primaryKey = 'ed_id';
	protected $fillable = [
		's_id',
		'ed_eid',
		'ed_sort',
		'ed_qid',
		'ed_ans',
		'ed_times',
		'ed_right'
	];
	
	//大題題目
    public function que(){
        return $this->hasOne(Pubsque::class, 'pq_qid','ed_qid')->select('*');
    }

	public function chap(){
		return $this->belongsto(Gscs::class, 'pq_chap')->select('g_name as name')->first();
	}
	// 格式化
	public function info_format(){
		$data = new \stdclass;
		$data->ans = '';
		$my_ans = '';
		switch ($this->pq_quetype) {
            case "S": 
                $ans_html = chr($this->pq_ans+64);
                if (!empty($this->ed_ans))$my_ans = chr($this->ed_ans+64);
                break;
            case "D": 
                $ans = array();
                $ans = explode(",", $this->pq_ans);
                $qans = array();
                foreach ($ans as $o) {
                    $qans[] = chr($o+64);
                }
                $ans_html = implode(", ", $qans);
                if (!empty($this->ed_ans)){
                        $ans = array();
                        $ans = explode(",", $this->ed_ans);
                        $qans = array();
                        foreach ($ans as $o) {
                            $qans[] = chr($o+64);
                        }
                        $my_ans = implode(", ", $qans);
                    }
                break;
            case "R": 
                $ans_html = ($this->pq_ans==="1") ? "O":"X";
                if (!empty($this->ed_ans))$my_ans = ($this->ed_ans==="1") ? "O":"X";
                break;
            case "M": 
                $ans = array();
                $ans = explode(",", $this->pq_ans);
                $qans = array();
                foreach ($ans as $o) {
                    if (!preg_match("/^[0-9]*$/", $o)){
                        $qans[] = ($o==="a") ? '-':'±';
                    }else{
                        $qans[] = $o;
                    }
                }
                $ans_html = implode(", ", $qans);
                if (!empty($this->ed_ans)){
                        $ans = array();
                        $ans = explode(",", $this->ed_ans);
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
        //題目
        $qcont = array();
        if (!empty($this->pq_quetxt))$qcont[] = nl2br(trim($this->pq_quetxt));
        if (!empty($this->pq_qm_src)){
            if (is_file($this->pq_qm_src))$qcont[] = '<img class="pic" src="'.URL::asset($this->pq_qm_src).'" width="100%">';
        }
        if (!empty($this->pq_qs_src)){
            if (is_file($this->pq_qs_src)){
                $qcont[] = '題目音訊 qrcode';
            }else{
                $qcont[] = '<font color="red">qrcode</font>';
            }
        }        
        //詳解
        $acont = array();
        //詳解文字
        if (!empty($this->pq_anstxt)) $acont[] = nl2br(trim($this->pq_anstxt));
        //詳解圖檔
        if(!empty($this->pq_am_src)){
                if (is_file($this->pq_am_src))$acont[] = '<IMG class="pic"  src="'.URL::asset($this->pq_am_src).'" width="100%">';
        }
        $amedia = array();
        //詳解聲音檔
        if(!empty($this->pq_as_src)){
            if(is_file($this->pq_as_src)){
                $amedia[] = 'qrcode';
            }else{
                $amedia[] = '<font color="red">qrcode</font>';
            }
        }
        //詳解影片檔
        if(!empty($this->pq_av_src)){
            if(is_file($this->pq_av_src)){
                $amedia[] = 'qrcode';
            }else{
                $amedia[] = '<font color="red">qrcode</font>';
            }
        }
        $acont[] = implode(' | ', $amedia);

        $data->ans = $ans_html;
		$data->myans = $my_ans;
		$data->right_pic = (!$this->ed_right) ? '/img/icon_op_f.png':'/img/icon_op_t.png';
		$data->qcont = implode("<br>", $qcont);
        $data->acont = '<strong>詳解</strong><br>'.implode("<br>", $acont);
        $know = '';
        if ($this->pq_know>0){
        	$q_know = $this->belongsto(Knows::class, 'pq_know', 'k_id')->select("k_content","k_picpath")->first();
        	$kcont = array();
        	if (!empty($q_know->k_content))$kcont[] = nl2br(trim($q_know->k_content));
        	if (!empty($q_know->k_picpath)){
        		if (is_file($q_know->k_picpath))$kcont[] = '<img class="pic" src="'.URL::asset($q_know->k_picpath).'" width="100%">';
        	}
        	$know = '<strong>知識點</strong><br>'.implode("<br>", $kcont);
        }
        $data->know = $know;
        return $data;
	}
}
