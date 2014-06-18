<?php
include '../include/globals.php';
include '../include/module/SetupList.php';

GrepUtil::InitGP(array('page',"code","msg",'bmsg','da'));

$sql_temp = '';

if($code != ''){$sql_temp .= " AND a.code LIKE'%$code%'";}
if($bmsg!=''){$bmsg_t = urldecode($bmsg);$sql_temp.=" AND b.msg LIKE '%$bmsg_t%'";}
if($msg!=''){$msg_t = urldecode($msg);$sql_temp.=" AND a.msg LIKE '%$msg_t%'";}
if($da){
	$sql_temp.=" AND a.dacode = '$da'";
}
$msg!='' && $msg = urlencode($msg);
$bmsg!='' && $bmsg = urlencode($bmsg);
$url = "in_dizhi_list.php?da=$da&code=$code&msg=$msg&bmsg=$bmsg&";

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = 15;
$SetupList = new SetupList();
$result = $SetupList->get_dizhi_list($params);

include 'template/header.htm';
include 'template/in_dizhi_list.htm';
include 'template/footer.htm';


?>