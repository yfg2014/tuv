<?php

/**
 * 查看操作日记
 * @2011-5-27
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
Power::CkPower('J2S');
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
	'eilinkman'=>array(
		'kind'=>'text',
		'name'=>'eilinkman',
		'msg'=>'操作人员',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'username',
		'sql_kind'=>'%like%'
	),
	's_date'=>array(
		'kind'=>'date1',
		'name'=>'s_date',
		'msg'=>'时间范围',
		'width'=>'80px',
		'arr'=>'',
		'sql_field'=>'logtime',
		'sql_kind'=>'>='
	 ),
	'e_date'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'',
		'width'=>'80px',
		'arr'=>'',
		'sql_field'=>'logtime',
		'sql_kind'=>'<='
	 ),
	 'info'=>array(
		'kind'=>'text',
		'name'=>'info',
		'msg'=>'操作内容',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'info',
		'sql_kind'=>'%like%'
	 ),
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$params = array(
	'search' => $sql_temp,
	'url' => $url
);
$url = "index.php?m=hr&do=sys_log&";

$width='800px';
$s = new LogRW();
$result = $s->logShow($params);

include TEMP.'header.htm';
include TEMP.'hr/sys_log.htm';
include TEMP.'footer.htm';
?>