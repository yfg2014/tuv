<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Complex.php';

GrepUtil::InitGP(array('id','zuzhi_id','ht_id','re_audit_date','iffuping','other','op'));

$params = array(
	'zuzhi_id' => $zuzhi_id,
	'ht_id' => $ht_id,
	'iffuping' => $iffuping,
	're_audit_date' => $re_audit_date,
	'other' => $other,
);
$Complex = new Complex();
$Complex->update($id, $params);
Url::goto_url('./index.php?m=audit&do=complex_list', '保存成功');
?>