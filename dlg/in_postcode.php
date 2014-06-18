<?php
include '../include/globals.php';
include '../include/module/setup_postcode.php';

GrepUtil::InitGP(array('page',"code","msg",'bmsg','da'));

$sql_temp = '';
if($code != ''){$sql_temp .= " AND postcode LIKE'%$code%'";}
if($bmsg!=''){$sql_temp.=" AND province LIKE '%$bmsg%'";}
if($msg!=''){$sql_temp.=" AND area LIKE '%$msg%'";}
$url = "in_postcode.php?bmsg=$bmsg&msg=$msg&code=$code&";

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = 15;
$setup_postcode = new setup_postcode();
$result = $setup_postcode->list_setup($params);

include 'template/header.htm';
include 'template/in_postcode.htm';
include 'template/footer.htm';

?>