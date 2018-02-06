<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Employes;
use App\Stus;
use Input;
use Validator;
// use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.goldlogin');
    }
    public function login(){
        $input = Input::all();
        $rule = [
            'code' => 'required|max:4',
            'accname' => 'required',
            'pwd' => 'required',
            'identity' => 'required'
        ];
        $validator = Validator::make($input, $rule);
        if ($validator->passes()){
            $user = null;
            switch ($input['identity']) {
                case 'T':
                    $user = Employes::where('e_epno', $input['accname'])->where('e_pwd', $input['pwd'])->first();
                    if ($user!=null){
                        session()->put('ident'=>'T');
                        session()->
                    }
                    break;
                case 'S':
                    $user = Stus::where('st_no', $input['accname'])->first();
                    if ($user!=null)$user->e_ident = "S";
                    break;
                default:
                    abort(400);
                    break;
            }
            if ($user!=null){
                Auth::login($user);
                $b = Auth::user();
                //dd($b);
                if ($input['identity']==='T')return redirect('/sets');
                if ($input['identity']==="S")return redirect('/exam');
            }else{
                return redirect('/login');
            }
        }
    }
    public function main(){
        if (Auth::check()){
            if (Auth::user()->e_ident==="T")return redirect('/sets');
            if (Auth::user()->e_ident==="S")return redirect('/exam');
        }else{
            return redirect('/login');
        }
    }
    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
