<?php 
namespace App\Http\Services;
// 串接管理系統的API
class ApiService {

  private $L_ip = null;
  private $L_first_class = null;
  private $L_first_classa = null;

  private function api_curl($url){
		$ch = curl_init();
		$options = array(
			CURLOPT_URL=>$url,
			CURLOPT_HEADER=>0,
			CURLOPT_RETURNTRANSFER=>1
			//CURLOPT_TIMEOUT_MS=> 3000 //逾時處理
		);
		curl_setopt_array($ch, $options);
		$data = curl_exec($ch);
		$error = curl_errno($ch);
		curl_close($ch);
		return json_decode($data);
	}
	// 初始化
  public function L_init($ip){
    $this->L_ip = $ip;
  }
  // 老師登入
  public function L_tea_check($acc, $pass){
    $url = 'http://'.$this->L_ip.'/QRCode/UserLogin.aspx?token='.md5("UserLogin".urlencode(strtolower($acc)).date('Ymd')).'&UserID='.$acc.'&UserPass='.$pass;
    $data = $this->api_curl($url);
    return ($data->MsgID==="1") ? $data:null;
  }
  // 串接班級清單，先格式化再回傳
  public function get_LClass_info(){
    $url = 'http://'.$this->L_ip.'/QRCODE/ClassList.aspx?token='.md5("ClassList".date('Ymd'));
    $data = $this->api_curl($url);
    $class_data = ($data->MsgID==="1") ? $data:null;
    $class_info = array();
    if ($class_data!=null){
      $cdata = $class_data->Data;
      $this->L_first_class = $cdata[0]->ClassID;
      foreach ($cdata as $v) {
        $tmp = new \stdClass;
        $tmp->ID = (int)$v->ClassID;
        $tmp->NAME = trim($v->ClassName);
        array_push($class_info, $tmp);
      }
      unset($class_data);
    }
    return $class_info;
  }
  // 串接班級清單，有預設班級id，先格式化再回傳
  public function get_LClassa_info(){
    $classa_info = array();
    if ($this->L_first_class===null)return $classa_info;
    $url = 'http://'.$this->L_ip.'/QRCODE/ClassAList.aspx?token='.md5("ClassAList".date('Ymd').$this->L_first_class).'&ClassID='.$this->L_first_class;
    $data = $this->api_curl($url);
    $classa_data = ($data->MsgID==="1") ? $data:null;
    if ($classa_data!=null){
      $cadata = $classa_data->Data;
      $this->L_first_classa = $cadata[0]->ClassAID;
      foreach ($cadata as $v) {
        $tmp = new \stdClass;
        $tmp->ID = (int)$v->ClassAID;
        $tmp->NAME = trim($v->ClassAName);
        array_push($classa_info, $tmp);
      }
      unset($classa_data);
    }
    return $classa_info;
  }
  // 串接特定班級的班別清單，先格式化再回傳
  public function get_Lclass_only($cid){
    $url = 'http://'.$this->L_ip.'/QRCODE/ClassAList.aspx?token='.md5("ClassAList".date('Ymd').$cid).'&ClassID='.$cid;
    $data = $this->api_curl($url);
    $classa_data = ($data->MsgID==="1") ? $data:null;
    $classa_info = array();
    if ($classa_data!=null){
      $cadata = $classa_data->Data;
      foreach ($cadata as $v) {
        $tmp = new \stdClass;
        $tmp->ID = (int)$v->ClassAID;
        $tmp->NAME = trim($v->ClassAName);
        array_push($classa_info, $tmp);
      }
      unset($classa_data);
    }
    return $classa_info;
  }
  // 取得L班級名稱
  public function get_LClass_name($id){
    $url = 'http://'.$this->L_ip.'/QRCODE/GetClassNameByID.aspx?token='.md5("GetClassNameByID".date('Ymd').$id).'&ClassID='.$id;
    $data = $this->api_curl($url);
    if ($data->MsgID==="1"){
      return trim($data->ClassName);
    }else{
      return null;
    }
  }
  // 取得L班別名稱
  public function get_LClassa_name($caid){
    $url = 'http://'.$this->L_ip.'/QRCODE/GetClassANameByID.aspx?token='.md5("GetClassANameByID".date('Ymd').$caid).'&ClassAID='.$caid;
    $data = $this->api_curl($url);
    if ($data->MsgID==="1"){
      return trim($data->ClassAName);
    }else{
      return null;
    }
  }
  // 串接班級清單，有預設班別id，先格式化再回傳
  public function get_LSets_info(){
    $sets_info = array();
    if ($this->L_first_class===null)return $sets_info;
    // $url = '';
    // $data = $this->api_curl($url);
    // $sets_data = ($data->MsgID==="1") ? $data:null;
    // if ($sets_data!=null){
    //   $sdata = $sets_data->Data;
    //   $this->L_first_classa = $sdata[0]->ClassAID;
    //   foreach ($sdata as $v) {
    //     $tmp = new \stdClass;
    //     $tmp->ID = (int)$v->ClassAID;
    //     $tmp->NAME = trim($v->ClassAName);
    //     array_push($sets_info, $tmp);
    //   }
    //   unset($classa_data);
    // }
    return $sets_info;
  }
  // 串接考卷清單，代入班別id，先格式化再回傳
  public function get_LSets_only($caid){
    // $url = '';
    // $data = $this->api_curl($url);
    // $sets_data = ($data->MsgID==="1") ? $data:null;
    $sets_info = array();
    // if ($sets_data!=null){
    //   $cadata = $sets_data->Data;
    //   foreach ($cadata as $v) {
    //     $tmp = new \stdClass;
    //     $tmp->ID = (int)$v->ClassAID;
    //     $tmp->NAME = trim($v->ClassAName);
    //     array_push($sets_info, $tmp);
    //   }
    //   unset($sets_data);
    // }
    return $sets_info;
  }
  // 格式化資料，回傳L 特定班別學生清單
  public function get_Lstu_info($caid){
    $url = 'http://'.$this->L_ip.'/QRCODE/StuListByClassAID.aspx?token='.md5("StuListByClassAID".date('Ymd').$caid).'&ClassAID='.$caid;
    $data = $this->api_curl($url);
    $stu_data = ($data->MsgID==="1") ? $data:null;
    $stu_info = array();
    if($stu_data!=null){
      $studata = $stu_data->Data;
      foreach ($studata as $v) {
        $tmp = new stdClass;
        $tmp->id = (string)$v->StuID;
        $tmp->name = trim($v->StuName);
        array_push($stu_info, $tmp);
      }
      unset($stu_data);
    }
    return $stu_info;
  }
}