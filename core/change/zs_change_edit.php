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

GrepUtil::InitGP(array('zuzhi_id','cg_task_id','changeitem'));
Power::CkPower('F0S');
Power::xt_htfrom($zuzhi_id);

if($cg_task_id == '' or $cg_task_id == '0'){
	exit('不存在的变更');
}

$qy = $db->get_one("SELECT eiregpostalcode,eiscpostalcode,eipropostalcode FROM mk_company WHERE id='$zuzhi_id'");

$cg_q = $db->query("SELECT * FROM zs_change WHERE cg_task_id='$cg_task_id' AND changeitem='$changeitem'");
while($cgdb = $db->fetch_array($cg_q)){
	if($cgdb['sp_online']!='0'){
		Url::goto_url('', '变更已审批，不能编辑');
	}
	$cg []= $cgdb;
	$zsid []= $cgdb['zsid'];
}
$zsid_str = implode("','",array_unique($zsid));
if($zsid_str==''){
	Url::goto_url('', '未找到变更数据，请确认是否已删除');
}
$zs_q = $db->query("SELECT id,certNo FROM zs_cert WHERE id IN('$zsid_str')");
while($zs_arr = $db->fetch_array($zs_q)){
	$zs []= $zs_arr;
}
$xm = $db->get_one("SELECT kind,iso,audit_ver,audit_code,renzhengfanwei FROM xm_item WHERE htxm_id='{$cg[0][htxm_id]}' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004') ORDER BY id DESC LIMIT 1");
$width = '700px';
$params = array('company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id);
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'change/zs_change_edit.htm';
include T_DIR.'footer.htm';
?>