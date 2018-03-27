<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Services\ApiService;
class ApiController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService){
    	$this->apiService = $apiService;
    }
    // L特定班別清單
    public function getLclass(){
    	$_get = request()->all();
    	if (empty($_get))exit;
    	$cid = (request()->has('c') && !empty(request()->input('c'))) ? trim(request()->input('c')):0;
    	if (!preg_match("/^[0-9]*$/", $cid))abort(400);
    	$cid = (int)$cid;
    	if ($cid<1)abort(400);
    	$this->apiService->L_init('118.163.21.147');
        return $this->apiService->get_Lclass_only($cid);
    }
}
