<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Gscs;
use Auth;
class TopController extends Controller
{
    protected $menu_user = null;
    protected $login_user = null;
    protected $login_type = null;

    public function __construct(){
    	$this->middleware('auth');
    	if (Auth::check()){
	    	$user = Auth::user();
            if ($user->e_ident==="T"){
                $log_dpname = "老師";
                $this->login_user = $user->e_epno;
                $this->menu_user = $user->e_epname.$log_dpname; 
            }else{
                $log_dpname = "學生";
                $this->login_user = $user->st_no;
                $this->menu_user = $user->st_name.$log_dpname; 
            }
            $this->login_type = $user->e_ident;
		}
    }
    //取得類別
    protected function grade(){
        return Gscs::where('g_graid', 0)->where('g_subjid', 0)->get()->all();
    }
    //取得科目
    protected function subject($graid){
        return Gscs::where('g_graid', $graid)->where('g_subjid', 0)->get()->all();
    }
    //取得章節
    protected function chapter($graid, $subjid){
        return Gscs::where('g_graid', $graid)->where('g_subjid', $subjid)->get()->all();
    }
}
