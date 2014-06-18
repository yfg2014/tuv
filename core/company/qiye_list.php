<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Company.php');
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_province.php';
include_once S_DIR.'core/company/qiye_list_search_arr.php';

Power::CkPower('A0S');

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower();

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$width='1000px';
$s = new Company();
$result = $s->listElement($params);


include T_DIR.'header.htm';
include T_DIR.'company/qiye_list.htm';
include T_DIR.'footer.htm';
?>