<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
                'e_begtime_at',
                'e_endtime_at',
                'e_used_time',
                'e_rnum',
                'e_wnum',
                'e_nnum',
                'e_score'
	];

}
