<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pubs extends Model
{
    public $timestamps = false;
    protected $table = 'pubs';
    protected $primaryKey = 'p_id';
    protected $fillable = [
    	's_id',
		'p_owner',
		'p_begtime',
		'p_endtime',
		'p_created_at',
		'p_updated_at',
		'p_limtime',
		'p_finish',
		'p_again',
		'p_pass_score',
		'p_sum'
    ];

    public function cas(){
    	return $this->hasMany(Pubcas::class, 'p_id');
    }
}
