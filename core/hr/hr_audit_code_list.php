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
include(SET_DIR.'setup_hr_reg_qualification.php');	    //能力来源
include_once S_DIR.'include/setup/setup_htfrom.php';

Power::CkPower('H2S');
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
	 'ability_source'=>array(
		'kind'=>'select',
		'name'=>'ability_source',
		'msg'=>'专业能力',
		'width'=>'100px',
		'arr'=>$setup_hr_ability_source,
		'sql_field'=>'ability_source',
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
	 'br'=>array(
	    'kind'=>'br'
	 ),
	'online'=>array(
		'kind'=>'select',
		'name'=>'online',
		'msg'=>'状态',
		'width'=>'80px',
		'arr'=>array('1'=>'有效','0'=>'关闭'),
		//'arr'=>array('1'=>'有效','0'=>'关闭','2'=>'申请'),
		'sql_field'=>'online',
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
	 'address'=>array(
		'kind'=>'text',
		'name'=>'address',
		'msg'=>'地址',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'address',
		'sql_kind'=>''
	 ),
);


$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML
$sql_temp	.= Power::xt_htfrom();
$width="1000px";
if($yjm != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE yjm='{$yjm}')";
}
if($address != ''){
	$sql_temp = $sql_temp." and hr_id in(SELECT id FROM {$dbtable['hr_information']} WHERE address LIKE '%{$address}%')";
}
if ($xiaolei != ''){
	$xiaolei = str_replace('；',';',$xiaolei);
	$lei = explode(';',$xiaolei);
	if (count($lei) > 1){
		foreach($lei as $v)
		{
			$xiao []= "xiaolei='".$v."'";
		}
		$xiaolei = '('.implode(' or ',$xiao).')';
		$sql_temp = $sql_temp." and {$xiaolei}";
	}else{
		$sql_temp = $sql_temp." and xiaolei='{$xiaolei}'";
	}
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Hr_audit_code();
$result = $s->list_audit_code($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_audit_code_list.htm';
include TEMP.'footer.htm';
?>