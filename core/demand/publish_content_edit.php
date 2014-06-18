<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Demand.php';
include_once S_DIR.'include/module/PublishContent.php';

GrepUtil::InitGP(array('eid','fbid'));

$Demand = new Demand();
$rows = $Demand->query($fbid);
$rows['menu'] = Cache::cache_menu($rows['menu']);
$rows['content'] = str_replace("\n",'<br>',$rows['content']);

$PublishContent = new PublishContent();
$params = array(
	'search' => $sql_temp .= " and fbid='$fbid'",
	'url' => './index.php?m=demand&do=publish_content_edit',
);
$result = $PublishContent->listElement($params);

$width = '500px';
include T_DIR.'header.htm';
include T_DIR.'demand/publish_content_edit.htm';
include T_DIR.'footer.htm';
?>