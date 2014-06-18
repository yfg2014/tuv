<?php
!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('pid','sp_date','sp_online'));

Power::CkPower('F1P');

foreach($pid as $v)
{
	$value = array(
		'sp_online' => $sp_online,
		'sp_date'=>$sp_date,
		'sp_ren' => $_SESSION['username'],
	);
	
	DBUtil::update_tb($db, $dbtable['xm_rzlx'], $value, "id='{$v}'");		
}

Url::goto_url("index.php?m=type&do=xm_type_list", '保存成功');
?>