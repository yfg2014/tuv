<?php

/**
 * 人员专业能力
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//人员基本信息类
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码
include(S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源
include(SET_DIR.'setup_mark.php');							//认可标志
include(SET_DIR.'setup_audit_iso.php');				//体系领域
include(SET_DIR.'setup_hr_ability_source.php');	    //能力来源

Power::CkPower('H3P');
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
	  'xiaolei'=>array(
		'kind'=>'text',
		'name'=>'xiaolei',
		'msg'=>'专业代码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'xiaolei',
		'sql_kind'=>'='
	 ),
	  'iso'=>array(
		'kind'=>'select',
		'name'=>'iso',
		'msg'=>'体系',
		'width'=>'50px',
		'arr'=>$setup_audit_iso,
		'sql_field'=>'iso',
		'sql_kind'=>'='
	 ),
	 'ability_source'=>array(
		'kind'=>'select',
		'name'=>'ability_source',
		'msg'=>'专业能力',
		'width'=>'100px',
		'arr'=>$setup_hr_ability_source,
		'sql_field'=>'ability_source',
		'sql_kind'=>'='
	 )
);

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML

$width="800px";
$sql_temp .=" and  online = 2";
if($yjm != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE yjm='{$yjm}')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$s = new Hr_audit_code();
$result = $s->list_audit_code($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_apply_code_list.htm';
include TEMP.'footer.htm';
?>