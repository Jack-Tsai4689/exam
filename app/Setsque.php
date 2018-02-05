<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ques;
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
        'sq_owner',
    	'updated_at'
    ];
    //大題題目
    public function que(){
        return $this->hasOne(Ques::class, 'q_id','sq_qid');
    }
}
