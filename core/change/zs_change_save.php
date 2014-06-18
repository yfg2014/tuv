<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Change.php';
$value = GrepUtil::InitGP(array('cg_task_id','zuzhi_id','cgid','zsid','changeitem','zs_change_date','zs_zanting_edate','audit_ver','changerange','audit_code','renzhengfanwei','creatitem','other','cw_other','creatitem','ifaudit','ifchouyan','ifchangecert','eiregistername','eireg_address','eiregpostalcode','eisc_address','eiscpostalcode','eipropostalcode','eipro_address','zs_address','zs_postalcode','eiman_amount','iso_people_num','eifaren','eikind','zt_changereason','cx_changereason','zs_zanting_edate','up_date'));
Power::CkPower('F0E');
Power::xt_htfrom($zuzhi_id);
$Change = new Change();
if($zsid != ''){
	foreach((array)$zsid as $z){
		$Change->GetBaseMsg($z,$value);
		$Change->ChangeSave($cg_task_id);
	}
	$zsid_str = implode("','",$zsid);
	$Change->ChangeSaveTask($cg_task_id,$zsid_str);
}
Url::goto_url("index.php?m=change&do=zs_change_list", '保存成功');
?>