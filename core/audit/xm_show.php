<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/ContractItem.php';
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Auditor.php';
include_once S_DIR.'/include/module/AuditorPlan.php';
include_once S_DIR.'/include/module/Certificate.php';

GrepUtil::InitGP(array('ht_id','htxm_id'));

$Item = new Item();
if((int)$htxm_id>0){
	$rows = $Item->toArray("htxm_id='{$htxm_id}'");
}else if((int)$ht_id>0){
	$rows = $Item->toArray("ht_id='{$ht_id}'");
}else{
	exit('错误的参数');
}
$rows == '' ? exit('没有项目信息') : $rows;

$ContractItem = new ContractItem();
$Task = new Task();
$Auditor = new Auditor();
$Certificate = new Certificate();
foreach ($rows as $v) {
	$v['htcode'] = Cache::cache_htcode($v['ht_id']);
	$result = $ContractItem->query($v['htxm_id']);
	$v['htxmcode'] = $result['htxmcode'];
	if ($v['zsid'] == '0'){
		$v['zsid'] = $result['zsid'];
		$v['coverFieldsE'] = $result['renzhengfanwei_e'];
		$v['mark'] = Cache::cache_mark($result['mark']);
	}
	$v['zjg'] = $result['zjg'];
	if ($v['zjg'] == '0'){$v['zjg'] = '否';}else{$v['zjg'] = '是';}

	$v['taskBeginDate'] = Cache::cache_time_value($v['taskBeginDate']);
	if ($v['taskBeginDate'] != ''){
		$v['taskBeginDate'] = $v['taskBeginDate'].'.'.$v['taskBeginHalfDate'] = Cache::cache_time_online($v['taskBeginHalfDate']);
	}
	$v['taskEndDate'] = Cache::cache_time_value($v['taskEndDate']);
	if ($v['taskEndDate'] != ''){
		$v['taskEndDate'] = $v['taskEndDate'].'.'.$v['taskEndHalfDate'] = Cache::cache_time_online($v['taskEndHalfDate']);
	}
	$v['coverFields'] = $v['renzhengfanwei'];
	$v['finalProjectDate'] = Cache::cache_time_value($v['finalProjectDate']);
	$v['assessmentdate'] = Cache::cache_time_value($v['assessmentdate']);
	$v['online'] = Cache::cache_Item_online($v['online']);
	$v['zs_if'] = Cache::setup_pd_online($v['zs_if']);

	$v['xm_yanqi'] == '1' ? $v['xm_yanqi'] = '是' : $v['xm_yanqi'] = '否';
	$v['zrd'] = $result['zrd'];

	if($v['taskId'] != '0' && $v['taskId'] != ''){
		$rows_t = $Task->query($v['taskId'],array('sp_ren','sp_date','jinxianchang'));
		$rows_t['jinxianchang'] == '1' ? $v['jinxianchang'] = '是' : $v['jinxianchang'] = '否';
		$v['sp_ren'] = $rows_t['sp_ren'];
		$v['sp_date'] = $rows_t['sp_date'];

		$add = array();$biao = array();$islean = array();
		$row = $Auditor->toArray("taskId='{$v['taskId']}'");
		$row == '' ? $row = array() : $row;
		foreach ($row as $vl) {
			$biao = explode(',', $vl['iso']);
			$islean = explode(',', $vl['isLeader']);
			foreach ($biao as $kkk => $vk){
				if ($vk == $v['iso'] && $islean[$kkk] == '1'){
					$v['zuzhang'] = $vl['empName'];
				}
			}
			$add []= $vl['empName'];
		}
		$v['empName'] = implode('；', $add);
	}

	if($v['zsid'] != '0'){
		$result = $Certificate->query($v['zsid']);
		$v['certNo'] = $result['certNo'];
		$v['certEnd'] = $result['certEnd'];
		$v['certStart'] = $result['certStart'];
		$v['zsonline'] = Cache::cache_Certification_online($result['online']);
		$v['mark'] = $result['mark'];
		$v['coverFields'] = str_replace("\n",'<br>',$result['coverFields']);
		$v['coverFieldsE'] = str_replace("\n",'<br>',$result['coverFieldsE']);
		$v['mark'] = Cache::cache_mark($result['mark']);
		$v['zs_change_date'] = Cache::cache_time_value($result['zs_change_date']);
		$v['zs_zanting_edate'] = Cache::cache_time_value($result['zs_zanting_edate']);
	}
	$v['coverFields'] = str_replace("\n",'<br>',$v['renzhengfanwei']);
	$v['coverFieldsE'] = str_replace("\n",'<br>',$v['renzhengfanwei_e']);

	$arr []= $v;
}

include T_DIR.'header.htm';
include T_DIR.'audit/xm_show.htm';
include T_DIR.'footer.htm';
?>