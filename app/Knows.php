<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Knows extends Model
{
    public $timestamps = false;
	protected $table = 'knows';
	protected $primaryKey = 'k_id';
	protected $fillable = [
		'k_name',
		'k_pic',
		'k_gra',
		'k_subj',
		'k_chap',
		'k_content',
		'k_owner',
		'created_at',
		'updated_at',
	];

	public function gra(){
		return $this->belongsto('App\Gscs', 'k_gra')->select('g_name as name');
	}
	public function subj(){
		return $this->belongsto('App\Gscs', 'k_subj')->select('g_name as name');
	}
	public function chap(){
		return $this->belongsto('App\Gscs', 'k_chap')->select('g_name as name');
	}
}
