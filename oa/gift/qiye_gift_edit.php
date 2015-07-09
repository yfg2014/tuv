<?php
/**
 * 添加、修改培训计划
 */
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/CompanyGift.php');

GrepUtil::InitGP(array('id'));
Power::CkPower('A3S');

echo $ajax_url;
$width='600px';

$CompanyGift = new CompanyGift();
$result = $CompanyGift->query($id);

$result['eiregistername'] = Cache::cache_company($result['zuzhi_id']);
$result['plan_complete_date']  == '0000-00-00' && $result['plan_complete_date'] = '';

include TEMP.'header.htm';
include 'template/qiye_gift_edit.htm';
include TEMP.'footer.htm';
?>