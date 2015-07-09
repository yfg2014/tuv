<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Auditor.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_item_online.php';

Power::CkPower('C1S');
$width = '1300px';
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
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'体&nbsp;&nbsp;系',
	  'width'=>'80px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'taskBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskBeginDate1',
	  'msg'=>'审核开始时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
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
	'br1'=>array(
	'kind'=>'br'
	),
	'htxmcode'=>array(
		'kind'=>'text',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	  ),
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'80px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'%like%'
	),
	'taskEndDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskEndDate1',
	  'msg'=>'审核结束时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskEndDate',
	  'sql_kind'=>'>='
	),
	'taskEndDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'taskEndDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskEndDate',
	  'sql_kind'=>'<='
	),
	'xmonline'=>array(
	  'kind'=>'hidden',
	  'name'=>'xmonline',
	  'msg'=>'',
	  'width'=>'',
	  'arr'=>'',
	  'sql_field'=>'xmonline',
	  'sql_kind'=>'='
	)
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
//$xmonline == '' && $sql_temp .= " AND xmonline='3' ";
$sql_temp .= Power::xt_htfrom();

$sql_temp = " AND xmonline!='5'".$sql_temp;

if($htxmcode != ''){
	$htxmid = array();
	$sql = "SELECT id FROM ht_contract_item WHERE htxmcode like '%{$htxmcode}%'";
	$query = $db->query($sql);
	while($rows = $db->fetch_array($query)){
		$htxmid []= $rows['id'];
	}
	$htxmid = implode('\',\'',$htxmid);
	$xm_q = $db->query("SELECT taskId FROM xm_item WHERE htxm_id in ('$htxmid')");
	while($xm = $db->fetch_array($xm_q)){
		$taskId_t []= $xm['taskId'];
	}
	$taskId_t = implode('\',\'',$taskId_t);
	$sql_temp = "  and id IN('$taskId_t')".$sql_temp;
}
if($product!='')
{
	$sql_temp = $sql_temp." AND id IN(SELECT taskId FROM xm_item WHERE product in( SELECT code FROM setup_product WHERE msg LIKE '%$product%' ))";
}

$qy_num = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM xm_task WHERE 1 $sql_temp ");

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Task = new Task();
$result = $Task->listTask($params);

include TEMP.'header.htm';
include TEMP.'audit/return_record.htm';
include TEMP.'footer.htm';
?>