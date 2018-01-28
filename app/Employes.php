<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Employes extends Model implements Authenticatable
{
    use AuthenticableTrait;
    public $timestamps = false;
    protected $table = 'employes';
    protected $primaryKey = 'e_id';
    protected $fillable = [
    	'e_epno',
    	'e_epname',
    	'e_ident',
    	'e_groupid',
    	'e_pwd',
    	'e_sex',
    	'e_email',
    	'e_webid'
    ];
}
