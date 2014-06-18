<?php
include '../include/globals.php';
include '../include/module/setup_audit_rule.php';

GrepUtil::InitGP(array('page',"code","msg",'bmsg','da'));

$sql_temp = '';
if($code != ''){$sql_temp .= " AND code LIKE'%$code%'";}
if($msg!=''){$sql_temp.=" AND msg LIKE '%$msg%'";}

$url = "in_audit_rule_list.php?msg=$msg&code=$code&";

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = 15;
$setup_audit_rule = new setup_audit_rule();
$result = $setup_audit_rule->list_setup($params);

include 'template/header.htm';
include 'template/in_audit_rule_list.htm';
include 'template/footer.htm';
?>
