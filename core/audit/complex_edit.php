<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Complex.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('id'));

$Complex = new Complex();
$rows = $Complex->query($id);
$rows['re_audit_date'] = Cache::cache_time_value($rows['re_audit_date']);

$width='600px';
$id_arr = array('zuzhi_id'=>$rows['zuzhi_id'],'ht_id'=>array($rows['ht_id']),'htxm_id' => $rows['htxm_id']);
$params = array('company' => array(),'contract' => array(),'item' => array(),'certificate' => array(),'finance' => array());
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'audit/complex_edit.htm';
include TEMP.'footer.htm';
?>