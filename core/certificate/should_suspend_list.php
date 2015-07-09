<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
Power::CkPower('E1S');
$width = '950px';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
			  'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxm_id',
		'sql_kind'=>'in'
	  ),
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	
	  'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'ht_id',
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

	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'70px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
	),'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'体系',
	  'width'=>'70px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'br1'=>array(
	'kind'=>'br'
	),
	  'finalItemDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'finalItemDate1',
	  'msg'=>'监审最后日',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'finalItemDate',
	  'sql_kind'=>'>='
	),
	  'finalItemDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'finalItemDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'finalItemDate',
	  'sql_kind'=>'<='
	),
	'auditplandate1'=>array(
	  'kind'=>'date1',
	  'name'=>'auditplandate1',
	  'msg'=>'计划开始',
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
	'certno'=>array(
	  'kind'=>'certNo',
	  'name'=>'certno',
	  'msg'=>'证书编号',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsid',
	  'sql_kind'=>'='
	)
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$time = date("Y-m-d");
$sql_temp = " and finalItemDate<'{$time}' and (audit_type='1002' or audit_type='1003' or audit_type='1004') and (online='0' or online='5') AND
(SELECT zs.id FROM zs_cert zs WHERE zs.id=zsid AND (zs.online='01' or zs.online='04'))!='' ".$sql_temp;
$sql_temp .= Power::xt_htfrom();
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$Item = new Item();
$result = $Item->listElementStop($params);

include TEMP.'header.htm';
include TEMP.'certificate/should_suspend_list.htm';
include TEMP.'footer.htm';
?>