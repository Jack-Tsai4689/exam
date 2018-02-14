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
}
