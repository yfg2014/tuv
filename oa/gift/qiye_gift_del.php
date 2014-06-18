<?php

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/CompanyGift.php');
Power::CkPower('A3S');

GrepUtil::InitGP(array('id'));

$CompanyGift = new CompanyGift();
$CompanyGift->del($id);	

Url::goto_url('index.php?m=gift&do=qiye_gift_list', '删除成功');

?>