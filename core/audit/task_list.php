<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Auditor.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array('s_online'));

$width="950px";

if($s_idcode){$zuzhi_id = Cache::cache_company_id($s_idcode);$sql_temp = $sql_temp." and zuzhi_id='".$zuzhi_id."'";}
if($s_htcode != ''){$s_htcode = Cache::cache_htcode($s_htcode);$sql_temp = $sql_temp." AND ht_id IN('$s_htcode')" ;}
if ($s_iso != ''){$sql_temp = $sql_temp." AND iso LIKE '%".$s_iso."%'";}
if($s_date_s != '' and $s_date_e != ''){ $sql_temp = $sql_temp." AND taskBeginDate>='".$s_date_s."' AND taskBeginDate<='".$s_date_e."'";}
if($s_date_s2 != '' and $s_date_e2 != ''){$sql_temp = $sql_temp." AND taskEndDate>='".$s_date_s2."' AND taskEndDate<='".$s_date_e2."'";}
if ($s_audit_type != ""){ $sql_temp = $sql_temp." AND id IN(SELECT taskId FROM {$tblNameConf['ProjectRegisterTbl']} WHERE audit_type='".$s_audit_type."')" ;}
if($s_jinxianchang != '') {$sql_temp = $sql_temp." AND jinxianchang='".$s_jinxianchang."'";}

if ($s_online == ''){$s_online = '0';}
$url = "index.php?m=audit&do=task_list&s_idcode=$s_idcode&";
$sql_temp = "and online='{$s_online}'".$sql_temp;
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Task = new Task();
$result = $Task->listTask($params);

include T_DIR.'header.htm';
include T_DIR.'audit/task_list.htm';
include T_DIR.'footer.htm';
?>