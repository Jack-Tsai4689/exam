<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setsque extends Model
{
    public $timestamps = false;
    protected $table = 'setsque';
    protected $primaryKey = 'sq_id';
    protected $fillable = [
    	'sq_sid',
    	'sq_part',
    	'sq_qid',
    	'sq_sort',
    	'update_at'
    ];

    public function que(){
        return $this->hasOne('App\Ques','q_id');
    }
}
