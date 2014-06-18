<?php
include '../include/globals.php';
include_once S_DIR.'include/module/Company.php';

GrepUtil::InitGP(array('zuzhi_id','taskId'));

$company = new company();
$rst =  $company->GetCompany($zuzhi_id);

include 'template/header.htm';
include 'template/in_audit_word.htm';
include 'template/footer.htm';
?>
