<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/CompanyGift.php');
Power::CkPower('A3E');

$value = GrepUtil::InitGP(array('id','zuzhi_id','gift_name','send_date','remark'));	//过滤数据，获取数据集

$qiye = $db->get_one("SELECT htfrom FROM  `mk_company` WHERE id='$zuzhi_id'");
$value['htfrom'] = $qiye['htfrom'];
$value['zd_ren'] = $_SESSION["username"];
$value['zd_time'] = date("Y-m-d");

$s = new CompanyGift();

if ($id > 0) {
	$re = $s->update($id,$value);
} else {
	$re = $s->add($value);
}

Url::goto_url('index.php?m=gift&do=qiye_gift_list', '保存成功');
?>