<?php
    /**********************************************************
    /*  程式名稱：觀念答對比率圖
    /*  作者：Jerry
    /*  版本：$Id: radar.php, v2 2016/03/03 13:38
    /*  更新日期：$Date: v2 2016/03/03 13:38
    /*  程式目的/問題描述：觀念答對比率圖
                v2->題號多加分隔號，所以需先拿掉，更新成績較正常
    /*  其它說明：
    /*********************************************************/

    session_start();
   #-----------  DB連線
   include(dirname(__FILE__).'/connect.php');
   $db = new Db_function();
    
    if($_GET['fkey']==6){
        if (isset($_GET['f_sid']))$f_sid = trim($_GET['f_sid']);
        if (!is_numeric($f_sid))die('錯誤的連結');
        //作答記錄
        $Query_String = sprintf("SELECT EPNO,SETSID,SETSNAME,CHAPTER,SUBJECT,GRADE FROM IFTEX_SCORE WHERE SID='%s';", $f_sid);
        $result1 = $db->query($Query_String);
        $sc_setsid = $result1[0]['SETSID'];
        $str_srctype = trim($result1[0]['CHAPTER']);
        $str_subject = trim($result1[0]['SUBJECT']);
        $str_grade   = trim($result1[0]['GRADE']);

        $Query_String = sprintf("SELECT QID, ANS FROM iftex_score_detail WHERE sid=%d;", $f_sid);
        $sid_result = $db->query($Query_String);
        $arr_testeropt = array();
        $arr_testerqid = array();
        foreach ($sid_result as $each) {
            $arr_testeropt[] = $each['ANS'];
            $arr_testerqid[] = $each['QID'];
        }
        $f_exnumr = count($sid_result);

        $f_menuname = trim($result1[0]['SETSNAME']);
        foreach($arr_testerqid as $key => $value){
            // $$str_a_qid = $arr_testerqid[$key];
            // $$str_a_opt = $value;
            //每一題的答案
            if ($sc_setsid==0){
                $Query_String = sprintf("SELECT IMGSRC,ANS,DEGREE,GRADE,SUBJECT,SRC_TYPE,WAITCORRECT FROM IFTEX_QUESTION WHERE QID=%d;", $arr_testerqid[$key]);
            }else{
                $Query_String = sprintf("SELECT IMGSRC,ANS,DEGREE,GRADE,SUBJECT,SRC_TYPE,WAITCORRECT FROM iftex_exam WHERE QID=%d AND SETS=%d;", $arr_testerqid[$key], $sc_setsid);
            }
            $result1 = $db->query($Query_String);
            $tea_imgsrc[$key] = $result1[0]['IMGSRC'];
            $tea_ans[$key]    = $result1[0]['ANS'];
            $tea_degree[$key] = $result1[0]['DEGREE'];
            $tea_grade[$key] = $result1[0]['GRADE'];
            $tea_subject[$key] = $result1[0]['SUBJECT'];
            $tea_srctype[$key]= $result1[0]['SRC_TYPE'];
            $tea_waitcorrect[$key]= $result1[0]['WAITCORRECT'];
            
            if($tea_srctype[$key] =='') $tea_srctype[$key]="其他";
            
            //待釐清，冒似簡答題
            // $Query_String = 'SELECT WCANSTXT,wcansimg,WCSTATE,TEATXT FROM IFTEX_STUANS where SID=\''.$f_sid.'\' and QID=\''.$arr_testerqid[$key].'\'';// and position=\''.$key.'\' and (WCSTATE is not NULL and WCSTATE<>\'\')
            // $result1 = $db->query($Query_String);
            // if(!empty($result1)){
            //     $$str_a_wcstate    = $result1->f('WCSTATE');
            // }elseif(''==$$str_a_wcstate){
            //     $$str_a_wcstate   = '';
            // }
        }
    }
    $arr_unsrc = array_unique($tea_srctype);//移除重覆值，並返回結果 (觀念章節)
    $opt_rows = $f_exnumr;
    $str_scrtype = implode(',', $arr_unsrc);
    foreach ($arr_unsrc as $key => $value) {
        $arr_rightcnt[$key] = 0;
        for ($i=0; $i < $opt_rows; $i++) { 
            if ($tea_srctype[$i]==$value){
                if ($arr_testeropt[$i] == $tea_ans[$i]){//對
                    ++$arr_rightcnt[$key];
                    $right_qno[$key].= '('.($i+1).')';
                }else{//錯
                    $wrong_qno[$key].= '('.($i+1).')';
                }
                ++$arr_allcnt[$key];//全部
            }
        }
    }
    $str_rightcnt = implode(',', $arr_rightcnt);
    $str_allcnt = implode(',', $arr_allcnt);

    $f_menuname = $epno_name.'['.$f_menuname.']-觀念答對比率圖';
    $f_bmenuname = $f_menuname;
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		min-width: 1152px;
    	}
    	#title {
    		height: 30px;
    		line-height: 30px;
    	}
    	#cen {
    		width: 100%;
    		text-align: center;
    	}
    	.hover {
    		background-color: #FCE3CE;
    	}
    	.select {
    		background-color: #F8CD89;
    	}
    	.content {
    		float: left;
    		width: 100%;
    		margin-bottom: 50px;
    	}
    	.btn {
    		height: 25px;
    		margin-bottom: 5px;
    		border: 0.5px #EED6B4 solid;
    	}
    	.title_intro {
    		position: relative;
    		height: 40px;
    		line-height: 40px;
    	}
    	.title_intro input {
    		margin-left: 5px;
    	}
    	.title_intro.btn{
    		height: 25px;
    	}
    	#end {
    		float: right;
    		margin-right: 10px;
    	}
    	.list {
    		margin-top: 5px;
    	}
    	.list tr td {
    		vertical-align: middle;
    		text-align: left;
    		border-right: 1px #B4B5B5 solid;
    		height: 25px;
    	}
    	.list .deep {
    		background-color: #EFEFEE;
    		font-weight: bold;
    	}
    	.list .shallow {
    		background-color: #FCFCFC;
    		font-weight: bold;
    	}
    	.list th.last{
    		text-align: center;
    		border-right: 0px;
    	}
    	.list td.last{
    		text-align: center;
    		border-right: 0px;
    		width: 150px;
    	}
    	.list label {
    		margin-left: 5px;
    	}
    	.currect {
    		color: #29ABE2;
    	}
    	.wrong {
    		color: #B7282C;
    	}
    	.btn:active {
    		border: 0.5px gray dashed;
    	}
    	.ps label {
    		margin-left: 20px;
    		color: #D35E69;
    	}
    	.exam_again {
    		border: #B4B4B5 solid thin;
    		background-color: white;
    		position: absolute;
    		width: 70px;
    		margin-left: 150px;
    		margin-top: -30px;
    		display: none;
    	}
    	.again li {
    		margin: 2px 0px 2px 0px;
    	}
    	.again li:hover {
    		background-color: #EFEFEE;
    	}
    	@media screen and (max-width: 1500px) {
    		#all {
    			min-width: 1028px;
    		}
    	}
	</style>
