<?php

/**
 * ��Աרҵ��f
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//��Ա����Ϣ��
include(S_DIR.'include/module/Hr_audit_code.php');			//��Աרҵ��f�� ��˴���

include(SET_DIR.'setup_htfrom.php');						//��ͬ4Դ ��Ա4Դ
include(SET_DIR.'setup_mark.php');							//�Ͽɱ�־
include(SET_DIR.'setup_audit_iso.php');						//��ϵ����

GrepUtil::InitGP(array('id','page','core','iso'));
Power::CkPower('K0S');
$hr_id = $_SESSION['userid'];
Power::xt_htfrom($hr_id,'hr');

$width='800px';
$s1 = new Hr_information();
$result_ren = $s1->query($hr_id);

$url = "index.php?m=auditor&do=au_audit_code&core=$core&hr_id=$hr_id&";

$search_tmp=" and hr_id='$hr_id'";
if (!empty($iso))
{
	$search_tmp=" and hr_id='$hr_id' and iso='$iso' ";
}


$params = array(
	'search' => $search_tmp,
	'url' => $url,
);

//$db_perpage=1;
$s = new Hr_audit_code();
$result = $s->list_audit_code($params);

include T_DIR.'header.htm';
include T_DIR.'auditor/au_audit_code.htm';
include T_DIR.'footer.htm';
?>