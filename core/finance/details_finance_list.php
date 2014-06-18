<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/details_finance.php');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'core/finance/details_finance_list_search_arr.php';

$width = '900px';
Power::CkPower('G2S');

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$sql_temp .= Power::xt_htfrom();

if($htxmcode != ''){
	$sql_temp = "  and ht_id IN(SELECT ht_id FROM (SELECT ht_id FROM ht_contract_item WHERE htxmcode LIKE'%$htxmcode%') AS t)".$sql_temp;
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$s = new details_finance();
$result = $s->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'finance/details_finance_list.htm';
include T_DIR.'footer.htm';
?>