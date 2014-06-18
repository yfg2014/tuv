<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/finance.php';

GrepUtil::InitGP(array('zuzhi_id','ht_id'));

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);
$w['eientercode'] = $com['eientercode'];        //档案号
$w['eiregistername'] = $com['eiregistername'];        //企业名称
$w['eisc_address'] = $com['eisc_address'];          //通讯地址
$w['eiscpostalcode'] = $com['eiscpostalcode'];     //通讯地址邮编
$w['eireg_address'] = $com['eireg_address'];          //注册地址
$w['eipro_address'] = $com['eipro_address'];          //生产地址
$w['eilinkman'] = $com['eilinkman'];                    //联系人
$w['eilinkman_mob'] = $com['eilinkman_mob'];            //联系人
$w['eiphone'] = $com['eiphone'];                    //电话
$w['eifax'] = $com['eifax'];                          //传真
$w['eiman_amount'] = $com['eiman_amount'];              //企业人数

$contract = new Contract();
$ht = $contract->query($ht_id,array('htcode','auditplandate'));
$w['htcode'] = $ht['htcode'];                       //合同编号
$w['auditplandate'] = $ht['auditplandate'];			//审核预期

$ContractItem = new ContractItem();
$htItem = $ContractItem->toArray("ht_id='{$ht_id}'");
$w['hedingrenri'] = 0;
foreach($htItem AS $val){
	$htxmcode []= $val['htxmcode'];
	$w['hedingrenri'] = $w['hedingrenri'] + $val['hedingrenri'];     //现场审核人日
	$w['audit_type10011'] = $val['audit_type'] == '1001'?'■' : '□';	//初次认证
	$w['audit_type10033'] = $val['audit_type'] == '1005'?'■' : '□';	//再认证
	$xm_rzlx = $db->get_one("SELECT renzhengleixing FROM `xm_rzlx` WHERE htxm_id='{$val[id]}'");
	if($xm_rzlx['renzhengleixing'] == '06'){
		$w['audit_type10044'] = '■';  //扩大缩小
	}else{
		$w['audit_type10044'] = '□';
	}
	$audit_type []= $val['audit_type'];
	$audit_ver []= $val['kind'] == '1' ? Cache::cache_audit_ver($val['audit_ver']):Cache::cache_product_ver($val['product_ver']);
	$renzhengfanwei [] = '<br/>'.$val['iso'].'认证范围：'.$val['renzhengfanwei'];
	$audit_code [] = $val['audit_code'];
	$kind [] = $val['kind'] == '1'? '体系' : '产品';
}
$w['htxmcode'] = implode("；",$htxmcode);			//项目编号
$w['audit_ver'] = implode(" & ",$audit_ver);			//依据标准
$w['renzhengfanwei'] = implode("<hr/>",$renzhengfanwei);		//认证范围
$w['audit_code'] = implode("；",array_unique($audit_code));		//专业代码
$w['kind'] = implode("；",array_unique($kind));			//认证类别
$w['hedingrenri'] == 0 && $w['hedingrenri'] = '';
//取收费信息
/*
$getFinance = $db->query("SELECT finance_item,contract_money,kind FROM `cw_finance_item` WHERE ht_id = '{$ht_id}'");
while($row = $db->fetch_array($getFinance)){
	$finance [] = $row;
}
foreach($finance AS $v){
		switch ($v['finance_item'])
		{
		case '1001':
		  $w['contract_money1001'] = $v['contract_money'];	//申请费
		  break;
		case '1002':
		  $w['contract_money1002'] = $v['contract_money'];	//预审费
		  break;
		case '1003':
		  $w['contract_money1003'] = $v['contract_money'];	//评审费
		  break;
		case '1004':
		  $w['contract_money1004'] = $v['contract_money'];	//注册费
		  break;
		case '1005':
		  $w['contract_money1005'] = $v['contract_money'];	//培训费
		  break;
		case '1006':
		  $w['contract_money1006'] = $v['contract_money']; 	//年金费
		  break;
		case '1007':
		  $w['contract_money1007'] = $v['contract_money']; 	//监督费
		  break;
		default: ;
		}
}
//$w['contract_money10067'] = $w['contract_money1006'] + $w['contract_money1007'];
$w['contract_money10067'] = $w['contract_money1007'];
*/
//-----------------------------------------------
$name = preg_replace ("/[\/\\ <> .\*\?\:\|]/","",$w['eiregistername']);
$filename = iconv("utf-8","gbk//IGNORE",$name.'-报价单.doc');
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Disposition: attachment; filename=".$filename);

$local = 'http://'.$_SERVER['HTTP_HOST'].'/tuv/doc/doc/';
$localh = $local.'files/print_01.htm';
$localx = $local.'files/print_01.xml';

foreach($w as $k=>$v){
	$w[$k] = iconv("utf-8","gbk//IGNORE",$v);
}

require "./doc/doc/print_01.doc";

?>
