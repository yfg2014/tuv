<?php
include '../include/globals.php';
include '../include/module/SetupList.php';

GrepUtil::InitGP(array('page','code','iso','msg'));

$sql_temp = '';
if($iso == 'QY'){$iso = 'Q';}
if($iso) { $sql_temp .= " AND iso = '$iso' ";}
if($code != ''){$sql_temp .= " AND code LIKE '%$code%'";}
if($msg != ''){$sql_temp .= " AND msg LIKE '%$msg%'";}
//if($code == '' && $msg == '' && $iso!='P')
//{
//	$sql_temp.=" AND code LIKE '19.11%'";
//}

$url = "in_daima_list.php?iso=$iso&code=$code&msg=$msg&";
$sql_temp = $sql_temp." AND online='1' AND shangbao!=''";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = 25;

$SetupList = new SetupList();
$result = $SetupList->get_daima_list($params);

include 'template/header.htm';
include 'template/in_daima_list.htm';
include 'template/footer.htm';


?>