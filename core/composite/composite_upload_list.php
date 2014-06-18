<?php
include_once S_DIR.'/include/module/CompositeUpload.php';
include_once S_DIR.'/include/setup/setup_uploadfile.php';
include(S_DIR.'include/topsearch.php');

Power::CkPower('L0E');
$width='850px';
$seach_arr = array(
	'filename'=>array(
		'kind'=>'text',
		'name'=>'filename',
		'msg'=>'文件名称',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'filename',
		'sql_kind'=>'%like%'
	),
	'filekind'=>array(
	  'kind'=>'select',
	  'name'=>'filekind',
	  'msg'=>'文件类型',
	  'width'=>'80px',
	  'arr'=>$setup_uploadfile,
	  'sql_field'=>'filekind',
	  'sql_kind'=>'='
	),
	'other'=>array(
		'kind'=>'text',
		'name'=>'other',
		'msg'=>'备注',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'other',
		'sql_kind'=>'%like%'
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
);
$TopSearch = new TopSearch($seach_arr);

//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;

$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml = $TopSearch->SearchHtml;		//构造搜索HTML表单项
//$sql_temp .= Power::xt_htfrom();

//权限文件
foreach($setup_uploadfile as $val){
	if(strpos($_SESSION['power'],$val['quanxian'] ) !== false){
		$filekind []= $val['code'];
	}
}
$sql_filekind = implode(',',$filekind);

$sql_temp .= "AND filekind IN ($sql_filekind)";

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$CompositeUpload = new CompositeUpload();
$result = $CompositeUpload->listSetup($params);

include T_DIR.'header.htm';
include T_DIR.'composite/composite_upload_list.htm';
include T_DIR.'footer.htm';
?>