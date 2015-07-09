<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include SET_DIR.'setup_province.php';
include_once S_DIR.'core/audit/xm_maintain_list_arr.php';

Power::CkPower('C8S');//监督维护查询

$width='1400px';
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$sql_temp  .= Power::xt_htfrom('0','1','');

if($eireg_address != ''){
	$sql_temp = $sql_temp."  and zuzhi_id IN(SELECT id FROM (SELECT id FROM mk_company WHERE eireg_address LIKE'%$eireg_address%') AS t)";
}
if($online == ''){
	$sql_temp = "  and online='5' and (audit_type='1002' or audit_type='1003' or  audit_type='1004')".$sql_temp;
}elseif($online == 'sv'){
	$sql_temp = "  and online!='5' and (audit_type='1002' or audit_type='1003' or  audit_type='1004')".$sql_temp;
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Item = new Item();
$result = $Item->listElementSv($params);

include TEMP.'header.htm';
include TEMP.'audit/xm_maintain_list.htm';
include TEMP.'footer.htm';
?>