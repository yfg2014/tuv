<?php
include '../include/globals.php';
include SET_DIR.'setup_key_part.php';

GrepUtil::InitGP(array('zuzhi_id'));

$arr = array();
$query = $db->query("SELECT id,eientercode,eiregistername,eipro_address FROM `mk_company` WHERE  fatherzuzhi_id='{$zuzhi_id}' or id = '{$zuzhi_id}'");
while($rows = $db->fetch_array($query)) {
	$arr []= $rows;
}

include 'template/header.htm';
include 'template/in_companyunit_list.htm';
include 'template/footer.htm';
?>