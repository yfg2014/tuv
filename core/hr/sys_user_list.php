<?php

/**
 * Աб  עʸ רҵ
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_public_list.php');
include(SET_DIR.'setup_htfrom.php');	
include(SET_DIR.'setup_province.php');
include(S_DIR.'include/topsearch.php');
Power::CkPower('J1E');
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
		'sql_kind'=>'%like%'
	  ),
	  'province'=>array(
	  'kind'=>'select',   //?????company
	  'name'=>'province',
	  'msg'=>'地区',
	  'width'=>'104px',
	  'arr'=>$setup_province,
	  'sql_field'=>'province',
	  'sql_kind'=>'='
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
	  'arr'=>array('01'=>'专职','02'=>'兼职','08'=>'无'),
	  'sql_field'=>'worktype',
	  'sql_kind'=>'='
	)

);



$TopSearch = new TopSearch($seach_arr);
//???
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?



//其它情况

if($s_t_date!=''){
$s_t_date_sql = date("Y-m-d",mktime(0,0,0,date("m")-$s_t_date,date("d"),date("Y")));
$sql_temp .= " and  pinyongover >= '$s_t_date_sql'";
}

$width='800px';

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Hr_public_list();
$result = $s->list_hr_public($params);

include T_DIR.'header.htm';
include T_DIR.'hr/sys_user_list.htm';
include T_DIR.'footer.htm';
?>