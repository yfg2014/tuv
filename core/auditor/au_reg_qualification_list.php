<?php

/**
 *
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Au_reg_qualification.php');
include (S_DIR.'include/module/Au_information_info.php');
include (S_DIR.'include/topsearch.php');
include (SET_DIR.'setup_audit_iso.php');
include (SET_DIR.'setup_htfrom.php');
include (SET_DIR.'setup_province.php');
include (SET_DIR.'setup_hr_reg_qualification.php');
include (SET_DIR.'setup_hr_reg_qualification_online.php');

$id = $_SESSION['userid'];
Power::CkPower('K0S');
Power::xt_htfrom($id,'hr');
$seach_arr=array(
	 'qualification_no'=>array(
	  'kind'=>'text',
	  'name'=>'qualification_no',
	  'msg'=>'注册证号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'qualification_no',
	  'sql_kind'=>'='
	),
	 'qualification'=>array(
	  'kind'=>'select',
	  'name'=>'qualification',
	  'msg'=>'注册资格',
	  'width'=>'60px',
	  'arr'=>$setup_hr_reg_qualification,
	  'sql_field'=>'qualification',
	  'sql_kind'=>'='
	),
	'iso'=>array(
		'kind'=>'select',
		'name'=>'iso',
		'msg'=>'体系',
		'width'=>'60px',
		'arr'=>$setup_audit_iso,
		'sql_field'=>'iso',
		'sql_kind'=>'%like%'
	),
	'online'=>array(
		'kind'=>'select',
		'name'=>'online',
		'msg'=>'证书状态',
		'width'=>'60px',
		'arr'=>$setup_hr_reg_qualification_online,
		'sql_field'=>'online',
		'sql_kind'=>'='
	),
	't_date'=>array(
		'kind'=>'t_date',
		'name'=>'t_date',
		'msg'=>'到期提醒',
		'width'=>'60px',
		'arr'=>'',
		'sql_field'=>'e_date',
		'sql_kind'=>'<='
	),
	 'date1'=>array(
		'kind'=>'date1',
		'name'=>'date',
		'msg'=>'注册日期',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'s_date',
		'sql_kind'=>'>='
	 ),
	'date2'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'s_date',
		'sql_kind'=>'<='
	 )
);

$TopSearch = new TopSearch($seach_arr);
//???
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&id='.$id.'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?

//其它情况 提前时间
if($id!=''){
$sql_temp .= " and  hr_id =$id ";
}

$width='950px';

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

//$db_perpage=1;

$s = new Au_reg_qualification();
$result = $s->list_reg_qualification($params);

$s1= new Au_information_info();
$hrinfo = $s1->query($id);

include TEMP.'header.htm';
include TEMP.'auditor/au_reg_qualification_list.htm';
include TEMP.'footer.htm';
?>