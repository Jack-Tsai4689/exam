<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    //考題來源表
    public function ques_source(){
    	return $this->hasOne(Pubsque::class, 'pq_qid','ed_qid')->select('q_chap','q_quetype','q_degree','q_ans','q_chap','q_id')->first();
    }
	public function chap(){
		return $this->belongsto(Gscs::class, 'pq_chap')->select('g_name as name')->first();
	}
}
