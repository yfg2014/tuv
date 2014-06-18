<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Change.php';
GrepUtil::InitGP(array('pid','sp_date','sp_online'));
Power::CkPower('F0P');
$Change = new Change();
if($sp_online == '1'){
	$Change->AppChangeOk($pid,$sp_date);
}else if($sp_online == '2'){
	$Change->AppChangeNg($pid,$sp_date);
}else if($sp_online == '0'){
	$Change->ReAppChange($pid);
}else{
	exit('错误的操作参数');
}
Url::goto_url("index.php?m=change&do=zs_change_list", '保存成功');
?>
