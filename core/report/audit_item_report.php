<?php
!defined('IN_SUPU') && exit('Forbidden');

$main_title = $menu_setup['statistics']['I7S'];
$array = array(
	'a' => array(
		'1' =>array(
			'code' => '1',
			'msg' => '审核项目统计表',
			'url' => './index.php?m=report&do=audit_department_report',
		),
	),
);

include TEMP.'header.htm';
include TEMP.'report/audit_item_report.htm';
include TEMP.'footer.htm';
?>
