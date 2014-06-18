<?php
include '../include/globals.php';
include '../include/module/SetupList.php';
include SET_DIR.'setup_province.php';
include '../include/setup/setup_htfrom.php';
GrepUtil::InitGP(array('page','yjm','htfrom','taskBeginDate','taskEndDate','taskBeginHalfDate','taskEndHalfDate','iso_t','getdaima','worktype','idname','address'));

$url = "in_auditor_list.php?idname=$idname&worktype=$worktype&htfrom=$htfrom&getdaima=$getdaima&iso_t=$iso_t&taskEndDate=$taskEndDate&taskBeginDate=$taskBeginDate&taskBeginHalfDate=$taskBeginHalfDate&taskEndHalfDate=$taskEndHalfDate&address=$address&";

$getdaima  = str_replace('；',';',str_replace(" ","",$getdaima));
$getdaima = implode(";",array_unique(explode(';',$getdaima)));

$s_daima = str_replace(';', '\',\'', $getdaima);
$iso_t = str_replace('QY', 'Q', $iso_t);
$iso_arr = explode(',', $iso_t);
$iso_arr = array_unique($iso_arr);
if ($address != "") { $sql_temp = $sql_temp."  AND b.address LIKE '%".$address."%'" ;}
if ($idname != "") { $sql_temp = $sql_temp."  AND b.username LIKE '%".$idname."%'" ;}
if ($worktype != "") { $sql_temp = $sql_temp."  AND b.worktype = '".$worktype."'" ;}
if ($htfrom != "") { $sql_temp = $sql_temp."  AND b.htfrom = '".$htfrom."'" ;}
if ($yjm != "") { $sql_temp = $sql_temp."  AND b.yjm = '".$yjm."'" ;}

$begin =  $taskBeginDate.$taskBeginHalfDate;
$end =  $taskEndDate.$taskEndHalfDate;

if($taskBeginDate!='' and $taskEndDate!=''){
	$esql = $db->query("SELECT zuzhi_id,empId,auditorBeginDate,auditorBeginHalfDate,auditorEndDate,auditorEndHalfDate,online,qj_other FROM `xm_auditor`
	WHERE (auditorBeginDate>='$taskBeginDate' AND auditorBeginDate<='$taskEndDate')
	OR (auditorEndDate>='$taskBeginDate' AND auditorEndDate<='$taskEndDate')
	OR (auditorBeginDate<='$taskBeginDate' AND auditorEndDate>='$taskBeginDate')
	 OR (auditorBeginDate<='$taskEndDate' AND auditorEndDate>='$taskEndDate')");
	while($date = $db->fetch_array($esql)){
		$begin_temp = $date['auditorBeginDate'].$date['auditorBeginHalfDate'];
		$end_temp = $date['auditorEndDate'].$date['auditorEndHalfDate'];
		$error = '1';
		if($begin_temp>=$begin and $begin_temp<=$end){
			$error = '1';
		}else if($end_temp>=$begin and $end_temp<=$end){
			$error = '1';
		}else if($begin_temp<=$begin and $end_temp>=$begin){
			$error = '1';
		}else if($begin_temp<=$end and $end_temp>=$end){
			$error = '1';
		}else{
			$error = '0';
		}
		if($error == '1'){
			$hr_idHas[] =$date['empId'];
			if($date['zuzhi_id'] != '0'){
				$pairen[$date['empId']] = '审核企业：'.Cache::cache_company($date['zuzhi_id']);
			}
			$date['online'] == '9' && $pairen[$date['empId']] = '请假：'.$date['qj_other'];
			$date['online'] == '8' && $pairen[$date['empId']] = '值班：'.$date['qj_other'];
			$date['online'] == '7' && $pairen[$date['empId']] = '培训：'.$date['qj_other'];		
		}
	}
}
//$hr_id2 = implode(',', array_unique($hr_idHas));echo $hr_id2;
$hr_id = array();
if($s_daima != '') {
	$query = $db->query("SELECT hr_id,iso,xiaolei FROM `hr_audit_code` WHERE  xiaolei IN('$s_daima') AND (qualification='1002' OR qualification='1003' OR qualification='1004' OR qualification='1005') AND online='1'");
	while($daimadb = $db->fetch_array($query)) {
		if(in_array($daimadb['iso'],$iso_arr)) {
			$hr_id []= $daimadb['hr_id'];
			$xiaolei_str[$daimadb['hr_id']][$daimadb['iso']] == '' ? $xiaolei_str[$daimadb['hr_id']][$daimadb['iso']] = $daimadb['xiaolei'] : $xiaolei_str[$daimadb['hr_id']][$daimadb['iso']] = $xiaolei_str[$daimadb['hr_id']][$daimadb['iso']].'<br>'.$daimadb['xiaolei'];
		}elseif($iso_t == ''){
			$hr_id []= $daimadb['hr_id'];
		}
	}
}

$siso = str_replace(',','\',\'', $iso_t);
$hr_id = implode('\',\'', array_unique($hr_id));
if($idname == '' and $s_daima != ''){
	if($hr_id != '') { $sql_temp = $sql_temp." AND a.hr_id IN('$hr_id')";}else{$sql_temp = $sql_temp." AND a.hr_id ='null'";}
	if($iso_t != '') { $sql_temp = $sql_temp." AND a.online='1' AND a.iso IN('$siso')";}
}
$sql_temp = " and b.working='1'".$sql_temp;

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = 18;
$SetupList = new SetupList();
$result = $SetupList->get_auditor_list($params);
foreach($result['data'] as $k => $v){
	if($taskBeginDate!='' and $taskEndDate!=''){
		$last_audit = $db->get_one("SELECT eiregistername,eisc_address FROM `mk_company` WHERE id =(SELECT zuzhi_id FROM `xm_auditor` WHERE empId='{$v[hr_id]}' AND taskBeginDate<'{$taskBeginDate}' ORDER BY taskBeginDate DESC LIMIT 1)");
		$next_audit = $db->get_one("SELECT eiregistername,eisc_address FROM `mk_company` WHERE id =(SELECT zuzhi_id FROM `xm_auditor` WHERE empId='{$v[hr_id]}' AND taskBeginDate>'{$taskEndDate}' ORDER BY taskBeginDate ASC LIMIT 1)");
		$last_audit['eisc_address']!='' && $result['data'][$k]['last_audit'] = $last_audit['eisc_address'].'；'.$last_audit['eiregistername'];
		$next_audit['eisc_address']!='' && $result['data'][$k]['next_audit'] = $next_audit['eisc_address'].'；'.$next_audit['eiregistername'];
	}else{
		$result['data'][$k]['last_audit'] = '';
		$result['data'][$k]['next_audit'] = '';
	}
}
include 'template/header.htm';
include 'template/in_auditor_list.htm';
include 'template/footer.htm';
?>