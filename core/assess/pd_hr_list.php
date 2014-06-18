<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Evaluate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/setup/setup_pd_online.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'core/assess/pd_hr_list_search_arr.php';

Power::CkPower('D1S');

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
if($approvaldate1 != '' && $approvaldate2 != '')
{
	$sql_temp = $sql_temp." AND pdid IN(SELECT id FROM {$dbtable['pd_xm']} WHERE approvaldate>='$approvaldate1' AND approvaldate<='$approvaldate2')";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Evaluate = new Evaluate();
$result = $Evaluate->listElement($params);

$width = '1000px';
include T_DIR.'header.htm';
include T_DIR.'assess/pd_hr_list.htm';
include T_DIR.'footer.htm';
?>