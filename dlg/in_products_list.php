<?php
include '../include/globals.php';
include '../include/module/setup_product.php';

GrepUtil::InitGP(array('page',"code","msg",'bmsg','da'));

$sql_temp = '';
if($code != ''){$sql_temp .= " AND code LIKE'%$code%'";}
if($msg!=''){$sql_temp.=" AND msg LIKE '%$msg%'";}

$url = "in_products_list.php?msg=$msg&code=$code&";

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = 15;
$setup_product = new setup_product();
$result = $setup_product->list_setup($params);

include 'template/header.htm';
include 'template/in_products_list.htm';
include 'template/footer.htm';
?>
