<?php // content="text/plain; charset=utf-8"
require_once ('../jpgraph.php');
require_once ('../jpgraph_radar.php');

//$titles=array(iconv("big5","UTF-8",$str_scrtype) ,$str_rightcnt,$str_allcnt,$_SESSION['LNK_USERID']."_uid",'CR',"\$f_sid=$f_sid",'測測試');
$arr_scrtype  = explode(',',urldecode($_GET['str_scrtype']));//iconv("big5","UTF-8",$str_scrtype)
$f_sid = $_GET['f_sid'];
$path = '../../concept/'.$f_sid.'.jpg';
if (is_file($path))exit;
//titles換行
foreach($arr_scrtype as $key => $value){
    $startstr='';
    $laststr='';
    for($j=0; $j<mb_strlen($value,'utf8'); $j++){
        $startstr .= mb_substr($value, $j,1,'utf8');
        if(((1<mb_strlen($startstr,'utf8')/5 && 0==mb_strlen($startstr,'utf8')%6 )
           || (1==mb_strlen($startstr,'utf8')/5 && 0==mb_strlen($startstr,'utf8')%5) ) 
          && $startstr!=$value) $startstr .="\n";
    }
//     for ($j=5; $j<mb_strlen($value,'utf8'); $j++){
//         $laststr .= mb_substr($value, $j,1,'utf8');
//     }
//     if(mb_strlen($value,'utf8')>5){
//     $arr_scrtype[$key] = ($startstr ."\n". $laststr); //. '<br>'
//     }
    $arr_scrtype[$key] = $startstr;
}

$arr_rightcnt = explode(',',$_GET['str_rightcnt']);
$arr_allcnt   = explode(',',$_GET['str_allcnt']);
for($i=0;$i<count($arr_rightcnt);$i++){
    $arr_data[$i] = $arr_rightcnt[$i]/$arr_allcnt[$i]*100;
}
for($i=0;$i<count($arr_scrtype);$i++){
    $arr_scrtype[$i].= "\n".round($arr_data[$i],1)."%($arr_rightcnt[$i]/$arr_allcnt[$i])";
}
$titles = $arr_scrtype;
$data = $arr_data;
//$data=array(75, 21, 70, 50, 42,66,60);
$graph = new RadarGraph (700,550);
$title=urldecode($_GET['title']);
//$graph->title->SetFont(FF_BIG5, FS_NORMAL);
$graph->title->Set($title);//$str_userepname.' '.$f_bmenuname.' '.'觀念答對比率圖'
$graph->title->SetFont(FF_SIMSUN_UTF8, FS_BOLD,20); //(FF_VERDANA,FS_NORMAL,12)
$graph->SetTitles($titles);
$graph->SetCenter(0.5,0.55);
$graph->HideTickMarks();
$graph->SetColor('white@0.7');//lightgreen
$graph->axis->SetColor('darkgray');
$graph->grid->SetColor('darkgray');
$graph->grid->Show();

$graph->axis->title->SetFont(FF_SIMSUN_UTF8, FS_BOLD,10);//SetFont(FF_ARIAL,FS_NORMAL,12);
$graph->axis->title->SetMargin(10);
$graph->SetGridDepth(DEPTH_BACK);
$graph->SetSize(0.6);

$plot = new RadarPlot($data);
$plot->SetColor('red@0.1');
$plot->SetLineWeight(1.5);
$plot->SetFillColor('blue@0.7');

$plot->mark->SetType(MARK_IMG_BALL,'red');//MARK_IMG_SBALL

$graph->Add($plot);
$graph->Stroke($path);
$graph->Stroke();
?>
