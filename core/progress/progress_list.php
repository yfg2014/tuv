<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

$width='1000px';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'70px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'taskBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskBeginDate1',
	  'msg'=>'任务开始日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'auditplandate',
	  'sql_kind'=>'>='
	),
	  'taskBeginDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'taskBeginDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'<='
	),
	  'auditplandate1'=>array(
	  'kind'=>'date1',
	  'name'=>'auditplandate1',
	  'msg'=>'计划开始日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'auditplandate',
	  'sql_kind'=>'>='
	),
	  'auditplandate2'=>array(
	  'kind'=>'date2',
	  'name'=>'auditplandate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'auditplandate',
	  'sql_kind'=>'<='
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
	'br1'=>array(
	'kind'=>'br'
	),
	 'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'ht_id',
	  'sql_kind'=>'='
	),
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'70px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
	),
	  'assessmentdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'assessmentdate1',
	  'msg'=>'评定通过日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'assessmentdate',
	  'sql_kind'=>'>='
	),
	  'assessmentdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'assessmentdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'assessmentdate',
	  'sql_kind'=>'<='
	),
	'zsprintdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'zsprintdate1',
	  'msg'=>'证书打印日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsprintdate',
	  'sql_kind'=>'>='
	),
	  'zsprintdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'zsprintdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsprintdate',
	  'sql_kind'=>'<='
	),
	
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$sql_temp = $sql_temp." and online!='6' and online!='7'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$Item = new Item();
$result = $Item->listElement($params);

include TEMP.'header.htm';
include TEMP.'progress/progress_list.htm';
include TEMP.'footer.htm';
?>