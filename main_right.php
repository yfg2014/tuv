<?php
session_start();
include dirname(__FILE__).'/'.'include/globals.php';
include_once S_DIR.'include/module/InformationRelease.php';

$InformationRelease = new InformationRelease();

$where = "num='1' ORDER BY zd_date DESC LIMIT 0,5";
$arr = $InformationRelease->toArray($where);
$arr == '' ? $arr = array(0) : $arr;

$where = "num='2'and touserid like '%{$_SESSION['user']}%' ORDER BY zd_date DESC LIMIT 0,5";
$rows = $InformationRelease->toArray($where);
$rows == '' ? $rows = array(0) : $rows;

include T_DIR.'main_right.htm';
?>