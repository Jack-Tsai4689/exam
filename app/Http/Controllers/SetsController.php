<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Sets;
use App\Setsque;
use DB;
use Input;
use URL;

class SetsController extends TopController
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
        if (!$this->login_status)return redirect('/login');
        $p_gra = 0;
        $p_subj = 0;

        $_get = Input::all();
        if (!empty($_get)){
            $p_gra = (int)request()->input('gra');
            $p_subj = (int)request()->input('subj');
        }
        $sets = new Sets;
        $sets = $sets->where('s_pid',0);
        if ($p_gra>0)$sets = $sets->where('s_gra', $p_gra);
        if ($p_subj>0)$sets = $sets->where('s_subj', $p_subj);
        $sets_data = $sets->paginate(10);
        $gra = $this->grade();
        $gra_html = '';
        $subj_html = '';
        if ($gra!=null){
            $gsel = '';
            foreach ($gra as $v) {
                $sel_gra = ($p_gra===$v->g_id) ? 'selected':'';
                $gra_html.= '<option '.$sel_gra.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($p_gra>0){
            $subj = $this->subject($p_gra);
            foreach ($subj as $v) {
                $sel_subj = ($p_subj===$v->g_id) ? 'selected':'';
                $subj_html.= '<option '.$sel_subj.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($sets_data!=null){
            foreach ($sets_data as $k => $v) {
                $sets_data[$k]->s_again = ($v->s_again) ? "O":"X";
                $sets_data[$k]->updated_at = date('Y/m/d H:i:s', $v->updated_at);
                $sets_data[$k]->time = (!empty($v->s_begtime)) ? $v->s_begtime.' - '.$v->s_endtime:'不限';
                $sets_data[$k]->finish = ($v->s_finish) ? '定案':'可編輯';
            }
        }

        $page_info = $this->page_info(
            $sets_data->currentPage(),
            $sets_data->lastPage(),
            $sets_data->total()
        );
        $pfunc = new \stdClass;
        $pfunc->prev = $this->prev_page;
        $pfunc->next = $this->next_page;
        $pfunc->pg = $this->group_page;

        return view('sets.index_set', [
            'menu_user' => $this->menu_user,
            'title' => '試卷庫',
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Data' => $sets_data,
            'Page' => $pfunc,
            'Num' => $sets_data->total()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->login_status)return redirect('/login');
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
        return view('sets.create_set', [
            'menu_user' => $this->menu_user,
            'title' => '新增試卷',
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
        if (!$this->login_status)return redirect('/login');
        $chk_date = ($req->has('chk_date')) ? (int)$req->input('chk_date'):0;
        $s_name = ($req->has('setsname') && !empty($req->input('setsname'))) ? $req->input('setsname'):'';
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
        $s_sum = ($req->has('sum')) ? (int)$req->input('sum'):100;
        $data['s_sum'] = $s_sum;
        $s_pass_score = ($req->has('passscore')) ? (int)$req->input('passscore'):60;
        $data['s_pass_score'] = $s_pass_score;

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

        $have_sub = ($req->has('have_sub') && !empty($req->input('have_sub'))) ? trim($req->input('have_sub')):'';
        // 有大題
        if (!empty($have_sub)) {
            $s_page = ($req->has('control') && !empty($req->input('control'))) ? trim($req->input('control')):'N';
            $data['s_page'] = $s_page;
            $data['s_sub'] = 1; 
        }else{
            $data['s_sub'] = 0; 
        }
        //主體
        $ins = new Sets;
        $ins->fill($data);
        $ins->save();
        if ($have_sub){
            //大題
            $sort  = ($req->has('sub_sort') && !empty($req->input('sub_sort'))) ? $req->input('sub_sort'):array();
            $control  = ($req->has('sub_control') && !empty($req->input('sub_control'))) ? $req->input('sub_control'):array();
            $score  = ($req->has('sub_score') && !empty($req->input('sub_score'))) ? $req->input('sub_score'):array();
            $intro  = ($req->has('sub_intro') && !empty($req->input('sub_intro'))) ? $req->input('sub_intro'):array();
            foreach ($sort as $k => $v) {
                $data['s_name'] = '';
                $data['s_part'] = $v;
                $data['s_page'] = $control[$k];
                $data['s_percen'] = $score[$k];
                $data['s_intro'] = $intro[$k];
                $data['s_sum'] = $s_sum * $score[$k];
                $data['s_pass_score'] = $s_pass_score * $score[$k];
                $data['s_pid'] = $ins->s_id;
                $sub_ins = new Sets;
                $sub_ins->fill($data);
                $sub_ins->save();
            }
        }
        return redirect('/sets');
    }
    public function store_set(Request $req)
    {
        if (!$this->login_status)return redirect('/login');
        $s_name = ($req->has('setsname') && !empty($req->input('setsname'))) ? $req->input('setsname'):'';
        $s_gra = ($req->has('grade')) ? $req->input('grade'):0;
        $s_subj = ($req->has('subject')) ? $req->input('subject'):0;
        if ($s_gra===0 || 
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
        $s_sum = ($req->has('sum')) ? (int)$req->input('sum'):100;
        $data['s_sum'] = $s_sum;
        $s_pass_score = ($req->has('passscore')) ? (int)$req->input('passscore'):60;
        $data['s_pass_score'] = $s_pass_score;
        $data['s_owner'] = $this->login_user;
        $data['created_at'] = time();
        $data['updated_at'] = time();

        $have_sub = ($req->has('have_sub') && !empty($req->input('have_sub'))) ? trim($req->input('have_sub')):'';
        // 有大題
        if (!empty($have_sub)) {
            $s_page = ($req->has('control') && !empty($req->input('control'))) ? trim($req->input('control')):'N';
            $data['s_page'] = $s_page;
            $data['s_sub'] = 1; 
        }else{
            $data['s_sub'] = 0; 
        }
        //主體
        $ins = new Sets;
        $ins->fill($data);
        $ins->save();
        if ($have_sub){
            //大題
            $sort  = ($req->has('sub_sort') && !empty($req->input('sub_sort'))) ? $req->input('sub_sort'):array();
            $control  = ($req->has('sub_control') && !empty($req->input('sub_control'))) ? $req->input('sub_control'):array();
            $score  = ($req->has('sub_score') && !empty($req->input('sub_score'))) ? $req->input('sub_score'):array();
            $intro  = ($req->has('sub_intro') && !empty($req->input('sub_intro'))) ? $req->input('sub_intro'):array();
            foreach ($sort as $k => $v) {
                $data['s_name'] = '';
                $data['s_part'] = $v;
                $data['s_page'] = $control[$k];
                $data['s_percen'] = $score[$k];
                $data['s_intro'] = $intro[$k];
                $data['s_sum'] = $s_sum * $score[$k];
                $data['s_pass_score'] = $s_pass_score * $score[$k];
                $data['s_pid'] = $ins->s_id;
                $sub_ins = new Sets;
                $sub_ins->fill($data);
                $sub_ins->save();
            }
        }
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
        if (!$this->login_status)return redirect('/login');
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
        $first_sub = new \stdClass;
        $first_sub->que = array();
        $other_sub = array();
        //大題
        if ($data->s_sub){
            $sub = $data->sub()->get()->all();
            $other_sub = $sub;
            $fsub = array_shift($other_sub);
            $first_sub->s_part = '(第1大題)';
            $first_sub->subque = $fsub->subque;
            $first_sub->s_id = $fsub->s_id;
        }else{
            $first_sub->s_id = $sid;
            $first_sub->s_part = '';
            $first_sub->subque = $data->subque;
            $sub = array();
            // $fsub = $data->sub()->first();
            // $first_sub->s_id = $fsub->s_id;
            // $first_sub->subque = $fsub->subque;
            // $first_sub->s_part = '';
        }
        //$first_sub->que = $this->sets_review_format($first_sub->subque);
        foreach ($first_sub->subque as $v) {
            $first_sub->que[] = $this->sets_review_format($v);
        }
        // foreach ($other_sub as $k => $v) {
        //     $other_sub[$k]->que = $this->sets_review_format($v->subque);
        // }
        return view('sets.review', [
            'menu_user' => $this->menu_user,
            'title' => $data->s_name.' - 題目預覽',
            'Sid' => $sid,
            'Set_name' => $data->s_name,
            'Sum' => $data->s_sum,
            'Pass' => $data->s_pass_score,
            'Limtime' => (int)$lime[0].'時'.(int)$lime[1].'分'.(int)$lime[2].'秒',
            //'Sub' => $data->SUB,
            'Part' => $sub,
            'Edit' => $data->s_finish,
            'FirstPart' => $first_sub,
            'OtherPart' => $other_sub,
            'Have_sub' => $data->s_sub
        ]);
    }
    //試卷題目預覽 格式化
    protected function sets_review_format($data){
        $que = new \stdClass;
        $que->q_ans = '';
        $que->q_qcont = '';
        $que->sq_qid = $data->q_id;
        $que->sq_sort = $data->sq_sort;
        //foreach ($data as $k => $v) {
            //題型
            switch ($data->q_quetype) {
                case "S": 
                    $que->q_ans = chr($data->q_ans+64);
                    break;
                case "D": 
                    $ans = array();
                    $ans = explode(",", $data->q_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        $ans_html[] = chr($o+64);
                    }
                    $que->q_ans = implode(", ", $ans_html);
                    break;
                case "R": 
                    $que->q_ans = ($data->q_ans==="1") ? "O":"X";
                    break;
                case "M": 
                    $ans = array();
                    $ans = explode(",", $data->q_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        if (!preg_match("/^[0-9]*$/", $o)){
                            $ans_html[] = ($o==="a") ? '-':'±';
                        }else{
                            $ans_html[] = $o;
                        }
                    }
                    $que->q_ans = implode(", ", $ans_html);
                    break;
            }
            $qcont =  array();
            //題目文字
            if (!empty($data->q_quetxt)) $qcont[] = nl2br(trim($data->q_quetxt));
            //題目圖檔
            if (!empty($data->q_qm_src)){
                if(is_file($data->q_qm_src))$qcont[] = '<IMG src="'.URL::asset($data->q_qm_src).'" width="98%">';
            }
            //題目聲音檔
            if (!empty($data->q_qs_src)){
                if(is_file($data->q_qs_src)){
                    $qcont[] = '<font color="green">題目音訊 O</font>';
                }else{
                    $qcont[] = '<font color="red">題目音訊遺失 X</font>';
                }
            }
            $que->q_qcont = implode("<br>", $qcont);
        //}
        return $que;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($sid)
    {
        if (!$this->login_status)return redirect('/login');
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
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
        //大題
        if ($data->s_sub){
            $sub = Sets::where('s_pid', $sid)->orderby('s_part')->get()->all();
        }else{
            $sub = array();
        }
        return view('sets.edit_havesub', [
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
            'Pass' => $data->s_pass_score,
            'Sub' => $sub,
            'Hsub' => $data->s_sub,
            'Page' => $data->s_page
        ]);
    }
    public function edit_set($sid)
    {
        if (!$this->login_status)return redirect('/login');
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
        $data = Sets::find($sid);

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
        //大題
        if ($data->s_sub){
            $sub = Sets::where('s_pid', $sid)->orderby('s_part')->get()->all();
        }else{
            $sub = array();
        }
        return view('sets.edit_set', [
            'menu_user' => $this->menu_user,
            'title' => '編輯考卷',
            'Sid' => $sid,
            'Setsname' => $data->s_name,
            'Intro' => $data->s_intro,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Sum' => $data->s_sum,
            'Pass' => $data->s_pass_score,
            'Sub' => $sub,
            'Hsub' => $data->s_sub,
            'Page' => $data->s_page
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
        if (!$this->login_status)return redirect('/login');
        //$chk_date = ($req->has('chk_date')) ? (int)$req->input('chk_date'):0;
        $s_name = ($req->has('setsname')) ? $req->input('setsname'):'';
        $s_gra = ($req->has('grade')) ? $req->input('grade'):0;
        $s_subj = ($req->has('subject')) ? $req->input('subject'):0;
        if ($sid===0 ||
            $s_gra===0 || 
            $s_subj===0 || 
            empty($s_name))abort(400);
        $sets = Sets::find($sid);
        $sets->s_finish = 2;
        $sets->save();
        $s_intro = ($req->has('intro')) ? $req->input('intro'):'';
        $data = array();
        $data['s_name'] = $s_name;
        $data['s_intro'] = $s_intro;
        $data['s_begtime'] = '';
        $data['s_endtime'] = '';
        $data['s_gra'] = $s_gra;
        $data['s_subj'] = $s_subj;
        // if ($chk_date===1){
        //     $p_begdate = ($req->has('begdate')) ? trim($req->input('begdate')):'';
        //     $p_begTimeH = ($req->has('begTimeH')) ? (int)$req->input('begTimeH'):0;
        //     $p_begTimeH = str_pad($p_begTimeH,2,0,STR_PAD_LEFT);

        //     $p_enddate = ($req->has('enddate')) ? trim($req->input('enddate')):'';
        //     $p_endTimeH = ($req->has('endTimeH')) ? (int)$req->input('endTimeH'):0;
        //     $p_endTimeH = str_pad($p_endTimeH,2,0,STR_PAD_LEFT);
        //     $data['s_begtime'] = $p_begdate.' '.$p_begTimeH.':00:00';
        //     $data['s_endtime'] = $p_enddate.' '.$p_endTimeH.':00:00';
        // }
        $data['s_sum'] = ($req->has('sum')) ? (int)$req->input('sum'):100;
        $data['s_pass_score'] = ($req->has('passscore')) ? (int)$req->input('passscore'):60;

        //限時
        // $lim = array();
        // $p_limTimeH = ($req->has('limTimeH')) ? (int)$req->input('limTimeH'):1;
        // $lim[] = str_pad($p_limTimeH,2,0,STR_PAD_LEFT);
        // $p_limTimeM = ($req->has('limTimeM')) ? (int)$req->input('limTimeM'):0;
        // $lim[] = str_pad($p_limTimeM,2,0,STR_PAD_LEFT);
        // $p_limTimeS = ($req->has('limTimeS')) ? (int)$req->input('limTimeS'):0;
        // $lim[] = str_pad($p_limTimeS,2,0,STR_PAD_LEFT);
        // $data['s_limtime'] = implode(":", $lim);
        // if ($p_limTimeH<=0 && $p_limTimeM<=0 && $p_limTimeS<=0){
        //     $this->_errmsg(400);
        //     return;
        // }

        //次數 2=>1次(again=0) 1=>多次
        // $p_again = ($req->has('f_times')) ? (int)$req->input('f_times'):2;
        // $data['s_again'] = ($p_again===2) ? 0:1;
        
        $data['s_owner'] = $this->login_user;
        $data['updated_at'] = time();
        
        $have_sub = ($req->has('have_sub') && !empty($req->input('have_sub'))) ? trim($req->input('have_sub')):'';
        $data['s_sub'] = 0;
        if (!empty($have_sub)){
            $no = ($req->has('sub_no') && !empty($req->input('sub_no'))) ? $req->input('sub_no'):array();
            $sort = ($req->has('sub_sort') && !empty($req->input('sub_sort'))) ? $req->input('sub_sort'):array();
            $score = ($req->has('sub_score') && !empty($req->input('sub_score'))) ? $req->input('sub_score'):array();
            $control = ($req->has('sub_control') && !empty($req->input('sub_control'))) ? $req->input('sub_control'):array();
            $intro = ($req->has('sub_intro') && !empty($req->input('sub_intro'))) ? $req->input('sub_intro'):array();
            foreach ($no as $k => $v) {
                if (empty($v)){
                    Sets::create([
                        's_part'=> $sort[$k],
                        's_page'=> $control[$k],
                        's_percen'=> $score[$k],
                        's_intro'=> $intro[$k],
                        's_pid'=> $sid
                    ]);
                    // $sub_ins = new Sets;
                    // $sub_ins->s_part = $sort[$k];
                    // $sub_ins->s_page = $control[$k];
                    // $sub_ins->s_percen =  $score[$k];
                    // $sub_ins->s_intro = $intro[$k];
                    // $sub_ins->s_pid = $sid;
                    // $sub_ins->save();
                }else{
                    Sets::where('s_id', $v)
                        ->update([
                            's_part' => $sort[$k],
                            's_page' => $control[$k],
                            's_percen' => $score[$k],
                            's_intro' => $intro[$k]
                        ]);
                }
                $data['s_sub'] = 1;
            }
        }
        $del = ($req->has('delsub') && !empty($req->input('delsub'))) ? trim($req->input('delsub')):'';
        if (!empty($del)){
            $id = explode(",", $del);
            foreach ($id as $v) {
                $part_sid = (int)$v;
                if ($part_sid>0){
                    Sets::destroy($part_sid);
                    //刪題目
                    Setsque::where('sq_part', $part_sid)->delete();
                }
            }
        }
        $data['s_finish'] = 0;
        Sets::where('s_id', $sid)->update($data);
        $act = ($req->has('_act') && !empty($req->input('_act'))) ? trim($req->input('_act')):'';
        if ($act==="aj"){
            echo true;
        }else{
            return redirect('/sets');    
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->login_status)return redirect('/login');
        //先鎖住
        Sets::where('s_id',$id)->update(['s_finish'=>2]);
        //刪大題題目
        Setsque::where('sq_sid', $id)->delete();
        //刪大題
        Sets::where('s_pid', $id)->delete();
        //刪本體
        Sets::destroy($id);
        return redirect('/sets');
    }
    //ajax更新大題
    public function ajstore_part(Request $req, $sid){
        if (!$this->login_status)abort(401);
        $sub = ($req->has('sub') && !empty($req->input('sub'))) ? $req->input('sub'):array();
        $sub_score = ($req->has('sub_score') && !empty($req->input('sub_score'))) ? $req->input('sub_score'):array();
        $sub_control = ($req->has('sub_control') && !empty($req->input('sub_control'))) ? $req->input('sub_control'):array();
        $sub_intro = ($req->has('sub_intro') && !empty($req->input('sub_intro'))) ? $req->input('sub_intro'):array();

        $sets = Sets::find($sid);
        $sets->s_finish = 2;
        $sets->save();
        
        //先查全部大題的id，照順序
        $all_sub_id = array();
        $sub_all = Sets::select('s_id','s_part')
                       ->where('s_pid', $sid)
                       ->orderby('s_part')->get()->all();
        foreach ($sub_all as $v) {
            $all_sub_id[] = $v->s_id;
        }

        if (!empty($sub)){
            
            $del_id = array();
            $del_id = array_diff($all_sub_id, $sub);
            foreach ($del_id as $v) {
                //如果說有大題就不能刪
                if (Setsque::where('sq_sid', $sid)->where('sq_part',$v)->exists()){
                    about(400);
                }
            }            
            $have_sub = false;
            foreach ($sub as $k => $v) {
                //新增
                if (empty($v)&& !empty($sub_score[$k])){
                    $have_sub = true;
                    $data = [
                        's_intro' => $sub_intro[$k],
                        's_percen' => $sub_score[$k],
                        's_pid' => $sid,
                        's_part' => ($k+1),
                        'created_at' => time(),
                        'updated_at' => time(),
                        's_page' => $sub_control[$k]
                    ];
                    if ($k===0){
                        $ins = new Sets;
                        $ins->fill($data);
                        $ins->save();
                        $first_id = $ins->s_id;
                    }else{
                        Sets::create($data);
                    }
                }else{
                    //更新
                    Sets::where('s_id', $v)
                        ->update([
                            's_intro' => $sub_intro[$k],
                            's_page' => $sub_control[$k],
                            's_percen' => $sub_score[$k],
                            's_part' => ($k+1),
                            'updated_at' => time(),
                        ]);
                }
                if ($k===0){
                    if (empty($all_sub_id)){
                        //之前沒有大題 $all_sub_id = 空陣列
                        Setsque::where('sq_sid', $sid)->where('sq_part', $sid)->update(['sq_part'=> $first_id]);
                    // }else{
                    //     //所有原來的第一大題題目，重新對應至新的第一大題id
                    //     Setsque::where('sq_sid', $sid)->where('sq_part', $all_sub_id[0])->update(['sq_part'=> $v]);
                    }
                }
                //unset($all_sub_id[array_search($v, $all_sub_id)]);
            }
            //剩下的刪掉
            foreach ($del_id as $v) {
                Sets::destroy($v);
            }
            if ($have_sub){
                Sets::where('s_id', $sid)
                    ->update(['s_sub' => 1]);
            }
        }else{
            //全刪
            if (!empty($all_sub_id)){
                foreach ($all_sub_id as $k => $v) {
                    if ($k===0)continue;
                    //除了第一大題，其他大題有題目就不能刪
                    if (Setsque::where('sq_sid', $sid)->where('sq_part', $v)->exists()){
                        about(400);
                    }
                }
                //第一大題題目，挪至試卷下
                Setsque::where('sq_sid', $sid)->where('sq_part', $all_sub_id[0])->update(['sq_part'=> $sid]);
                //全部大題全刪除，變更至無大題狀態
                Sets::where('s_pid', $sid)->delete();
                Sets::where('s_id', $sid)->update(['s_sub' => 0]);
            }
        }
        Sets::where('s_id', $sid)->update(['s_finish' => 0]);
        $json['Success'] = true;
        echo json_encode($json);
    }
    //ajax查詢大題
    public function ajedit_part($sid){
        if (!$this->login_status)abort(401);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
        //大題
        $have = Sets::find($sid);
        $data = array();
        if ($have->s_sub){
            $sub = Sets::select('s_id','s_intro','s_percen','s_page')
                        ->where('s_pid', $sid)
                        ->orderby('s_part')->get()->all();
            foreach ($sub as $v) {
                $tmp = new \stdClass;
                $tmp->sid = $v->s_id;
                $tmp->percen = $v->s_percen;
                $tmp->control = $v->s_page;
                $tmp->intro = $v->s_intro;
                array_push($data, $tmp);
            }
            unset($sub);
        }
        echo json_encode($data);
    }
    //ajax大題加入題目
    public function partjoinque(Request $req, $sid){
        if (!$this->login_status)abort(401);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<1)abort(400);
        $sets = Sets::find($sid);
        if ($sets===null)abort(400);
        $sets->s_finish = 2;
        $sets->save();
        $ques = ($req->has('ques') && !empty($req->input('ques'))) ? $req->input('ques'):'';
        $part = ($req->has('npart') && (int)$req->input('npart')>0) ? (int)$req->input('npart'):0;
        if (empty($ques) || $part===0)abort(400);
        $addque = explode(',',$ques);

        foreach ($addque as $q) {
            //如果大題的題目沒重覆才新增，並自動遞增序號
            $sql = "INSERT INTO setsque(sq_sid, sq_part, sq_qid, sq_sort, sq_owner, updated_at) 
            SELECT ?, ?, ?, (SELECT (Case When Max(sq_sort) is null then 0 else max(sq_sort) end)+1 from setsque 
                WHERE sq_sid=? AND sq_part=?), ?, ? 
            WHERE NOT EXISTS( 
                SELECT * FROM setsque WHERE sq_sid=? AND sq_part=? AND sq_qid=?
            ) limit 1";
            $data = [
                $sid, $part, $q, 
                $sid, $part, $this->login_user, time(),
                $sid, $part, $q];
            DB::insert($sql, $data);
            //看資料是否存在，不存在會原條件返回，有存在會返回該筆資料
            // $sql = "INSERT INTO setsque(sq_sid, sq_part, sq_qid, sq_sort, sq_owner, updated_at)
            //     SELECT ?, ?, ?, (SELECT IFNULL(1, max('sq_sort')) FROM setsque WHERE sq_sid=? AND sq_part=?), ?, ? FROM setsque
            //     WHERE NOT EXISTS(
            //         SELECT 1 FROM setsque WHERE sq_sid=? AND sq_part=? AND sq_qid=? limit 1
            //     )";
            // $que_exists = Setsque::firstOrNew(['sq_sid' => $sid, 'sq_part' => $part, 'sq_qid' => $q]);
            // if (!$que_exists->exists){
            //     $que_exists->sq_sort = (int)Setsque::select('sq_sort')->where('sq_sid',$sid)->where('sq_part', $part)->max('sq_sort')+1;
            //     $que_exists->sq_owner = $this->login_user;
            //     $que_exists->updated_at = time();
            //     $que_exists->save();
            // }
        }
        Sets::where('s_id', $sid)->update(['s_finish'=>0]);
        echo true;
        // $json['Success'] = true;
        // echo json_encode($json);
    }
    //ajax查詢大題題目
    public function ajshow_que($sid){
        if (!$this->login_status)abort(401);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<1)abort(400);
        $part_id = request()->input('part');
        if (!is_numeric($part_id))abort(400);
        $part_id = (int)$part_id;
        if ($part_id<1)abort(400);

        $sets = Sets::find($sid);
        if ($sets===null)abort(400);
        $que = Setsque::select('sq_qid','sq_sort')
                        ->where('sq_sid', $sid)
                        ->where('sq_part', $part_id)
                        ->orderby('sq_sort')->get()->all();
        $html = '';
        foreach ($que as $k => $v) {
            $data = $this->sets_review_format($v->que);
            $html.= '<tr align="center" name="node" id="'.$v->sq_qid.'">';
            $html.= '<td class="handle">: :</td>';
            $html.= '<td class="qno_ans">'.$data->q_ans.'</td>';
            $html.= '<td class="qno">'.$v->sq_sort.'</td>';
            $html.= '<td align="left" class="que">'.$data->q_qcont.'</td>';
            $html.= '<td><form>';
            $html.= '<input type="hidden" name="part" value="'.$part_id.'">';
            $html.= '<input type="hidden" name="que" value="'.$v->sq_qid.'">';
            $html.= '<input type="hidden" name="_method" value="DELETE">';
            $html.= '<a href="javascript:void(0)" onclick="delq(this)"><img src="'.URL::asset('img/icon_op_f.png').'" width="20"></a>';
            $html.= '</form></td>';
            $html.= '</tr>';
        }
        $json['html'] = $html;
        echo json_encode($json);
    }
    //ajax更新大題題目順序
    public function ajupdate_sortq(Request $req, $sid){
        if (!$this->login_status)abort(401);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<1)abort(400);
        $sets = Sets::find($sid);
        if ($sets===null)abort(400);
        $sets->s_finish = 2;
        $sets->save();
        $node = ($req->has('node') && !empty($req->input('node'))) ? json_decode(trim($req->input('node'))):array();
        $part = ($req->has('s') && (int)$req->input('s')>0) ? (int)$req->input('s'):0;
        if ($part===0)abort(400);
        foreach ($node as $position => $item){
            Setsque::where('sq_sid', $sid)
                   ->where('sq_part', $part)
                   ->where('sq_qid', $item)
                   ->update(['sq_sort' => ($position+1)]);
            // $query = sprintf("UPDATE iftex_exsets_sort SET sort_no=%d WHERE sub_qid=%d AND listseq=%d;", $position+1, $item, $setsid);
            // $db->query($query);
        }
        Sets::where('s_id', $sid)->update(['s_finish'=>0]);
        echo '1';
    }
    //ajax更新大題順序
    public function ajupdate_psort(Request $req, $sid){
        if (!$this->login_status)abort(401);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<1)abort(400);
        $sets = Sets::find($sid);
        if ($sets===null)abort(400);
        $sets->s_finish = 2;
        $sets->save();
        $node = ($req->has('node') && !empty($req->input('node'))) ? json_decode(trim($req->input('node'))):array();
        foreach ($node as $position => $item) {
            Sets::where('s_id', $item)->where('s_pid', $sid)->update(['s_part'=>($position+1)]);
        }
        Sets::where('s_id', $sid)->update(['s_finish'=>0]);
        echo '1';
    }
    //ajax刪除題目
    public function ajdelete_que(Request $req, $sid){
        if (!$this->login_status)abort(401);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
        $part = ($req->has('part') && (int)$req->input('part')>0) ? (int)$req->input('part'):0;
        $que = ($req->has('que') && (int)$req->input('que')>0) ? (int)$req->input('que'):0;
        if ($part===0 || $que===0)abort(400);
        $sets = Sets::find($sid);
        if ($sets===null)abort(400);
        $sets->s_finish = 2;
        $sets->save();
        Setsque::where('sq_sid', $sid)
               ->where('sq_part', $part)
               ->where('sq_qid', $que)
               ->delete();
        Sets::where('s_id', $sid)->update(['s_finish'=>0]);
        echo '1';
    }
    //切換試卷狀態
    public function status_change(Request $req, $sid){
        if (!$this->login_status)abort(400);
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
        
        $status = ($req->has('status') && !empty($req->input('status'))) ? trim($req->input('status')):'';
        if ($status==="open"){
            //檢查配分
            $have_sub = Sets::find($sid);
            if ($have_sub->s_sub){
                //有大題
                $sub = Sets::select('s_id','s_percen')->where('s_pid', $sid)->orderby('s_part')->get()->all();
                $percen = 0;
                foreach ($sub as $k => $v) {
                    $percen+=$v->s_percen;
                }
                if ($percen!== 100){
                    echo '試卷 - 「'.$have_sub->s_name.'」 配分錯誤，請返回確認';
                    return;
                }
                //重新順號
                foreach ($sub as $v) {
                    $sub_q = Setsque::select('sq_qid','sq_sort')
                                ->where('sq_sid', $sid)
                                ->where('sq_part', $v->s_id)
                                ->orderby('sq_sort')->get()->all();
                    foreach ($sub_q as $sk => $sv) {
                        Setsque::where('sq_sid', $sid)
                               ->where('sq_part', $v->s_id)
                               ->update(['sq_sort'=> ($sk+1)]);
                    }
                }
            }else{
                //沒有大題
                $sub_q = Setsque::select('sq_qid','sq_sort')
                                ->where('sq_sid', $sid)
                                ->where('sq_part', $sid)
                                ->orderby('sq_sort')->get()->all();
                foreach ($sub_q as $sk => $sv) {
                    Setsque::where('sq_sid', $sid)
                           ->where('sq_part', $sid)
                           ->update(['sq_sort'=> ($sk+1)]);
                }
            }
            Sets::where('s_id', $sid)->update(['s_finish'=>1]);
            return redirect('/sets');
        }
    }
    //ajax編輯試卷 (iframe open show)
    public function ajstru($sid){
        if (!$this->login_status)return redirect('/login');
        if (!is_numeric($sid))abort(400);
        $sid = (int)$sid;
        if ($sid<=0)abort(400);
        $data = Sets::find($sid);
        if ($data===null)abort(400);
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
        //大題
        if ($data->s_sub){
            $sub = Sets::where('s_pid', $sid)->orderby('s_part')->get()->all();
        }else{
            $sub = array();
        }
        return view('sets.ajedit_set', [
            'menu_user' => $this->menu_user,
            'title' => '編輯考卷',
            'Sid' => $sid,
            'Setsname' => $data->s_name,
            'Intro' => $data->s_intro,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Sum' => $data->s_sum,
            'Pass' => $data->s_pass_score,
            'Sub' => $sub,
            'Hsub' => $data->s_sub,
            'Page' => $data->s_page
        ]);
    }
    public function ajpublish(){
        $_get = Input::all();
        if (!empty($_get)){
            $gra = request()->input('g');
            $subj = request()->input('s');
            if (!is_numeric($gra) || !is_numeric($subj))abort(400);
            $g = (int)$gra;
            $s = (int)$subj;
            if ($g<=0 || $s<=0)return;
            if (!$this->login_status)abort(400);
            $data = Sets::select('s_id','s_name')
                        ->where('s_gra', $g)
                        ->where('s_subj', $s)
                        ->where('s_pid', 0)
                        ->where('s_finish', 0)
                        ->get()->all();
            $sets = array();
            if (empty($data)){
                $tmp = new \stdClass;
                $tmp->ID = 0;
                $tmp->NAME = '無考卷';
                array_push($sets, $tmp);
            }else{
                foreach ($data as $v) {
                    $tmp = new \stdClass;
                    $tmp->ID = $v->s_id;
                    $tmp->NAME = $v->s_name;
                    array_push($sets, $tmp);
                }
            }
            echo json_encode($sets);
        }
    }
}
