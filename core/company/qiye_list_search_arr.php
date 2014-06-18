<?php
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
	'eimark'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eimark',
	  'msg'=>'易记码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'eimark',
	  'sql_kind'=>'%like%'
	),
	'eientercode'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eientercode',
	  'msg'=>'企业编号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'eientercode',
	  'sql_kind'=>'='
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
?>