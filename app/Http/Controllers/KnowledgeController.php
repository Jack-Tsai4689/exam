<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Knows;
use URL;

const UPLOAD_DIR = "uploads/know";
class KnowledgeController extends TopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->login_status)return redirect('/login');

        $data = $this->knows_list();
        return view('know.index', $data);
        
    }
    private function knows_list(){
        $p_gra = 0;
        $p_subj = 0;
        $p_chap = 0;
        $p_keyword = '';
        $_get = request()->all();
        $query_search = false;
        if (!empty($_get)){
            $p_gra = (int)request()->input('gra');
            $p_subj = (int)request()->input('subj');
            $p_chap = (int)request()->input('chap');
            $p_keyword = trim(request()->input('q'));
            if (!empty($p_keyword))$query_search = $query_search = true;
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
            $knows = new Knows;
            if ($p_gra>0)$knows = $knows->where('k_gra', $p_gra);
            if ($p_subj>0)$knows = $knows->where('k_subj', $p_subj);
            if ($p_chap>0)$knows = $knows->where('k_chap', $p_chap);
            if (!empty($p_keyword))$knows = $knows->where('k_keyword','like','%|'.$p_keyword.'|%');
            $knows_data = $knows->paginate(10);
        }else{
            $knows_data = Knows::paginate(10);
        }
        foreach ($knows_data as $k => $v) {
            //知識點
            $kcont = array();
            //文字
            $kcont[] = '<strong>'.trim($v->k_name).'</strong>';
            if (!empty($v->k_content)) $kcont[] = nl2br(trim($v->k_content));
            //圖檔
            if(!empty($v->k_picpath)){
                if (is_file($v->k_picpath))$kcont[] = '<IMG class="know" src="'.URL::asset($v->k_picpath).'" width="98%">';
            }
            $knows_data[$k]->k_content = implode("<br>", $kcont);
        }
        
        $page_info = $this->page_info(
            $knows_data->currentPage(),
            $knows_data->lastPage(),
            $knows_data->total()
        );
        $pfunc = new \stdClass;
        $pfunc->prev = $this->prev_page;
        $pfunc->next = $this->next_page;
        $pfunc->pg = $this->group_page;

        $data = [
            'menu_user' => $this->menu_user,
            'title' => '知識點',
            'Data' => $knows_data,
            'Grade' => $gra_html,
            'Subject' => $subj_html,
            'Chapter' => $chap_html,
            'Page' => $pfunc,
            'Num' => $knows_data->total(),
            'Keyword' => $p_keyword
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

        $data['Kimg_html'] = $kimg_html;
        $data['menu_user'] = $this->menu_user;
        $data['title'] = '知識點 - 新增';
        $data['Owner'] = $this->login_user;
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
        if (!$this->login_status)return redirect('/login');
        $k_name = ($req->has('f_kname') && !empty($req->input('f_kname'))) ? trim($req->input('f_kname')):'';
        $k_content = ($req->has('f_kcont') && !empty($req->input('f_kcont'))) ? trim($req->input('f_kcont')):'';
        $k_keyword = ($req->has('fk') && !empty($req->input('fk'))) ? $req->input('fk'):array();
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapter') && (int)$req->input('f_chapter')>0) ? (int)$req->input('f_chapter'):0;

        if (!is_dir("uploads"))mkdir("uploads", 777);
        if (!is_dir("uploads/know"))mkdir("uploads/know", 777);
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
        $km_src = '';
        $km_name = '';
        $km_file = $req->file('kpic');
        if ($km_file!=null){
            $file_error = false;
            if ($req->hasFile('kpic')){
                $mime = $km_file->getMimeType();
                $all_mime = array('image/jpg','image/jpeg','impage/png');
                if (!in_array($mime, $all_mime))$file_error = true;
                if (!$file_error){
                    $uuid = md5($km_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$km_file->getClientOriginalExtension();
                    $km_src = UPLOAD_DIR.'/'.$file;
                    $km_file->move(UPLOAD_DIR, $file);
                    $km_name = $km_file->getClientOriginalName();
                }
            }
        }
        $key = array();
        foreach ($k_keyword as $v) {
            if (!empty($v))$key[] = $v;
        }
        $data = [
            'k_name' => $k_name,
            'k_pic' => $km_name,
            'k_picpath' => $km_src,
            'k_gra' => $graid,
            'k_subj' => $subjid,
            'k_chap' => $chapid,
            'k_content' => $k_content,
            'k_owner' => session('epno'),
            'k_keyword' => '|'.implode('|', $key).'|',
            'created_at' => time(),
            'updated_at' => time()
        ];
        Knows::create($data);
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
        if (!$this->login_status)return redirect('/login');
        if (!preg_match("/^[0-9]*$/", $kid))abort(400);
        $kid = (int)$kid;
        if ($kid<1)abort(400);

        if ($this->login_type!=="T"){
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
        $km_upload = true;
        $data['Kimg_src'] = '';
        if (!empty($know->k_picpath)){
            $kimg_html = '檔名：'.$know->k_pic;
            if (is_file($know->k_picpath)){
                $kimg_html.= '　<input type="button" value="刪除圖片" class="btn w100" id="delkm" onclick="rem(this.id)">';
                $km_upload = false;
            }else{
                $kimg_html.= '　<font color="red">檔案遺失</font>';
            }
            $data['Kimg'] = URL::asset($know->k_picpath);
        }
        $data['Kimgsrc'] = $know->k_picpath;
        $data['Kmsold'] = ($km_upload) ? 'class="hiden"':'style="inline-block;"';
        $data['Km_upload'] = (!$km_upload) ? 'class="hiden"':'';
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
        //$data['Kimgsrc'] = $know->k_pic;
        $data['Kimg_html'] = $kimg_html;

        $data['Kid'] = $kid;
        $data['Owner'] = $know->k_owner;
        $data['Kname'] = $know->k_name;
        $data['Kcontent'] = $know->k_content;

        $keyword = explode("|", $know->k_keyword);
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


        $data['Kkeword'] = $key;
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
        if (!$this->login_status)return redirect('/login');
        if (!preg_match("/^[0-9]*$/", $kid))abort(400);
        $kid = (int)$kid;
        if ($kid<1)abort(400);

        if ($this->login_type!=="T"){
            die('很抱歉，權限不足');
            return;
        }
        $know = Knows::find($kid);
        if ($know==null)die('無此資料');

        $k_name = ($req->has('f_kname') && !empty($req->input('f_kname'))) ? trim($req->input('f_kname')):'';
        $k_content = ($req->has('f_kcont') && !empty($req->input('f_kcont'))) ? trim($req->input('f_kcont')):'';
        $k_keyword = ($req->has('fk') && !empty($req->input('fk'))) ? $req->input('fk'):array();
        $k_pic = ($req->has('f_kpic') && !empty($req->input('f_kpic'))) ? trim($req->input('f_kpic')):'';
        $graid = ($req->has('f_grade') && (int)$req->input('f_grade')>0) ? (int)$req->input('f_grade'):0;
        $subjid = ($req->has('f_subject') && (int)$req->input('f_subject')>0) ? (int)$req->input('f_subject'):0;
        $chapid = ($req->has('f_chapter') && (int)$req->input('f_chapter')>0) ? (int)$req->input('f_chapter'):0;


        $km_src = '';
        $km_name = '';
        $km_file = $req->file('kpic');
        if ($km_file!=null){
            $file_error = false;
            if ($req->hasFile('kpic')){
                $mime = $km_file->getMimeType();
                $all_mime = array('image/jpg','image/jpeg','impage/png');
                if (!in_array($mime, $all_mime))$file_error = true;
                if (!$file_error){
                    $uuid = md5($km_file->getClientOriginalName().time());
                    $file = $uuid.'.'.$km_file->getClientOriginalExtension();
                    $km_src = UPLOAD_DIR.'/'.$file;
                    $km_file->move(UPLOAD_DIR, $file);
                    $km_name = $km_file->getClientOriginalName();
                }
            }
        }
        $kmold_src = ($req->has('km_src') && !empty($req->input('km_src'))) ? trim($req->input('km_src')):'';


        $know = Knows::find($kid);

        //刪舊的或本來就沒有
        if (empty($kmold_src)){
            if (!empty($know->k_picpath)){
                if (is_file($know->k_picpath)){ if (unlink($know->k_picpath)){} }
            }
            $know->k_picpath = $km_src;
            $know->k_pic = $km_name;
        }
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
        $key = array();
        foreach ($k_keyword as $v) {
            $c = (string)$v;
            if ($c==="")continue;
            $key[] = $v;
        }
        
        $know->k_name = $k_name;
        $know->k_gra = $graid;
        $know->k_subj = $subjid;
        $know->k_chap = $chapid;
        $know->k_content = $k_content;
        $know->k_keyword = "|".implode("|", $key)."|";
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
        if (!$this->login_status)abort(401);
        $data = $this->knows_list();
        $data['title'] = '選擇 知識點';
        return view('know.join', $data);
    }
}
