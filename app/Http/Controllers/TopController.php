<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use Session;
use Auth;
class TopController extends Controller
{
    protected $menu_user = null;
    protected $login_user = null;

    public function __construct(){
    	$this->middleware('auth');
    	if (Auth::check()){
	    	$user = Auth::user();
	    	$this->login_user = $user;
			$log_dpname = ($user->e_ident==="T") ? "老師":"學生";
			$this->menu_user = $user->e_epname.$log_dpname;	
		}
    }
}
