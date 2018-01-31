<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Ques;

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
        // $subject_data = array();
        // $chapter_data = array();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $sel_gra = ($p_gra===$v->g_id) ? 'selected':'';
                $gra_html.= '<option '.$sel_gra.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            // $subject_data = $this->subject($grade_data[0]->g_id);
        }
        // if (!empty($subject_data)){
        //     foreach ($subject_data as $v) {
        //         $sel_subj = ($p_subj===$v->g_id) ? 'selected':'';
        //         $subj_html.= '<option '.$sel_subj.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
        //     }
        //     $chapter_data = $this->chapter($grade_data[0]->g_id, $subject_data[0]->g_id);
        // }
        // if (!empty($chapter_data)){
        //     foreach ($chapter_data as $v) {
        //         $sel_chap = ($p_chap===$v->g_id) ? 'selected':'';
        //         $chap_html.= '<option '.$sel_chap.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
        //     }
        // }
        $que_data = Ques::all()->all();
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
            if (!empty($v->qm_src)){
                if(is_file($v->qm_src))$qcont[] = '<IMG name="t_imgsrc" src="'.$v->qm_src.'" width="98%">';
            }
            //題目聲音檔
            if (!empty($v->qs_src)){
                if(is_file($v->qs_src)){
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
            if(!empty($v->a_av_src)){
                if(is_file($v->a_av_src)){
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
        // $que_type = Input::get('f_qus_type');
        // $que_txt = Input::get('f_quetxt');
        $qsound = $req->file('qsound');
        //echo Input::get('f_qus_type');
        foreach ($qsound as $v) {
            $uuid = md5(uniqid(rand(), true));
            echo $v->getMimeType().'<br>';
            echo $v->getSize().'<br>';
            echo $v->getClientOriginalExtension().'<br>';
            $v->move('uploads', $uuid.'.mp3');
        }
        

        //$qsound->move('uploads', 'test.mp3');
        exit;
        // $que_type = ($req->has('f_qus_type') && !empty($req->input('f_qus_type')) ? trim($req->input('f_qus_type')):'';
        // $quetxt = ($req->has('f_quetxt') && !empty($req->input('f_quetxt')) ? trim($req->input('f_quetxt')):'';
        // $keyword = ($req->has('f_keyword') && !empty($req->input('f_keyword')) ? trim($req->input('f_keyword')):'';
        // $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        // $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        // $chapid = ($req->has('f_chapterui') && (int)$req->input('f_chapterui')>0) ? (int)$req->input('f_chapterui'):0;

        // $degree = ($req->has('f_degree') && !empty($req->input('f_degree')) ? trim($req->input('f_degree')):"E";
        // $anstxt = ($req->has('f_anstxt') && !empty($req->input('f_anstxt')) ? trim($req->input('f_anstxt')):'';
        // $qimg = ($req->has('f_qimg') && !empty($req->input('f_qimg')) ? trim($req->input('f_qimg')):'';
        // $aimg = ($req->has('f_aimg') && !empty($req->input('f_aimg')) ? trim($req->input('f_aimg')):'';
        // $qpath = '';
        // $apath = '';

        // // if ($graid===0 || $subjid===0 || $chapid===0){
        // //  $this->_errmsg(400);
        // //  return;
        // // }
        // switch ($que_type) {
        //     case 'S'://單選
        //     case 'D'://複選
        //     case 'R'://是非
        //         if ($que_type==="R"){
        //             $num = 2;
        //         }else{
        //             $num = ($req->has('option_num') && (int)$req->input('option_num')>1) ? (int)$req->input('option_num'):2;  
        //         }
        //         $ans = ($req->has('ans') && is_array($req->input('ans')) ? $req->has('ans'):array();
        //         //複選 => 1↑
        //         //單選 or 是非 => only 1
        //         if (($que_type==="D" && count($ans)<2) || ($que_type!=="D" && count($ans)!==1)){
        //             $this->_errmsg(400);
        //             return;
        //         }
        //         $error = false;
        //         foreach ($ans as $v) {
        //             $e = (int)$v;
        //             if ($e<=0){
        //                 $error = true;
        //                 break;
        //             }
        //         }
        //         if ($error){
        //             $this->_errmsg(400);
        //             return;
        //         }
        //         $all_ans = implode(",", $ans);
        //         break;
        //     case 'M'://選填
        //         $num = ($req->has('num') && (int)$req->input('num')>0) ? (int)$req->input('num'):1;
        //         $i = 1;
        //         $ans = array();
        //         while ($i<=$num) {
        //             $each_ans = (isset($_POST['ans'.$i])) ? $_POST['ans'.$i]:-1;
        //             if ($each_ans===-1 || !preg_match("/^[0-9ab]*$/", $each_ans)){
        //                 $this->_errmsg(400);
        //                 return;
        //             }
        //             $ans[] = $each_ans;
        //             $i++;
        //         }
        //         //數量不對
        //         if ($num!==count($ans)){
        //             $this->_errmsg(400);
        //             return;
        //         }
        //         $all_ans = implode(",", $ans);
        //         break;
        //     default:
        //         $this->_errmsg(400);
        //         return;
        //         break;
        // }
        // $config['upload_path'] = 'questions/';
        // $config['allowed_types'] = 'mp3|mp4';
        // $config['max_size'] = '10485760';//10M
        // $config['encrypt_name'] = true;
        // $this->load->library('upload', $config);
        
        // $qs_src = '';
        // $qs_name = '';
        // if (!empty($_FILES['qsound']['name'])){
        //     $this->upload->do_upload('qsound');
        //     $result = $this->upload->data();
        //     $qs_src = 'questions/'.$result['file_name'];
        //     $qs_name = $result['orig_name'];
        // }
        // $as_src = '';
        // $as_name = '';
        // if (!empty($_FILES['asound']['name'])){
        //     $this->upload->do_upload('asound');
        //     $result = $this->upload->data();
        //     $as_src = 'questions/'.$result['file_name'];
        //     $as_name = $result['orig_name'];
        // }
        // $av_src = '';
        // $av_name = '';
        // if (!empty($_FILES['avideo']['name'])){
        //     $this->upload->do_upload('avideo');
        //     $result = $this->upload->data();
        //     $av_src = 'questions/'.$result['file_name'];
        //     $av_name = $result['orig_name'];
        // }
        // if (!empty($qimg)){
        //     if (is_file('questions/tmp/'.$qimg)){
        //         $ext = pathinfo($qimg, PATHINFO_EXTENSION);
        //         $old_nam = 'questions/tmp/'.$qimg;
        //         $new_name = 'questions/'.md5(uniqid(rand(), true)).'.'.$ext;
        //         rename($old_nam, $new_name);
        //         $qpath = $new_name;
        //     }
        // }
        // if (!empty($aimg)){
        //     if (is_file('questions/tmp/'.$aimg)){
        //         $ext = pathinfo($aimg, PATHINFO_EXTENSION);
        //         $old_nam = 'questions/tmp/'.$aimg;
        //         $new_name = 'questions/'.md5(uniqid(rand(), true)).'.'.$ext;
        //         rename($old_nam, $new_name);
        //         $apath = $new_name;
        //     }   
        // }
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
