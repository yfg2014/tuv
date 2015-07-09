<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Demand.php';
include_once S_DIR.'conf/menu.inc.php';

GrepUtil::InitGP(array('eid'));
$upload = "./core/demand/demand_file_upload.php";

if($eid != ''){
	$Demand = new Demand();
	$result = $Demand->query($eid);
}

$width = '500px';

include TEMP.'header.htm';
include TEMP.'demand/demand_edit.htm';
include TEMP.'footer.htm';
?>