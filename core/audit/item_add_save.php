<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/AssessmentItem.php';

GrepUtil::InitGP(array('cg_task_id','rzlx_id','cgid','htxm_id','zuzhi_id','audit_type','auditplandate','audit_kind','ifchangecert'));
Power::CkPower('C0T');
$xm = $db->get_one("SELECT ht_id,zsid,htfrom,iso,audit_ver,renzhengfanwei,audit_code,product,product_ver,kind FROM xm_item WHERE htxm_id='$htxm_id' ORDER BY id DESC LIMIT 1 ");
switch($audit_kind){
	case '1' :
	$Item = new Item();
	$value = array(
		'htxm_id' => $htxm_id,
		'htfrom' => $xm['htfrom'],
		'ht_id' => $xm['ht_id'],
		'zuzhi_id' => $zuzhi_id,
		'zsid' => $xm['zsid'],
		'renzhengfanwei' => $xm['renzhengfanwei'],
		'iso' => $xm['iso'],
		'audit_ver' => $xm['audit_ver'],
		'product'=>$xm['product'],
		'product_ver'=>$xm['product_ver'],
		'audit_code' => $xm['audit_code'],
		'audit_type' => $audit_type,
		'auditplandate' => $auditplandate,
		'ifchangecert' => $ifchangecert,
		'kind' => $xm['kind'],
		'cj_ren' => $_SESSION['username'],
		'cj_time' => date('Y-m-d'),
		'online' => '0'
	);
	$Item->add($value);
	$n_xmid = $db->insert_id();
	LogRW::logWriter($zuzhi_id,$xm['iso'].'：变更  需现场审核');
	if($cgid > '0'){
		$cg = $db->get_one("SELECT xmid FROM zs_change WHERE id='$cgid'");
		$db->query("UPDATE zs_change SET xmid='$n_xmid',base_xmid='$cg[xmid]' WHERE cg_task_id='$cg_task_id' AND htxm_id='$htxm_id'");
	}
	break;
	case '2' :
	$Item = new Item();
	$value = array(
		'htxm_id' => $htxm_id,
		'htfrom' => $xm['htfrom'],
		'ht_id' => $xm['ht_id'],
		'zuzhi_id' => $zuzhi_id,
		'zsid' => $xm['zsid'],
		'renzhengfanwei' => $xm['renzhengfanwei'],
		'iso' => $xm['iso'],
		'audit_ver' => $xm['audit_ver'],
		'product'=>$xm['product'],
		'product_ver'=>$xm['product_ver'],
		'audit_code' => $xm['audit_code'],
		'audit_type' => $audit_type,
		'auditplandate' => '',
		'ifchangecert' => $ifchangecert,
		'kind' => $xm['kind'],
		'cj_ren' => $_SESSION['username'],
		'cj_time' => date('Y-m-d'),
		'online' => '3'
	);
	$Item->add($value);
	$n_xmid = $db->insert_id();
	$AssessmentItem = new AssessmentItem();
	$value = array(
		'htxm_id' => $htxm_id,
		'xmid' => $n_xmid,
		'htfrom' => $xm['htfrom'],
		'ht_id' => $xm['ht_id'],
		'zuzhi_id' => $zuzhi_id,
		'zsid' => $xm['zsid'],
		'renzhengfanwei' => $xm['renzhengfanwei'],
		'iso' => $xm['iso'],
		'audit_ver' => $xm['audit_ver'],
		'product'=>$xm['product'],
		'product_ver'=>$xm['product_ver'],
		'audit_code' => $xm['audit_code'],
		'audit_type' => $audit_type,
		'ifchangecert' => $ifchangecert,
		'kind' => $xm['kind'],
		'pd_ren' => $_SESSION['username'],
		'pd_date' => date('Y-m-d'),
		'to_assess_date' => date('Y-m-d'),
		'online' => '0'
	);
	$AssessmentItem->add($value);
	$n_pdid = $db->insert_id();
	LogRW::logWriter($zuzhi_id,$xm['iso'].'：变更  不需要现场审核');
	if($cgid > '0'){
		$db->query("UPDATE zs_change SET pdid='$n_pdid' WHERE cg_task_id='$cg_task_id' AND htxm_id='$htxm_id'");
	}
	break;
	default : Url::goto_url('index.php?m=audit&do=xm_no_list', '操作失败');
}

Url::goto_url('index.php?m=audit&do=xm_no_list', '保存成功');
?>