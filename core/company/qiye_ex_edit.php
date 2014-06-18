<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/CompanyEx.php';

Power::CkPower('A0S');

GrepUtil::InitGP(array('id','zuzhi_id'));

if($id!=''){
	$company_ex = new companyEx();
	$rst =  $company_ex->GetCompanyEx($id);
}
$rst['eiregistername_zhu'] = Cache::cache_company($rst['zuzhi_id']);

$eiregistername_zhu = Cache::cache_company($zuzhi_id);

include T_DIR.'header.htm';
include T_DIR.'company/qiye_ex_edit.htm';
include T_DIR.'footer.htm';
?>