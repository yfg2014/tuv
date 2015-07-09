<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
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
	  'yjm'=>array(
		'kind'=>'text',
		'name'=>'yjm',
		'msg'=>'易记码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'yjm',
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
	)
);

$width='700px';
$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp .= Power::xt_htfrom();

$sql_temp .= " AND working='1'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new hr_information();
$result = $s->listHr($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_wage.htm';
include TEMP.'footer.htm';
?>