<?php
!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('type','ht_id','finance_item'));
switch ($type) {
	case 'q_finance_item':
		if($ht_id !='' and $finance_item != '')
		{
			$result	= $db->get_one("SELECT id,htfrom,basket1,basket2,basket3,basket4 FROM `cw_finance_item` WHERE ht_id = '$ht_id' AND finance_item = '$finance_item' LIMIT 1");
			echo json_encode($result);
		}
		break;
	default:;
}
    
?>