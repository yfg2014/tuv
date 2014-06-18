<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';

GrepUtil::InitGP(array('zsid','zuzhi_id'));
Power::CkPower('E0D');
Power::xt_htfrom($zuzhi_id);
$Certificate = new Certificate();
$Certificate->delete($zsid);

Url::goto_url('index.php?m=certificate&do=cert_xm_list', '操作成功');
?>