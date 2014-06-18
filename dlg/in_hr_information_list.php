<?php
include '../include/globals.php';
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_htfrom.php');
include(SET_DIR.'setup_province.php');
		
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
	  'yjm'=>array(
		'kind'=>'text',
		'name'=>'yjm',
		'msg'=>'易记码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'yjm',
		'sql_kind'=>'='
	  ),
	  'province'=>array(
	  'kind'=>'select',   //?????company
	  'name'=>'province',
	  'msg'=>'地区',
	  'width'=>'100px',
	  'arr'=>$setup_province,
	  'sql_field'=>'province',
	  'sql_kind'=>'='
	),
    'htfrom'=>array(
	  'kind'=>'select',   
	  'name'=>'htfrom',
	  'msg'=>'人员来源',
	  'width'=>'100px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
		'br'=>array(
	    'kind'=>'br'
	 ),
    'worktype'=>array(
	  'kind'=>'select',   
	  'name'=>'worktype',
	  'msg'=>'兼专职',
	  'width'=>'100px',
	  'arr'=>array('01'=>'兼职','02'=>'专职','08'=>'无'),
	  'sql_field'=>'worktype',
	  'sql_kind'=>'='
	),
    'working'=>array(
	  'kind'=>'select',   
	  'name'=>'working',
	  'msg'=>'在聘情况',
	  'width'=>'100px',
	  'arr'=>array('1'=>'在职','0'=>'离职'),
	  'sql_field'=>'working',
	  'sql_kind'=>'='
	)
);

$width='100%';

$TopSearch = new TopSearch($seach_arr);
$baseurl	= './in_hr_information_list.php?';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new hr_information();
$result = $s->listHr($params);

include 'template/header.htm';
include 'template/in_hr_information_list.htm';
include 'template/footer.htm';
?>