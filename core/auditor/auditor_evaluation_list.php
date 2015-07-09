<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AuditorPlan.php';
include(S_DIR.'include/topsearch.php');

Power::CkPower('K0J');//再认证维护查询
$width = '800px';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	 'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'组织名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp  .= Power::xt_htfrom();

$sql_temp .= " AND evaluate='2' AND empId ='".$_SESSION['userid']."'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$AuditorPlan = new AuditorPlan();
$result = $AuditorPlan->listAuditorEval($params);

include TEMP.'header.htm';
include TEMP.'auditor/auditor_evaluation_list.htm';
include TEMP.'footer.htm';
?>