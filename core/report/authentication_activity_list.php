<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Auditor.php';
include_once S_DIR.'include/module/Hr_information.php';
include_once S_DIR.'include/module/Report_information.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once SET_DIR.'setup_upplan_online.php';
include(S_DIR.'include/topsearch.php');

Power::CkPower('I3S');
$width = '1550px';
$db_perpage = '100';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),

	'taskBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskBeginDate1',
	  'msg'=>'审核日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'>='
	),
	'taskBeginDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'taskBeginDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'<='
	),
	'upplan_online'=>array(
	  'kind'=>'hidden',
	  'name'=>'upplan_online',
	  'msg'=>'',
	  'width'=>'',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	)
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

if($upplan_online == ''){
	$sql_temp = $sql_temp." AND (upplan='0' OR upplan='2') AND taskBeginDate>='".date("Y-m-d")."'";
}else if($upplan_online == '1'){
	$sql_temp = $sql_temp." AND (upplan='1' OR upplan='3')";
}else if($upplan_online == '2'){
	$sql_temp = $sql_temp." AND (upplan='2')";
}else if($upplan_online == '3'){
	$sql_temp = $sql_temp." AND (upplan='3')";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Report_information = new Report_information();
$result = $Report_information->authentication_activity_list($params);

include TEMP.'header.htm';
include TEMP.'report/authentication_activity_list.htm';
include TEMP.'footer.htm';
?>