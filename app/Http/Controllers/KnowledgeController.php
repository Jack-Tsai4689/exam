<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Knows;
use Auth;
class KnowledgeController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->login_status())return redirect('/login');
        $data = Knows::all()->all();
        foreach ($data as $k => $v) {
            //知識點
            $kcont = array();
            //文字
            $kcont[] = '<strong>'.trim($v->k_name).'</strong>';
            if (!empty($v->k_content)) $kcont[] = nl2br(trim($v->k_content));
            //圖檔
            if(!empty($v->k_pic)){
                if (is_file($v->k_pic))$kcont[] = '<IMG class="know" src="'.$v->k_pic.'" width="98%">';
            }
            $data[$k]->k_content = implode("<br>", $kcont);
        }
        $gra_html = '';
        $subj_html = '';
        $chap_html = '';
        $grade_data = $this->grade();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $sel_gra = '';//($p_gra===$v->g_id) ? 'selected':'';
                $gra_html.= '<option '.$sel_gra.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        return view('know.index', [
            'menu_user' => $this->menu_user,
            'title' => '知識點',
            'Data' => $data,
            'Grade' => $gra_html,
            'Subject' => '',
            'Chapter' => '',
            'Prev' => '',
            'Num' => 0,
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
        if (!$this->login_status())return redirect('/login');
        $data = array();
        //年級、科目 篩選條件
        $grade_html = '<option value="0">無年級</option>';
        $subject_html = '<option value="0">無科目</option>';
        $chapter_html = '<option value="0">無章節</option>';
        $grade_data = $this->grade();
        $subject_data = array();
        $chap_data = array();
        if (!empty($grade_data)){
            $grade_html = '';
            foreach ($grade_data as $v) {
                $grade_html.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $subject_data = $this->subject($grade_data[0]->g_id);
        }
        if (!empty($subject_data)){
            $subject_html = '';
            foreach ($subject_data as $v) {
                $subject_html.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $chap_data = $this->chapter($grade_data[0]->g_id, $subject_data[0]->g_id);
        }
        if (!empty($chap_data)){
            $chapter_html = '';
            foreach ($chap_data as $v) {
                $chapter_html.= '<option value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        //題目圖片
        //不刪檔
        $kimg_html = '';
        $data['Kimg'] = '';
        $loading_dnk = false;
        //刪檔
        $loading_nk = false;
        //$epno = $_SESSION['gold']->epno;
        // if (is_file('know/tmp/dnkrc_'.$epno.'.jpg')){
        //     $loading_dnk = true;
        //     //不刪檔，裁過的，直接載入
        //     $data['Kimg'] = base_url('know/tmp/dnkrc_'.$epno.'.jpg');
        //     $kimg_html.= '<input type="button" value="重新裁切" id="dnknow" class="btn w100 h25" onClick="uknow(this.id);" >';
        // }else if (is_file('know/tmp/dnkr_'.$epno.'.jpg')){
        //     $loading_dnk = true;
        //     //不刪檔，沒裁過，跳至裁切
        //     $kimg_html.= '<input type="button" value="載入舊圖檔" id="dnknow" class="btn w100 h25" onClick="uknow(this.id);" >';
        // }
        // if (!$loading_dnk){
        //     if (is_file('know/tmp/nkrc_'.$epno.'.jpg')){
        //         //刪檔，裁過的，直接載入
        //         $loading_nk = true;
        //         $data['Kimg'] = base_url('know/tmp/nkrc_'.$epno.'.jpg');
        //     }
        //     $kimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nknow" class="btn w160 h25" onClick="uknow(this.id)" >   ';
        //     $kimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnknow" class="btn w160 h25" onClick="uknow(this.id)" >   ';
        // }
        // if ($loading_dnk || $loading_nk){
        //     $kimg_html.= '<input type="button" value="刪除圖檔" id="dknow" class="btn w100 h25" onClick="uknow(this.id)" >   ';
        // }
        $data['Kimg_html'] = $kimg_html;
        $data['menu_user'] = $this->menu_user;
        $data['title'] = '知識點 - 新增';
        $data['Owner'] = Auth::user()->e_epno;
        $data['Grade'] = $grade_html;
        $data['Subject'] = $subject_html;
        $data['Chapter'] = $chapter_html;

        return view('know.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        if (!$this->login_status())return redirect('/login');
        $k_name = ($req->has('f_kname') && !empty($req->input('f_kname'))) ? trim($req->input('f_kname')):'';
        $k_content = ($req->has('f_kcont') && !empty($req->input('f_kcont'))) ? trim($req->input('f_kcont')):'';
        $k_keyword = ($req->has('f_kw') && !empty($req->input('f_kw'))) ? trim($req->input('f_kw')):'';
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapter') && (int)$req->input('f_chapter')>0) ? (int)$req->input('f_chapter'):0;

        // if ($graid===0 || $subjid===0 || $chapid===0){
        //  $this->_errmsg(400);
        //  return;
        // }
        // $kimg = (isset($_POST['f_kimg']) && !empty($_POST['f_kimg'])) ? trim($_POST['f_kimg']):'';
        // $kname = '';
        // if (!empty($kimg)){
        //     if (is_file('know/tmp/'.$kimg)){
        //         $ext = pathinfo($kimg, PATHINFO_EXTENSION);
        //         $old_nam = 'know/tmp/'.$kimg;
        //         $new_name = 'know/'.md5(uniqid(rand(), true)).'.'.$ext;
        //         rename($old_nam, $new_name);
        //         $kname = $new_name;
        //     }
        // }
        $data = [
            'k_name' => $k_name,
            'k_pic' => '',//$kname,
            'k_gra' => $graid,
            'k_subj' => $subjid,
            'k_chap' => $chapid,
            'k_content' => $k_content,
            'k_owner' => Auth::user()->e_epno,
            'k_keyword' => $k_keyword,
            'created_at' => time(),
            'updated_at' => time()
        ];
        $save = new Knows;
        $save->fill($data);
        $save->save();
        return redirect('know');
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
    public function edit($kid)
    {
        if (!$this->login_status())return redirect('/login');
        if (!is_numeric($kid))abort(400);
        $kid = (int)$kid;
        if ($kid<=0)abort(400);

        if (Auth::user()->e_ident!=="A" && Auth::user()->e_ident!=="T"){
            die('很抱歉，權限不足');
            return;
        }
        $know = Knows::find($kid);
        if ($know==null)die('無此資料');
        
        $grade_html = '';
        $subject_html = '';
        $chapter_html = '';
        $grade_data = $this->grade();
        $subject_data = array();
        $chap_data = array();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $g_sel = ($v->g_id===$know->k_gra) ? 'selected':'';
                $grade_html.= '<option '.$g_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $subject_data = $this->subject($know->k_gra);
        }
        if (!empty($subject_data)){
            foreach ($subject_data as $v) {
                $s_sel = ($v->g_id===$know->k_subj) ? 'selected':'';
                $subject_html.= '<option '.$s_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
            $chap_data = $this->chapter($know->k_gra, $know->k_subj);
        }
        if (!empty($chap_data)){
            foreach ($chap_data as $v) {
                $c_sel = ($v->g_id===$know->k_chap) ? 'selected':'';
                $chapter_html.= '<option '.$c_sel.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        //題目圖片
        $kimg_html = '';
        $del_qimg = '';
        $data['Kimg'] = '';
        $data['Kimg_src'] = '';
        // if (!empty($know->K_PIC)){
        //     if (is_file($know->K_PIC)){
        //         $data['Kimg'] = base_url($know->K_PIC);
        //         $data['Kimg_src'] = $know->K_PIC;
        //         $del_qimg = '<input type="button" value="刪除圖檔" id="deknow" class="btn w100 h25" onClick="uknow(this.id)" >   ';
        //     }
        // }
        // $kimg_html.= '<input type="button" value="上傳圖檔(裁剪後刪檔)" id="nknow" class="btn w160 h25" onClick="uknow(this.id)" >   ';
        // $kimg_html.= '<input type="button" value="上傳圖檔(裁剪後不刪檔)" id="dnknow" class="btn w160 h25" onClick="uknow(this.id)" >   ';
        // $kimg_html.= $del_qimg;
        $data['Kimgsrc'] = $know->k_pic;
        $data['Kimg_html'] = $kimg_html;

        $data['Kid'] = $kid;
        $data['Owner'] = $know->k_owner;
        $data['Kname'] = $know->k_name;
        $data['Kcontent'] = $know->k_content;
        $data['Kkeword'] = $know->k_keyword;
        $data['Grade'] = $grade_html;
        $data['Subject'] = $subject_html;
        $data['Chapter'] = $chapter_html;
        $data['menu_user'] = $this->menu_user;
        $data['title'] = '知識點 - 編輯';
       
        return view('know.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $kid)
    {
        if (!$this->login_status())return redirect('/login');
        if (!is_numeric($kid))abort(400);
        $kid = (int)$kid;
        if ($kid<=0)abort(400);

        if (Auth::user()->e_ident!=="A" && Auth::user()->e_ident!=="T"){
            die('很抱歉，權限不足');
            return;
        }
        $know = Knows::find($kid);
        if ($know==null)die('無此資料');

        $k_name = ($req->has('f_kname') && !empty($req->input('f_kname'))) ? trim($req->input('f_kname')):'';
        $k_content = ($req->has('f_kcont') && !empty($req->input('f_kcont'))) ? trim($req->input('f_kcont')):'';
        $k_keyword = ($req->has('f_kw') && !empty($req->input('f_kw'))) ? trim($req->input('f_kw')):'';
        $k_pic = ($req->has('f_kpic') && !empty($req->input('f_kpic'))) ? trim($req->input('f_kpic')):'';
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapter') && (int)$req->input('f_chapter')>0) ? (int)$req->input('f_chapter'):0;

        // if ($graid===0 || $subjid===0 || $chapid===0){
        //  $this->_errmsg(400);
        //  return;
        // }
        // $kimg = (isset($_POST['f_kimg']) && !empty($_POST['f_kimg'])) ? trim($_POST['f_kimg']):'';
        // $k_picname = '';
        // $this->load->model("KnowledgeModel");
        // $know = $this->KnowledgeModel->view_knowledge($kid);
        // if (!empty($kimg)){
        //     if (is_file('know/tmp/'.$kimg)){
        //         if ($know->K_PIC!==$kimg){
        //             $old_nam = 'know/tmp/'.$kimg;
        //             if (empty($know->K_PIC)){
        //                 $new_name = 'know/'.md5(uniqid(rand(), true)).'.jpg';
        //             }else{
        //                 $new_name = $know->K_PIC;
        //             }
        //             rename($old_nam, $new_name);
        //             $k_picname = $new_name;
        //         }
        //     }else if (is_file($kimg) && $kimg===$know->K_PIC){
        //         $k_picname = $know->K_PIC;
        //     }
        // }else{
        //     if (is_file($know->K_PIC)){
        //         if (unlink($know->K_PIC)){}
        //     }
        // }
        $know = Knows::find($kid);
        $know->k_name = $k_name;
        $know->k_gra = $graid;
        $know->k_subj = $subjid;
        $know->k_chap = $chapid;
        $know->k_content = $k_content;
        $know->k_keyword = $k_keyword;
        $know->updated_at = time();
        $know->save();
        return redirect('/know');
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
        if (!$this->login_status())abort(400);
        $data = Knows::all()->all();
        foreach ($data as $k => $v) {
            //知識點
            $kcont = array();
            //文字
            $kcont[] = '<strong>'.trim($v->k_name).'</strong>';
            if (!empty($v->k_content)) $kcont[] = nl2br(trim($v->k_content));
            //圖檔
            if(!empty($v->k_pic)){
                if (is_file($v->k_pic))$kcont[] = '<IMG class="know" src="'.$v->k_pic.'" width="98%">';
            }
            $data[$k]->k_content = implode("<br>", $kcont);
        }
        $gra_html = '';
        $subj_html = '';
        $chap_html = '';
        $grade_data = $this->grade();
        if (!empty($grade_data)){
            foreach ($grade_data as $v) {
                $sel_gra = '';//($p_gra===$v->g_id) ? 'selected':'';
                $gra_html.= '<option '.$sel_gra.' value="'.$v->g_id.'">'.$v->g_name.'</option>';
            }
        }
        return view('know.join', [
            'menu_user' => $this->menu_user,
            'title' => '選擇 知識點',
            'Data' => $data,
            'Grade' => $gra_html,
            'Subject' => '',
            'Chapter' => '',
            'Prev' => '',
            'Num' => 0,
            'Next' => '',
            'Pg' => ''
        ]);
    }
}
