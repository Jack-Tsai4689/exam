<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Ques;
use URL;
//use Auth;

const UPLOAD_DIR = "uploads/que";

class QueController extends TopController
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

        $data = $this->ques_list();
        return view('que.index', $data);
    }
    private function _ques_ans_format($v){
        $data = new \stdClass;
        //題型、答案
        switch ($v->q_quetype) {
            case "S": 
                $data->q_quetype = "單選"; 
                $data->q_ans = chr($v->q_ans+64);
                break;
            case "D": 
                $data->q_quetype = "複選"; 
                $ans = array();
                $ans = explode(",", $v->q_ans);
                $ans_html = array();
                foreach ($ans as $o) {
                    $ans_html[] = chr($o+64);
                }
                $data->q_ans = implode(", ", $ans_html);
                break;
            case "R": 
                $data->q_quetype = "是非"; 
                $data->q_ans = ($v->q_ans==="1") ? "O":"X";
                break;
            case "M": 
                $data->q_quetype = '選填'; 
                $ans = array();
                $ans = explode(",", $v->q_ans);
                $ans_html = array();
                foreach ($ans as $o) {
                    if (!preg_match("/^[0-9]*$/", $o)){
                        $ans_html[] = ($o==="a") ? '-':'±';
                    }else{
                        $ans_html[] = $o;
                    }
                }
                $data->q_ans = implode(", ", $ans_html);
                break;
        }
        //難度
        switch ($v->q_degree) {
            case "M": $data->q_degree = "中等"; break;
            case "H": $data->q_degree = "困難"; break;
            case "E": $data->q_degree = "容易"; break;
            default: $data->q_degree = "容易"; break;
        }
        return $data;
    }
    private function ques_list(){
        $p_gra = 0;
        $p_subj = 0;
        $p_chap = 0;
        $p_degree = '';
        $p_keyword = '';
        $sel_Degree = new \stdClass;
        $sel_Degree->A = '';
        $sel_Degree->E = '';
        $sel_Degree->M = '';
        $sel_Degree->H = '';

        $_get = request()->all();
        $query_search = false;
        if (!empty($_get)){
            $p_gra = (int)request()->input('gra');
            $p_subj = (int)request()->input('subj');
            $p_chap = (int)request()->input('chap');
            $p_keyword = trim(request()->input('q'));
            if (!empty($p_keyword))$query_search = true;
            $p_degree = trim(request()->input('degree'));
            $allow_degree = array('E','M','H');
            if (!empty($p_degree)){
                if (!in_array($p_degree, $allow_degree)){
                    $p_degree = '';
                }else{
                    $query_search = true;
                    switch ($p_degree) {
                        case 'E': $sel_Degree->E = 'selected'; break;
                        case 'M': $sel_Degree->M = 'selected'; break;
                        case 'H': $sel_Degree->H = 'selected'; break;
                    }
                }
            }
        }

        $gra_html = '';
        $subj_html = '';
        $chap_html = '';
        $grade_data = $this->grade();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $sel_gra = ($p_gra===$v->g_id) ? 'selected':'';
                $gra_html.= '<option '.$sel_gra.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($p_gra>0){
            $query_search = true;
            $subject_data = $this->subject($p_gra);
            foreach ($subject_data as $v) {
                $sel_subj = ($p_subj===$v->g_id) ? 'selected':'';
                $subj_html.= '<option '.$sel_subj.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($p_subj>0){
            $query_search = true;
            $chapter_data = $this->chapter($p_gra, $p_subj);
            foreach ($chapter_data as $v) {
                $sel_chap = ($p_chap===$v->g_id) ? 'selected':'';
                $chap_html.= '<option '.$sel_chap.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        if ($query_search){
            $ques = new Ques;
            if ($p_degree>"")$ques = $ques->where('q_degree', $p_degree);
            if ($p_gra>0)$ques = $ques->where('q_gra', $p_gra);
            if ($p_subj>0)$ques = $ques->where('q_subj', $p_subj);
            if ($p_chap>0)$ques = $ques->where('q_chap', $p_chap);
            if (!empty($p_keyword))$ques = $ques->where('q_keyword','like','%|'.$p_keyword.'|%');
            $que_data = $ques->paginate(10);
        }else{
            $que_data = Ques::paginate(10);
        }
        foreach ($que_data as $k => $v) {
            
            $format = $this->_ques_ans_format($v);
            $que_data[$k]->q_quetype = $format->q_quetype;
            $que_data[$k]->q_ans = $format->q_ans;

            $cont =  array();
            //題目文字
            if (!empty($v->q_quetxt)) $cont[] = '<div>'.nl2br(trim($v->q_quetxt)).'</div>';
            //題目圖檔
            if (!empty($v->q_qm_src)){
                if(is_file($v->q_qm_src))$cont[] = '<div>題目圖片：'.$v->q_qm_name.'</div><IMG class="pic" src="'.URL::asset($v->q_qm_src).'">';
            }
            //題目聲音檔
            if (!empty($v->q_qs_src)){
                if(is_file($v->q_qs_src)){
                    $cont[] = '<div>題目音訊：'.$v->q_qs_name.'</div>';
                }else{
                    $cont[] = '<div>題目音訊：'.$v->q_qs_name.'　(<font color="red">遺失</font>)</div>';
                }
            }
            $acont = array();
            //詳解文字
            if (!empty($v->q_anstxt)) $acont[] = '<div>'.nl2br(trim($v->q_anstxt)).'</div>';
            //詳解圖檔
            if(!empty($v->q_am_src)){
                if (is_file($v->q_am_src))$acont[] = '<div>詳解圖片：'.$v->q_am_name.'</div><IMG class="pic"  src="'.URL::asset($v->q_am_src).'">';
            }
            //詳解聲音檔
            if(!empty($v->q_as_src)){
                if(is_file($v->q_as_src)){
                    $acont[] = '<div>詳解音訊：'.$v->q_as_name.'</div>';
                }else{
                    $acont[] = '<div>詳解音訊：'.$v->q_as_name.'(<font color="red">遺失</font>)<div>';
                }
            }
            //詳解影片檔
            if(!empty($v->q_av_src)){
                if(is_file($v->q_av_src)){
                    $acont[] = '<div>詳解視訊：'.$v->av_name.'</div>';
                }else{
                    $acont[] = '<div>詳解視訊：'.$v->av_name.'(<font color="red">遺失</font>)</div>';
                }
            }
            if (!empty($acont)){
                $cont[] = "<br><div><strong>詳解</strong></div>";
                $cont = array_merge($cont, $acont);
            }
            $que_data[$k]->cont = implode("", $cont);
            //難度
            $que_data[$k]->q_degree = $format->q_degree;
            $que_data[$k]->q_update = date('Y/m/d H:i:s', $v->q_updated_at);
            $que_data[$k]->q_know = ($v->q_know!==0) ? $v->knows->name:'';

            $que_data[$k]->q_gra = $v->gra->name;
            $que_data[$k]->q_subj = $v->subj->name;
            $que_data[$k]->q_chap = $v->chap->name;
        }
        
        $page_info = $this->page_info(
            $que_data->currentPage(),
            $que_data->lastPage(),
            $que_data->total()
        );
        $pfunc = new \stdClass;
        $pfunc->prev = $this->prev_page;
        $pfunc->next = $this->next_page;
        $pfunc->pg = $this->group_page;
        
        $data = [
            'menu_user' => $this->menu_user,
            'title' => '我的題庫',
            'Data' => $que_data,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Chapter' => $chap_html,
            'Degree' => $sel_Degree,
            'Page' => $pfunc,
            'Num' => $que_data->total(),
            'Qkeyword' => $p_keyword
        ];
        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->login_status)return redirect('/login');
        $sets_message = '';//'<div id="sets_title"><label class="17">'.$msg.'</label></div>';
        //年級、科目 篩選條件
        $Q_Grade = '';
        $Q_Subject = '';
        $Q_Chapter = '';
        $Sets = '';
        $grade_data = $this->grade();
        $subject_data = array();
        $chap_data = array();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $Q_Grade.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $subject_data = $this->subject($grade_data[0]->g_id);
        }
        if (!empty($subject_data)){
            foreach ($subject_data as $v) {
                $Q_Subject.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $chap_data = $this->chapter($grade_data[0]->g_id, $subject_data[0]->g_id);
        }
        if (!empty($chap_data)){
            foreach ($chap_data as $v) {
                $Q_Chapter.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        $data = array();
        /*
        如果有舊檔，以不刪檔的為主
        上傳裁剪後刪檔 nq
            1.上傳至temp dir
            2.轉圖並裁切
                沒裁切的話，上傳頁進行偵測，有圖跳裁切頁
            3.裁完，多一個btn刪檔

        上傳裁剪後不刪檔 dnq
            1.上傳至temp dir
            2.轉圖並裁切
                沒裁切的話，上傳頁進行偵測，有圖跳裁切頁
            3.裁完，btn重新裁圖，add 刪檔btn
        */
        //題目圖片
        //不刪檔
        $qimg_html = '';
        $data['Qimg'] = '';
        $loading_dnq = false;
        //刪檔
        $loading_nq = false;
        $epno = $this->login_user;
        // if (is_file('questions/tmp/dnqrc_'.$epno.'.jpg')){
        //     $loading_dnq = true;
        //     //不刪檔，裁過的，直接載入
        //     $data['Qimg'] = base_url('questions/tmp/dnqrc_'.$epno.'.jpg');
        //     $qimg_html.= '<input type="button" value="重新裁切" id="dnque" class="btn w100 h25" onClick="uque(this.id);" >';
        // }else if (is_file('questions/tmp/dnqr_'.$epno.'.jpg')){
        //     $loading_dnq = true;
        //     //不刪檔，沒裁過，跳至裁切
        //     $qimg_html.= '<input type="button" value="載入舊圖檔" id="dnque" class="btn w100 h25" onClick="uque(this.id);" >';
        // }
        // if (!$loading_dnq){
        //     if (is_file('questions/tmp/nqrc_'.$epno.'.jpg')){
        //         //刪檔，裁過的，直接載入
        //         $loading_nq = true;
        //         $data['Qimg'] = base_url('questions/tmp/nqrc_'.$epno.'.jpg');
                //$qimg_html.= '<input type="button" value="載入舊圖檔" id="nlque" class="btn w100 h25" onClick="uque(this.id);" >';
            //}else if (is_file('questions/tmp/nqr_'.$epno.'.jpg')){
                //刪檔，沒裁過，跳至裁切
                //$loading_nq = true;
                //$qimg_html.= '<input type="button" value="載入舊圖檔" id="nclque" class="btn w100 h25" onClick="uque(this.id);" >';
            // }
        //     $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        //     $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        // }
        // if ($loading_dnq || $loading_nq){
        //     $qimg_html.= '<input type="button" value="刪除圖檔" id="dque" class="btn w100 h25" onClick="uque(this.id)" >   ';
        // }
        $data['Qimg_html'] = $qimg_html;
        $data['Sets_msg'] = $sets_message;
        $data['Q_Grade'] = $Q_Grade;
        $data['Q_Subject'] = $Q_Subject;
        $data['Q_Chapter'] = $Q_Chapter;

        //詳解圖片
        $aimg_html = '';
        $data['Aimg'] = '';
        $loading_dna = false;
        //刪檔
        $loading_na = false;
        // if (is_file('questions/tmp/dnarc_'.$epno.'.jpg')){
        //     $loading_dna = true;
        //     //不刪檔，裁過的，直接載入
        //     $data['Aimg'] = base_url('questions/tmp/dnarc_'.$epno.'.jpg');
        //     $aimg_html.= '<input type="button" value="重新裁切" id="dnans" class="btn w100 h25" onClick="uans(this.id);" >';
        // }else if (is_file('questions/tmp/dnar_'.$epno.'.jpg')){
        //     $loading_dna = true;
        //     //不刪檔，沒裁過，跳至裁切
        //     $aimg_html.= '<input type="button" value="載入舊圖檔" id="dnans" class="btn w100 h25" onClick="uans(this.id);" >';
        // }
        // if (!$loading_dna){
        //     if (is_file('questions/tmp/narc_'.$epno.'.jpg')){
        //         //刪檔，裁過的，直接載入
        //         $loading_na = true;
        //         $data['Aimg'] = base_url('questions/tmp/narc_'.$epno.'.jpg');
        //         //$aimg_html.= '<input type="button" value="載入舊圖檔" id="nlque" class="btn w100 h25" onClick="uans(this.id);" >';
        //     }else if (is_file('questions/tmp/nar_'.$epno.'.jpg')){
        //         //刪檔，沒裁過，跳至裁切
        //         $loading_na = true;
        //         $aimg_html.= '<input type="button" value="載入舊圖檔" id="nclans" class="btn w100 h25" onClick="uans(this.id);" >';
        //     }
        //     $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        //     $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        // }
        // if ($loading_dna || $loading_na){
        //     $aimg_html.= '<input type="button" value="刪除圖檔" id="dans" class="btn w100 h25" onClick="uans(this.id)" >   ';
        // }
        $data['Aimg_html'] = $aimg_html;
        //難度
        $degree = new \stdClass;
        $degree->E = 'checked';
        $degree->M = '';
        $degree->H = '';
        $data['Degree'] = $degree;
        $data['que_type'] = '';
        $data['title'] = '建立題目';
        return view('que.create', $data);
    }
    public function create_group()
    {
        if (!$this->login_status)return redirect('/login');
        $sets_message = '';//'<div id="sets_title"><label class="17">'.$msg.'</label></div>';
        //年級、科目 篩選條件
        $Q_Grade = '';
        $Q_Subject = '';
        $Q_Chapter = '';
        $Sets = '';
        $grade_data = $this->grade();
        $subject_data = array();
        $chap_data = array();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $Q_Grade.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $subject_data = $this->subject($grade_data[0]->g_id);
        }
        if (!empty($subject_data)){
            foreach ($subject_data as $v) {
                $Q_Subject.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $chap_data = $this->chapter($grade_data[0]->g_id, $subject_data[0]->g_id);
        }
        if (!empty($chap_data)){
            foreach ($chap_data as $v) {
                $Q_Chapter.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        $data = array();
        /*
        如果有舊檔，以不刪檔的為主
        上傳裁剪後刪檔 nq
            1.上傳至temp dir
            2.轉圖並裁切
                沒裁切的話，上傳頁進行偵測，有圖跳裁切頁
            3.裁完，多一個btn刪檔

        上傳裁剪後不刪檔 dnq
            1.上傳至temp dir
            2.轉圖並裁切
                沒裁切的話，上傳頁進行偵測，有圖跳裁切頁
            3.裁完，btn重新裁圖，add 刪檔btn
        */
        //題目圖片
        //不刪檔
        $qimg_html = '';
        $data['Qimg'] = '';
        $loading_dnq = false;
        //刪檔
        $loading_nq = false;
        $epno = $this->login_user;
        // if (is_file('questions/tmp/dnqrc_'.$epno.'.jpg')){
        //     $loading_dnq = true;
        //     //不刪檔，裁過的，直接載入
        //     $data['Qimg'] = base_url('questions/tmp/dnqrc_'.$epno.'.jpg');
        //     $qimg_html.= '<input type="button" value="重新裁切" id="dnque" class="btn w100 h25" onClick="uque(this.id);" >';
        // }else if (is_file('questions/tmp/dnqr_'.$epno.'.jpg')){
        //     $loading_dnq = true;
        //     //不刪檔，沒裁過，跳至裁切
        //     $qimg_html.= '<input type="button" value="載入舊圖檔" id="dnque" class="btn w100 h25" onClick="uque(this.id);" >';
        // }
        // if (!$loading_dnq){
        //     if (is_file('questions/tmp/nqrc_'.$epno.'.jpg')){
        //         //刪檔，裁過的，直接載入
        //         $loading_nq = true;
        //         $data['Qimg'] = base_url('questions/tmp/nqrc_'.$epno.'.jpg');
                //$qimg_html.= '<input type="button" value="載入舊圖檔" id="nlque" class="btn w100 h25" onClick="uque(this.id);" >';
            //}else if (is_file('questions/tmp/nqr_'.$epno.'.jpg')){
                //刪檔，沒裁過，跳至裁切
                //$loading_nq = true;
                //$qimg_html.= '<input type="button" value="載入舊圖檔" id="nclque" class="btn w100 h25" onClick="uque(this.id);" >';
            // }
        //     $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        //     $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        // }
        // if ($loading_dnq || $loading_nq){
        //     $qimg_html.= '<input type="button" value="刪除圖檔" id="dque" class="btn w100 h25" onClick="uque(this.id)" >   ';
        // }
        $data['Qimg_html'] = $qimg_html;
        $data['Sets_msg'] = $sets_message;
        $data['Q_Grade'] = $Q_Grade;
        $data['Q_Subject'] = $Q_Subject;
        $data['Q_Chapter'] = $Q_Chapter;

        //詳解圖片
        $aimg_html = '';
        $data['Aimg'] = '';
        $loading_dna = false;
        //刪檔
        $loading_na = false;
        // if (is_file('questions/tmp/dnarc_'.$epno.'.jpg')){
        //     $loading_dna = true;
        //     //不刪檔，裁過的，直接載入
        //     $data['Aimg'] = base_url('questions/tmp/dnarc_'.$epno.'.jpg');
        //     $aimg_html.= '<input type="button" value="重新裁切" id="dnans" class="btn w100 h25" onClick="uans(this.id);" >';
        // }else if (is_file('questions/tmp/dnar_'.$epno.'.jpg')){
        //     $loading_dna = true;
        //     //不刪檔，沒裁過，跳至裁切
        //     $aimg_html.= '<input type="button" value="載入舊圖檔" id="dnans" class="btn w100 h25" onClick="uans(this.id);" >';
        // }
        // if (!$loading_dna){
        //     if (is_file('questions/tmp/narc_'.$epno.'.jpg')){
        //         //刪檔，裁過的，直接載入
        //         $loading_na = true;
        //         $data['Aimg'] = base_url('questions/tmp/narc_'.$epno.'.jpg');
        //         //$aimg_html.= '<input type="button" value="載入舊圖檔" id="nlque" class="btn w100 h25" onClick="uans(this.id);" >';
        //     }else if (is_file('questions/tmp/nar_'.$epno.'.jpg')){
        //         //刪檔，沒裁過，跳至裁切
        //         $loading_na = true;
        //         $aimg_html.= '<input type="button" value="載入舊圖檔" id="nclans" class="btn w100 h25" onClick="uans(this.id);" >';
        //     }
        //     $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        //     $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        // }
        // if ($loading_dna || $loading_na){
        //     $aimg_html.= '<input type="button" value="刪除圖檔" id="dans" class="btn w100 h25" onClick="uans(this.id)" >   ';
        // }
        $data['Aimg_html'] = $aimg_html;
        //難度
        $degree = new \stdClass;
        $degree->E = 'checked';
        $degree->M = '';
        $degree->H = '';
        $data['Degree'] = $degree;
        $data['que_type'] = '';
        $data['title'] = '建立題組';
        return view('que.createg', $data);
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

        /*
        file()
        ->getClientOriginalName() 原始名稱
        ->getClientOriginalExtension() 副檔名
        ->getSize() 檔案大小 位元組
        ->getMimeType() mime類型  mp3=>audio/mpeg
        ->save(路徑,檔名)
        */
        $que_type = ($req->has('f_qus_type') && !empty($req->input('f_qus_type'))) ? trim($req->input('f_qus_type')):'';
        $quetxt = ($req->has('f_quetxt') && !empty($req->input('f_quetxt'))) ? trim($req->input('f_quetxt')):'';
        $keyword = ($req->has('fk') && !empty($req->input('fk'))) ? $req->input('fk'):array();
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapterui') && (int)$req->input('f_chapterui')>0) ? (int)$req->input('f_chapterui'):0;
        $know_id = ($req->has('f_pid') && (int)$req->input('f_pid')>0) ? (int)$req->input('f_pid'):0;
        $degree = ($req->has('f_degree') && !empty($req->input('f_degree'))) ? trim($req->input('f_degree')):"E";
        $anstxt = ($req->has('f_anstxt') && !empty($req->input('f_anstxt'))) ? trim($req->input('f_anstxt')):'';
        //$qimg = ($req->has('f_qimg') && !empty($req->input('f_qimg'))) ? trim($req->input('f_qimg')):'';
        //$aimg = ($req->has('f_aimg') && !empty($req->input('f_aimg'))) ? trim($req->input('f_aimg')):'';
        $qpath = '';
        $apath = '';

        // if ($graid===0 || $subjid===0 || $chapid===0){
        //  abort(400);
        //  return;
        // }
        switch ($que_type) {
            case 'S'://單選
            case 'D'://複選
            case 'R'://是非
                if ($que_type==="R"){
                    $num = 2;
                }else{
                    $num = ($req->has('option_num') && (int)$req->input('option_num')>1) ? (int)$req->input('option_num'):2;  
                }
                $ans = ($req->has('ans') && is_array($req->input('ans'))) ? $req->input('ans'):array();
                //複選 => 1↑
                //單選 or 是非 => only 1
                if (($que_type==="D" && count($ans)<2) || ($que_type!=="D" && count($ans)!==1)){
                    abort(400);
                    return;
                }
                $error = false;
                foreach ($ans as $v) {
                    $e = (int)$v;
                    if ($e<=0){
                        $error = true;
                        break;
                    }
                }
                if ($error){
                    abort(400);
                    return;
                }
                $all_ans = implode(",", $ans);
                break;
            case 'M'://選填
                $num = ($req->has('num') && (int)$req->input('num')>0) ? (int)$req->input('num'):1;
                $i = 1;
                $ans = array();
                while ($i<=$num) {
                    $each_ans = ($req->has('ans'.$i)) ? $req->input('ans'.$i):-1;
                    if ($each_ans===-1 || !preg_match("/^[0-9ab]*$/", $each_ans)){
                        abort(400);
                        return;
                    }
                    $ans[] = $each_ans;
                    $i++;
                }
                //數量不對
                if ($num!==count($ans)){
                    abort(400);
                    return;
                }
                $all_ans = implode(",", $ans);
                break;
            default:
                abort(400);
                return;
                break;
        }
        //上傳check
        
        if (!is_dir('uploads'))mkdir('uploads',777);
        if (!is_dir('uploads/que'))mkdir('uploads/que',777);
        // const DIR = "uploads/que";
        //題目圖片
        $qm_src = '';
        $qm_name = '';
        $qm_file = $req->file('qpic');
        if ($qm_file!=null){
            $file_error = false;
            if ($req->hasFile('qpic')){
                $mime = $qm_file->getMimeType();
                $all_mime = array('image/jpg','image/jpeg','image/png');
                if (!in_array($mime, $all_mime))$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($qm_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$qm_file->getClientOriginalExtension();
                    $qm_src = UPLOAD_DIR.'/'.$file;
                    $qm_file->move(UPLOAD_DIR, $file);
                    $qm_name = $qm_file->getClientOriginalName();
                }
            }
        }
        //題目聲音
        $qs_src = '';
        $qs_name = '';
        $qs_file = $req->file('qsound');
        if ($qs_file!=null){
            $file_error = false;
            if ($req->hasFile('qsound')) {
                $mime = $qs_file->getMimeType();
                if ($mime!='audio/mpeg')$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($qs_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$qs_file->getClientOriginalExtension();
                    $qs_src = UPLOAD_DIR.'/'.$file;
                    $qs_file->move(UPLOAD_DIR, $file);
                    $qs_name = $qs_file->getClientOriginalName();
                }
            }           
        }
        //詳解圖片
        $am_src = '';
        $am_name = '';
        $am_file = $req->file('apic');
        if ($am_file!=null){
            $file_error = false;
            if ($req->hasFile('apic')) {
                $mime = $am_file->getMimeType();
                $all_mime = array('image/jpg','image/jpeg','image/png');
                if (!in_array($mime, $all_mime))$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($am_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$am_file->getClientOriginalExtension();
                    $am_src = UPLOAD_DIR.'/'.$file;
                    $am_file->move(UPLOAD_DIR, $file);
                    $am_name = $am_file->getClientOriginalName();
                }
            }           
        }
        //詳解聲音
        $as_src = '';
        $as_name = '';
        $as_file = $req->file('asound');
        if ($as_file!=null){
            $file_error = false;
            if ($req->hasFile('asound')) {
                $mime = $as_file->getMimeType();
                if ($mime!='audio/mpeg')$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($as_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$as_file->getClientOriginalExtension();
                    $as_src = UPLOAD_DIR.'/'.$file;
                    $as_file->move(UPLOAD_DIR, $file);
                    $as_name = $as_file->getClientOriginalName();
                }
            }           
        }
        //詳解影片
        $av_src = '';
        $av_name = '';
        $av_file = $req->file('avideo');
        if ($av_file!=null){
            $file_error = false;
            if ($req->hasFile('avideo')) {
                $mime = $av_file->getMimeType();
                if ($mime!='video/mpeg')$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($av_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$av_file->getClientOriginalExtension();
                    $av_src = UPLOAD_DIR.'/'.$file;
                    $av_file->move(UPLOAD_DIR, $file);
                    $av_name = $av_file->getClientOriginalName();
                }
            }           
        }
        $key = array();
        foreach ($keyword as $v) {
            if (!empty($v))$key[] = $v;
        }
        $save = [
            'q_quetype' => $que_type,
            'q_quetxt' => $quetxt,
            'q_qm_src' => $qm_src,
            'q_qm_name' => $qm_name,
            'q_qs_src' => $qs_src,
            'q_qs_name' => $qs_name,
            'q_num' => $num,
            'q_ans' => $all_ans,
            'q_anstxt' => $anstxt,
            'q_am_src' => $am_src,
            'q_am_name' => $am_name,
            'q_as_src' => $as_src,
            'q_as_name' => $as_name,
            'q_av_src' => $av_src,
            'q_av_name' => $av_name,
            'q_owner' => $this->login_user,
            'q_degree' => $degree,
            'q_gra' => $graid,
            'q_subj' => $subjid,
            'q_chap' => $chapid,
            'q_know' => $know_id,
            'q_created_at' => time(),
            'q_updated_at' => time(),
            'q_keyword' => '|'.implode('|', $key).'|'
        ];
        $que_data = new Ques;
        $que_data->fill($save);
        $que_data->save();
        echo '<script>opener.location.reload();window.close();</script>';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($qid)
    {
        if (!preg_match("/^[0-9]*$/", $qid))abort(400);
        if ($qid<1)abort(400);
        $qdata = Ques::find($qid);
        //題型、答案
        $format = $this->_ques_ans_format($qdata);
        $Quetype = $format->q_quetype.'題';
        $Ans = $format->q_ans;
        $Degree = $format->q_degree;
        $Grade = $qdata->gra->name;
        $Subject = $qdata->subj->name;
        $Chapter = $qdata->chap->name;
        $Owner = $qdata->q_owner;

        $qcont =  array();
        //題目文字
        if (!empty($v->q_quetxt)) $qcont[] = nl2br(trim($qdata->q_quetxt));
        //題目圖檔
        if (!empty($qdata->q_qm_src)){
            if(is_file($qdata->q_qm_src))$qcont[] = '圖：'.$qdata->q_qm_name.'<br><IMG class="pic" src="'.URL::asset($qdata->q_qm_src).'">';
        }
        //題目聲音檔
        if (!empty($qdata->q_qs_src)){
            $qs_name = '音訊：'.$qdata->q_qs_name;
            if(is_file($qdata->q_qs_src)){
                $qcont[] = $qs_name.'<br><audio controls preload oncontextmenu="return false;">
                        <source src="'.URL::asset($qdata->q_qs_src).'" type="audio/mpeg">
                        <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                      </audio>';
            }else{
                $qcont[] = $qs_name.'<font color="red">(遺失)</font>';
            }
        }
        $Que_content = implode("<br>", $qcont);

        $acont = array();
        //詳解文字
        if (!empty($qdata->q_anstxt)) $acont[] = nl2br(trim($qdata->q_anstxt));
        //詳解圖檔
        if(!empty($qdata->q_am_src)){
            if (is_file($qdata->q_am_src))$acont[] = '圖：'.$qdata->q_am_name.'<br><IMG class="pic" src="'.URL::asset($qdata->q_am_src).'">';
        }
        $amedia = array();
        //詳解聲音檔
        if(!empty($qdata->q_as_src)){
            $as_name = '音訊：'.$qdata->q_as_name;
            if(is_file($qdata->q_as_src)){
                $amedia[] = $as_name.'<br><audio controls preload oncontextmenu="return false;">
                        <source src="'.URL::asset($qdata->q_as_src).'" type="audio/mpeg">
                        <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                      </audio>';
            }else{
                $amedia[] = $as_name.'(<font color="red">遺失</font>)';
            }
        }
        //詳解影片檔
        if(!empty($qdata->q_av_src)){
            $av_name = '視訊：'.$qdata->q_av_name;
            if(is_file($qdata->q_av_src)){
                $amedia[] = $av_name.'<br><video controls preload oncontextmenu="return false;">
                        <source src="'.URL::asset($qdata->q_av_src).'" type="video/mpeg">
                        <em>提醒您，您的瀏覽器無法支援此服務的媒體，請更新</em>
                      </video>';
            }else{
                $amedia[] = $av_name.'(<font color="red">遺失</font>)';
            }
        }
        $acont[] = implode(' | ', $amedia);
        $Ans_content = implode("<br>", $acont);
        $Know_content = ($qdata->q_know!==0) ? $qdata->knows->name:'';

        $keyword = explode("|", $qdata->q_keyword);
        $key = array();
        foreach ($keyword as $v) {
            $c = (string)$v;
            if ($c==="")continue;
            $key[] = $v;
        }
        $Keyword = implode(", ", $key);
        $title = '題目資訊-第'.$qid.'題';
        return view("que.info", 
            compact("qid", "title", "Owner", "Quetype", "Que_content", 
                    "Know_content", "Grade", "Subject", "Chapter", "Degree", 
                    "Ans", "Ans_content", "Keyword"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($qid)
    {
        if (!$this->login_status)return redirect('/login');
        if (!preg_match("/^[0-9]*$/", $qid))abort(400);
        $qid = (int)$qid;
        if ($qid<1)abort(400);
        if (session('ident')!=="T"){
            echo '很抱歉，權限不足';
            return;
        }

        $que = Ques::find($qid);
        if ($que==null)die('無此資料');

        $data = array();
        $que_type = new \stdClass;
        $que_type->S = '';
        $que_type->D = '';
        $que_type->R = '';
        $que_type->M = '';
        $que_type->G = '';
        $ans_html = '';
        $option_num = '';
        $num = '';
        $data['now_type'] = '';
        $data['Num'] = '';
        $data['Rtype'] = '';
        $data['Correct_ans_math'] = '';
        
        switch ($que->q_quetype) {
            case 'S': 
            case 'D': 
                //選項個數
                $num_i = 2;
                while ($num_i<=12) {
                    $num_sel = ($num_i===$que->q_num) ? 'selected':'';
                    $option_num.= '<option '.$num_sel.' value="'.$num_i.'">'.$num_i.'</option>';
                    $num_i++;
                }
                //正確答案
                $ans_i = 1;
                if ($que->q_quetype==="S"){
                    $que_type->S = 'checked';
                    while ($ans_i<=$que->q_num) {
                        $ans_sel = ($ans_i===(int)$que->q_ans) ? 'checked':'';
                        $ans_html.= '<label><input name="ans[]" '.$ans_sel.' type="radio" value="'.$ans_i.'"><font id="ans_'.$ans_i.'">'.chr($ans_i+64).'</font></label>';
                        $ans_i++;
                    }
                }else{
                    $que_type->D = 'checked';
                    $ans = explode(',', $que->q_ans);
                    while ($ans_i<=$que->q_num) {
                        $ans_sel = '';
                        foreach ($ans as $v) {
                            if ((int)$v==$ans_i){
                                $ans_sel = 'checked';
                                break;
                            }
                        }
                        $ans_html.= '<label><input name="ans[]" '.$ans_sel.' type="checkbox" value="'.$ans_i.'"><font id="ans_'.$ans_i.'">'.chr($ans_i+64).'</font></label>';
                        $ans_i++;
                    }
                }
                break;
            case 'R': 
                $que_type->R = 'checked';
                $data['Rtype'] = 'style="display:none;"';
                $ans_html = '<label><input type="radio" '.(($que->q_ans==="1") ? 'checked':'').' name="ans[]" value="1">O</label>  ';
                $ans_html.= '<label><input type="radio" '.(($que->q_ans==="2") ? 'checked':'').' name="ans[]" value="2">X</label>';
                break;
            case 'M': 
                $que_type->M = 'checked';
                $data['now_type'] = "change_type('M');";
                $num_i = 1;
                while ($num_i<=12) {
                    $num_sel = ($num_i===$que->q_num) ? 'selected':'';
                    $num.= '<option '.$num_sel.' value="'.$num_i.'">'.$num_i.'</option>';
                    $num_i++;
                }
                $ans = explode(',', $que->q_ans);
                $ans_math = '';
                foreach ($ans as $i => $o) {
                    $ans_math.= '<div id="a'.($i+1).'"><span>No.'.($i+1).'</span>';

                    if (preg_match("/^[0-9]*$/", $o)){
                        $now = (int)$o;
                    }else{
                        if ($o==="a")$now = 10;
                        if ($o==="b")$now = 11;
                    }
                    $each = 1;
                    while($each<=9){
                        $sel = ($each===$now) ? 'checked':'';
                        $ans_math.= '<label><input type="radio" '.$sel.' name="ans'.($i+1).'" value="'.$each.'">'.$each.'</label>';
                        $each++;
                    }
                    $ans_math.= '<label><input type="radio" '.(($now===0) ? 'checked':'').' name="ans'.($i+1).'" value="0">0</label>';
                    $ans_math.= '<label><input type="radio" '.(($now===10) ? 'checked':'').' name="ans'.($i+1).'" value="a">-</label>';
                    $ans_math.= '<label><input type="radio" '.(($now===11) ? 'checked':'').' name="ans'.($i+1).'" value="b">±</label>';
                    $ans_math.= '</div>';
                }
                $data['Correct_ans_math'] = $ans_math;
                break;
        }
        if ($que->q_know>0){
            $data['Know_cancell'] = '<input type="button" class="" name="pcancell" id="pcancell" value="取消知識點">';
        }else{
            $data['Know_cancell'] = '';
        }
        //單複選 選項個數 初始化
        if ($que->q_quetype!=="S" && $que->q_quetype!=="D"){
            $num_i = 2;
            while ($num_i<=12) {
                $num_sel = ($num_i===$que->q_num) ? 'selected':'';
                $option_num.= '<option '.$num_sel.' value="'.$num_i.'">'.$num_i.'</option>';
                $num_i++;
            }
        }       
        //選填 選項、題數個數 初始化
        if ($que->q_quetype!=="M"){
            $num_i = 1;
            while ($num_i<=12) {
                $num.= '<option value="'.$num_i.'">'.$num_i.'</option>';
                $num_i++;
            }
            $ans_math = '<div id="a1"><span>No.1</span>';
            $each = 1;
            while($each<=9){
                $ans_math.= '<label><input type="radio" name="ans1" value="'.$each.'">'.$each.'</label>';
                $each++;
            }
            $ans_math.= '<label><input type="radio" name="ans1" value="0">0</label>';
            $ans_math.= '<label><input type="radio" name="ans1" value="a">-</label>';
            $ans_math.= '<label><input type="radio" name="ans1" value="b">±</label>';
            $ans_math.= '</div>';
            $data['Correct_ans_math'] = $ans_math;
        }
        $Q_Grade = '';
        $Q_Subject = '';
        $Q_Chapter = '';
        $grade_data = $this->grade();
        $subject_data = array();
        $chap_data = array();
        
        
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $g_sel = ($que->q_gra===$v->g_id) ? 'selected':'';
                $Q_Grade.= '<option '.$g_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $subject_data = $this->subject($que->q_gra);
        }
        $data['Q_Grade'] = $Q_Grade;
        if (!empty($subject_data)){
            foreach ($subject_data as $v) {
                $s_sel = ($que->q_subj===$v->g_id) ? 'selected':'';
                $Q_Subject.= '<option '.$s_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $chap_data = $this->chapter($que->q_gra, $que->q_subj);
        }
        $data['Q_Subject'] = $Q_Subject;
        if (!empty($chap_data)){
            foreach ($chap_data as $v) {
                $c_sel = ($que->q_chap===$v->g_id) ? 'selected':'';
                $Q_Chapter.= '<option '.$c_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        $data['Q_Chapter'] = $Q_Chapter;

        //題目圖片
        $qimg_html = '';
        $del_qimg = '';
        $data['Qimg'] = '';
        $qm_upload = true;
        if (!empty($que->q_qm_src)){
            $qimg_html = '檔名：'.$que->q_qm_name;
            if (is_file($que->q_qm_src)){
                $qimg_html.= '　<input type="button" value="刪除圖片"  class="btn w100" id="delqm" onclick="rem(this.id)">';
                $qm_upload = false;
            }else{
                $qimg_html.= '　<font color="red">檔案遺失</font>';
            }
            $data['Qimg'] = URL::asset($que->q_qm_src);
            
        }
        // if (!empty($que->QIMGSRC)){
        //     if (is_file($que->QIMGSRC)){
        //         $data['Qimg'] = base_url($que->QIMGSRC);
        //         $del_qimg = '<input type="button" value="刪除圖檔" id="deque" class="btn w100 h25" onClick="uque(this.id)" >   ';
        //     }
        // }
        // $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        // $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        // $qimg_html.= $del_qimg;
        
        //題目音訊
        $qsound_html = '';
        $qs_upload = true;
        if (!empty($que->q_qs_src)){
            $qsound_html = '檔名：'.$que->q_qs_name;
            if (is_file($que->q_qs_src)){
                $qsound_html.= '　<input type="button" value="刪除聲音檔"  class="btn w100" id="delqs" onclick="rem(this.id)">';
                $qs_upload = false;
            }else{
                $qsound_html.= '　<font color="red">檔案遺失</font>';
            }
            $qsound_html.='<br>';
        }
        //詳解圖片
        $aimg_html = '';
        $del_aimg = '';
        $data['Aimg'] = '';
        $am_upload = true;
        if (!empty($que->q_am_src)){
            $aimg_html = '檔名：'.$que->q_am_name;
            if (is_file($que->q_am_src)){
                $aimg_html.= '　<input type="button" value="刪除圖片"  class="btn w100" id="delam" onclick="rem(this.id)">';
                $am_upload = false;
            }else{
                $aimg_html.= '　<font color="red">檔案遺失</font>';
            }
            $data['Aimg'] = URL::asset($que->q_am_src);
        }
        // if (!empty($que->AIMGSRC)){
        //     if (is_file($que->AIMGSRC)){
        //         $data['Aimg'] = base_url($que->AIMGSRC);
        //         $del_aimg = '<input type="button" value="刪除圖檔" id="deans" class="btn w100 h25" onClick="uans(this.id)" >   ';
        //     }
        // }
        // $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        // $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        // $aimg_html.= $del_aimg;

        //詳解音訊
        $asound_html = '';
        $as_upload = true;
        if (!empty($que->q_as_src)){
            $asound_html = '檔名：'.$que->q_as_name;
            if (is_file($que->q_as_src)){
                $asound_html.= '　<input type="button" value="刪除聲音檔"  class="btn w100" id="delas" onclick="rem(this.id)">';
                $as_upload = false;
            }else{
                $asound_html.= '　<font color="red">檔案遺失</font>';
            }
            $asound_html.= '<br>';
        }
        //詳解視訊
        $avideo_html = '';
        $av_upload = true;
        if (!empty($que->q_av_src)){
            if (is_file($que->q_av_src)){
                $avideo_html.= '檔名：'.$que->q_av_name.'　<input type="button" value="刪除影片檔"  class="btn w100" id="delav" onclick="rem(this.id)">';
                $av_upload = false;
            }else{
                $avideo_html.= '<font color="red">檔案遺失</font>';
            }
        }else{
            $avideo_html.= '<br>';
        }

        $data['Qid'] = $qid;
        $data['Qimgsrc'] = $que->q_qm_src;
        $data['Qimg_html'] = $qimg_html;
        $data['Qmsold'] = ($qm_upload) ? 'class="hiden"':'style="inline-block;"';
        $data['Qm_upload'] = (!$qm_upload) ? 'class="hiden"':'';
        $data['Quetxt'] = $que->q_quetxt;
        $data['Qsoundsrc'] = $que->q_qs_src;
        $data['Qsound_html'] = $qsound_html;
        $data['Qsold'] = ($qs_upload) ? 'class="hiden"':'style="inline-block;"';
        $data['Qs_upload'] = (!$qs_upload) ? 'class="hiden"':'';
        $keyword = explode("|", $que->q_keyword);
        $key = array();
        foreach ($keyword as $v) {
            $c = (string)$v;
            if ($c==="")continue;
            $key[] = $v;
        }
        $key_count = count($key);
        if ($key_count<5){
            while(5>$key_count){
                $key[] = "";
                $key_count++;
            }
        }
        $data['Keyword'] = $key;        
        $data['Anstxt'] = $que->q_anstxt;
        $data['Aimgsrc'] = $que->q_am_src;
        $data['Aimg_html'] = $aimg_html;
        $data['Amsold'] = ($am_upload) ? 'class="hiden"':'style="inline-block;"';
        $data['Am_upload'] = (!$am_upload) ? 'class="hiden"':'';
        $data['Asoundsrc'] = $que->q_as_src;
        $data['Asound_html'] = $asound_html;
        $data['Asold'] = ($as_upload) ? 'class="hiden"':'style="inline-block;"';
        $data['As_upload'] = (!$as_upload) ? 'class="hiden"':'';
        $data['Avideosrc'] = $que->q_av_src;
        $data['Avideo_html'] = $avideo_html;
        $data['Avold'] = ($av_upload) ? 'class="hiden"':'style="inline-block;"';
        $data['Av_upload'] = (!$av_upload) ? 'class="hiden"':'';

        $data['Kid'] = $que->q_know;
        $data['Kname'] = ($que->q_know>0) ? $que->knows->name:'';

        $data['Option_num'] = $option_num;
        $data['Num'] = $num;
        $data['Ans'] = $ans_html;
        $data['Que_type'] = $que_type;
        

        //難度
        $degree = new \stdClass;
        $degree->E = '';
        $degree->M = '';
        $degree->H = '';
        switch ($que->q_degree) {
            case 'M': $degree->M = 'checked'; break;
            case 'H': $degree->H = 'checked'; break;
            case 'E': $degree->E = 'checked'; break;
            default: $degree->E = 'checked'; break;
        }
        $data['title'] = "編輯題目";
        $data['Degree'] = $degree;
        return view('que.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $qid)
    {
        if (!$this->login_status)return redirect('/login');
        if (!preg_match("/^[0-9]*$/", $qid))abort(400);
        $qid = (int)$qid;
        if (session('ident')!=="T"){
            die('很抱歉，權限不足');
            return;
        }
        $que_type = ($req->has('f_qus_type') && !empty($req->input('f_qus_type'))) ? trim($req->input('f_qus_type')):'';
        $quetxt = ($req->has('f_quetxt') && !empty($req->input('f_quetxt'))) ? trim($req->input('f_quetxt')):'';
        $keyword = ($req->has('fk') && !empty($req->input('fk'))) ? $req->input('fk'):array();
        $know_id = ($req->has('f_pid') && (int)$req->input('f_pid')>0) ? (int)$req->input('f_pid'):0;
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapterui') && (int)$req->input('f_chapterui')>0) ? (int)$req->input('f_chapterui'):0;
        $degree = ($req->has('f_degree') && !empty($req->input('f_degree'))) ? trim($req->input('f_degree')):"E";
        $anstxt = ($req->has('f_anstxt') && !empty($req->input('f_anstxt'))) ? trim($req->input('f_anstxt')):'';

        switch ($que_type) {
            case 'S'://單選
            case 'D'://複選
            case 'R'://是非
                if ($que_type==="R"){
                    $num = 2;
                }else{
                    $num = ($req->has('option_num') && (int)$req->input('option_num')>1) ? (int)$req->input('option_num'):2;  
                }
                $ans = ($req->has('ans') && is_array($req->input('ans'))) ? $req->input('ans'):array();
                //複選 => 1↑
                //單選 or 是非 => only 1
                if (($que_type==="D" && count($ans)<2) || ($que_type!=="D" && count($ans)!==1)){
                    abort(400);
                    return;
                }
                $error = false;
                foreach ($ans as $v) {
                    $e = (int)$v;
                    if ($e<=0){
                        $error = true;
                        break;
                    }
                }
                if ($error){
                    abort(400);
                    return;
                }
                $all_ans = implode(",", $ans);
                break;
            case 'M'://選填
                $num = ($req->has('num') && (int)$req->input('num')>0) ? (int)$req->input('num'):1;
                $i = 1;
                $ans = array();
                while ($i<=$num) {
                    $each_ans = ($req->has('ans'.$i)) ? $req->input('ans'.$i):-1;
                    if ($each_ans===-1 || !preg_match("/^[0-9ab]*$/", $each_ans)){
                        abort(400);
                        return;
                    }
                    $ans[] = $each_ans;
                    $i++;
                }
                //數量不對
                if ($num!==count($ans)){
                    abort(400);
                    return;
                }
                $all_ans = implode(",", $ans);
                break;
            default:
                abort(400);
                return;
                break;
        }
        $qmold_src = ($req->has('qm_src') && !empty($req->input('qm_src'))) ? trim($req->input('qm_src')):'';
        $qsold_src = ($req->has('qs_src') && !empty($req->input('qs_src'))) ? trim($req->input('qs_src')):'';

        $amold_src = ($req->has('am_src') && !empty($req->input('am_src'))) ? trim($req->input('am_src')):'';
        $asold_src = ($req->has('as_src') && !empty($req->input('as_src'))) ? trim($req->input('as_src')):'';
        $avold_src = ($req->has('av_src') && !empty($req->input('av_src'))) ? trim($req->input('av_src')):'';

        //題目圖片
        $qm_src = '';
        $qm_name = '';
        $qm_file = $req->file('qpic');
        if ($qm_file!=null){
            $file_error = false;
            if ($req->hasFile('qpic')) {
                $mime = $qm_file->getMimeType();
                $all_mime = array('image/jpg','image/jpeg','image/png');
                if (!in_array($mime, $all_mime))$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($qm_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$qm_file->getClientOriginalExtension();
                    $qm_src = UPLOAD_DIR.'/'.$file;
                    $qm_file->move(UPLOAD_DIR, $file);
                    $qm_name = $qm_file->getClientOriginalName();
                }
            }           
        }
        //題目聲音
        $qs_src = '';
        $qs_name = '';
        $qs_file = $req->file('qsound');
        if ($qs_file!=null){
            $file_error = false;
            if ($req->hasFile('qsound')) {
                $mime = $qs_file->getMimeType();
                if ($mime!='audio/mpeg')$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($qs_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$qs_file->getClientOriginalExtension();
                    $qs_src = UPLOAD_DIR.'/'.$file;
                    $qs_file->move(UPLOAD_DIR, $file);
                    $qs_name = $qs_file->getClientOriginalName();
                }
            }           
        }
        //詳解圖片
        $am_src = '';
        $am_name = '';
        $am_file = $req->file('apic');
        if ($am_file!=null){
            $file_error = false;
            if ($req->hasFile('apic')) {
                $mime = $am_file->getMimeType();
                $all_mime = array('image/jpg','image/jpeg','image/png');
                if (!in_array($mime, $all_mime))$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($am_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$am_file->getClientOriginalExtension();
                    $am_src = UPLOAD_DIR.'/'.$file;
                    $am_file->move(UPLOAD_DIR, $file);
                    $am_name = $am_file->getClientOriginalName();
                }
            }           
        }
        //詳解聲音
        $as_src = '';
        $as_name = '';
        $as_file = $req->file('asound');
        if ($as_file!=null){
            $file_error = false;
            if ($req->hasFile('asound')) {
                $mime = $as_file->getMimeType();
                if ($mime!='audio/mpeg')$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($as_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$as_file->getClientOriginalExtension();
                    $as_src = UPLOAD_DIR.'/'.$file;
                    $as_file->move(UPLOAD_DIR, $file);
                    $as_name = $as_file->getClientOriginalName();
                }
            }           
        }
        //詳解影片
        $av_src = '';
        $av_name = '';
        $av_file = $req->file('avideo');
        if ($av_file!=null){
            $file_error = false;
            if ($req->hasFile('avideo')) {
                $mime = $av_file->getMimeType();
                if ($mime!='video/mpeg')$file_error = true;
                if (!$file_error){
                    //上傳
                    $uuid = md5($av_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$av_file->getClientOriginalExtension();
                    $av_src = UPLOAD_DIR.'/'.$file;
                    $av_file->move(UPLOAD_DIR, $file);
                    $av_name = $av_file->getClientOriginalName();
                }
            }           
        }
        $key = array();
        foreach ($keyword as $v) {
            $c = (string)$v;
            if ($c==="")continue;
            $key[] = $v;
        }

        $que = Ques::find($qid);
        $que->q_quetype = $que_type;
        $que->q_quetxt = $quetxt;
        $que->q_keyword = "|".implode("|", $key)."|";
        $que->q_gra = $graid;
        $que->q_subj = $subjid;
        $que->q_chap = $chapid;
        $que->q_degree = $degree;
        $que->q_anstxt = $anstxt;
        $que->q_ans = $all_ans;
        $que->q_num = $num;
        $que->q_know = $know_id;
        $que->q_updated_at = time();

        //刪舊的或本來就沒有
        if (empty($qmold_src)){
            if (!empty($que->q_qm_src)){
                if (is_file($que->q_qm_src)){ if (unlink($que->q_qm_src)){} }
            }
            $que->q_qm_src = $qm_src;
            $que->q_qm_name = $qm_name;
        }
        if (empty($qsold_src)){
            if (!empty($que->q_qs_src)){
                if (is_file($que->q_qs_src)){ if (unlink($que->q_qs_src)){} }
            }
            $que->q_qs_src = $qs_src;
            $que->q_qs_name = $qs_name;
        }
        if (empty($amold_src)){
            if (!empty($que->q_am_src)){
                if (is_file($que->q_am_src)){ if (unlink($que->q_am_src)){} }
            }
            $que->q_am_src = $am_src;
            $que->q_am_name = $am_name;
        }
        if (empty($asold_src)){
            if (!empty($que->q_as_src)){
                if (is_file($que->q_as_src)){ if (unlink($que->q_as_src)){} }
            }
            $que->q_as_src = $as_src;
            $que->q_as_name = $as_name;
        }
        if (empty($avold_src)){
            if (!empty($que->q_av_src)){
                if (is_file($que->q_av_src)){
                    if (unlink($que->q_av_src)){}
                }
            }
            $que->q_av_src = $av_src;
            $que->q_av_name = $av_name;
        }
        $que->save();
        echo '<script>opener.location.reload();window.close();</script>';
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
    public function join(){
        if (!$this->login_status)die('登入逾時，請重新登入');

        $data = $this->ques_list();
        return view('que.join', $data);
    }
}

