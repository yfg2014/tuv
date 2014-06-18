<?php
!defined('IN_SUPU') && exit('Forbidden');

Power::CkPower('B0D');

GrepUtil::InitGP(array('zuzhi_id','htxm_id'));

if($htxm_id != ''){
	DBUtil::Del($db, $dbtable['ht_contract_item'], "id='".$htxm_id."'");
	DBUtil::Del($db, $dbtable['xm_item'], "htxm_id='".$htxm_id."'");
	DBUtil::Del($db, $dbtable['pd_xm'], "htxm_id='".$htxm_id."'");
	DBUtil::Del($db, $dbtable['zs_cert'], "htxm_id='".$htxm_id."'");
	DBUtil::Del($db, $dbtable['xm_rzlx'], "htxm_id='".$htxm_id."'");
	DBUtil::Del($db, $dbtable['zs_change'], "htxm_id='".$htxm_id."'");
}

LogRW::logWriter($zuzhi_id, '合同项目信息删除');
Url::goto_url('index.php?m=contract&do=contractitem_list&', '操作成功');
?>
