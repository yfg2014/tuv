<?php
include '../include/globals.php';
include '../include/module/Auditor.php';

GrepUtil::InitGP(array('id'));

$Auditor = new Auditor();
$rows = $Auditor->delete($id);
if ($rows){
	echo '1';
}else{
	echo '0';
}

?>