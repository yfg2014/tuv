<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/Wage.php');
include(SET_DIR.'setup_htfrom.php');
include(S_DIR.'include/topsearch.php');			

Power::CkPower('H0G');

$seach_arr=array(
	'username'=>array(
		'kind'=>'text',
		'name'=>'username',
		'msg'=>'姓名',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'username',
		'sql_kind'=>'%like%'
	  ),
	  'grant_time1'=>array(
	  'kind'=>'date1',
	  'name'=>'grant_time1',
	  'msg'=>'审核补助时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'grant_time',
	  'sql_kind'=>'>='
	),
	  'grant_time2'=>array(
	  'kind'=>'date2',
	  'name'=>'grant_time2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'grant_time',
	  'sql_kind'=>'<='
	),
);

$width='700px';
$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Wage = new Wage();
$result = $Wage->listElement($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_wage_list.htm';
include TEMP.'footer.htm';
?>