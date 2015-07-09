<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/module/Training.php';

Power::CkPower('H5S');

$seach_arr = array(

	'title'=>array(
	  'kind'=>'text',
	  'name'=>'title',
	  'msg'=>'培训标题',
	  'width'=>'105px',
	),
	'teachertraining'=>array(
	  'kind'=>'text',
	  'name'=>'teachertraining',
	  'msg'=>'培训老师',
	  'width'=>'85px',
	),
	 'trainingdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'trainingdate1',
	  'msg'=>'培训时间',
	  'width'=>'85px',
	),
	  'trainingdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'trainingdate2',
	  'msg'=>'',
	  'width'=>'85px',
	),
	'username'=>array(
	  'kind'=>'text',
	  'name'=>'username',
	  'msg'=>'人员名称',
	  'width'=>'105px',
	),
	'hr_id'=>array(
	  'kind'=>'hidden',
	  'name'=>'hr_id',
	  'msg'=>'',
	  'width'=>'',
	),
	 'yjm'=>array(
		'kind'=>'text',
		'name'=>'yjm',
		'msg'=>'易记码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	)
);
$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;

if($hr_id!=''){$sql_temp = $sql_temp." and hr_id='$hr_id'";}
if ($title != ''){$sql_temp = $sql_temp." and mix_id in(SELECT id FROM {$dbtable['hr_training']} WHERE title like '%{$title}%')";}
if ($teachertraining != ''){$sql_temp = $sql_temp." and mix_id in(SELECT id FROM {$dbtable['hr_training']} WHERE teachertraining like '%{$teachertraining}%')";}
if ($trainingdate1 != '' && $trainingdate2 != ''){$sql_temp = $sql_temp." and mix_id in(SELECT id FROM {$dbtable['hr_training']} WHERE trainingdate>='{$trainingdate1}' and trainingdate<='{$trainingdate2}')";}
if ($username != ''){$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE username LIKE '%{$username}%')";}
if($yjm != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE yjm='{$yjm}')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Training = new Training();
$result = $Training->listElementId($params);

$width= '600px';
include TEMP.'header.htm';
include TEMP.'hr/hr_training_list2.htm';
include TEMP.'footer.htm';
?>