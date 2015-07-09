<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include S_DIR.'include/module/Sampling.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_audit_type.php';
include SET_DIR.'setup_audit_iso.php';
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_audit_online.php';
include SET_DIR.'setup_audit_ver.php';
include SET_DIR.'setup_ht_online.php';
include_once S_DIR.'include/setup/setup_sampling_online.php';
include(S_DIR.'core/contract/contract_sampling_seach_arr.php');

$width='1100px';
Power::CkPower('B3S');

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&svonline='.$svonline.'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();

if(strpos($_SESSION['power'],'Z6S')!==false){
	$qiye = $db->query("SELECT id FROM `mk_company` WHERE zd_ren='$_SESSION[username]'");
	while($row = $db->fetch_array($qiye)){ $q_zuzhi_id []= $row['id']; }
	$zd_zuzhi_id = implode(',', $q_zuzhi_id);
	$sql_temp .= " AND a.zuzhi_id IN ('$zd_zuzhi_id')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Sampling = new Sampling();
$result = $Sampling->listElement($params);

include TEMP.'header.htm';
include TEMP.'contract/contract_sampling_list.htm';
include TEMP.'footer.htm';
?>