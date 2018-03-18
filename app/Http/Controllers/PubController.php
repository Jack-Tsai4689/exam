<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Pubs;
use App\Pubcas;
use App\Pubsque;
use App\Sets;
use App\Setsque;
use URL;
class PubController extends TopController
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
        $data = Pubs::where('p_pid',0)->get()->all();
        foreach ($data as $k => $v) {
            $data[$k]->exam_day = (empty($v->p_begtime)) ? '不限':$data[$k]->exam_day = $v->p_begtime.'~<br>'.$v->p_endtime;
        }
        $sel = new \stdclass;
        $sel->gra = 0;
        $sel->subj = 0;
        $page = new \stdclass;
        $page->prev = '';
        $page->next = '';
        $page->pg = '';
        $grade_data = $this->grade();
        $subject_data = array();
        return view('pub.index', [
            'menu_user' => $this->menu_user,
            'title' => '發佈記錄',
            'Data' => $data,
            'Grade' => $grade_data,
            'Subject' => $subject_data,
            'Num' => 0,
            'Page' => $page
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
        $_get = request()->all();
        $sel = new \stdclass;
        $sel->sid = 0;
        $sel->gra = 0;
        $sel->subj = 0;
        if (!empty($_get)){
            $sid = request()->input('sid');
            if (!is_numeric($sid))return redirect('/pub/create');
            $sid = (int)$sid;
            if ($sid<1)return redirect('/pub/create');
            $sel->sid = $sid;
            $s = Sets::find($sid);
            $sel->gra = $s->s_gra;
            $sel->subj = $s->s_subj;
        }
        $grade_data = $this->grade();
        $subject_data = array();
        $sets_data = array();
        if (!empty($grade_data)){
            if ($sel->gra===0)$sel->gra = $grade_data[0]->g_id;
            $subject_data = $this->subject($sel->gra);
        }
        if (!empty($subject_data)){
            if ($sel->subj===0)$sel->subj = $subject_data[0]->g_id;
            $sets_data = Sets::select('s_name','s_id')
                             ->where('s_pid', 0)
                             ->where('s_gra', $sel->gra)
                             ->where('s_subj', $sel->subj)
                             ->get()->all();
        }
        return view('pub.create', [
            'menu_user' => $this->menu_user,
            'title' => '發佈測驗',
            'Time' => $Time,
            'Lim' => $Lim,
            'Grade' => $grade_data,
            'Subject' => $subject_data,
            'Sets' => $sets_data,
            'Sum' => 100,
            'Pass' => 60,
            'Sel' => $sel
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
        $sid = ($req->has('sets') && !empty($req->input('sets'))) ? (int)$req->input('sets'):0;
        if ($sid===0)abort(400);
        $sets = Sets::find($sid);
        //如果更新中不能動
        if ($sets->s_finish===2)abort(406);
        $chk_date = ($req->has('chk_date')) ? (int)$req->input('chk_date'):0;
        if ($chk_date===0)abort(400);
        $data = array();
        $p_begtime = '';
        $p_endtime = '';
        if ($chk_date===1){
            $p_begdate = ($req->has('begdate')) ? trim($req->input('begdate')):'';
            $p_begTimeH = ($req->has('begTimeH')) ? (int)$req->input('begTimeH'):0;
            $p_begTimeH = str_pad($p_begTimeH,2,0,STR_PAD_LEFT);

            $p_enddate = ($req->has('enddate')) ? trim($req->input('enddate')):'';
            $p_endTimeH = ($req->has('endTimeH')) ? (int)$req->input('endTimeH'):0;
            $p_endTimeH = str_pad($p_endTimeH,2,0,STR_PAD_LEFT);
            $p_begtime = $p_begdate.' '.$p_begTimeH.':00:00';
            $p_endtime = $p_enddate.' '.$p_endTimeH.':00:00';
        }
        $s_sum = ($req->has('sum')) ? (int)$req->input('sum'):100;
        $s_pass_score = ($req->has('passscore')) ? (int)$req->input('passscore'):60;

        //限時
        $lim = array();
        $p_limTimeH = ($req->has('limTimeH')) ? (int)$req->input('limTimeH'):1;
        $lim[] = str_pad($p_limTimeH,2,0,STR_PAD_LEFT);
        $p_limTimeM = ($req->has('limTimeM')) ? (int)$req->input('limTimeM'):0;
        $lim[] = str_pad($p_limTimeM,2,0,STR_PAD_LEFT);
        $p_limTimeS = ($req->has('limTimeS')) ? (int)$req->input('limTimeS'):0;
        $lim[] = str_pad($p_limTimeS,2,0,STR_PAD_LEFT);
        $p_limtime = implode(":", $lim);
        if ($p_limTimeH<=0 && $p_limTimeM<=0 && $p_limTimeS<=0){
            $this->_errmsg(400);
            return;
        }
        //次數 2=>1次(again=0) 1=>多次
        $again = ($req->has('f_times')) ? (int)$req->input('f_times'):2;
        $p_again = ($again===2) ? 0:1;

        $c = 1;//($req->has('ca') && !empty($req->input('ca'))) ? $req->input('ca'):0;
        $ca = 2;//($req->has('cla')) ? $req->input('ca'):"";
        $wsets = 5;//($req->has('wsets') && !empty($req->input('wsets'))) ? $req->input('wsets'):0;
        if (!is_numeric($c) || $c===0)abort(400);
        if (!is_numeric($ca) || $ca<0)abort(400);
        if (!is_numeric($wsets) || $wsets<=0)abort(400);
        $c = (int)$c;
        $ca = (int)$ca;
        $wsets = (int)$wsets;

        //先重整一次，再發佈
        if ($sets->s_sub){
            $sub = Sets::select('s_id','s_percen')->where('s_pid', $sid)->orderby('s_part')->get()->all();
            $percen = 0;
            foreach ($sub as $k => $v) {
                $percen+=$v->s_percen;
            }
            //試卷配分錯誤
            if ($percen!== 100)abort(406);
            //重新順號
            // foreach ($sub as $v) {
            //     $sub_q = Setsque::select('sq_qid','sq_sort')
            //                 ->where('sq_sid', $sid)
            //                 ->where('sq_part', $v->s_id)
            //                 ->orderby('sq_sort')->get()->all();
            //     foreach ($sub_q as $sk => $sv) {
            //         Setsque::where('sq_sid', $sid)
            //                ->where('sq_part', $v->s_id)
            //                ->update(['sq_sort'=> ($sk+1)]);
            //     }
            // }
        }
        //發佈一份測驗卷，複制
        //主卷
        $pub = Pubs::create([
            's_id' => $sid,
            'p_name' => $sets->s_name,
            'p_intro' => $sets->s_intro,
            'p_owner' => session('epno'),
            'p_begtime' => $p_begtime,
            'p_endtime' => $p_endtime,
            'p_limtime' => $p_limtime,
            'p_status' => 'Y',
            'p_again' => $p_again,
            'p_gra' => $sets->s_gra,
            'p_subj' => $sets->s_subj,
            'p_pass_score' => $s_pass_score,
            'p_sum' => $s_sum,
            'p_part' => 0,
            'p_sub' => $sets->s_sub,
            'p_pid' => 0,
            'p_percen' => $sets->s_percen,
            'p_page' => $sets->s_page,
            'p_created_at' => time(),
            'p_updated_at' => time(),
        ]);
        if ($sets->s_sub){
            $part = Sets::where('s_pid', $sid)->orderby('s_part')->get()->all();
            foreach ($part as $s) {
                //大題
                $pubs = Pubs::create([
                    's_id' => $s->s_id,
                    'p_name' => $s->s_name,
                    'p_intro' => $s->s_intro,
                    'p_owner' => session('epno'),
                    'p_begtime' => $p_begtime,
                    'p_endtime' => $p_endtime,
                    'p_limtime' => $p_limtime,
                    'p_status' => 'Y',
                    'p_again' => $p_again,
                    'p_gra' => $s->s_gra,
                    'p_subj' => $s->s_subj,
                    'p_pass_score' => $s->s_pass_score,
                    'p_sum' => $s->s_sum,
                    'p_part' => $s->s_part,
                    'p_sub' => 0,
                    'p_pid' => $pub->p_id,
                    'p_percen' => $s->s_percen,
                    'p_page' => $s->s_page,
                    'p_created_at' => time(),
                    'p_updated_at' => time(),
                ]);
                //題目
                $que = Setsque::select('sq_qid','q_ans','q_num','q_quetype','q_quetxt','q_qm_src','q_qm_name','q_qs_src','q_qs_name','q_anstxt','q_am_src','q_am_name','q_as_src','q_as_name','q_av_src','q_av_name','q_degree','q_gra','q_subj','q_chap')
                              ->where('sq_part', $s->s_id)
                              ->join('ques', 'ques.q_id','=','setsque.sq_qid')
                              ->orderby('sq_sort')->get()->all();
                foreach ($que as $i => $q) {
                    Pubsque::create([
                        'pq_pid' => $pub->p_id,
                        'pq_part' => $pubs->p_id,
                        'pq_sort' => ($i+1),
                        'pq_qid' => $q->sq_qid,
                        'pq_ans' => $q->q_ans,
                        'pq_num' => $q->q_num,
                        'pq_quetype' => $q->q_quetype,
                        'pq_quetxt' => $q->q_quetxt,
                        'pq_qm_src' => $q->q_qm_src,
                        'pq_qm_name' => $q->q_qm_name,
                        'pq_qs_src' => $q->q_qs_src,
                        'pq_qs_name' => $q->q_qs_name,
                        'pq_anstxt' => $q->q_anstxt,
                        'pq_am_src' => $q->q_am_src,
                        'pq_am_name' => $q->q_am_name,
                        'pq_as_src' => $q->q_as_src,
                        'pq_as_name' => $q->q_as_name,
                        'pq_av_src' => $q->q_av_src,
                        'pq_av_name' => $q->q_av_name,
                        'pq_degree' => $q->q_degree,
                        'pq_gra' => $q->q_gra,
                        'pq_subj' => $q->q_subj,
                        'pq_chap' => $q->q_chap,
                        'pq_created_at' => time(),
                        'pq_updated_at' => time()
                    ]);
                }
            }
        }else{
            //題目
            $que = Setsque::select('sq_qid','q_ans','q_quetype','q_quetxt','q_qm_src','q_qm_name','q_qs_src','q_qs_name','q_anstxt','q_am_src','q_am_name','q_as_src','q_as_name','q_av_src','q_av_name','q_degree','q_gra','q_subj','q_chap')
                              ->where('sq_sid', $sid)
                              ->join('ques', 'ques.q_id','=','setsque.sq_qid')
                              ->orderby('sq_sort')->get()->all();
            foreach ($que as $i => $q) {
                Pubsque::create([
                    'pq_pid' => $pub->p_id,
                    'pq_part' => $pub->p_id,
                    'pq_sort' => ($i+1),
                    'pq_qid' => $q->sq_qid,
                    'pq_ans' => $q->q_ans,
                    'pq_quetype' => $q->q_quetype,
                    'pq_quetxt' => $q->q_quetxt,
                    'pq_qm_src' => $q->q_qm_src,
                    'pq_qm_name' => $q->q_qm_name,
                    'pq_qs_src' => $q->q_qs_src,
                    'pq_qs_name' => $q->q_qs_name,
                    'pq_anstxt' => $q->q_anstxt,
                    'pq_am_src' => $q->q_am_src,
                    'pq_am_name' => $q->q_am_name,
                    'pq_as_src' => $q->q_as_src,
                    'pq_as_name' => $q->q_as_name,
                    'pq_av_src' => $q->q_av_src,
                    'pq_av_name' => $q->q_av_name,
                    'pq_degree' => $q->q_degree,
                    'pq_gra' => $q->q_gra,
                    'pq_subj' => $q->q_subj,
                    'pq_chap' => $q->q_chap,
                    'pq_created_at' => time(),
                    'pq_updated_at' => time()
                ]);
            }
        }
        //全部班別
        if ($ca===0){
            //curl：所有班別的考卷都核對過，有此考卷的班別才新增
            /*
            找該班級所有班別，搜尋每個班別的考卷，如果有此考卷，那就要新增這個班別
            */
        }else{
            //指定班別
            // Pubcas::create([
            //     'p_id' => $pub->p_id,
            //     'pc_class' => $c,
            //     'pc_classa' => $ca,
            //     'pc_webid' => $wsets
            // ]);
        }
        echo true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($pid)
    {
        if (!$this->login_status)return redirect('/login');
        if (!preg_match("/^[0-9]*$/", $pid))abort(400);
        $pid = (int)$pid;
        if ($this->login_type==="S"){
            echo('很抱歉，權限不足以瀏覽');
            return;
        }
        $data = Pubs::find($pid);
        //限時
        $lime = explode(":", $data->p_limtime);
        $first_sub = new \stdClass;
        $first_sub->que = array();
        $other_sub = array();
        //大題
        if ($data->p_sub){
            $sub = $data->sub()->get()->all();
            $other_sub = $sub;
            $fsub = array_shift($other_sub);
            $first_sub->p_part = '(第1大題)';
            $first_sub->subque = $fsub->subque()->get()->all();
            $first_sub->p_id = $fsub->p_id;
        }else{
            $first_sub->p_id = $pid;
            $first_sub->p_part = '';
            $first_sub->subque = $data->subque()->get()->all();
            $sub = array();
            // $fsub = $data->sub()->first();
            // $first_sub->s_id = $fsub->s_id;
            // $first_sub->subque = $fsub->subque;
            // $first_sub->s_part = '';
        }
        //$first_sub->que = $this->pubs_review_format($first_sub->subque);
        foreach ($first_sub->subque as $k => $v) {
            $first_sub->que[] = $this->pubs_review_format($v);
        }
        // foreach ($other_sub as $k => $v) {
        //     $other_sub[$k]->que = $this->pubs_review_format($v->subque);
        // }

        return view('pub.review', [
            'menu_user' => $this->menu_user,
            'title' => $data->p_name.'測驗卷 - 題目預覽',
            'Sid' => $pid,
            'Set_name' => $data->p_name,
            'Sum' => $data->p_sum,
            'Pass' => $data->p_pass_score,
            'Limtime' => (int)$lime[0].'時'.(int)$lime[1].'分'.(int)$lime[2].'秒',
            //'Sub' => $data->SUB,
            'Part' => $sub,
            'Edit' => $data->p_status,
            'FirstPart' => $first_sub,
            'OtherPart' => $other_sub,
            'Have_sub' => $data->p_sub
        ]);
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
    //ajax查詢大題題目
    public function ajshow_que($pid){
        if (!$this->login_status)abort(401);
        if (!preg_match("/^[0-9]*$/", $pid))abort(400);
        $pid = (int)$pid;
        $part_id = request()->input('part');
        if (!preg_match("/^[0-9]*$/", $part_id))abort(400);
        $part_id = (int)$part_id;
        $que = Pubsque::where('pq_pid', $pid)
                        ->where('pq_part', $part_id)
                        ->orderby('pq_sort')->get()->all();
        $html = '';
        foreach ($que as $k => $v) {
            $data = $this->pubs_review_format($v);
            $html.= '<tr align="center" name="node" id="'.$v->pq_qid.'">';
            $html.= '<td class="handle">: :</td>';
            $html.= '<td class="qno_ans">'.$data->pq_ans.'</td>';
            $html.= '<td class="qno">'.$v->pq_sort.'</td>';
            $html.= '<td align="left" class="que">'.$data->pq_qcont.'</td>';
            $html.= '</tr>';
        }
        $json['html'] = $html;
        echo json_encode($json);
    }
    //試卷題目預覽 格式化
    protected function pubs_review_format($data){
        //foreach ($data as $k => $v) {
            //題型
            switch ($data->pq_quetype) {
                case "S": 
                    $data->pq_ans = chr($data->pq_ans+64);
                    break;
                case "D": 
                    $ans = array();
                    $ans = explode(",", $data->pq_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        $ans_html[] = chr($o+64);
                    }
                    $data->pq_ans = implode(", ", $ans_html);
                    break;
                case "R": 
                    $data->pq_ans = ($data->pq_ans==="1") ? "O":"X";
                    break;
                case "M": 
                    $ans = array();
                    $ans = explode(",", $data->pq_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        if (!preg_match("/^[0-9]*$/", $o)){
                            $ans_html[] = ($o==="a") ? '-':'±';
                        }else{
                            $ans_html[] = $o;
                        }
                    }
                    $data->pq_ans = implode(", ", $ans_html);
                    break;
            }
            $qcont =  array();
            //題目文字
            if (!empty($data->pq_quetxt)) $qcont[] = nl2br(trim($data->pq_quetxt));
            //題目圖檔
            if (!empty($data->pq_qm_src)){
                if(is_file($data->pq_qm_src))$qcont[] = '<IMG src="'.URL::asset($data->pq_qm_src).'" width="98%">';
            }
            //題目聲音檔
            if (!empty($data->pq_qs_src)){
                if(is_file($data->pq_qs_src)){
                    $qcont[] = '<font color="green">題目音訊 O</font>';
                }else{
                    $qcont[] = '<font color="red">題目音訊遺失 X</font>';
                }
            }
            $data->pq_qcont = implode("<br>", $qcont);
        //}
        return $data;
    }
}
