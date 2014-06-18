<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/fees_finance.php');
include_once SET_DIR.'setup_finance_item.php';
include_once S_DIR.'include/module/Information.php';
include_once SET_DIR.'setup_money_unit.php';
include_once SET_DIR.'setup_finance_item.php';

Power::CkPower('G1S');

GrepUtil::InitGP(array('zuzhi_id','ht_id','audit_type','cwid'));
//页面配置信息
$width = '750px';
$s = new fees_finance();

//取合同收费项目
if($ht_id!=''){
	$cw_finance_item =  $s->GetFinance($ht_id);
	$cw_finance_list =  $s->GetFinanceList($ht_id);
	//$where = "zuzhi_id='{$zuzhi_id}' and (zsid!='0' and zsid in(SELECT id FROM $dbtable[zs_cert] where zuzhi_id='{$zuzhi_id}' and (online='01' or online='04' or online='03')) or zsid='0') ORDER BY ht_id DESC";
	$where = " ht_id='{$ht_id}' ";
	$xm_item =  $s->GetXmList($where,array('id','audit_type','iso','renzhengfanwei','cw_online'));
	//取变更关联财务备注
	$cg_sql = "SELECT iso,zs_change_date,cw_other FROM $dbtable[zs_change] WHERE ht_id='$ht_id' ORDER BY id DESC";
	$cg_q = $db->query($cg_sql);
	while($cgdb = $db->fetch_array($cg_q)){
		$cg []= $cgdb;
	}
	//取变更关联财务备注
	$cg_sql = "SELECT iso,xm_change_date as zs_change_date,cw_other FROM $dbtable[xm_rzlx] WHERE ht_id='$ht_id' ORDER BY id DESC";
	$cg_q = $db->query($cg_sql);
	while($cgdb = $db->fetch_array($cg_q)){
		$cg []= $cgdb;
	}
	//取合同费用登记表id
	$f_item_q = $db->query("SELECT id,finance_item FROM `cw_finance_item` WHERE ht_id='$ht_id' ORDER BY id DESC");
	while($f_item_arr = $db->fetch_array($f_item_q)){
		$f_item []= $f_item_arr;
	}
}

if($cwid!=''){
	$cwxmid = $s->GetFinanceOneListEx($cwid);
	//$cw['xmid'] = explode(',',$cw['xmid']);
	//把非编辑的项目去除
	foreach($xm_item as $k=>$v){
		if(!in_array($v['id'],$cwxmid)){
			unset($xm_item[$k]);
		}
	}
	$cw = $s->GetFinanceOneList($cwid);

	//获取篮子信息
	//$basket = $db->get_one("SELECT * FROM $dbtable[cw_finance_basket] WHERE cwid='$cwid'");
	//$f_item = $db->get_one("SELECT finance_item FROM $dbtable[cw_finance_item] WHERE id='$cw[f_item_id]'");
}





/* 排错处理 */
//如果无收费信息，项目强制都为未交完费
if(empty($cw_finance_list)){
	foreach ($xm_item as $k=>$v) {
		$xm_item[$k]['cw_online'] = '0';
	}
}
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));
$params = array('company' => array(),'contract' => array());
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'finance/fees_finance_edit.htm';
include T_DIR.'footer.htm';
?>