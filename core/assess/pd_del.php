<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
Power::CkPower('D0D');
GrepUtil::InitGP(array('zuzhi_id','id'));
$AssessmentItem = new AssessmentItem();
$pd = $AssessmentItem->query($id,array('zs_if','iso','audit_type'));
if($pd['zs_if'] == '0'){
	if(in_array($pd['audit_type'],array('1011','1012','1004','1006','1010'))){
		$cg = $db->get_one("SELECT id,htxm_id,cg_task_id FROM zs_change WHERE pdid='$id'");
		if($cg['id'] > '0'){
			$db->query("UPDATE zs_change SET pdid='0' WHERE cg_task_id='$cg[cg_task_id]' AND htxm_id='$cg[htxm_id]'");
		}
		/*$rzlx = $db->get_one("SELECT id FROM xm_rzlx WHERE pdid='$id'");
		if($rzlx['id'] > '0'){
			$db->query("UPDATE xm_rzlx SET pdid='0' WHERE id='$rzlx[id]'");
		}*/
	}
	$AssessmentItem->delete($id);
	LogRW::logWriter($zuzhi_id, $pd['iso'].'评定项目删除');
	Url::goto_url("index.php?m=assess&do=pd_list&", '操作成功');
}else{
	Url::goto_url("index.php?m=assess&do=pd_list&", '项目已评定，无法删除');
}
?>