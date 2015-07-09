<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Wage.php');

GrepUtil::InitGP(array('eid','hr_id'));

Power::CkPower('H0G');

$width='500px';
if($eid != ''){
	$Wage = new Wage();
	$result = $Wage->query($eid);
}

include TEMP.'header.htm';
include TEMP.'hr/hr_wage_edit.htm';
include TEMP.'footer.htm';
?>