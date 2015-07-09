<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('id','op'));

if ($op == '1'){
    $Certificate = new Certificate();
	$rows = $Certificate->query($id);
}else if ($op == '2'){
	$Item = new Item();
	$rows = $Item->query($id);
}

$width= '550px';
$params = array('company' => array(),'contract' => array(),'certificate' => array());
$id_arr = array('zuzhi_id'=>$rows['zuzhi_id'],'ht_id'=>array($rows['ht_id']));
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'certificate/cert_mailother_edit.htm';
include TEMP.'footer.htm';
?>