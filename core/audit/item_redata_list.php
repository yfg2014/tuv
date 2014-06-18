<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'core/audit/item_redata_list_search_arr.php';

Power::CkPower('C7S');

$width = '1250px';

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();

if($ymdate != ''){
	$ymdate_arr = explode("-",$ymdate);
	$tianshu=date("t",mktime(0,0,0,$ymdate_arr['1'],01,$ymdate_arr['0']));//获得当月天数
	$sql_temp .= "and taskEndDate>= '{$ymdate}-01' and taskEndDate<= '{$ymdate}-{$tianshu}'";	
}

if($zlok == ''){
	$sql_temp = $sql_temp." AND turn='0'";
	$field_t = '资料未收回';
	$field = '资料已收回';
}
elseif($zlok == '1'){
	$sql_temp = $sql_temp." AND turn='1'";
	$field_t = '资料已收回';
	$field = '资料未收回';
}

$sql_temp .= " AND audit_type!='1007' AND online='3'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$qy_num = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM xm_item WHERE 1 $sql_temp ");
$tx = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM xm_item WHERE kind='1' $sql_temp ");
$cp = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM xm_item WHERE kind='2' $sql_temp ");

$Item = new Item();
$result = $Item->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'audit/item_redata_list.htm';
include T_DIR.'footer.htm';
?>