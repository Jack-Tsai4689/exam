<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Gscs;

class BasicController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        parent::__construct();
    }
    public function index()
    {
        if (!$this->login_status) return redirect('/login');
        $grade_data = $this->grade();
        return view('basic.index', [
            'menu_user' => $this->menu_user,
            'title' => '基本設定',
            'Grade' => $grade_data

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        if (!$this->login_status) abort(401);
        $error = false;
        $type = ($req->has('type')) ? $req->input('type'):'';

        if (empty($type))$error = true;
        if ($error)abort(400);
        $data = new Gscs;
        $data->g_owner = $this->login_user;
        $data->created_at = time();
        $data->updated_at = time();
        switch ($type) {
            case 'gra': //新增類別
                $gra_name = ($req->has('graname')) ? $req->input('graname'):'';
                if (empty($gra_name))$error = true;
                if ($error)abort(400);
                $data = new Gscs;
                $data->g_name = $gra_name;
                $data->save();
                $grade_data = $this->grade();
                $rs_data = array();
                foreach ($grade_data as $v) {
                    $tmp = new \stdClass;
                    $tmp->ID = $v->g_id;
                    $tmp->NAME = $v->g_name;
                    $tmp->OWNER = $v->g_owner;
                    $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                    array_push($rs_data, $tmp);
                }
                unset($grade_data);
                break;
            case 'subj': //新增科目
                $subj_name = ($req->has('subjname')) ? $req->input('subjname'):'';
                $g = ($req->has('g')) ? (int)$req->input('g'):0;
                if (empty($subj_name) || $g===0)$error = true;
                if ($error)abort(400);
                $data->g_name = $subj_name;
                $data->g_graid = $g;
                $data->save();
                $subj_data = $this->subject($g);
                $rs_data = array();
                foreach ($subj_data as $v) {
                    $tmp = new \stdClass;
                    $tmp->ID = $v->g_id;
                    $tmp->NAME = $v->g_name;
                    $tmp->OWNER = $v->g_owner;
                    $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                    array_push($rs_data, $tmp);
                }
                unset($subj_data);
                break;
            case 'chap': //新增章節
                $chap_name = ($req->has('chapname')) ? $req->input('chapname'):'';
                $g = ($req->has('g')) ? (int)$req->input('g'):0;
                $s = ($req->has('s')) ? (int)$req->input('s'):0;
                if (empty($chap_name) || $g===0 || $s===0)$error = true;
                if ($error)abort(400);
                $data->g_name = $chap_name;
                $data->g_graid = $g;
                $data->g_subjid = $s;
                $data->save();
                $chap_data = $this->chapter($g, $s);
                $rs_data = array();
                foreach ($chap_data as $v) {
                    $tmp = new \stdClass;
                    $tmp->ID = $v->g_id;
                    $tmp->NAME = $v->g_name;
                    $tmp->OWNER = $v->g_owner;
                    $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                    array_push($rs_data, $tmp);
                }
                unset($chap_data);
                break;
            case 'ugra':
                // $gra_id = ($req->has('ugraid') && (int)$req->input('ugraid')>0) ? (int)$req->input('ugraid'):0;
                // $gra_name = ($req->has('ugraname') && (int)$req->input('ugraname')>0) ? (int)$req->input('ugraname'):0;
                // if ($gra_id<=0 || empty($gra_name))abort(400);
                // $gra = Gscs::find($gra_id);
                // $gra->g_name = $gra_name;
                // $gra->save();
                // $grade_data = $this->grade();
                // $rs_data = array();
                // foreach ($grade_data as $v) {
                //     $tmp = new \stdClass;
                //     $tmp->ID = $v->g_id;
                //     $tmp->NAME = $v->g_name;
                //     $tmp->OWNER = $v->g_owner;
                //     $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                //     array_push($rs_data, $tmp);
                // }
                // unset($grade_data);
                break;
            case 'usubj':
                // $subj_id = ($req->has('usubjid') && (int)$req->input('usubjid')>0) ? (int)$req->input('usubjid'):0;
                // $subj_name = ($req->has('usubjname') && (int)$req->input('usubjname')>0) ? (int)$req->input('usubjname'):0;
                // if ($subj_id<=0 || empty($subj_name))abort(400);
                // $subj = Gscs::find($subj_id);
                // $subj->g_name = $subj_name;
                // $subj->save();
                // $subj_data = $this->grade();
                // $rs_data = array();
                // foreach ($subj_data as $v) {
                //     $tmp = new \stdClass;
                //     $tmp->ID = $v->g_id;
                //     $tmp->NAME = $v->g_name;
                //     $tmp->OWNER = $v->g_owner;
                //     $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                //     array_push($rs_data, $tmp);
                // }
                // unset($subj_data);
                break;
            case 'uchap':
                // uchapid
                // uchapname
                break;
        }
        return response()->json($rs_data);
    }
    public function ajshow(Request $req){
        if (!$this->login_status)abort(401);
        $error = false;
        $type = ($req->has('type')) ? $req->input('type'):'';
        if (empty($type))abort(400);
        switch ($type) {
            case 'subj':
                $g = ($req->has('g')) ? (int)$req->input('g'):0;
                if ($g===0)abort(400);
                $subj_data = $this->subject($g);
                $rs_data = array();
                foreach ($subj_data as $v) {
                    $tmp = new \stdClass;
                    $tmp->ID = $v->g_id;
                    $tmp->NAME = $v->g_name;
                    $tmp->OWNER = $v->g_owner;
                    $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                    array_push($rs_data, $tmp);
                }
                if (empty($rs_data))abort(400);
                unset($subj_data);
                break;
            case 'chap':
                $g = ($req->has('g')) ? (int)$req->input('g'):0;
                $s = ($req->has('s')) ? (int)$req->input('s'):0;
                if ($g===0 || $s===0)abort(400);
                $chap_data = $this->chapter($g, $s);
                $rs_data = array();
                foreach ($chap_data as $v) {
                    $tmp = new \stdClass;
                    $tmp->ID = $v->g_id;
                    $tmp->NAME = $v->g_name;
                    $tmp->OWNER = $v->g_owner;
                    $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
                    array_push($rs_data, $tmp);
                }
                if (empty($rs_data))abort(400);
                unset($chap_data);
                break;
        }
        return response()->json($rs_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
