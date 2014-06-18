<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/fees_finance.php');

Power::CkPower('G1D');

GrepUtil::InitGP(array('zuzhi_id','ht_id','cwid'));

$s = new fees_finance();
if($cwid!=''){
	$s->DelFeesFinance($cwid,$zuzhi_id);
}
Url::goto_url('index.php?m=finance&do=fees_finance_edit&ht_id='.$ht_id.'&zuzhi_id='.$zuzhi_id,'操作成功');
?>