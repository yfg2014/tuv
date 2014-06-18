<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Change.php';
GrepUtil::InitGP(array('cgid','zsid','ht_id','htxm_id','zuzhi_id','kind'));
if($cgid == ''){
	exit('不存在的变更');
}
Power::CkPower('F0D');
Power::xt_htfrom($zuzhi_id);
$Change = new Change();
$Change->DelChange($cgid);
if($kind == 'zs_change_list'){
	$url = "index.php?m=change&do=zs_change_list";
}else{
	$url = "index.php?m=change&do=zs_change&zsid=$zsid&htxm_id=$htxm_id&ht_id=$ht_id&zuzhi_id=$zuzhi_id";
}
Url::goto_url($url, '删除成功');
?>