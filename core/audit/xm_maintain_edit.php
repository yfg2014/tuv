<?php
!defined('IN_SUPU') && exit('Forbidden');

include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
GrepUtil::InitGP(array('zuzhi_id','op','online','comvid'));

$where	='';
if($online == ''){
	$where = "  and online='5' and (audit_type='1002' or audit_type='1003' or  audit_type='1004')";
}else{
	$where = "  and online!='5' and (audit_type='1002' or audit_type='1003' or  audit_type='1004')
	and ((select online from zs_cert where id = zsid ) in ('01','03','04') )";
}


Power::CkPower('C8E');//监督维护操作
Power::xt_htfrom($zuzhi_id);


$sql_cm = "SELECT * FROM mk_company_sv WHERE zuzhi_id = '{$zuzhi_id}'";
$other = $db->query($sql_cm);


$width='700px';
//$other = $Item->toArray("htxm_id ='".$rows['htxm_id']."' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004') order by id desc", array('id','audit_type','sv_other','xm_other'));
$Item = new Item();
$xm =  $Item->toArray("zuzhi_id ='".$zuzhi_id."' $where ", array('id','ht_id','htxm_id','zsid','zuzhi_id','iso','audit_type','xm_other','product','renzhengfanwei','kind','auditplandate','finalItemDate','online','ifchangecert','xm_other','sv_other'));

foreach($xm as $v)
{
  $ht_id[]=$v['ht_id'];
  $htxm_id[]=$v['htxm_id'];
  $xmid[]=$v['id'];
  $zsid[]=$v['zsid'];
  $auditplandate[]= $v['auditplandate'];
}
$ht_id = array_unique($ht_id);
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>(array)$ht_id,'htxm_id' => (array)$htxm_id);
$params = array('company' => array(),'contract' => array(),'item' => array(),'certificate' => array(),'finance' => array());
$Information = new Information($id_arr,$width,'',$params);

if($comvid !='')
{
  $sql_cm .=" and id=".$comvid;
  $comv = $db->get_one($sql_cm);
}

include T_DIR.'header.htm';
include T_DIR.'audit/xm_maintain_edit.htm';
include T_DIR.'footer.htm';
?>