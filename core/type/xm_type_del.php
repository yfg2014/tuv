<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/TypeChange.php';
GrepUtil::InitGP(array('cgid','xmid','htxm_id','zuzhi_id','kind'));
Power::CkPower('F1D');
Power::xt_htfrom($zuzhi_id);
if($cgid == ''){
	exit('不存在的变更');
}

$Change = new TypeChange();
$result = $Change->DelType($cgid);
if($kind == 'xm_type_list'){
	$url = "index.php?m=type&do=xm_type_list";
}else{
	$url = "index.php?m=type&do=xm_type&xmid=$xmid&htxm_id=$htxm_id&zuzhi_id=$zuzhi_id";
}
Url::goto_url($url, '操作成功');
?>