<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/setup_audit_iso.php';

Power::CkPower('I6S');
GrepUtil::InitGP(array('begindate','enddate','iso'));

if($begindate==''){$begindate=date("Y").'-01-01';}
if($enddate==''){$enddate=date("Y").'-12-31';}

include T_DIR.'header.htm';
include T_DIR.'report/year_audit_list.htm';
include T_DIR.'footer.htm';
?>
