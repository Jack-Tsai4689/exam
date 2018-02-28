<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Gscs;
use App\Setsque;
use App\Ques;
class Sets extends Model
{
    public $timestamps = false;
    protected $table = 'sets';
    protected $primaryKey = 's_id';
    protected $fillable = [
    	's_name',
    	's_intro',
    	's_owner',
    	's_begtime',
    	's_endtime',
    	's_limtime',
    	's_finish',
    	's_again',
    	's_gra',
    	's_subj',
    	's_pass_score',
    	's_sum',
    	's_part',
    	's_sub',
    	's_pid',
    	's_percen',
    	's_page',
    	'created_at',
    	'updated_at'
    ];
    //類型
    public function gra(){
        return $this->belongsto(Gscs::class,'s_gra')->select('g_name as name');
    }
    //科目
    public function subj(){
        return $this->belongsto(Gscs::class, 's_subj')->select('g_name as name');
    }
    //大題
    public function sub(){
        return $this->hasMany($this, 's_pid')->select('s_id','s_part','s_intro','s_percen','s_page')->orderby('s_part');
    }
    //大題題目 題目排序需依考卷，而非大題
    //sets show
    public function subque(){
        return $this->hasMany(Setsque::class, 'sq_sid', 's_id')
                    ->join('ques', 'ques.q_id','=','setsque.sq_qid')
                    ->select('ques.q_ans')
                    ->orderby('sq_sort');
    }
}
