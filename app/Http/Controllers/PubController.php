<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Pubs;
use App\Sets;

class PubController extends TopController
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$data = Pubs::all();

        $page = new \stdclass;
        $page->prev = '';
        $page->next = '';
        $page->pg = '';
        return view('pub.index', [
            'menu_user' => $this->menu_user,
            'title' => '發佈測驗',
            'Data' => array(),
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
        $sets_data = array();
        if (!empty($grade_data))$subject_data = $this->subject($grade_data[0]->g_id);
        if (!empty($subject_data)){
            $sets_data = Sets::select('s_name','s_id')->where('s_gra',$grade_data[0]->g_id)
                             ->where('s_subj', $subject_data[0]->g_id)
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
            'Pass' => 60
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
