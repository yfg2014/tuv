<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_reg_qualification.php');
include (S_DIR.'include/module/Hr_information.php');
include (S_DIR.'include/topsearch.php');
include (SET_DIR.'setup_audit_iso.php');
include (SET_DIR.'setup_htfrom.php');
include (SET_DIR.'setup_province.php');
include (SET_DIR.'setup_hr_reg_qualification.php');
include (SET_DIR.'setup_hr_reg_qualification_online.php');

Power::CkPower('H1S');
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
	 'qualification_no'=>array(
	  'kind'=>'text',
	  'name'=>'qualification_no',
	  'msg'=>'注册证号',
	  'width'=>'104px',
	  'arr'=>'',
	  'sql_field'=>'qualification_no',
	  'sql_kind'=>'='
	),
	'online'=>array(
		'kind'=>'select',
		'name'=>'online',
		'msg'=>'证书状态',
		'width'=>'50px',
		'arr'=>$setup_hr_reg_qualification_online,
		'sql_field'=>'online',
		'sql_kind'=>'='
	),
	'htfrom'=>array(
	  'kind'=>'select',
	  'name'=>'htfrom',
	  'msg'=>'人员来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	't_date'=>array(
		'kind'=>'t_date',
		'name'=>'t_date',
		'msg'=>'到期提醒',
		'width'=>'80px',
		'arr'=>'',
		'sql_field'=>'e_date',
		'sql_kind'=>'<='
	),
	'yearok'=>array(
		'kind'=>'select',
		'name'=>'yearok',
		'msg'=>'该做年度确认提醒',
		'width'=>'80px',
		'arr'=>array('1'=>'第一次年度确认','2'=>'第二次年度确认'),
		'sql_field'=>'',
		'sql_kind'=>''
	),
	'br'=>array(
	    'kind'=>'br'
	 ),
	 'daoqi'=>array(
		'kind'=>'days',
		'name'=>'daoqi',
		'msg'=>'到期天数提醒',
		'width'=>'92px',
		'arr'=>'',
		'sql_field'=>'e_date',
		'sql_kind'=>''
	 ),
	'province'=>array(
	  'kind'=>'province',   //
	  'name'=>'province',
	  'msg'=>'地区',
	  'width'=>'104px',
	  'arr'=>$setup_province,
	  'sql_field'=>'hr_id',
	  'sql_kind'=>'in'
	),
	'iso'=>array(
		'kind'=>'select',
		'name'=>'iso',
		'msg'=>'体系',
		'width'=>'50px',
		'arr'=>$setup_audit_iso,
		'sql_field'=>'iso',
		'sql_kind'=>'%like%'
	),
	'worktype'=>array(
	  'kind'=>'select',
	  'name'=>'worktype',
	  'msg'=>'审核员性质',
	  'width'=>'50px',
	  'arr'=>array('01'=>'专职','02'=>'兼职','08'=>'其他'),
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	 'qualification'=>array(
	  'kind'=>'select',
	  'name'=>'qualification',
	  'msg'=>'注册资格',
	  'width'=>'50px',
	  'arr'=>$setup_hr_reg_qualification,
	  'sql_field'=>'qualification',
	  'sql_kind'=>'='
	),
	'br2'=>array(
	    'kind'=>'br'
	 ),
	 'date1'=>array(
		'kind'=>'date1',
		'name'=>'date',
		'msg'=>'注册到期',
		'width'=>'92px',
		'arr'=>'',
		'sql_field'=>'s_date',
		'sql_kind'=>'>='
	 ),
	'date2'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'',
		'width'=>'92px',
		'arr'=>'',
		'sql_field'=>'s_date',
		'sql_kind'=>'<='
	 ),

);

$TopSearch = new TopSearch($seach_arr);

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
//$sql_temp	.= Power::xt_htfrom();

$width='950px';

if($online == ''){
	$sql_temp .= " AND online='1'";
}
if($yjm != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE yjm='{$yjm}')";
}
if($worktype != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE worktype='{$worktype}')";
}
//年度确认提醒
if($yearok == '1'){
	$y = date("Y") - 1 ;
	$sql_temp .= " AND yeardate1 = '' AND s_date >= '$y-01-01' AND s_date <= '$y-12-31'";
}
if($yearok == '2'){
	$y = date("Y") - 2 ;
	$sql_temp .= " AND yeardate1 != '' AND yeardate2 = '' AND s_date >= '$y-01-01' AND s_date <= '$y-12-31'";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Hr_reg_qualification();
$result = $s->list_reg_qualification($params);

include T_DIR.'header.htm';
include T_DIR.'hr/hr_reg_qualification_list.htm';
include T_DIR.'footer.htm';
?>