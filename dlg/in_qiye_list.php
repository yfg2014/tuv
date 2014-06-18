<?php
include '../include/globals.php';
include(S_DIR.'include/module/Company.php');
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_province.php';

GrepUtil::InitGP(array('power', 'username'));

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'id',
	  'sql_kind'=>'in'
	),
	'eientercode'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eientercode',
	  'msg'=>'企业编号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'eientercode',
	  'sql_kind'=>'%like%'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'100px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'province'=>array(
	  'kind'=>'select',
	  'name'=>'province',
	  'msg'=>'省份地址',
	  'width'=>'100px',
	  'arr'=>$setup_province,
	  'sql_field'=>'eiarea_code',
	  'sql_kind'=>'='
	),
	'br'=>array(
	    'kind'=>'br'
	 ),
	  'eilinkman'=>array(
	  'kind'=>'text',
	  'name'=>'eilinkman',
	  'msg'=>'联 系 人',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'eilinkman',
	  'sql_kind'=>'%like%'
	)
);

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= './in_qiye_list.php?';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

if(strpos($power,'Z6S')!==false){
	$sql_temp .= " AND zd_ren='$username'";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$width='850px';
$s = new Company();
$result = $s->listElement($params);

include 'template/header.htm';
include 'template/in_qiye_list.htm';
include 'template/footer.htm';

?>