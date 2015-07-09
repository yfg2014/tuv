<?php

/**
 * Աб  עʸ רҵ 
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_public_list.php');
include(SET_DIR.'setup_htfrom.php');			//ͬԴ ԱԴ
include(SET_DIR.'setup_province.php');
include(S_DIR.'include/topsearch.php');
Power::CkPower('H2E');
$seach_arr=array(
//?
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
	  'width'=>'104px',
	  'arr'=>$setup_province,
	  'sql_field'=>'province',
	  'sql_kind'=>'in'
	),
	't_date'=>array(
		'kind'=>'t_date',
		'name'=>'t_date',
		'msg'=>'到期提醒',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'pinyongover',
		'sql_kind'=>'<='
	),
	'br'=>array(
	    'kind'=>'br'
	 ),
    'htfrom'=>array(
	  'kind'=>'select',   
	  'name'=>'htfrom',
	  'msg'=>'人员来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
    'worktype'=>array(
	  'kind'=>'select',   
	  'name'=>'worktype',
	  'msg'=>'兼专职',
	  'width'=>'104px',
	 'arr'=>array('01'=>'兼职','02'=>'专职','08'=>'无'),
	  'sql_field'=>'worktype',
	  'sql_kind'=>'='
	)

);


$TopSearch = new TopSearch($seach_arr);
//???
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&core='.$core.'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp	.= Power::xt_htfrom();	

$width='700px';

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Hr_public_list();
$result = $s->list_hr_public($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_public_list_01.htm';
include TEMP.'footer.htm';
?>