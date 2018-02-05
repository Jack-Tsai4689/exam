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
		'q_num'
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
}
