<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Gscs;
// use Auth;
class TopController extends Controller
{
    protected $menu_user = null;
    protected $login_user = null;
    protected $login_status = false;
    protected $login_type = null;

    protected $prev_page = '';
    protected $next_page = '';
    protected $group_page = '';
    protected $curl_url = '';

    public function __construct(){
    	if (!empty(session('ident'))){
          $this->login_status = true;
          $this->login_type = session('ident');
            if (session('ident')==='T')$log_dpname = "老師";
            if (session('ident')==='S')$log_dpname = "同學";
            $this->login_user = session('epno');
            $this->menu_user = session('epname').$log_dpname;
        }
  //   	if (Auth::check()){
	 //    	$user = Auth::user();
  //           if ($user->e_ident==="T"){
  //               $log_dpname = "老師";
  //               $this->login_user = $user->e_epno;
  //               $this->menu_user = $user->e_epname.$log_dpname; 
  //           }else{
  //               $log_dpname = "學生";
  //               $this->login_user = $user->st_no;
  //               $this->menu_user = $user->st_name.$log_dpname; 
  //           }
  //           $this->login_type = $user->e_ident;
		// }
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
    //分頁
    protected function page_info($curr, $last, $total){
      if ($total>10){
        $prev_happened = (($curr-1)>=1) ? 'onclick="gp('.($curr-1).')"':'style="visibility: hidden;"';
        $this->prev_page = '<input type="button" '.$prev_happened.' value="上一頁">';

        $next_happened = (($curr+1)<=$last) ? 'onclick="gp('.($curr+1).')"':'style="visibility: hidden;"';
        $this->next_page = '<input type="button" '.$next_happened.' value="下一頁">';
            
        $p = 1;
        while($p<=$last){
            $sel_page = ($curr===$p) ? 'selected':'';
            $this->group_page.= '<option '.$sel_page.' value="'.$p.'">第'.$p.'頁</option>';
            $p++;
        }
      }else{
        $this->group_page = '<option value="1">第1頁</option>';
      }
    }
    protected function url_curl($url){
      $ch = curl_init();
      $options = array(
        CURLOPT_URL=>$address,
        CURLOPT_HEADER=>0,
        CURLOPT_RETURNTRANSFER=>1
        //CURLOPT_TIMEOUT_MS=> 3000 //逾時處理
      );
      curl_setopt_array($ch, $options);
      $data = curl_exec($ch);
      $error = curl_errno($ch);
      curl_close($ch);
      return json_decode($data);
   }
}
