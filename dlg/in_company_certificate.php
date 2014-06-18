<?php
include '../include/globals.php';
include '../include/module/Company.php';

GrepUtil::InitGP(array('zuzhi_id'));

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);

$last = array('zs_address'=>$com['eipro_address'],'zs_address_e'=>$com['eipro_address_e'],'zs_postalcode'=>$com['eipropostalcode']);
$last = implode('|',$last);
$arr = array('first'=>$com['eiregistername'],'prev' => $com['eiregistername_e'],'last' => $last);
$arr = implode('@',$arr);
$wrap = json_encode($arr);
echo $wrap;
?>
