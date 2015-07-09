<?php

/**
 * Աб  עʸ רҵ 
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//人员基本信息类
include(S_DIR.'include/module/Hr_reg_qualification.php');			//人员专业能力类 审核代码
include(S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源
include(SET_DIR.'setup_mark.php');							//认可标志
include(SET_DIR.'setup_audit_iso.php');				//体系领域
include(SET_DIR.'setup_hr_ability_source.php');	    //能力来源
include(SET_DIR.'setup_hr_reg_qualification.php');	    //能力来源
include_once S_DIR.'include/setup/setup_htfrom.php';

Power::CkPower('H2E');
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
	  'iso'=>array(
		'kind'=>'select',
		'name'=>'iso',
		'msg'=>'体系',
		'width'=>'50px',
		'arr'=>$setup_audit_iso,
		'sql_field'=>'iso',
		'sql_kind'=>'='
	 ),
	 'qualification'=>array(
		'kind'=>'select',
		'name'=>'qualification',
		'msg'=>'资格',
		'width'=>'50px',
		'arr'=>$setup_hr_reg_qualification,
		'sql_field'=>'qualification',
		'sql_kind'=>'='
	 ),
	 'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
);


$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML
$sql_temp	.= Power::xt_htfrom();
$sql_temp   .= "and online='1'";
$width="800px";
if($yjm != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE yjm='{$yjm}')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$s = new Hr_reg_qualification();
$result = $s->list_reg_qualification($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_public_list_02.htm';
include TEMP.'footer.htm';
?>