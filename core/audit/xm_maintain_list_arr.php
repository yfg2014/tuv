<?php
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
	'audit_code'=>array(
	  'kind'=>'text',
	  'name'=>'audit_code',
	  'msg'=>'专业代码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'audit_code',
	  'sql_kind'=>'%like%'
	),
	'auditplandate1'=>array(
	  'kind'=>'date1',
	  'name'=>'auditplandate1',
	  'msg'=>'计划开始时间',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'auditplandate',
	  'sql_kind'=>'>='
	),
	  'auditplandate2'=>array(
	  'kind'=>'date2',
	  'name'=>'auditplandate2',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'auditplandate',
	  'sql_kind'=>'<='
	),
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'104px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
	),
	'eiarea'=>array(
	  'kind'=>'eiarea',
	  'name'=>'eiarea',
	  'msg'=>'省份',
	  'width'=>'104px',
	  'arr'=>$setup_province,
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),

	'br1'=>array(
	'kind'=>'br'
	),
	'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxm_id',
		'sql_kind'=>'in'
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

	  'finalItemDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'finalItemDate1',
	  'msg'=>'监审最后期限',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'finalItemDate',
	  'sql_kind'=>'>='
	),
	  'finalItemDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'finalItemDate2',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'finalItemDate',
	  'sql_kind'=>'<='
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'104px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'eireg_address'=>array(
	  'kind'=>'text',
	  'name'=>'eireg_address',
	  'msg'=>'地址',
	  'width'=>'104px',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	),

	'online'=>array(
	  'kind'=>'hidden',
	  'name'=>'online'
	  ),
	'br2'=>array(
	'kind'=>'br'
	),
	'ymdate'=>array(
	  'kind'=>'days',
	  'name'=>'ymdate',
	  'msg'=>'到期提醒',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'finalItemDate',
	  'sql_kind'=>''
	),
		'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'90px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'wh_date1'=>array(
	  'kind'=>'date1',
	  'name'=>'wh_date1',
	  'msg'=>'维护时间',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'wh_date',
	  'sql_kind'=>'>='
	),
	  'wh_date2'=>array(
	  'kind'=>'date2',
	  'name'=>'wh_date2',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'wh_date',
	  'sql_kind'=>'<='
	),
	'product'=>array(
		'kind'=>'text',
		'name'=>'product',
		'msg'=>'认证产品',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	)
)
?>