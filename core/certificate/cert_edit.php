<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/AssessmentItem.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/setup/setup_mark.php';
include_once S_DIR.'include/setup/setup_renewal_reason.php';

GrepUtil::InitGP(array('zsid','pdid','zuzhi_id','op'));

Power::xt_htfrom($zuzhi_id);
Power::CkPower('E0S');

$Company = new Company();
$ContractItem = new ContractItem();
$AssessmentItem = new AssessmentItem();
$Certificate = new Certificate();
$zs = $Certificate->query($zsid);

if($zs['audit_type']=='1005')
{
   $OldCer='dataType="Require" msg="再认证，原注册号不能为空！"';
   $ChanTime='dataType="Date" msg="请输入正确注册到期时间 如:1980-01-01"';
   $CerNo='<font color="#FF0000">*</font>';
}
if($zs['kind'] == '1')
{
	$where = "iso='".$zs['iso']."'";
}else{
	$where = "product='".$zs['product']."' and iso='".$zs['iso']."'";
}

$rows = $AssessmentItem->query($pdid);
$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
$rows['product'] = Cache::cache_product($rows['product']);
$rows['product_ver'] = Cache::cache_product_ver($rows['product_ver']);
$rows['certfanwei'] == '' && $rows['certfanwei'] = $rows['renzhengfanwei'];
$com = $Company->GetCompany($zuzhi_id);
$arr = $Company->toArray("id='{$zuzhi_id}' or fatherzuzhi_id='{$zuzhi_id}' ORDER BY id ASC");

$htxm = $ContractItem->query($rows['htxm_id']);
$htxm['audit_type'] = Cache::cache_audit_type($htxm['audit_type']);
$htxm['product_ver'] = Cache::cache_product_ver($htxm['product_ver']);
$htxm['key_part'] = Cache::cache_key_part($htxm['key_part']);
$htxm['product_test'] = Cache::cache_product_test($htxm['product_test']);
$htxm['mark'] == '' && $htxm['mark'] = '00';
$mark = array_unique(explode(',',$htxm['mark']));
$result['data'] = $Certificate->toArray("fatherzuzhi_id='".$zuzhi_id."' and zsprintdate!='0000-00-00' and $where ORDER BY id ASC");

$gzsid = $zsid;
if($op == '1'){
	$zs['audit_ver'] == 'QY' && $zs['audit_ver'] = 'Q2';
	$zs['certNo'] = $Certificate->CertificatNumber($certid, $zs['audit_ver'], $htxm['iso_people_num'], $htxm['re_views']);
	$zs['renewaldate'] = Cache::cache_time_value($zs['renewaldate']);

	$date_t	= explode("-",$zs['certStart']);
	$zs['certEnd'] = date("Y-m-d",mktime(0,0,0,$date_t[1],$date_t[2]-1,$date_t[0]+3));
	if ($htxm['re_views'] == '0'){
		$zs['firstDate'] = $zs['certStart'];
	}
	if($htxm['zjg'] == '1'){
		$zs['firstDate'] = $htxm['zjg_firstDate'];
		$zs['certNo_y'] = $htxm['zjg_no'];
		$zs['renewaldate'] = $rows['assessmentdate'];
	}

	$zs['zsprintdate'] = $zs['certStart'];
}elseif($op == '2'){
	$gzsid = '';
	$zs['renewaldate'] = '';
	$zs['certNo_y'] = '';
}

$width = '800px';
$params = array('company' => array(),'contract' => array(),'finance' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($rows['ht_id']));
$Information = new Information($id_arr,$width,'',$params);

if ($op != ''){
	$zs['audit_ver'] == 'QY' && $zs['audit_ver'] = 'Q2';
	$zsend = $db->get_one("SELECT certNo FROM {$dbtable['zs_cert']} WHERE audit_ver='{$zs['audit_ver']}' and certNo!='' ORDER BY id DESC");
}
$htxm['mark'] = Cache::cache_mark($htxm['mark']);
$op != '' ? $zsstr = '<font color=blue>证书登记</font>&nbsp;&nbsp;&nbsp;&nbsp;该体系最后一次证书编号：'.$zsend['certNo'] : $zsstr = '<font color=red>证书编辑>> '.$zs['certNo'].'   '.Cache::cache_mark($zs['mark'])."&nbsp;</font><a href=\"./index.php?m=certificate&do=cert_edit&pdid=$pdid&zsid=$zsid&zuzhi_id=$zuzhi_id&op=2\"><span  class=\"newinfo\">新增证书</span></a>";
$zs['renewaldate'] == '0000-00-00' && $zs['renewaldate'] = '';

include TEMP.'header.htm';
include TEMP.'certificate/cert_edit.htm';
include TEMP.'footer.htm';
?>