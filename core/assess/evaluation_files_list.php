<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Files.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array('eiregistername','page'));

if($eiregistername){
	$sql_temp = $sql_temp." and id IN(SELECT id FROM (SELECT id FROM mk_zuzhi_info WHERE eiregistername LIKE'%$eiregistername%' LIMIT 10) AS t)";
}
$url = "index.php?m=assess&do=evaluation_files_list&";
$sql_temp = "and id!=''".$sql_temp;
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Files = new Files();
$result = $Files->listElement($params);

$width = '800px';
include TEMP.'header.htm';
include TEMP.'assess/evaluation_files_list.htm';
include TEMP.'footer.htm';
?>