<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Auditor.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array());

if($s_idcode){$zuzhi_id = Cache::cache_company_id($s_idcode);$sql_temp = $sql_temp." and zuzhi_id='".$zuzhi_id."'";}
if($s_htcode != ''){$s_htcode = Cache::cache_htcode($s_htcode);$sql_temp = $sql_temp." AND ht_id IN('$s_htcode')" ;}
if ($s_iso != ''){$sql_temp = $sql_temp." AND iso LIKE '%".$s_iso."%'";}
if($s_date_s != '' and $s_date_e != ''){ $sql_temp = $sql_temp." AND taskBeginDate>='".$s_date_s."' AND taskBeginDate<='".$s_date_e."'";}
if($s_date_s2 != '' and $s_date_e2 != ''){$sql_temp = $sql_temp." AND taskEndDate>='".$s_date_s2."' AND taskEndDate<='".$s_date_e2."'";}
if ($s_audit_type != ""){ $sql_temp = $sql_temp." AND id IN(SELECT taskId FROM {$tblNameConf['ProjectRegisterTbl']} WHERE audit_type='".$s_audit_type."')" ;}

$url = "index.php?m=audit&do=xm_task_list&s_online=$s_online&s_idcode=$s_idcode&s_htcode=$s_htcode&s_iso=$s_iso&s_date_s=$s_date_s&s_date_e=$s_date_e&s_date_s2=$s_date_s2&s_date_e2=$s_date_e2&s_audit_type=$s_audit_type&";
$sql_temp = "and online='1'and xmonline='3'".$sql_temp;
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Task = new Task();
$result = $Task->listTask($params);

include TEMP.'header.htm';
include TEMP.'audit/xm_task_list.htm';
include TEMP.'footer.htm';
?>