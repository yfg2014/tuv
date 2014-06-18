<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/finance.php');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

Power::CkPower('G0S');

$width = '800px';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	 'htxmcode'=>array(
	  'kind'=>'htxmcode',
	  'name'=>'htxmcode',
	  'msg'=>'项目编号',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'id',
	  'sql_kind'=>'in'
	),
	  'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'id',
	  'sql_kind'=>'='
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'70px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'体系',
	  'width'=>'50px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	)
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
if(strpos($_SESSION['power'],'Z6S')!==false){
	$qiye = $db->query("SELECT id FROM `mk_company` WHERE zd_ren='$_SESSION[username]'");
	while($row = $db->fetch_array($qiye)){ $q_zuzhi_id []= $row['id']; }
	$zd_zuzhi_id = implode(',', $q_zuzhi_id);
	$sql_temp .= " AND zuzhi_id IN ('$zd_zuzhi_id')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Finance();
$result = $s->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'finance/finance_list.htm';
include T_DIR.'footer.htm';

?>