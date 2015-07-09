<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('zuzhi_id','ht_id','htxm_id','cgid','rzlx_id','cg_task_id'));
Power::CkPower('C0T');

Power::xt_htfrom($zuzhi_id);

if($cgid != ''){
	$zsbg = $db->get_one("SELECT id,base_xmid,xmid,pdid FROM {$dbtable['zs_change']} WHERE id='$cgid'");
	if($zsbg['id'] != ''){
		if($zsbg['base_xmid'] != $zsbg['xmid'] or $zsbg['pdid']>'0'){
			$disabled = 'disabled="disabled"';
			$msg = '<font color="red">特殊审核/评定项目已生成</font>';
		}
	}
}
/*if($rzlx_id != ''){
	$rzlx = $db->get_one("SELECT id,base_xmid,xmid FROM {$dbtable['xm_rzlx']} WHERE id='$rzlx_id'");
	if($rzlx['id'] != ''){
		if($rzlx['base_xmid'] != $rzlx['xmid']){
			$disabled = 'disabled="disabled"';
			$msg = '<font color="red">特殊审核项目已生成</font>';
		}
	}
}*/


$width='600px';
$params = array('company' => array(),'contract' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'audit/item_add_edit.htm';
include TEMP.'footer.htm';
?>