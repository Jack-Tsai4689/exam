<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Ques;
use Auth;
class QueController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = array();
        $p_gra = 0;
        $p_subj = 0;
        $p_chap = 0;
        $p_degree = '';

        $sel_Degree = new \stdClass;
        $sel_Degree->A = '';
        $sel_Degree->E = '';
        $sel_Degree->M = '';
        $sel_Degree->H = '';

        //年級、科目 篩選條件
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
        $que_data = Ques::all();
        foreach ($que_data as $k => $v) {
            //題型、答案
            switch ($v->q_quetype) {
                case "S": 
                    $que_data[$k]->q_quetype = "單選"; 
                    $que_data[$k]->q_ans = chr($v->q_ans+64);
                    break;
                case "D": 
                    $que_data[$k]->q_quetype = "複選"; 
                    $ans = array();
                    $ans = explode(",", $v->q_ans);
                    $ans_html = array();
                    foreach ($ans as $o) {
                        $ans_html[] = chr($o+64);
                    }
                    $que_data[$k]->q_ans = implode(", ", $ans_html);
                    break;
                case "R": 
                    $que_data[$k]->q_quetype = "是非"; 
                    $que_data[$k]->q_ans = ($v->q_ans==="1") ? "O":"X";
                    break;
                case "M": 
                    $que_data[$k]->q_quetype = '選填'; 
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
                    $que_data[$k]->q_ans = implode(", ", $ans_html);
                    break;
            }
            $qcont =  array();
            //題目文字
            if (!empty($v->q_quetxt)) $qcont[] = nl2br(trim($v->q_quetxt));
            //題目圖檔
            if (!empty($v->q_qm_src)){
                if(is_file($v->q_qm_src))$qcont[] = '<IMG name="t_imgsrc" src="'.$v->q_qm_src.'" width="98%">';
            }
            //題目聲音檔
            if (!empty($v->q_qs_src)){
                if(is_file($v->q_qs_src)){
                    $qcont[] = '<font color="green">題目音訊 O</font>';
                }else{
                    $qcont[] = '<font color="red">題目音訊遺失 X</font>';
                }
            }
            $que_data[$k]->q_qcont = implode("<br>", $qcont);

            $acont = array();
            //詳解文字
            if (!empty($v->q_anstxt)) $acont[] = nl2br(trim($v->q_anstxt));
            //詳解圖檔
            if(!empty($v->q_am_src)){
                if (is_file($v->q_am_src))$acont[] = '<IMG name="t_imgsrc"  src="'.$v->q_am_src.'" width="98%">';
            }
            $amedia = array();
            //詳解聲音檔
            if(!empty($v->q_as_src)){
                if(is_file($v->q_as_src)){
                    $amedia[] = '<font color="green">詳解音訊 O</font>';
                }else{
                    $amedia[] = '<font color="red">詳解音訊遺失 X</font>';
                }
            }
            //詳解影片檔
            if(!empty($v->q_av_src)){
                if(is_file($v->q_av_src)){
                    $amedia[] = '<font color="green">詳解視訊 O</font>';
                }else{
                    $amedia[] = '<font color="red">詳解視訊遺失 X</font>';
                }
            }
            $acont[] = implode(' | ', $amedia);
            $que_data[$k]->q_acont = '<br>'.implode("<br>", $acont);
            //難度
            switch ($v->DEGREE) {
                case "M": $que_data[$k]->q_degree = "中等"; break;
                case "H": $que_data[$k]->q_degree = "困難"; break;
                case "E": $que_data[$k]->q_degree = "容易"; break;
                default: $que_data[$k]->q_degree = "容易"; break;
            }
            $que_data[$k]->q_update = date('Y/m/d H:i:s', $v->q_updated_at);
            $que_data[$k]->q_know = ($v->q_know!==0) ? '知識點：'.$v->knows->name:'';

            $que_data[$k]->q_gra = $v->gra->name;
            $que_data[$k]->q_subj = $v->subj->name;
            $que_data[$k]->q_chap = $v->chap->name;
        }
        return view('que.index', [
            'menu_user' => $this->menu_user,
            'title' => '題庫',
            'Data' => $que_data,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Chapter' => $chap_html,
            'Degree' => $sel_Degree,
            'Prev' => '',
            'Next' => '',
            'Pg' => ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sets_message = '';//'<div id="sets_title"><label class="17">'.$msg.'</label></div>';
        $data = array();
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
        if (is_file('questions/tmp/dnqrc_'.$epno.'.jpg')){
            $loading_dnq = true;
            //不刪檔，裁過的，直接載入
            $data['Qimg'] = base_url('questions/tmp/dnqrc_'.$epno.'.jpg');
            $qimg_html.= '<input type="button" value="重新裁切" id="dnque" class="btn w100 h25" onClick="uque(this.id);" >';
        }else if (is_file('questions/tmp/dnqr_'.$epno.'.jpg')){
            $loading_dnq = true;
            //不刪檔，沒裁過，跳至裁切
            $qimg_html.= '<input type="button" value="載入舊圖檔" id="dnque" class="btn w100 h25" onClick="uque(this.id);" >';
        }
        if (!$loading_dnq){
            if (is_file('questions/tmp/nqrc_'.$epno.'.jpg')){
                //刪檔，裁過的，直接載入
                $loading_nq = true;
                $data['Qimg'] = base_url('questions/tmp/nqrc_'.$epno.'.jpg');
                //$qimg_html.= '<input type="button" value="載入舊圖檔" id="nlque" class="btn w100 h25" onClick="uque(this.id);" >';
            //}else if (is_file('questions/tmp/nqr_'.$epno.'.jpg')){
                //刪檔，沒裁過，跳至裁切
                //$loading_nq = true;
                //$qimg_html.= '<input type="button" value="載入舊圖檔" id="nclque" class="btn w100 h25" onClick="uque(this.id);" >';
            }
            $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nque" class="btn w160 h25" onClick="uque(this.id)" >   ';
            $qimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnque" class="btn w160 h25" onClick="uque(this.id)" >   ';
        }
        if ($loading_dnq || $loading_nq){
            $qimg_html.= '<input type="button" value="刪除圖檔" id="dque" class="btn w100 h25" onClick="uque(this.id)" >   ';
        }
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
        if (is_file('questions/tmp/dnarc_'.$epno.'.jpg')){
            $loading_dna = true;
            //不刪檔，裁過的，直接載入
            $data['Aimg'] = base_url('questions/tmp/dnarc_'.$epno.'.jpg');
            $aimg_html.= '<input type="button" value="重新裁切" id="dnans" class="btn w100 h25" onClick="uans(this.id);" >';
        }else if (is_file('questions/tmp/dnar_'.$epno.'.jpg')){
            $loading_dna = true;
            //不刪檔，沒裁過，跳至裁切
            $aimg_html.= '<input type="button" value="載入舊圖檔" id="dnans" class="btn w100 h25" onClick="uans(this.id);" >';
        }
        if (!$loading_dna){
            if (is_file('questions/tmp/narc_'.$epno.'.jpg')){
                //刪檔，裁過的，直接載入
                $loading_na = true;
                $data['Aimg'] = base_url('questions/tmp/narc_'.$epno.'.jpg');
                //$aimg_html.= '<input type="button" value="載入舊圖檔" id="nlque" class="btn w100 h25" onClick="uans(this.id);" >';
            }else if (is_file('questions/tmp/nar_'.$epno.'.jpg')){
                //刪檔，沒裁過，跳至裁切
                $loading_na = true;
                $aimg_html.= '<input type="button" value="載入舊圖檔" id="nclans" class="btn w100 h25" onClick="uans(this.id);" >';
            }
            $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nans" class="btn w160 h25" onClick="uans(this.id)" >   ';
            $aimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnans" class="btn w160 h25" onClick="uans(this.id)" >   ';
        }
        if ($loading_dna || $loading_na){
            $aimg_html.= '<input type="button" value="刪除圖檔" id="dans" class="btn w100 h25" onClick="uans(this.id)" >   ';
        }
        $data['Aimg_html'] = $aimg_html;
        //難度
        $degree = new \stdClass;
        $degree->E = 'checked';
        $degree->M = '';
        $degree->H = '';
        $data['Degree'] = $degree;
        $data['que_type'] = '';
        $data['title'] = '建立題庫';
        return view('que.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
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
        $keyword = ($req->has('f_keyword') && !empty($req->input('f_keyword'))) ? trim($req->input('f_keyword')):'';
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapterui') && (int)$req->input('f_chapterui')>0) ? (int)$req->input('f_chapterui'):0;
        $know_id = ($req->has('f_pid') && (int)$req->input('f_pid')>0) ? (int)$req->input('f_pid'):0;
        $degree = ($req->has('f_degree') && !empty($req->input('f_degree'))) ? trim($req->input('f_degree')):"E";
        $anstxt = ($req->has('f_anstxt') && !empty($req->input('f_anstxt'))) ? trim($req->input('f_anstxt')):'';
        $qimg = ($req->has('f_qimg') && !empty($req->input('f_qimg'))) ? trim($req->input('f_qimg')):'';
        $aimg = ($req->has('f_aimg') && !empty($req->input('f_aimg'))) ? trim($req->input('f_aimg')):'';
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
        //題目聲音
        if (!is_dir('uploads'))mkdir('uploads',777);
        if (!is_dir('uploads/que'))mkdir('uploads/que',777);
        $qs_src = '';
        $qs_name = '';
        $qs_file = $req->file('qsound');
        if ($qs_file!=null){
            $file_error = false;
            if ($req->hasFile('qsound')) {
                $mime = $qs_file->getMimeType();
                if ($mime!='audio/mpeg')$file_error = true;
                if (!$file_error){
                    $uuid = md5(uniqid(rand(), true));
                    //上傳
                    $qs_file->move('uploads/que', $uuid.'.'.$qs_file->getClientOriginalExtension());
                    $qs_src = 'uploads/que/'.$uuid.'.'.$qs_file->getClientOriginalExtension();
                    $qs_name = $qs_file->getClientOriginalName();
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
                    $uuid = md5(uniqid(rand(), true));
                    //上傳
                    $as_file->move('uploads/que', $uuid.'.'.$as_file->getClientOriginalExtension());
                    $as_src = 'uploads/que/'.$uuid.'.'.$as_file->getClientOriginalExtension();
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
                    $uuid = md5(uniqid(rand(), true));
                    //上傳
                    $av_file->move('uploads/que', $uuid.'.'.$av_file->getClientOriginalExtension());
                    $av_src = 'uploads/que/'.$uuid.'.'.$av_file->getClientOriginalExtension();
                    $av_name = $av_file->getClientOriginalName();
                }
            }           
        }
        $save = [
            'q_quetype' => $que_type,
            'q_quetxt' => $quetxt,
            'q_qm_src' => '',
            'q_qm_name' => '',
            'q_qs_src' => $qs_src,
            'q_qs_name' => $qs_name,
            'q_num' => $num,
            'q_ans' => $all_ans,
            'q_anstxt' => $anstxt,
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
            'q_keyword' => $keyword
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
    public function edit($qid)
    {
        if (!is_numeric($qid))abort(400);
        $qid = (int)$qid;
        if ($qid<=0)abort(400);
        if (Auth::user()->e_ident!=="A" && Auth::user()->e_ident!=="T"){
            die('很抱歉，權限不足');
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
        if (!empty($subject_data)){
            foreach ($subject_data as $v) {
                $s_sel = ($que->q_subj===$v->g_id) ? 'selected':'';
                $Q_Subject.= '<option '.$s_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $chap_data = $this->chapter($que->q_gra, $que->q_subj);
        }
        if (!empty($chap_data)){
            foreach ($chap_data as $v) {
                $c_sel = ($que->q_chap===$v->g_id) ? 'selected':'';
                $Q_Chapter.= '<option '.$c_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }


        //題目圖片
        $qimg_html = '';
        $del_qimg = '';
        $data['Qimg'] = '';
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
        if (!empty($que->q_qs_src)){
            if (is_file($que->q_qs_src)){
                $qsound_html.= '檔名：'.$que->q_qs_name;
                $qsound_html.= '<br><input type="button" value="刪除聲音檔"  class="btn w100" name="delsaudio" id="delsaudio" onclick="rem("imgsrc_s","")">';
            }else{
                $qsound_html.= '<font color="red">檔案遺失</font>';
            }
            $qsound_html.='<br>';
        }
        //詳解圖片
        $aimg_html = '';
        $del_aimg = '';
        $data['Aimg'] = '';
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
        if (!empty($que->q_as_src)){
            if (is_file($que->q_as_src)){
                $asound_html.= '檔名：'.$que->q_as_name;
                $asound_html.= '<br><input type="button" value="刪除聲音檔"  class="btn w100" name="delsaudio" id="delsaudio" onclick="rem("imgsrc_s","")">';
            }else{
                $asound_html.= '<font color="red">檔案遺失</font>';
            }
            $asound_html.= '<br>';
        }
        //詳解視訊
        $avideo_html = '';
        if (!empty($que->q_av_src)){
            if (is_file($que->q_av_src)){
                $avideo_html.= '檔名：'.$que->q_as_name;
                $avideo_html.= '<br><input type="button" value="刪除影片檔"  class="btn w100" name="delsaudio" id="delsaudio" onclick="rem("imgsolv","")">';
            }else{
                $avideo_html.= '<font color="red">檔案遺失</font>';
            }
        }else{
            $avideo_html.= '<br>';
        }

        $data['Qid'] = $qid;
        $data['Qimgsrc'] = $que->QIMGSRC;
        $data['Qimg_html'] = $qimg_html;
        $data['Quetxt'] = $que->q_quetxt;
        $data['Qsoundsrc'] = $que->q_qs_src;
        $data['Qsound_html'] = $qsound_html;
        $data['Keyword'] = $que->q_keyword;
        
        $data['Anstxt'] = $que->q_anstxt;
        $data['Aimgsrc'] = $que->q_am_src;
        $data['Aimg_html'] = $aimg_html;
        $data['Asoundsrc'] = $que->q_as_src;
        $data['Asound_html'] = $asound_html;
        $data['Avideosrc'] = $que->q_av_src;
        $data['Avideo_html'] = $avideo_html;
        
        $data['Kid'] = $que->q_know;
        $data['Kname'] = ($que->q_know>0) ? $que->knows->name:'';

        $data['Option_num'] = $option_num;
        $data['Num'] = $num;
        $data['Ans'] = $ans_html;
        $data['Que_type'] = $que_type;
        $data['Q_Grade'] = $Q_Grade;
        $data['Q_Subject'] = $Q_Subject;
        $data['Q_Chapter'] = $Q_Chapter;

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
        $data['title'] = "編輯題庫";
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
        if (!is_numeric($qid))abort(400);
        $qid = (int)$qid;
        if ($qid<=0)abort(400);
        if (Auth::user()->e_ident!=="A" && Auth::user()->e_ident!=="T"){
            die('很抱歉，權限不足');
            return;
        }
        $que_type = ($req->has('f_qus_type') && !empty($req->input('f_qus_type'))) ? trim($req->input('f_qus_type')):'';
        $quetxt = ($req->has('f_quetxt') && !empty($req->input('f_quetxt'))) ? trim($req->input('f_quetxt')):'';
        $keyword = ($req->has('f_keyword') && !empty($req->input('f_keyword'))) ? trim($req->input('f_keyword')):'';
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
        
        $que = Ques::find($qid);
        $que->q_quetype = $que_type;
        $que->q_quetxt = $quetxt;
        $que->q_keyword = $keyword;
        $que->q_gra = $graid;
        $que->q_subj = $subjid;
        $que->q_chap = $chapid;
        $que->q_degree = $degree;
        $que->q_anstxt = $anstxt;
        $que->q_ans = $all_ans;
        $que->q_num = $num;
        $que->q_know = $know_id;
        $que->q_updated_at = time();
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
}
