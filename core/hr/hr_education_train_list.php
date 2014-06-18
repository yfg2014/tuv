<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_information.php');
include (S_DIR.'include/module/EducationTrain.php');
include (S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_hr_education_train.php');

Power::CkPower('H1P');
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
	 'type'=>array(
	  'kind'=>'select',
	  'name'=>'type',
	  'msg'=>'教育/培训类型',
	  'width'=>'80px',
	  'arr'=>$setup_hr_education_train,
	  'sql_field'=>'type',
	  'sql_kind'=>'='
	),
);

$TopSearch = new TopSearch($seach_arr);

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;	
$SearchHtml	= $TopSearch->SearchHtml;	
$sql_temp	.= Power::xt_htfrom();

$width='650px';

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new EducationTrain();
$result = $s->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'hr/hr_education_train_list.htm';
include T_DIR.'footer.htm';
?>