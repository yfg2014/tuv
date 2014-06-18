<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';
include(S_DIR.'core/certificate/cert_list_search_arr.php');
Power::CkPower('E0S');

$width = '1300px';

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
if($product != '')
{
	$sql_temp = $sql_temp." AND  coverFields LIKE '%{$product}%'";
}
$sql_temp = $sql_temp." AND zsprintdate!='0000-00-00'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$qy_num = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM zs_cert WHERE 1 $sql_temp ");

$Certificate = new Certificate();
$result = $Certificate->listCertification($params);

include T_DIR.'header.htm';
include T_DIR.'certificate/cert_list.htm';
include T_DIR.'footer.htm';
?>