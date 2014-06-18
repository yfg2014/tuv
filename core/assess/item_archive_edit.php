<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('xmid'));
Power::CkPower('D3E');
$Item = new Item();
$rows = $Item->query($xmid);
//归档编号默认是合同项目编号
if($rows[archivecode] == ""){
$ht_xm = $db->get_one("SELECT htxmcode FROM `ht_contract_item` WHERE id='$rows[htxm_id]'");
$rows[archivecode] = $ht_xm[htxmcode];
}
Power::xt_htfrom($rows['zuzhi_id']);
if ($rows['audit_type'] == '1008'){$rows['audit_type'] = '1001';}
$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
$rows['zl_okdate'] = Cache::cache_time_value($rows['zl_okdate']);
$rows['archivedate'] = Cache::cache_time_value($rows['archivedate']);

$Information = new Information(array('zuzhi_id'=>$rows['zuzhi_id'],'ht_id'=>array($rows['ht_id'])),'600px');

$width = '600px';
include T_DIR.'header.htm';
include T_DIR.'assess/item_archive_edit.htm';
include T_DIR.'footer.htm';
?>