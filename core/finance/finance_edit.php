<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/finance.php');
include(S_DIR.'include/module/FinanceBasket.php');
include_once SET_DIR.'setup_finance_item.php';
include_once S_DIR.'include/module/Information.php';

Power::CkPower('G0S');

GrepUtil::InitGP(array('zuzhi_id','ht_id','cwid'));
//页面配置信息
$width = '500px';

if($ht_id!=''){
	//取合同信息
	$finance = new finance();
	$rst =  $finance->GetFinance($ht_id);
	$cwid == '' ? $titmsg = '<font color=#003399>登记</font>' : $titmsg = '<font color=#FF6600>编辑</font>';
	if($cwid!=''){
		$cwrst =  $finance->GetFinanceItem($cwid);
	}

	$com = $db->get_one("SELECT eiregistername FROM `{$dbtable[mk_company]}` WHERE id='{$zuzhi_id}'");
	$last_money = $db->get_one("SELECT get_money_date,get_money FROM mk_company_finance WHERE zuzhi_id='{$zuzhi_id}' ORDER BY id DESC");
}
else{
	exit('不存在的收费合同');
}
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));
$params = array('company' => array(),'contract' => array());
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'finance/finance_edit.htm';
include T_DIR.'footer.htm';
?>