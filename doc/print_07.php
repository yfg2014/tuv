<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';
include_once SET_DIR.'setup_country_area.php';

GrepUtil::InitGP(array('zuzhi_id','taskId'));

$w['dateNow'] = date("Y 年 m 月 d 日");

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);

$w['eientercode'] = $com['eientercode'];        //档案号
$w['eiregistername'] = $com['eiregistername'];        //企业名称
$w['eiregistername_e'] = $com['eiregistername_e'];        //企业名称
$w['eisc_address'] = $com['eisc_address'];          //通讯地址
$w['eisc_address_e'] = $com['eisc_address_e'];          //通讯地址(英)
$w['eiscpostalcode'] = $com['eiscpostalcode'];        //通讯地址邮编
$country_area = $db->get_one("SELECT msg FROM `setup_country_area` WHERE num_code='{$com['country_area']}'");
$w['country_area'] = $country_area['msg'];      //国家
$w['eilinkman'] = $com['eilinkman'];                    //联系人
$w['eilinkman_mob'] = $com['eilinkman_mob']; 
$w['eilinkman_email'] = $com['eilinkman_email'];
$w['bank_number'] = $com['bank_number'];
$w['tax_number'] = $com['tax_number'];
$w['eiphone'] = $com['eiphone'];                    //电话
$w['eifax'] = $com['eifax'];                          //传真

$name = preg_replace ("/[\/\\ <> .\*\?\:\|]/","",$w['eiregistername']);
$filename = iconv("utf-8","gbk//IGNORE",$name.'-审核问卷确认表.doc');
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Disposition: attachment; filename=".$filename);

$localimg = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/images/';
$localh = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_07.htm';
$localx = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/files/print_07.xml';

foreach($w as $k=>$v){
	$w[$k] = iconv("utf-8","gbk//IGNORE",$v);
}

require "./doc/doc/print_07.doc";

?>