</head>
<body>
<div id="all">
	<div id="title"><label class="f17"><?=$f_menuname?></label></div>
	<div class="title_intro">
		<input type="button" class="btn w150" name="" id="" value="回到題目區" onclick='window.open("result.php?f_sid=<?=$f_sid?>&f_subject=<?=$f_subject?>&fkey=6","_self","width=800,height=600,resizable=yes,scrollbars=yes,location=no");'>
		<input type="button" class="btn w150" name="" id="" value="考題來源表" onclick='window.open("analy_result.php?f_sid=<?=$f_sid?>&f_subject=<?=$f_subject?>&fkey=6","_self","width=800,height=600,resizable=yes,scrollbars=yes,location=no");'>
		<input type="button" class="btn w150" name="" id="" value="列印" onclick="print();">
		<label class="f15" id="end"><a href="javascript:void(0)" onclick="if(confirm('您確定要關閉?'))window.close();">關閉</a></label>
	</div>
	<div class="content">
		<div id="cen">
		<?php 
        if (file_exists('concept/'.$f_sid.'.jpg')){
            echo '<img height="700" src="concept/'.$f_sid.'.jpg">';
        }else{
            if(count($arr_unsrc)>2){//雷達圖
                echo '<img height="700" src="jpgraph/examples/radarmarkex1.php?f_sid='.$f_sid.'&str_scrtype='.$str_scrtype.'&str_rightcnt='.$str_rightcnt.'&str_allcnt='.$str_allcnt.'&title='.$f_bmenuname.'">';
            }elseif(count($arr_unsrc)==2){//兩種圓餅圖 
                echo '<img height="700" src="jpgraph/examples/pieex5_2.php?f_sid='.$f_sid.'&str_rightcnt='.$str_rightcnt.'&str_allcnt='.$str_allcnt.'&title='.$f_bmenuname.'觀念('.$str_scrtype.')答對比率圖">';
            }else{
                echo '<img height="700" src="jpgraph/examples/pieex5.php?f_sid='.$f_sid.'&str_rightcnt='.$str_rightcnt.'&str_allcnt='.$str_allcnt.'&title='.$f_bmenuname.'觀念('.$str_scrtype.')答對比率圖">';
            }
        }
        ?>
			<table cellpadding="0" cellspacing="0" width="100%" class="list">
