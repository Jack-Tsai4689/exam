<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pubsque extends Model
{
    public $timestamps = false;
    protected $table = 'pubsque';
    protected $primaryKey = 'pq_id';
    protected $fillable = [
    	'pq_pid',
    	'pq_part',
    	'pq_sort',
    	'pq_qid',
		'pq_ans',
		'pq_num',
		'pq_quetype',
		'pq_quetxt',
		'pq_qm_src',
		'pq_qm_name',
		'pq_qs_src',
		'pq_qs_name',
		'pq_anstxt',
		'pq_am_src',
		'pq_am_name',
		'pq_as_src',
		'pq_as_name',
		'pq_av_src',
		'pq_av_name',
		'pq_degree',
		'pq_gra',
		'pq_subj',
		'pq_chap',
		'pq_created_at',
		'pq_updated_at'
    ];
}
