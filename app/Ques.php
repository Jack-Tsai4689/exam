<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Gscs;
use App\Knows;
class Ques extends Model
{
	public $timestamps = false;
	protected $table = 'ques';
	protected $primaryKey = 'q_id';
	protected $fillable = [
		'q_quetype',
		'q_quetxt',
		'q_qm_src',
		'q_qm_name',
		'q_qs_src',
		'q_qs_name',
		'q_ans',
		'q_anstxt',
		'q_am_src',
		'q_am_name',
		'q_as_src',
		'q_as_name',
		'q_av_src',
		'q_av_name',
		'q_owner',
		'q_degree',
		'q_gra',
		'q_subj',
		'q_chap',
		'q_created_at',
		'q_updated_at',
		'q_keyword',
		'q_know',
		'q_num',
		'q_cgroup', // 配合題用
		'q_cans', // 配合題用
		'q_cmatch', // 配合題用
		'q_qm_url',
		'q_qs_url',
		'q_am_url',
		'q_as_url',
		'q_av_url',
		'q_pid', // 題組用
		'q_gsc_allset' // 題組用
	];
	public function gra(){
		return $this->belongsto(Gscs::class, 'q_gra')->select('g_name as name');
	}
	public function subj(){
		return $this->belongsto(Gscs::class, 'q_subj')->select('g_name as name');
	}
	public function chap(){
		return $this->belongsto(Gscs::class, 'q_chap')->select('g_name as name');
	}
	public function knows(){
		return $this->belongsto(Knows::class, 'q_know')->select('k_name as name');
	}
	// public function typeG_subs(){
	// 	return $this->hasMany($this, 'q_pid','q_id')->count();
	// }
	// 題組小題 題目
	public function typeG_data(){
		return $this->hasMany($this, 'q_pid', 'q_id')->select('*')->get()->all();
	}
}
