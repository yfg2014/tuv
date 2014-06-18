<?php
include '../include/globals.php';
include '../include/module/setup_product_ver.php';

GrepUtil::InitGP(array('page',"ver","msg",'bmsg','da'));

$sql_temp = '';
if($ver != ''){$sql_temp .= " AND ver LIKE'%$ver%'";}
if($msg!=''){$sql_temp.=" AND msg LIKE '%$msg%'";}

$url = "in_standards_list.php?msg=$msg&ver=$ver&";

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$setup_product_ver = new setup_product_ver();
$result = $setup_product_ver->list_setup($params);

include 'template/header.htm';
include 'template/in_standards_list.htm';
include 'template/footer.htm';
?>