<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array('source'));
Power::CkPower('I0S');
if ($source == 'htxm'){
	$do = 'htxm_statistics';
}else{
	$do = 'ht_statistics';
}

include TEMP.'header.htm';
include TEMP.'report/'.$do.'.htm';
include TEMP.'footer.htm';
?>