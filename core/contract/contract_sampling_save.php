<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Sampling.php';



GrepUtil::InitGP(array('zuzhi_id','ht_id','htxm_id','id','htfrom','audit_type','product_test','samplingxm','samplingcode','samplingdate','samplingbase','samplingquantity','testreportcode','testreportdate','samplingmobe','samplingclass','samplingtrue','online','other'));

Power::CkPower('B3E');

$value = array(
	'ht_id' => $ht_id,
	'htxm_id'=>$htxm_id,
	'zuzhi_id' => $zuzhi_id,
	'htfrom'=>$htfrom,
	'audit_type' => $audit_type,
	'product_test' => $product_test,
	'samplingxm' => $samplingxm,
	'samplingcode' => $samplingcode,
	'samplingdate' => $samplingdate,
	'samplingbase' => $samplingbase,
	'samplingquantity' => $samplingquantity,
	'testreportcode' => $testreportcode,
	'testreportdate' => $testreportdate,
	'samplingmobe' => $samplingmobe,
	'samplingclass' => $samplingclass,
	'samplingtrue' => $samplingtrue,
	'other' => $other,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date('Y-m-d'),
);

$Sampling = new Sampling();
$Sampling->save($id,$value);

Url::goto_url("index.php?m=contract&do=contract_sampling_list&", '保存成功');
?>
