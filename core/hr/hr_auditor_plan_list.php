<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_auditor_plan.php');
include (S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_hr_auditor_plan.php');

Power::CkPower('H6S');
$seach_arr=array(
	'username'=>array(
		'kind'=>'username',
		'name'=>'username',
		'msg'=>'姓名',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'hr_id ',
		'sql_kind'=>'in'
	  ),
	  'yjm'=>array(
		'kind'=>'text',
		'name'=>'yjm',
		'msg'=>'易记码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	  ),
	 'plan_item'=>array(
	  'kind'=>'select',
	  'name'=>'plan_item',
	  'msg'=>'拟培养方向',
	  'width'=>'80px',
	  'arr'=>$setup_hr_plan_item,
	  'sql_field'=>'qualification',
	  'sql_kind'=>'='
	),
);

$TopSearch = new TopSearch($seach_arr);

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp	.= Power::xt_htfrom();

$width='850px';

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Hr_auditor_plan();
$result = $s->list_auditor_plan($params);

include T_DIR.'header.htm';
include T_DIR.'hr/hr_auditor_plan_list.htm';
include T_DIR.'footer.htm';
?>