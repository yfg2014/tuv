<?php
include_once S_DIR.'/include/module/Company.php';
include S_DIR.'/include/module/CompanyQuestion.php';
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
	  'msg'=>'登记日期',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'printdate',
	  'sql_kind'=>'>='
	),
	'e_date'=>array(
	  'kind'=>'date2',  
	  'name'=>'e_date',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'printdate',
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

$width='900px';
$CompanyQuestion = new CompanyQuestion();
$result = $CompanyQuestion->listQuestion($params);



include TEMP.'header.htm';
include TEMP.'company/qiyedengji_question_list.htm';
include TEMP.'footer.htm';
?>