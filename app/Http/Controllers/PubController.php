<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Pubs;
use App\Pubcas;
use App\Sets;

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
        $data = Pubs::all();
        foreach ($data as $k => $v) {
            $s = Sets::find($v->s_id);
            $data[$k]->s_name = $s->s_name;
            $data[$k]->gra = $s->gra->name;
            $data[$k]->subj = $s->subj->name;
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
            'title' => '發佈測驗',
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
            if ($s->s_finish===0)echo '<script>alert("此試卷尚未定案，無法使用");location.href="'.url('/sets').'"</script>';
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
            $sets_data = Sets::select('s_name','s_id')->where('s_gra', $sel->gra)
                             ->where('s_subj', $sel->subj)
                             ->where('s_finish', 1)
                             ->get()->all();
        }
        return view('pub.create', [
            'menu_user' => $this->menu_user,
            'title' => '建立測驗',
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
        $pub = Pubs::create([
            's_id' => $sid,
            'p_owner' => session('epno'),
            'p_begtime' => $p_begtime,
            'p_endtime' => $p_endtime,
            'p_created_at' => time(),
            'p_updated_at' => time(),
            'p_limtime' => $p_limtime,
            'p_finish' => 1,
            'p_again' => $p_again,
            'p_pass_score' => $s_pass_score,
            'p_sum' => $s_sum
        ]);
        //全部班別
        if ($ca===0){
            //curl：所有班別的考卷都核對過，有此考卷的班別才新增
            /*
            找該班級所有班別，搜尋每個班別的考卷，如果有此考卷，那就要新增這個班別
            */
        }else{
            //指定班別
            Pubcas::create([
                'p_id' => $pub->p_id,
                'pc_class' => $c,
                'pc_classa' => $ca,
                'pc_webid' => $wsets
            ]);            
        }
        return redirect('/pub');
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
