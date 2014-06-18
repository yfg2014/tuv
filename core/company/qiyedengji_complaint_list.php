<?php
include_once S_DIR.'/include/module/Company.php';
include S_DIR.'/include/module/CompanyComplaint.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';

Power::CkPower('A2S');
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
	's_date'=>array(
	  'kind'=>'date1',   //要搜索的类型
	  'name'=>'s_date',
	  'msg'=>'处理时间',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'processing_date',
	  'sql_kind'=>'>='
	),
	'e_date'=>array(
	  'kind'=>'date2',  
	  'name'=>'e_date',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'processing_date',
	  'sql_kind'=>'<='
	)
);
$TopSearch = new TopSearch($seach_arr);

//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;

//构造搜索SQL
$sql_temp	= $TopSearch->SearchSql;
//构造搜索HTML表单项
$SearchHtml = $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower(1);

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$width='1200px';
$CompanyComplaint = new CompanyComplaint();
$result = $CompanyComplaint->list_Complaint($params);

include T_DIR.'header.htm';
include T_DIR.'company/qiyedengji_complaint_list.htm';
include T_DIR.'footer.htm';
?>