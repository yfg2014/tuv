<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/CompanyGift.php');
include (S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';

Power::CkPower('A3S');
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
	  'width'=>'100px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	's_date'=>array(
	  'kind'=>'date1',   //要搜索的类型
	  'name'=>'s_date',
	  'msg'=>'发放日期',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'send_date',
	  'sql_kind'=>'>='
	),
	'e_date'=>array(
	  'kind'=>'date2',  
	  'name'=>'e_date',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'send_date',
	  'sql_kind'=>'<='
	)
);

$TopSearch = new TopSearch($seach_arr);

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp	.= Power::xt_htfrom();

$width='850px';

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

$s = new CompanyGift();
$result = $s->list_company_gift($params);

include TEMP.'header.htm';
include 'template/qiye_gift_list.htm';
include TEMP.'footer.htm';
?>