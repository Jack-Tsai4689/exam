<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function gra(){
        return $this->belongsto('App\Gscs','s_gra')->select('g_name as name');
    }
    public function subj(){
        return $this->belongsto('App\Gscs', 's_subj')->select('g_name as name');
    }
    public function sub(){
        return $this->belongsto('App\Sets', 's_pid');
    }
}
