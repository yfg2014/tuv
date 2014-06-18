<?php

/**
 * Աб  עʸ רҵ 
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//人员基本信息类
include(S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源


Power::CkPower('H2E');
$seach_arr=array(
	'username'=>array(
		'kind'=>'username',
		'name'=>'username',
		'msg'=>'姓名',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'id ',
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
	$sql_temp = $sql_temp." and id in(SELECT id FROM {$dbtable['hr_information']} WHERE yjm='{$yjm}')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$s = new Hr_information();
$result = $s->listHr($params);

include T_DIR.'header.htm';
include T_DIR.'audit/ask_for_leave.htm';
include T_DIR.'footer.htm';
?>