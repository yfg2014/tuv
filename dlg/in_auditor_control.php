<?php
include '../include/globals.php';

GrepUtil::InitGP(array('name'));

$arr = array();
$name = str_replace(',', '；', $name);
$name = str_replace('，', '；', $name);
$name = str_replace(';', '；', $name);
$name = str_replace(':', '；', $name);
$name = str_replace('：', '；', $name);
$params = explode('；',$name);
foreach ($params as $v)
{
	$rows = $db->get_one("SELECT id FROM {$dbtable['hr_information']} WHERE username='{$v}'");
	if ($rows == ''){
		$value = array("1",'1：'.$v.' 姓名不存在，请输入正确姓名');
		echo implode('|',$value);
		exit;
	}else{
		$arr []= $rows['id'];
	}
}
$value = array("2",implode(',',$arr));
echo implode('|',$value);
exit;
?>