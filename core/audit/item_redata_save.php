<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'/include/module/AssessmentItem.php';
GrepUtil::InitGP(array('zuzhi_id','kehujibie','xmid','taskId','zl_okman','zl_okdate','zlother','htxm_id','to_assess_date','turn','data'));

Power::CkPower('C7E');//资料回收登记

//update 客户级别
DBUtil::update_tb($db,'mk_company',array('kehujibie'=>$kehujibie),"id='$zuzhi_id'");

$Item = new Item();
$ContractItem = new ContractItem();
$AssessmentItem = new AssessmentItem();
foreach($xmid as $k=>$v){
	$params = array(
		//'renzhengfanwei' => $renzhengfanwei[$k],
		'zl_okman' => $_SESSION['username'],
		'zl_okdate' => $zl_okdate[$k],
		'zlother' => $zlother[$k],
		'turn' => $turn[$k],
		'to_assess_date' => $to_assess_date[$k]
	);
	$Item->update($v, $params);

	if($turn[$k] == '1')
	{
		//创建评定项目
		$AssessmentItem->buildItem($v);
	}else{
		//删除评定项目
		$AssessmentItem->backItem($v);
	}

}
/*if($taskId > 0){
	$params = array(
		'fail1' => $fail1,
		'fail2' => $fail2,
		'fail3' => $fail3,
		'data' => implode(',',$data),
	);
	DBUtil::update_tb($db, $dbtable['xm_task'], $params, "id='{$taskId}'");
}*/

LogRW::logWriter($zuzhi_id, '审核资料收回');

Url::goto_url('index.php?m=audit&do=item_redata_list', '保存成功');
?>