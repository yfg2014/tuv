<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';
include_once SET_DIR.'setup_organize_information.php';
include_once SET_DIR.'setup_htfrom.php';
include_once SET_DIR.'setup_province.php';
include_once SET_DIR.'setup_industry.php';
include_once SET_DIR.'setup_money_unit.php';
include_once SET_DIR.'setup_country_area.php';
include_once SET_DIR.'setup_qualification_kind.php';
//权限检查
Power::CkPower('A0S');

GrepUtil::InitGP(array('zuzhi_id','kelong'));

if($zuzhi_id!=''){
	Power::xt_htfrom($zuzhi_id);
	$company = new company();
	$rst =  $company->GetCompany($zuzhi_id);
	
	$rst['manual_date'] == '0000-00-00' && $rst['manual_date'] = '';
	
	//企业资质
	$q_zz = $db->query("SELECT * FROM  `mk_company_qualification` WHERE zuzhi_id='$zuzhi_id'");
	while($row_zz = $db->fetch_array($q_zz)){
		$zz_arr []= $row_zz;
	}
}

include TEMP.'header.htm';
include TEMP.'company/qiyedengji_edit.htm';
include TEMP.'footer.htm';
?>