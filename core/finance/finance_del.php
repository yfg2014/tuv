<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/finance.php');

Power::CkPower('G0D');

GrepUtil::InitGP(array('zuzhi_id','ht_id','cwid'));

$s = new finance();
if($cwid!=''){
	$s->DelFinance($cwid,$zuzhi_id);
}
Url::goto_url('index.php?m=finance&do=finance_edit&ht_id='.$ht_id.'&zuzhi_id='.$zuzhi_id,'操作成功');
?>