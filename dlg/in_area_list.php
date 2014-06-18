<?php
include '../include/globals.php';

$sql_temp = '';
$url = "in_area_list.php?";

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$result = AreaCodeDao::listSetup($params);

include 'template/header.htm';
include 'template/in_area_list.htm';
include 'template/footer.htm';

?>