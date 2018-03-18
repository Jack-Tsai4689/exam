<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//發佈的測驗
class Pubs extends Model
{
    public $timestamps = false;
    protected $table = 'pubs';
    protected $primaryKey = 'p_id';
    protected $fillable = [
    	's_id',
		'p_name',
		'p_intro',
		'p_owner',
		'p_begtime',
		'p_endtime',
		'p_limtime',
		'p_status',
		'p_again',
		'p_gra',
		'p_subj',
		'p_pass_score',
		'p_sum',
		'p_part',
		'p_sub',
		'p_pid',
		'p_percen',
		'p_page',
		'p_created_at',
		'p_updated_at'
    ];

    public function cas(){
    	return $this->hasMany(Pubcas::class, 'p_id');
    }
    public function gra(){
    	return $this->belongsto(Gscs::class,'p_gra')->select('g_name as name');
    }
    public function subj(){
    	return $this->belongsto(GScs::class,'p_subj')->select('g_name as name');
    }
    public function sub(){
        return $this->hasMany($this, 'p_pid')->select('p_id','p_part','p_intro','p_percen','p_page')->orderby('p_part');
    }
    //大題題目 題目排序需依考卷，而非大題
    //sets show
    public function subque(){
        return $this->hasMany(Pubsque::class, 'pq_part', 'p_id')
                    ->select('pq_sort','pq_qid','pq_ans','pq_quetype','pq_quetxt','pq_qm_src','pq_num','pq_qs_src')
                    ->orderby('pq_sort');
    }
}
