<?php
include '../include/globals.php';
include '../include/module/Task.php';

GrepUtil::InitGP(array('taskId','zl_okdate','actualtaskBeginDate','actualtaskBeginHalfDate','actualtaskEndDate','actualtaskEndHalfDate'));

$value = array(
	'zl_okdate' => $zl_okdate,
	'actualtaskBeginDate' => $actualtaskBeginDate,
	'actualtaskBeginHalfDate' => $actualtaskBeginHalfDate,
	'actualtaskEndDate' => $actualtaskEndDate,
	'actualtaskEndHalfDate' => $actualtaskEndHalfDate,
);

$Task = new Task();
$row = $Task->recovery($taskId, $value);
echo $row;
exit;
?>