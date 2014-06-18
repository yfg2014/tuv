<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Complex.php';
Power::CkPower('C9D');
GrepUtil::InitGP(array('zuzhi_id','id'));

$Complex = new Complex();
$Complex->delete($id);
LogRW::logWriter($zuzhi_id, '再认证提醒删除');

Url::goto_url('./index.php?m=audit&do=complex_list', '删除成功');
?>