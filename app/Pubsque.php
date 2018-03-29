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
		'pq_updated_at',
		'pq_know',
		'pq_cgroup',
		'pq_cans',
		'pq_cmatch',
		'pq_qm_url',
		'pq_qs_url',
		'pq_am_url',
		'pq_as_url',
		'pq_av_url'
    ];
    // pq_cgroup, pq_cans, pq_cmatch 配合題用
 	public function knows(){
		return $this->belongsto(Knows::class, 'pq_know')->select('k_name as name');
	}
}
