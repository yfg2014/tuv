<?php
!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('zuzhi_id','ht_id','xmid','zsid','eiregistername','htxmcode','base_htxmcode'));
if($htxmcode !=''){
	$zuzhi_id = array();
	$sql_temp = Power::CpPower(1);
	$xm_sql = $db->query("SELECT id,zuzhi_id FROM  ht_contract_item WHERE htxmcode LIKE'%$htxmcode%' $sql_temp LIMIT 10");
	while($xmdb = $db->fetch_array($xm_sql)){
		if(!in_array($xmdb['zuzhi_id'],$zuzhi_id)){
			$qy_sql = $db->query("SELECT id,eiregistername FROM mk_company WHERE id='$xmdb[zuzhi_id]'");
			while($qydb = $db->fetch_array($qy_sql)){
				$qy []= $qydb;
			}
		}
		$zuzhi_id []= $xmdb['zuzhi_id'];
	}
}else if($eiregistername!=''){
	$sql_temp = Power::CpPower();
 	$qy_sql = $db->query("SELECT id,eiregistername FROM mk_company WHERE eiregistername LIKE'%$eiregistername%' $sql_temp LIMIT 10");
	while($qydb = $db->fetch_array($qy_sql)){
		$qy []= $qydb;
	}
}

$progressdb = array(
	'mk_company'=>array(
		'id',
		'eiregistername',
		'zd_ren',
		'zd_time',
		'up_ren',
		'up_time'
	),
	'ht_contract'=>array(
		'id',
		'htcode',
		'iso',
		'zd_ren',
		'zd_time',
		'sh_ren',
		'sh_time',
		'ps_ren',
		'ps_time',
		'online'
	),
	'pd_xm'=>array(
		'id',
		'xmid',
		'ht_id',
		'zsid',
		'iso',
		'audit_type',
		'to_assess_date',
		'assessmentdate', //评定日期
		'approvaldate', //评定通过日期
		'zs_if', //评定结果		
		'online'
	),
	'xm_item'=>array(
		'cj_ren',
		'cj_time',
		'taskBeginDate', //审核开始时间
		'taskEndDate', //审核结束时间
		'zl_okdate', //资料收回时间
		'zl_okman', //资料收回人
		'finalItemDate', //监察最后日
		'archivedate', //归档日期
		'wh_date', //维护日期
		'wh_ren', //维护人	
		'online'
	),
	'zs_cert'=>array(
		'id',
		'xmid',
		'certNo',
		'zd_ren',
		'zd_date',
		'maildate',
		'certStart',
		'certEnd',
		'online'
	),
	'zs_change'=>array(
		'zs_change_date',
		'zs_zanting_edate',
		'zs_zanting_edate',
		'changeitem'
	),
	'xm_rzlx'=>array(
		'xm_change_date',
		'renzhengleixing'
	)
);
if($zuzhi_id != ''){
	$zuzhi_id = implode("','",(array)$zuzhi_id);
	$filed = implode(",",$progressdb['mk_company']);
	$mk_company = $db->get_one("SELECT $filed FROM $dbtable[mk_company] WHERE id IN('$zuzhi_id')");

	$filed = implode(",",$progressdb['ht_contract']);
	$query = $db->query("SELECT $filed FROM $dbtable[ht_contract] WHERE zuzhi_id IN('$zuzhi_id')");
	while($arr = $db->fetch_array($query)){
		$htxmcode_t = array();
		$htxm_q = $db->query("SELECT htxmcode FROM $dbtable[ht_contract_item] WHERE ht_id='$arr[id]'");
		while($htxm = $db->fetch_array($htxm_q)){
			$htxmcode_t []= $htxm['htxmcode'];
		}
		$arr['htxmcode'] = implode('<br>',$htxmcode_t);
		if($htxmcode != ''){
			if(strstr($arr['htxmcode'],$htxmcode)){
				$ht_contract [$arr['id']]= $arr;
			}
		}else{
			$ht_contract [$arr['id']]= $arr;
		}
	}

	$filed = implode(",",$progressdb['pd_xm']);
	$xmfiled = implode(",",$progressdb['xm_item']);
	$zsfiled = implode(",",$progressdb['zs_cert']);
	if($ht_id!=''){
		$query = $db->query("SELECT $filed FROM $dbtable[pd_xm] WHERE ht_id ='$ht_id' ORDER BY id ASC");
		while($arr = $db->fetch_array($query)){
			$xm = $db->get_one("SELECT $xmfiled FROM $dbtable[xm_item] WHERE id ='$arr[xmid]'");
			$arr['cj_ren'] = $xm['cj_ren'];
			$arr['cj_time'] = $xm['cj_time'];
			$arr['taskBeginDate'] = $xm['taskBeginDate'];
			$arr['taskEndDate'] = $xm['taskEndDate'];
			$arr['zl_okdate'] = $xm['zl_okdate'];
			$arr['zl_okman'] = $xm['zl_okman'];
			$arr['finalItemDate'] = $xm['finalItemDate'];
			$arr['archivedate'] = $xm['archivedate'];
			$arr['wh_date'] = $xm['wh_date'];
			$arr['wh_ren'] = $xm['wh_ren'];
			$arr['xmonline'] = $xm['online'];
			
			$cert = $db->get_one("SELECT $zsfiled FROM $dbtable[zs_cert] WHERE id ='$arr[zsid]'");
			$arr['zsmaildate'] = $cert['maildate'];
			$arr['certStart'] = $cert['certStart'];
			$arr['certEnd'] = $cert['certEnd'];
			$arr['zs_online'] = $cert['online'];
			$arr['certNo'] = $cert['certNo'];
			$xm_item []= $arr;
			$zsid []= $arr['zsid'];
		}
		$zsid = implode("','",$zsid);
	}
}
$width = '1600px';

include TEMP.'header.htm';
include TEMP.'plugins/progress.htm';
include TEMP.'footer.htm';

?>