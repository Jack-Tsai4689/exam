<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Employes;
use Input;
use Validator;
use Auth;
use Session;

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
            $user = Employes::where('e_epno', $input['accname'])
                            ->where('e_ident', $input['identity'])
                            ->where('e_pwd', $input['pwd'])->first();
            if ($user!=null){
                Auth::login($user);
                return redirect('/sets');
            }
        }
    }
    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
