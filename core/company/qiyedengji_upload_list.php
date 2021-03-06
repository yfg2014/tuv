<?php
include_once S_DIR.'/include/module/CompanyUpload.php';
//include_once S_DIR.'/include/setup/setup_company_uploadfile.php';
include_once S_DIR.'/include/setup/setup_zuzhi_uploadfile.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_province.php';

Power::CkPower('A1S');
$width='850px';
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
	  'msg'=>'上传日期',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'uploadtime',
	  'sql_kind'=>'>='
	),
	'e_date'=>array(
	  'kind'=>'date2',  
	  'name'=>'e_date',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'uploadtime',
	  'sql_kind'=>'<='
	),
	'filekind'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'filekind',
	  'msg'=>'文件类型',
	  'width'=>'104px',
	  'arr'=>$setup_zuzhi_uploadfile,
	  'sql_field'=>'filekind',
	  'sql_kind'=>'='
	),
);
$TopSearch = new TopSearch($seach_arr);

//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;

$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml = $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower(1);

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$CompanyUpload = new CompanyUpload();
$result = $CompanyUpload->listSetup($params);

include TEMP.'header.htm';
include TEMP.'company/qiyedengji_upload_list.htm';
include TEMP.'footer.htm';
?>