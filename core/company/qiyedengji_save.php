<?php
!defined('IN_SUPU') && exit('Forbidden');
//要处理的字段数据
$CompanyFields = array(
'eientercode','childkind','work_language','manual_ver','manual_date',
'eimark','eialias','eidaima','eifaren','eiman_amount','eikind','eiarea_code','eiarea','eiaddress_code','eiaddress','eizhuceziben','htfrom','recommended_man','attribution_man','zixun','kehujibie','zuzhi_id',
'eiregistername','eiregistername_y','eiregistername_e','zs_address','zs_address_e','zs_postalcode','industry','country_area','money_unit',
'eireg_address','eireg_address_e','eiregpostalcode','eisc_address','eisc_address_e','eiscpostalcode','eipro_address','eipro_address_e','eipropostalcode',
'eiphone','eifax','eilinkman','eilinkman_zw','eilinkman_mob','eilinkman_email','eilinkman_email','eiguandai','eiguandai_mob','eiguandai_email','eimanager','eimanager_mob',
'client_man','run_date','bank_name','bank_number','reg_mob','tax_number','taxpayer','other','kelong'
);

Power::CkPower('A0E');

$value = GrepUtil::InitGP($CompanyFields);//过滤数据，获取数据集
GrepUtil::InitGP(array('id','qualification','qualification_kind','qualification_start','qualification_end','q_online'));//过滤数据，获取数据集

include_once S_DIR.'include/module/Company.php';//引用企业模块数据处理文
include_once SET_DIR.'setup_province.php';
include_once SET_DIR.'setup_industry.php';
$company = new Company();
unset($value['zuzhi_id'],$value['kelong']); //剔除不需要的数组元素

$value['industry'] = str_replace(';','；',$value['industry']);
$value['industry'] = str_replace(',','；',$value['industry']);
$value['industry'] = str_replace('，','；',$value['industry']);
$value['industry'] = str_replace('/','；',$value['industry']);
$industry_arr = explode('；',$value['industry']);
$setup_industry_k = array_keys($setup_industry);
foreach($industry_arr as $v){
	if(!in_array($v,$setup_industry_k)){
		Url::goto_url('', '错误的所属行业代码'.$v);
	}
}

if($value['eiaddress_code'] !=''){
	 $value['eiarea_code']= substr($value['eiaddress_code'],0,2).'0000';
	 foreach($setup_province as $vp)
	 {
	     if($value['eiarea_code']==$vp['code'])
	 {
	    $value['eiarea']=$vp['msg'];
		 }
	 }
}
//添加资质
foreach($qualification as $k=>$v){
	if($v!=''){
		$zz_value[$k] = array(
			'id' => $id[$k],
			'qualification' => $v,
			'qualification_kind' => $qualification_kind[$k],
			'qualification_start' => $qualification_start[$k],
			'qualification_end' => $qualification_end[$k],
			'q_online' => $q_online[$k]
		);
	}
}


if ($zuzhi_id > 0) {
	if ($kelong == 1) {
		$company->addChild($value,$zuzhi_id); //新增关联公司
	} else {
		$company->EditCompany($value,$zuzhi_id,$zz_value); //修改企业
	}
} else {
	$zuzhi_id = $company->AddCompany($value,$zuzhi_id,$zz_value); //新增企业
}

Url::goto_url('index.php?m=company&do=qiyedengji_edit&zuzhi_id='.$zuzhi_id, '保存成功');
?>