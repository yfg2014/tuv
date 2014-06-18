<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/ContractItem.php';
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Complex.php';
include_once S_DIR.'/include/module/Certificate.php';
include_once S_DIR.'/include/module/MaintenanceItem.php';
GrepUtil::InitGP(array('kind','pdid'));
switch($kind){
	case 'nextitem' :
		$MaintenanceItem = new MaintenanceItem();
		$MaintenanceItem->Maintenance($pdid);
		exit('已生成下次监督维护');
		break;
}
exit;
?>