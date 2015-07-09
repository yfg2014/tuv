<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Company.php');
include_once S_DIR.'include/module/Information.php';
include(S_DIR.'include/module/Companyfinance.php');

Power::CkPower('A1C');

GrepUtil::InitGP(array('id','zuzhi_id'));

if($id != ''){
	$Companyfinance = new Companyfinance();
	$result = $Companyfinance->query($id);
}else{
	$Company = new Company();
	$com = $Company->GetCompany($zuzhi_id,array('htfrom'));
	$result['htfrom'] = $com['htfrom'];
}

($result['get_money_date'] == '' or $result['get_money_date'] == '0000-00-00') && $result['get_money_date'] = date('Y-m-d');

$width = '500px';
$id_arr = array('zuzhi_id'=>$zuzhi_id);
$params = array('company' => array());
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'company/qiyedengji_finance_edit.htm';
include TEMP.'footer.htm';
?>
