<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_item_online.php';
include_once S_DIR.'core/audit/xm_no_list_search_arr.php';
$width = '1200px';

Power::CkPower('C0S');

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$sql_temp .= " AND online='0'";
if($product!=''){
	$sql_temp = $sql_temp." AND product in( SELECT code FROM setup_product WHERE msg LIKE '%$product%')";
}
if($certEnd_date != ''){
	$date = date("Y-m-d",strtotime(date("Y-m-d"))+(24*3600*$certEnd_date));
	$sql_temp = $sql_temp." AND certEnd_date <= $date";
}

//一阶段审核过了 N 天了，还没有安排二阶段审核的
if($audit_date != ''){
	$audit_date = date("Y-m-d",strtotime(date("Y-m-d"))-(24*3600*$audit_date));
	$sql_temp .= " AND audit_type='1008' AND taskBeginDate='0000-00-00' AND htxm_id IN(SELECT htxm_id FROM xm_item WHERE audit_type='1007' AND taskEndDate <= '$audit_date') ";
}

$params = array(
'search'=>$sql_temp,
'url'=>$url
);

$Item = new Item();
$result = $Item->listElement($params);

include TEMP.'header.htm';
include TEMP.'audit/xm_no_list.htm';
include TEMP.'footer.htm';
?>