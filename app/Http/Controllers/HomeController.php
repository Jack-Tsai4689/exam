<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Employes;
use App\Stus;
use Input;
use Validator;
use Cookie;
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
        $accname = Cookie::get('id');
        $rem_chk = (empty($accname)) ? '':'checked';
        return view('auth.goldlogin', [
            'accname' => $accname,
            'rem_chk' => $rem_chk
        ]);
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
                        session()->put('ident', 'T');
                        session()->put('epno',  $input['accname']);
                        session()->put('epname', $user->e_epname);
                    }
                    break;
                case 'S':
                    $user = Stus::where('st_no', $input['accname'])->first();
                    if ($user!=null){
                        session()->put('ident', 'S');
                        session()->put('epno',  $input['accname']);
                        session()->put('epname', $user->st_name);
                    }
                    break;
            }
            if ($user!=null){
                if (request()->has('remember')){
                    Cookie::queue('id', $input['accname'], 1440);
                }else{
                    Cookie::queue('id', $input['accname'], -1440);
                }                
                if ($input['identity']==='T')return redirect('/sets');
                if ($input['identity']==="S")return redirect('/exam');
            }else{
                // return redirect('/login');
                return redirect()->back()->withInput([
                    'accname' => request()->old('accname')
                ])->with('msg','登入失敗');
            }
        }
    }
    public function main(){
        if (!empty(session('ident'))){
            if (session('ident')==="T")return redirect('/sets');
            if (session('ident')==="S")return redirect('/exam');    
        }else{
            return redirect('/login');
        }
        // if (Auth::check()){
        //     if (Auth::user()->e_ident==="T")return redirect('/sets');
        //     if (Auth::user()->e_ident==="S")return redirect('/exam');
        // }else{
        //     return redirect('/login');
        // }
    }
    public function logout(){
        // Auth::logout();
        session()->forget('ident');
        session()->forget('epno');
        session()->forget('epname');
        session()->flush();
        return redirect('/');
    }
}
