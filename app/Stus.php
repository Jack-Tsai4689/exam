<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Stus extends Model implements Authenticatable
{
	use AuthenticableTrait;
    public $timestamps = false;
    protected $table = 'stus';
    protected $primaryKey = 'st_id';
    protected $fillable = [
    	'st_no',
    	'st_name'
    ];
}