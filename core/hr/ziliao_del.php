<?php
include(S_DIR.'include/module/HrZiliao.php');
GrepUtil::InitGP(array('fid','hr_id'));

try {
	$zil = new ZiliaoDao();
	$inf = $zil->delete($fid);
	Url::goto_url('index.php?m=hr&do=ziliao_edit&hr_id='.$hr_id, '删除文件成功');
} catch (Exception $e) {
	
}

?>