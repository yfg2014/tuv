<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/setup/setup_mark.php';
include_once S_DIR.'include/setup/setup_renewal_reason.php';

GrepUtil::InitGP(array('zsid'));

$Certificate = new Certificate();
$zs = $Certificate->query($zsid);
$zs['online'] = Cache::cache_Certification_online($zs['online']);
$zs['renewal_reason'] = $setup_renewal_reason[$zs['renewal_reason']];


include T_DIR.'header.htm';
include T_DIR.'certificate/cert_show.htm';
include T_DIR.'footer.htm';
?>
