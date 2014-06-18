<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/module/Change.php';
include_once S_DIR.'include/setup/setup_changeitem.php';
include_once S_DIR.'include/setup/setup_zs_stop.php';
include_once S_DIR.'include/setup/setup_zs_revocation.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_product_ver.php';
include_once SET_DIR.'setup_organize_information.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('zsid','zuzhi_id','cg_task_id'));
Power::CkPower('F0S');
Power::xt_htfrom($zuzhi_id);

if(empty($zsid) || $zsid == '' || $zsid == '0'){
	exit('不存在的证书');
}
$count_zs = count($zsid);
$zsid_str = implode("','",(array)$zsid);
$zs_q = $db->query("SELECT id,htxm_id,certNo,certEnd FROM zs_cert WHERE id IN('$zsid_str')");
while($zs_arr = $db->fetch_array($zs_q)){
	$zs []= $zs_arr;
	$htxm_id = $zs_arr['htxm_id'];
}
$com = $db->get_one("SELECT * FROM mk_company WHERE id='$zuzhi_id'");
$htxm = $db->get_one("SELECT iso_people_num FROM ht_contract_item WHERE id='$htxm_id'");
$xm = $db->get_one("SELECT kind,iso,audit_ver,audit_code,renzhengfanwei FROM xm_item WHERE htxm_id='$htxm_id' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004') ORDER BY id DESC LIMIT 1");
$width = '700px';
$params = array('company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id);
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'change/zs_change.htm';
include T_DIR.'footer.htm';
?>