<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Sets;
use Input;

class SetsController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $get = Input::all();
        $gra_id = 0;
        $subj_id = 0;
        if (!empty($get)){
            $gra_id = (int)Input::get('f_grade');
            $subj_id = (int)Input::get('f_subject');
        }
        $sets = new Sets;
        $sets = $sets->where('s_pid',0);
        if ($gra_id>0)$sets = $sets->where('s_gra', $gra_id);
        if ($subj_id>0)$sets = $sets->where('s_subj', $subj_id);
        $sets_data = $sets->get();
        $gra = $this->grade();
        $grade_data = '';
        $subj_data = '';
        $g_data = false;
        if ($gra!=null){
            $gsel = '';
            foreach ($gra as $v) {
                if ($gra_id===$v->g_id){
                    $gsel = 'selected';
                    $g_data = true;
                }else{
                    $gsel = '';
                }
                $grade_data.= '<option '.$gsel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($g_data){
            $ssel = '';
            $subj = $this->subject($gra_id);
            foreach ($subj as $v) {
                $ssel = ($subj_id===$v->g_id) ? 'selected':'';
                $subj_data.= '<option '.$ssel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($sets_data!=null){
            foreach ($sets_data as $k => $v) {
                $sets_data[$k]->s_again = ($v->s_again) ? "O":"X";
                $sets_data[$k]->updated_at = date('Y/m/d H:i:s', $v->updated_at);
                $sets_data[$k]->time = (!empty($v->s_begtime)) ? $v->s_begtime.' - '.$v->s_endtime:'不限';
            }
        }
        return view('sets.index', [
            'menu_user' => $this->menu_user,
            'title' => '考卷列表',
            'Grade' => $grade_data,
            'Subject' => $subj_data,
            'Data' => $sets_data,
            'Prev' => '',
            'Next' => '',
            'Pg' => ''
        ]);

        // $page = (isset($_GET['p']) && (int)$_GET['p']>0) ? (int)$_GET['p']:1;
        // $this->_page($page);
        // $this->load->model("SetsModel");
        // $sets_row = $this->SetsModel->tea_sets_row(array($_SESSION['gold']->epno));
        // $sets_data = $this->SetsModel->tea_sets_data(array($_SESSION['gold']->epno, $this->_pstart(), $this->_pend()));
        // //年級、科目 篩選條件
        // $this->load->model("BasicModel");
        // $gra_html = '';
        // $subj_html = '';
        // $grade_data = $this->BasicModel->get_grade();
        // if (!empty($grade_data)){
        //     foreach ($grade_data as $v) {
        //         $gra_html.= '<option value="'.$v->ID.'">'.$v->NAME.'</option>';
        //     }
        //     $subject_data = $this->BasicModel->get_subject(array($grade_data[0]->ID));
        //     if (!empty($subject_data)){
        //         foreach ($subject_data as $v) {
        //             $subj_html.= '<option value="'.$v->ID.'">'.$v->NAME.'</option>';
        //         }
        //     }
        // }
        // $pagegroup = ceil($sets_row/$this->_prow());
        // $prev = '';
        // $next = '';
        // if ($page>1)$prev = '<input type="button" class="btn btn-default" onclick="page('.($page-1).')" value="上一頁">';
        // if ($pagegroup>$page)$next = '<input type="button" class="btn btn-default" onclick="page('.($page+1).')" value="下一頁">';
        // $pg = '';
        // for ($i = 1; $i<=$pagegroup;$i++){
        //     $pg.='<option value="'.$i.'">'.$i.'</option>';
        // }
        // $c_id = array();
        // $c_name = array();
        // $ca_id = array();
        // $ca_name = array();
        // $gra_id = array();
        // $gra_name = array();
        // $subj_id = array();
        // $subj_name = array();
        // $this->load->library('gold');
        // $this->gold = new gold;
        // $this->load->model('AuthModel');
        // $this->AuthModel->Set_db($_SESSION['gold']->code);
        // $user = $this->AuthModel->user_info(array($_SESSION['gold']->epno));
        // $this->gold->Web_init($_SESSION['gold']->code, $_SESSION['gold']->epno, $user->PASS, $_SESSION['gold']->ident, $user->WEBID);
        // foreach ($sets_data as $k => $v) {
        //     //if ($v->WEBSETID>0){
        //         $gca = $this->SetsModel->sets_gca_showone(array($v->ID));
        //         $sets_data[$k]->cname = '';
        //         if ($gca!=null){
        //             if (in_array($gca->CID, $c_id)){
        //                 $sets_data[$k]->cname = $c_name[array_search($gca->CID, $c_id)];
        //             }else{
        //                 $c_id[] = $gca->CID;
        //                 $cdata = $this->gold->get_Class_only($gca->GID, $gca->CID);
        //                 $c_name[] = $cdata->Data[0]->name;
        //                 $sets_data[$k]->cname = $cdata->Data[0]->name;
        //             }
        //         }
        //     //} 
        // }
        // $this->gold = null;
        // $this->load->view('_header', array(
        //     'ident' => $this->dp_info,
        //     'title' => '考卷列表'
        // ));
        // $this->load->view('sets/index', array(
        //     'Grade' => $gra_html,
        //     'Subject' => $subj_html,
        //     'Data' => $sets_data,
        //     'Prev' => $prev,
        //     'Next' => $next,
        //     'Pg' => $pg
        // ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //考試時間
        $Time = new \stdClass;
        $Time->begdate = date('Y/m/d');
        $Time->enddate = date('Y/m/d');
        $Time->begTimeH = '';
        $Time->endTimeH = '';
        $enh = 23;
        for($i=0;$i<24;$i++){
            $h = str_pad($i,2,0,STR_PAD_LEFT);
            $ehs = ($enh == $i) ? 'selected':'';
            $Time->begTimeH.= '<option value="'.$i.'">'.$h.'</option>';
            $Time->endTimeH.= '<option value="'.$i.'"'.$ehs.'>'.$h.'</option>';
        }
        //考試限時
        $Lim = new \stdClass;
        $Lim->limTimeH = 1;
        $Lim->limTimeM = 0;
        $Lim->limTimeS = 0;
        $lh = 1;
        for($i=0;$i<24;$i++){
            $limh = ($lh == $i) ? 'selected':'';
            $Lim->limTimeH.= '<option value="'.$i.'"'.$limh.'>'.$i.'</option>';
        }
        $lm = 0;
        for($i=0;$i<60;$i++){
            $m = str_pad($i,2,0,STR_PAD_LEFT);
            $limm = ($lm == $i) ? 'selected':'';
            $Lim->limTimeM.= '<option value="'.$i.'"'.$limm.'>'.$m.'</option>';
            $Lim->limTimeS.= '<option value="'.$i.'"'.$limm.'>'.$m.'</option>';
        }
        $gra_html = '';
        $subj_html = '';
        $grade_data = $this->grade();
        $subject_data = array();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $gra_html.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $subject_data = $this->subject($grade_data[0]->g_id);
        }
        if (!empty($subject_data)){
                foreach ($subject_data as $v) {
                    $subj_html.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
                }
        }
        return view('sets.create', [
            'menu_user' => $this->menu_user,
            'title' => '新增考卷',
            'Time' => $Time,
            'Lim' => $Lim,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Sum' => 100,
            'Pass' => 60
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $chk_date = ($req->has('chk_date')) ? (int)$req->input('chk_date'):0;
        $s_name = ($req->has('setsname')) ? $req->input('setsname'):'';
        $s_gra = ($req->has('grade')) ? $req->input('grade'):0;
        $s_subj = ($req->has('subject')) ? $req->input('subject'):0;
        if ($chk_date===0 || 
            $s_gra===0 || 
            $s_subj===0 || 
            empty($s_name))abort(400);
        $s_intro = ($req->has('intro')) ? $req->input('intro'):'';
        $data = array();
        $data['s_name'] = $s_name;
        $data['s_intro'] = $s_intro;
        $data['s_begtime'] = '';
        $data['s_endtime'] = '';
        $data['s_gra'] = $s_gra;
        $data['s_subj'] = $s_subj;
        if ($chk_date===1){
            $p_begdate = ($req->has('begdate')) ? trim($req->input('begdate')):'';
            $p_begTimeH = ($req->has('begTimeH')) ? (int)$req->input('begTimeH'):0;
            $p_begTimeH = str_pad($p_begTimeH,2,0,STR_PAD_LEFT);

            $p_enddate = ($req->has('enddate')) ? trim($req->input('enddate')):'';
            $p_endTimeH = ($req->has('endTimeH')) ? (int)$req->input('endTimeH'):0;
            $p_endTimeH = str_pad($p_endTimeH,2,0,STR_PAD_LEFT);
            $data['s_begtime'] = $p_begdate.' '.$p_begTimeH.':00:00';
            $data['s_endtime'] = $p_enddate.' '.$p_endTimeH.':00:00';
        }
        $data['s_sum'] = ($req->has('sum')) ? (int)$req->input('sum'):100;
        $data['s_pass_score'] = ($req->has('passscore')) ? (int)$req->input('passscore'):60;

        //限時
        $lim = array();
        $p_limTimeH = ($req->has('limTimeH')) ? (int)$req->input('limTimeH'):1;
        $lim[] = str_pad($p_limTimeH,2,0,STR_PAD_LEFT);
        $p_limTimeM = ($req->has('limTimeM')) ? (int)$req->input('limTimeM'):0;
        $lim[] = str_pad($p_limTimeM,2,0,STR_PAD_LEFT);
        $p_limTimeS = ($req->has('limTimeS')) ? (int)$req->input('limTimeS'):0;
        $lim[] = str_pad($p_limTimeS,2,0,STR_PAD_LEFT);
        $data['s_limtime'] = implode(":", $lim);
        if ($p_limTimeH<=0 && $p_limTimeM<=0 && $p_limTimeS<=0){
            $this->_errmsg(400);
            return;
        }
        //次數 2=>1次(again=0) 1=>多次
        $p_again = ($req->has('f_times')) ? (int)$req->input('f_times'):2;
        $data['s_again'] = ($p_again===2) ? 0:1;
        
        $data['s_owner'] = $this->login_user;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        //主體
        $ins = new Sets;
        $ins->fill($data);
        $ins->save();
        //大題
        $data['s_name'] = '';
        $data['s_part'] = 1;
        $data['s_pid'] = $ins->s_id;
        $sub_ins = new Sets;
        $sub_ins->fill($data);
        $sub_ins->save();
        return redirect('/sets');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sid)
    {
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
        if ($this->login_type==="S"){
            echo('很抱歉，權限不足以瀏覽');
            return;
        }
        $data = Sets::find($sid);
        //限時
        $lime = explode(":", $data->s_limtime);
        //大題
        if ($data->s_sub){
            $sub = Sets::select('s_id','s_intro','s_percen','s_page')
                    ->where('s_pid', $sid)
                    ->orderby('s_part')->get()->all();    
        }else{
            $sub = array();
        }
        

        $part_button = '';
        $part = '';
        $part_que = '';
        //題目排序用
        $part_array = array();
        // foreach ($sub as $i => $v) {
        //     $j = $i+1;
        //     $now = ($j==1) ? 'now':'';
        //     $print_control = ($v->s_page=='Y')? '可回上頁修改':'不可回上頁修改';
        //     //if ($parent_control!='S')$print_control.= '(考卷控制)';
        //     $sub_intro[] = trim($v->s_intro);
        //     $display_no = ($j>1) ? 'style="display:none;"':'';
        //     $part_button.='<input type="button" class="btn w150 h25 bpart_div '.$now.'>" onclick="view('.$j.')" name="bpart" id="bpart'.$j.'" value="第'.$j.'大題('.$v->s_percen.'%)">';
        //     $part.= '<div name="node" id="'.$v->s_id.'">';
        //     $part.= '<div class="part_sort">: :</div>';
        //     $part.= '<div style="display:inline-block;">';
        //     $part.= '第'.$j.'大題('.$v->s_percen.'%)　'.$print_control;
        //     $part.= '</div>';
        //     $part.= '<img title="刪除" class="sub_del" src="'.URL::asset('img/icon_op_f.png').'" width="15" onclick="del_ask('.$sid.','.$v->ID.','.$j.')">';
        //     $part.= '<div class="sub_intro" name="intro" id="intro'.$i.'">'.nl2br($v->INTRO).'</div>';
        //     $part.= '</div>';
        //     //按扭
        //     $part_que.= '<input type="button" class="btn w100 partq" data-id="'.$v->ID.'" value="第'.$j.'大題">';
        //     //題目排序
        //     $part_array[] = $v->ID;
        // }
        // $qdata = array();
        // $FirstPart = 0;
        // //有大題，先loading
        // if (!empty($sub)){
        //     $FirstPart = $sub[0]->ID;
        // }else{
        //     $que = $this->SetsModel->sub_que(array($sid, 0));
        //     $qdata = $this->_part_que($que);
        // }
        return view('sets.review', [
            'menu_user' => $this->menu_user,
            'title' => $data->s_name.' - 題目預覽',
            'SETID' => $sid,
            'Set_name' => $data->s_name,
            'Sum' => $data->s_sum,
            'Pass' => $data->s_pass_score,
            'Limtime' => (int)$lime[0].'時'.(int)$lime[1].'分'.(int)$lime[2].'秒',
            'Sub' => $data->SUB,
            'Part_btn' => $part_que,
            'Part_cont' => $part,
            'Part' => $sub,
            // 'FirstPart' => $FirstPart,
            // 'Part_ar' => $part_array,
            'Qdata' => array()//$qdata
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($sid)
    {
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        $data = Sets::find($sid);
        //考試時間
        $Time = new \stdClass;
        $Time->begTimeH = '';
        $Time->endTimeH = '';
        $Time->date_Y = '';
        $Time->date_N = '';
        if (!empty($data->s_begtime) && !empty($data->s_endtime)){
            $begdate = explode(" ", $data->s_begtime);
            $enddate = explode(" ", $data->s_endtime);
            $begtime_H = explode(":", $begdate[1]);
            $endtime_H = explode(":", $enddate[1]);
            $beg_H = (int)$begtime_H[0];
            $end_H = (int)$endtime_H[0];
            $Time->begdate = $begdate[0];
            $Time->enddate = $enddate[0];
            $Time->date_Y = 'checked';
        }else{
            $beg_H = 0;
            $end_H = 0;
            $Time->begdate = date('Y/m/d');
            $Time->enddate = date('Y/m/d');
            $Time->date_N = 'checked';
        }
        for($i=0;$i<24;$i++){
            $h = str_pad($i,2,0,STR_PAD_LEFT);
            $bhs = ($beg_H == $i) ? 'selected':'';
            $Time->begTimeH.= '<option value="'.$i.'"'.$bhs.'>'.$h.'</option>';
            $ehs = ($end_H == $i) ? 'selected':'';
            $Time->endTimeH.= '<option value="'.$i.'"'.$ehs.'>'.$h.'</option>';
        }
        //考試限時
        $Lim = new \stdClass;
        $limtime = explode(":", $data->s_limtime);
        $lh = (int)$limtime[0];
        $lm = (int)$limtime[1];
        $ls = (int)$limtime[2];
        $Lim->limTimeH = '';
        $Lim->limTimeM = '';
        $Lim->limTimeS = '';
        for($i=0;$i<24;$i++){
            $h = str_pad($i,2,0,STR_PAD_LEFT);
            $limh = ($lh == $i) ? 'selected':'';
            $Lim->limTimeH.= '<option value="'.$i.'"'.$limh.'>'.$h.'</option>';
        }
        for($i=0;$i<60;$i++){
            $m = str_pad($i,2,0,STR_PAD_LEFT);
            $limm = ($lm == $i) ? 'selected':'';
            $lims = ($ls == $i) ? 'selected':'';
            $Lim->limTimeM.= '<option value="'.$i.'"'.$limm.'>'.$m.'</option>';
            $Lim->limTimeS.= '<option value="'.$i.'"'.$lims.'>'.$m.'</option>';
        }
        //重覆考
        $again = new \stdClass;
        $again->Y = ($data->s_again) ? 'checked':'';
        $again->N = (!$data->s_again) ? 'checked':'';

        $gra_html = '';
        $subj_html = '';
        $grade_data = $this->grade();
        foreach ($grade_data as $v) {
            $g_sel = ($data->s_gra===$v->g_id) ? 'selected':'';
            $gra_html.= '<option '.$g_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
        }

        $subject_data = $this->subject($data->s_gra);
        foreach ($subject_data as $v) {
            $s_sel = ($data->s_subj===$v->g_id) ? 'selected':'';
            $subj_html.= '<option '.$s_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
        }
        return view('sets.edit', [
            'menu_user' => $this->menu_user,
            'title' => '編輯考卷',
            'Sid' => $sid,
            'Setsname' => $data->s_name,
            'Intro' => $data->s_intro,
            'Time' => $Time,
            'Lim' => $Lim,
            'Again' => $again,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Sum' => $data->s_sum,
            'Pass' => $data->s_pass_score
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $sid)
    {
        $chk_date = ($req->has('chk_date')) ? (int)$req->input('chk_date'):0;
        $s_name = ($req->has('setsname')) ? $req->input('setsname'):'';
        $s_gra = ($req->has('grade')) ? $req->input('grade'):0;
        $s_subj = ($req->has('subject')) ? $req->input('subject'):0;
        if ($chk_date===0 || 
            $s_gra===0 || 
            $s_subj===0 || 
            empty($s_name))abort(400);
        $s_intro = ($req->has('intro')) ? $req->input('intro'):'';
        $data = array();
        $data['s_name'] = $s_name;
        $data['s_intro'] = $s_intro;
        $data['s_begtime'] = '';
        $data['s_endtime'] = '';
        $data['s_gra'] = $s_gra;
        $data['s_subj'] = $s_subj;
        if ($chk_date===1){
            $p_begdate = ($req->has('begdate')) ? trim($req->input('begdate')):'';
            $p_begTimeH = ($req->has('begTimeH')) ? (int)$req->input('begTimeH'):0;
            $p_begTimeH = str_pad($p_begTimeH,2,0,STR_PAD_LEFT);

            $p_enddate = ($req->has('enddate')) ? trim($req->input('enddate')):'';
            $p_endTimeH = ($req->has('endTimeH')) ? (int)$req->input('endTimeH'):0;
            $p_endTimeH = str_pad($p_endTimeH,2,0,STR_PAD_LEFT);
            $data['s_begtime'] = $p_begdate.' '.$p_begTimeH.':00:00';
            $data['s_endtime'] = $p_enddate.' '.$p_endTimeH.':00:00';
        }
        $data['s_sum'] = ($req->has('sum')) ? (int)$req->input('sum'):100;
        $data['s_pass_score'] = ($req->has('passscore')) ? (int)$req->input('passscore'):60;

        //限時
        $lim = array();
        $p_limTimeH = ($req->has('limTimeH')) ? (int)$req->input('limTimeH'):1;
        $lim[] = str_pad($p_limTimeH,2,0,STR_PAD_LEFT);
        $p_limTimeM = ($req->has('limTimeM')) ? (int)$req->input('limTimeM'):0;
        $lim[] = str_pad($p_limTimeM,2,0,STR_PAD_LEFT);
        $p_limTimeS = ($req->has('limTimeS')) ? (int)$req->input('limTimeS'):0;
        $lim[] = str_pad($p_limTimeS,2,0,STR_PAD_LEFT);
        $data['s_limtime'] = implode(":", $lim);
        if ($p_limTimeH<=0 && $p_limTimeM<=0 && $p_limTimeS<=0){
            $this->_errmsg(400);
            return;
        }
        //次數 2=>1次(again=0) 1=>多次
        $p_again = ($req->has('f_times')) ? (int)$req->input('f_times'):2;
        $data['s_again'] = ($p_again===2) ? 0:1;
        
        $data['s_owner'] = $this->login_user;
        $data['updated_at'] = time();
        
        Sets::where('s_id', $sid)
            ->update($data);        
        return redirect('/sets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Sets::where('s_pid', $id)->delete();
        Sets::find($id)->delete();
        return redirect('/sets');
    }
    //ajax更新大題
    public function ajpart(Request $req){
        dd($req->all());
    }
}