<?php 
	$i = 0;
    foreach($arr_unsrc as $key => $value){//$arr_type
        $subj_id = array_search($value, $tea_srctype);
        $subject = $tea_subject[$subj_id];
        $grade = $tea_grade[$subj_id];
        $class = ($i%2==0)?'deep':'shallow';
?>
				<tr class="<?=$class?>">
					<td width="400" rowspan="2">
						<label><?=$value?></label>
					</td>
					<td >
						<label class="currect">對</label><label class="currect"><?=$right_qno[$key]?></label>
					</td>
					<td rowspan="2" class="last">
						<input type="button" class="btn w150 f14" name="" id="" value="再考一次" onclick="aga('<?=$key?>')">
						<div class="exam_again" id="ag_<?=$key?>">
                            <ul class="again">
                                <a href="javascript:void(0);" onclick="go_ex('10','<?=$subject?>','<?=$grade?>','<?=$value?>')"><li>10 題</li></a>
                                <a href="javascript:void(0);" onclick="go_ex('20','<?=$subject?>','<?=$grade?>','<?=$value?>')"><li>20 題</li></a>
                                <a href="javascript:void(0);" onclick="go_ex('60','<?=$subject?>','<?=$grade?>','<?=$value?>')"><li>60 題</li></a>
                            </ul>
                        </div>
					</td>
				</tr>
				<tr class="<?=$class?>">
					<td>
						<label class="wrong">錯</label><label class="wrong"><?=$wrong_qno[$key]?></label>
					</td>
				</tr>
<?php 
		$i++;
	} 
?>
			</table>
		</div>
	</div>
</div>
<?php include(dirname(__FILE__).'/disconnect.php'); ?>
</body>
</html>
<script type="text/javascript">
var pnum = '';
function aga(num){
    if (pnum!=''){
        $('#ag_'+pnum).css('display','none');
    }
    if (pnum==num){
        $('#ag_'+num).css('display','none');
        pnum ='';    
    }else{
        $('#ag_'+num).css('display','block');
        pnum = num;
    }
}
function go_ex(n,s,g,v){
    var getdata = {'n':n,'g':g,'s':s,'c':v};
    $.ajax({
        type:'get',
        url:'validate_exam.php',
        dataType: 'json',
        data: getdata,
        success: function (data, textStatus, jqXHR){
            if (data.no){
                if (data.msg!='')alert(data.msg);
                window.open('startexam.php?n='+n+'&s='+s+'&g='+g+'&c='+v+'&key=7','result',"width=800,height=600,resizable=yes,scrollbars=yes,location=no");
            }else{
                alert(data.msg);
            }
        }
    });
}
</script>