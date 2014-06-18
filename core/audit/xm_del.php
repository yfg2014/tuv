<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';

GrepUtil::InitGP(array('xmid'));
Power::CkPower('C2D');
$Item = new Item();
$xm = $Item->query($xmid,array('online','audit_type'));
if($xm['online']!='0'){
	Url::goto_url('index.php?m=audit&do=xm_no_list','请先删除审核任务，再删除未安排项目');
}
if(in_array($xm['audit_type'],array('1011','1012','1004','1006','1010'))){
	$cg = $db->get_one("SELECT id,htxm_id,base_xmid,cg_task_id FROM zs_change WHERE xmid='$xmid'");
	if($cg['id'] > '0'){
		$db->query("UPDATE zs_change SET xmid='$cg[base_xmid]' WHERE cg_task_id='$cg[cg_task_id]' AND htxm_id='$cg[htxm_id]'");
	}
	/*$rzlx = $db->get_one("SELECT id,base_xmid FROM xm_rzlx WHERE xmid='$xmid'");
	if($rzlx['id'] > '0'){
		$db->query("UPDATE xm_rzlx SET xmid='$rzlx[base_xmid]' WHERE id='$rzlx[id]'");
	}*/
}
$del = $Item->delete($xmid);

if($del){
	$msg = '操作成功';
}else{
	$msg = '操作有误';
}

Url::goto_url('index.php?m=audit&do=xm_no_list&s_online='.$s_online, $msg);
?>