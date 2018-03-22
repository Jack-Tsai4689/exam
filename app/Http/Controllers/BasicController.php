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

        if (empty($type))abort(400);
        $data = array(
            'g_owner' => $this->login_user,
            'created_at' => time(),
            'updated_at' => time()
        );
        switch ($type) {
            case 'gra': //新增類別
                $gra_name = ($req->has('graname') && !empty($req->input('graname'))) ? trim($req->input('graname')):'';
                if (empty($gra_name))abort(400);
                $data['g_name'] = $gra_name;
                Gscs::create($data);
                $grade_data = $this->grade();
                $rs_data = $this->_gsclist_format($grade_data);
                unset($grade_data);
                break;
            case 'subj': //新增科目
                $subj_name = ($req->has('subjname') && !empty($req->input('subjname'))) ? trim($req->input('subjname')):'';
                $g = ($req->has('g') && !empty($req->input('g'))) ? trim($req->input('g')):0;
                if (!preg_match("/^[0-9]*$/", $g))abort(400);
                if (empty($subj_name) || $g<1)abort(400);
                $data['g_name'] = $subj_name;
                $data['g_graid'] = $g;
                Gscs::create($data);
                $subj_data = $this->subject($g);
                $rs_data = $this->_gsclist_format($subj_data);
                unset($subj_data);
                break;
            case 'chap': //新增章節
                $chap_name = ($req->has('chapname') && !empty($req->input('chapname'))) ? trim($req->input('chapname')):'';
                $g = ($req->has('g') && !empty($req->input('g'))) ? trim($req->input('g')):0;
                $s = ($req->has('s') && !empty($req->input('s'))) ? trim($req->input('s')):0;
                if (!preg_match("/^[0-9]*$/", $g))abort(400);
                if (!preg_match("/^[0-9]*$/", $s))abort(400);
                if (empty($chap_name) || $g<1 || $s<1)abort(400);
                $data['g_name'] = $chap_name;
                $data['g_graid'] = $g;
                $data['g_subjid'] = $s;
                Gscs::create($data);
                $chap_data = $this->chapter($g, $s);
                $rs_data = $this->_gsclist_format($chap_data);
                unset($chap_data);
                break;
            case 'ugra':
                $gra_id = ($req->has('ugraid') && !empty($req->input('ugraid'))) ? trim($req->input('ugraid')):0;
                $gra_name = ($req->has('ugraname') && !empty($req->input('ugraname'))) ? trim($req->input('ugraname')):'';
                if (!preg_match("/^[0-9]*$/", $gra_id))abort(400);
                if ($gra_id<1 || empty($gra_name))abort(400);
                Gscs::where('g_id', $gra_id)->update(['g_name'=>$gra_name, 'g_owner' => $this->login_user]);
                $grade_data = $this->grade();
                $rs_data = $this->_gsclist_format($grade_data);
                unset($grade_data);
                break;
            case 'usubj':
                $subj_id = ($req->has('usubjid') && !empty($req->input('usubjid'))) ? trim($req->input('usubjid')):0;
                $g_id = ($req->has('usg') && !empty($req->input('usg'))) ? trim($req->input('usg')):0;
                $subj_name = ($req->has('usubjname') && !empty($req->input('usubjname'))) ? trim($req->input('usubjname')):'';
                if (!preg_match("/^[0-9]*$/", $subj_id))abort(400);
                if (!preg_match("/^[0-9]*$/", $g_id))abort(400);
                if ($subj_id<1 || empty($subj_name))abort(400);
                Gscs::where('g_id', $subj_id)->update(['g_name'=>$subj_name, 'g_owner' => $this->login_user]);
                $subj_data = $this->subject($g_id);
                $rs_data = $this->_gsclist_format($subj_data);
                unset($subj_data);
                break;
            case 'uchap':
                $chap_id = ($req->has('uchapid') && !empty($req->input('uchapid'))) ? trim($req->input('uchapid')):0;
                $g_id = ($req->has('ucg') && !empty($req->input('ucg'))) ? trim($req->input('ucg')):0;
                $s_id = ($req->has('ucs') && !empty($req->input('ucs'))) ? trim($req->input('ucs')):0;
                $chap_name = ($req->has('uchapname') && !empty($req->input('uchapname'))) ? trim($req->input('uchapname')):'';
                if (!preg_match("/^[0-9]*$/", $chap_id))abort(400);
                if (!preg_match("/^[0-9]*$/", $g_id))abort(400);
                if (!preg_match("/^[0-9]*$/", $s_id))abort(400);
                if ($chap_id<1 || $g_id<1 || $s_id<1 || empty($chap_name))abort(400);
                Gscs::where('g_id', $chap_id)->update(['g_name'=>$chap_name, 'g_owner' => $this->login_user]);
                $chap_data = $this->chapter($g_id, $s_id);
                $rs_data = $this->_gsclist_format($chap_data);
                unset($chap_data);
                break;
            default:
                abort(400);
                break;
        }
        return response()->json($rs_data);
    }
    //清單格式化
    private function _gsclist_format($data){
        $rs = array();
        foreach ($data as $v) {
            $tmp = new \stdClass;
            $tmp->ID = $v->g_id;
            $tmp->NAME = $v->g_name;
            $tmp->OWNER = $v->g_owner;
            $tmp->UPDATETIME = date('Y/m/d H:i:s', $v->updated_at);
            array_push($rs, $tmp);
        }
        return $rs;
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
                $rs_data = $this->_gsclist_format($subj_data);
                if (empty($rs_data))abort(400);
                unset($subj_data);
                break;
            case 'chap':
                $g = ($req->has('g')) ? (int)$req->input('g'):0;
                $s = ($req->has('s')) ? (int)$req->input('s'):0;
                if ($g===0 || $s===0)abort(400);
                $chap_data = $this->chapter($g, $s);
                $rs_data = $this->_gsclist_format($chap_data);
                if (empty($rs_data))abort(400);
                unset($chap_data);
                break;
            default:
                abort(400);
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
