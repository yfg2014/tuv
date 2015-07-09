<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'core/assess/pd_list_search_arr.php';

Power::CkPower('D0E');

$width = '1300px';
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();

if($taskBeginDate1 != '' && $taskBeginDate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE taskBeginDate>='$taskBeginDate1' AND taskEndDate<='$taskBeginDate2')";
}
if($taskEndDate1 != '' && $taskEndDate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE taskEndDate>='$taskEndDate1' AND taskEndDate<='$taskEndDate2')";
}
if($zl_okdate1 != '' && $zl_okdate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE zl_okdate>='$zl_okdate1' AND zl_okdate<='$zl_okdate2')";
}

$sql_temp = " and audit_type!='1007' and (zs_if='0' or zs_if='2')".$sql_temp;
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$AssessmentItem = new AssessmentItem();
$result = $AssessmentItem->listElement($params);

include TEMP.'header.htm';
include TEMP.'assess/pd_list.htm';
include TEMP.'footer.htm';
?>