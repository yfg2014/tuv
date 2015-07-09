<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/module/Training.php';

Power::CkPower('H4S');

$seach_arr = array(
	'title'=>array(
	  'kind'=>'text',
	  'name'=>'title',
	  'msg'=>'培训标题',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'title',
	  'sql_kind'=>'%like%'
	),
	'teachertraining'=>array(
	  'kind'=>'text',
	  'name'=>'teachertraining',
	  'msg'=>'培训老师',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'teachertraining',
	  'sql_kind'=>'%like%'
	),
	 'trainingdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'trainingdate1',
	  'msg'=>'培训时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'trainingdate',
	  'sql_kind'=>'>='
	),
	  'trainingdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'trainingdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'trainingdate',
	  'sql_kind'=>'<='
	),
);
$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp  .= Power::xt_htfrom();

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$Training = new Training();
$result = $Training->listElement($params);

$width= '600px';
include TEMP.'header.htm';
include TEMP.'hr/hr_training_list.htm';
include TEMP.'footer.htm';
?>