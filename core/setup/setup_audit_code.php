<?php
/**
 * 业务分类
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_code.php');
include(SET_DIR.'setup_audit_iso.php');
include(SET_DIR.'setup_mark.php');
include(S_DIR.'include/topsearch.php');

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'体&nbsp;&nbsp;&nbsp;&nbsp;系',
	  'width'=>'80px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'code'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'code',
	  'msg'=>'专业代码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'code',
	  'sql_kind'=>'like%'
	),
	'msg'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'msg',
	  'msg'=>'专业内容',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'msg',
	  'sql_kind'=>'%like%'
	),
	'mark'=>array(
	  'kind'=>'select',   //要搜索的类型，企业搜索固定为company
	  'name'=>'mark',
	  'msg'=>'认可标志',
	  'width'=>'100px',
	  'arr'=>$setup_mark,
	  'sql_field'=>'',
	  'sql_kind'=>''
	)
);

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

if($mark != ''){
	if($mark == '00'){
		$sql_temp .= " AND mark = '00'";
	}else{
		$sql_temp .= " AND mark like '%$mark%'";
	}
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '50'; //一页显示记录条数

$s = new setup_audit_code();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include T_DIR.'header.htm';
include T_DIR.'setup/setup_audit_code.htm';
include T_DIR.'footer.htm';
?>