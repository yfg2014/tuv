<?php
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/TypeChange.php';
include_once S_DIR.'include/setup/setup_type_online.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_product_ver.php';
include_once S_DIR.'include/module/Information.php';
GrepUtil::InitGP(array('id','renzhengleixing','htxm_id','xmid','zuzhi_id'));
Power::CkPower('F1S');
Power::xt_htfrom($zuzhi_id);
$Item = new Item();
$xm = $Item->query($xmid);
if($xm['iso'] == 'P'){
	unset($setup_type_online['06']);
}else{
	unset($setup_type_online['07']);
}
$sql_temp = " and htxm_id='".$htxm_id."'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$TypeChange = new TypeChange();
if($id > '0'){
	$rows = $TypeChange->query($id);
}

$width = '600px';

$params = array('company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id);

$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
if($renzhengleixing == ''){
	include T_DIR.'type/xm_type.htm';
}else if($renzhengleixing == '03'){
	include T_DIR.'type/version_rzlx_edit.htm';
}else if($renzhengleixing == '06' or $renzhengleixing == '07'){
	include T_DIR.'type/size_rzlx_edit.htm';
}
include T_DIR.'footer.htm';
?>