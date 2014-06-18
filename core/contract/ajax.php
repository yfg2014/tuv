<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';

GrepUtil::InitGP(array('type'));
switch ($type) {
	case 'htxmcode':
		GrepUtil::InitGP(array('htxmcode','ht_id'));
		if($ht_id !='')
		{
		    $sql="SELECT id from ht_contract_item WHERE htxmcode = '$htxmcode' AND ht_id!='$ht_id' LIMIT 1";
			$result	= $db->get_one("SELECT id from ht_contract_item WHERE htxmcode = '$htxmcode' AND ht_id!='$ht_id' LIMIT 1");
			if ($result['id']) {
				exit('项目编号已存在');
			}
		}
		else
		{
		  	$result	= $db->get_one("SELECT id from ht_contract_item WHERE htxmcode = '$htxmcode'  LIMIT 1");
			if ($result['id']) {
				exit('项目编号已存在');
			}
		}
		break;
	default:;
	}
    
?>