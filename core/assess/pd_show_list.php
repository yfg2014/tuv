<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/setup/setup_pd_online.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'core/assess/pd_xm_list_search_arr.php';

Power::CkPower('D4S');
GrepUtil::InitGP(array('sp_if'));

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp = $sql_temp.Power::xt_htfrom();
$sql_temp = " and zs_if!='0' and zs_if!='2' and audit_type!='1007' ".$sql_temp;

if($taskBeginDate1 != '' && $taskBeginDate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE taskBeginDate>='$taskBeginDate1' AND taskEndDate<='$taskBeginDate2')";
}
if($zl_okdate1 != '' && $zl_okdate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE zl_okdate>='$zl_okdate1' AND zl_okdate<='$zl_okdate2')";
}

if($sp_if == '1'){
	$sql_temp .= " AND sp_if = '1'" ;
}else{
	$sql_temp .= " AND sp_if != '1'" ;
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$AssessmentItem = new AssessmentItem();
$result = $AssessmentItem->listElement($params," ORDER BY approvaldate DESC");

$width = '1500px';
include T_DIR.'header.htm';
include T_DIR.'assess/pd_show_list.htm';
include T_DIR.'footer.htm';
?>