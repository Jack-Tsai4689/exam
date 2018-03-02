<?php // content="text/plain; charset=utf-8"
require_once ('../jpgraph.php');
require_once ('../jpgraph_pie.php');

$data = array(urldecode($_GET['str_rightcnt']),urldecode($_GET['str_allcnt'])-urldecode($_GET['str_rightcnt']));
//$data = array(3,7);
// Setup graph
$graph = new PieGraph(700,550);
$f_sid = $_GET['f_sid'];
$path = '../../concept/'.$f_sid.'.jpg';
if (is_file($path))exit;
// Setup graph title
$startstr='';
$title=urldecode($_GET['title']);
for($j=0; $j<mb_strlen($title,'utf8'); $j++){
    $startstr .= mb_substr($title, $j,1,'utf8');
    if(((1<mb_strlen($startstr,'utf8')/25 && 0==mb_strlen($startstr,'utf8')%26 )
                    || (1==mb_strlen($startstr,'utf8')/25 && 0==mb_strlen($startstr,'utf8')%25) )
                    && $startstr!=$title) $startstr .="\n";
}
$graph->title->Set($startstr);//iconv("big5","UTF-8",$str_scrtype) $str_userepname.' 觀念('.$str_scrtype.')答對比率圖'
//$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->title->SetFont(FF_SIMSUN_UTF8, FS_BOLD,20);//SetFont(FF_ARIAL,FS_NORMAL,12);
$graph->legend->SetFont(FF_SIMSUN_UTF8, FS_NORMAL,12);
// Create pie plot
$p1 = new PiePlot($data);
//$p1->value->SetFont(FF_VERDANA,FS_BOLD);
$p1->value->SetFont(FF_SIMSUN_UTF8, FS_BOLD,15);
$p1->value->SetColor("darkred");

$p1->SetSize(0.3);
$p1->SetCenter(0.5,0.55);
//$p1->SetLegends(array("答對","答錯"));
//$p1->SetStartAngle(M_PI/8);
$p1->ExplodeSlice(0);

$graph->Add($p1);
$graph->Stroke($path);
$graph->Stroke();

?>


