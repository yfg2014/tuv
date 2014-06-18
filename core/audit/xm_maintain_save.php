<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';

GrepUtil::InitGP(array('xm_id','op','comvid','ifchangecert','xm_yanqi','msg','to_audit_msg','online','zuzhi_id','auditplandate'));

Power::CkPower('C8E');//监督维护操

$Item = new Item();
$value = array(
	'auditplandate' => $auditplandate,
	'wh_ren' => $_SESSION['username'],
	'wh_date' => date("Y-m-d"),
);
$msg != '' && $value['sv_other'] = $msg;
$to_audit_msg != '' && $value['xm_other'] = $to_audit_msg;
if($msg != '' || $to_audit_msg != ''){
	if($comvid==''){
		$strsql = "INSERT INTO mk_company_sv (zuzhi_id ,msg ,to_audit_msg,sv_date,zd_ren,zd_time )VALUES ('".$zuzhi_id."','".$msg."','".$to_audit_msg."','".$value[wh_date]."','".$value[wh_ren]."','".$value[wh_date]."')";
		$db->query($strsql);
	}else{
		$strsql = "UPDATE mk_company_sv SET zuzhi_id = '".$zuzhi_id."',
		msg = '".$msg."',to_audit_msg='".$to_audit_msg."',sv_date='".$value[wh_date]."',zd_ren='".$value[wh_ren]."',zd_time='".$value[wh_date]."' WHERE id =$comvid";
		$db->query($strsql);
	}
}

foreach((array)$xm_id as $k => $v){
	if(in_array($v,(array)$ifchangecert)){
		$value['ifchangecert']='1';
	}else{
	    $value['ifchangecert']='0';
	}
	if($online != ''){
		$xm = $Item->query($v,array('zuzhi_id','online','iso','audit_type'));
		if($xm['online'] == '5' or $xm['online'] == '9'){
	 		$value['online'] = $online;
	   	}
	}
	$Item->update($v, $value);
}
LogRW::logWriter($zuzhi_id, '维护项目');

Url::goto_url('index.php?m=audit&do=xm_maintain_list&', '保存成功');
?>