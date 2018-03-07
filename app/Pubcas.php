<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pubcas extends Model
{
    public $timestamps = false;
    protected $table = 'pubcas';
    protected $primaryKey = 'pc_id';
    protected $fillable = [
    	'p_id',
		'pc_class',
		'pc_classa',
		'pc_webid',
		'pc_again'
    ];
}
