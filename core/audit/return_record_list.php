<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Auditor.php';
include (S_DIR.'include/module/XmTaskReturnRecord.php');
include (S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';

Power::CkPower('M0S');

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
	's_date'=>array(
	  'kind'=>'date1',   //要搜索的类型
	  'name'=>'s_date',
	  'msg'=>'回访时间',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'record_date',
	  'sql_kind'=>'>='
	),
	'e_date'=>array(
	  'kind'=>'date2',  
	  'name'=>'e_date',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'record_date',
	  'sql_kind'=>'<='
	)
);

$TopSearch = new TopSearch($seach_arr);

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp	.= Power::xt_htfrom();

$width='1300px';

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new XmTaskReturnRecord();
$result = $s->list_return_record($params);

include TEMP.'header.htm';
include TEMP.'audit/return_record_list.htm';
include TEMP.'footer.htm';
?>