<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gscs extends Model
{
    public $timestamps = false;
    protected $table = 'gscs';
    protected $primaryKey = 'g_id';
    protected $fillable = [
    	'g_graid',
    	'g_subjid',
    	'g_name',
    	'g_owner',
    	'created_at',
    	'updated_at'
    ];
}
