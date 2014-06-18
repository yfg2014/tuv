<?php
!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('page','s_date_s','s_date_e','s_shenheleixing'));
Power::CkPower('I4S');
if($s_date_s == "" or $s_date_e == ""){
	$s_tian = date("t",mktime(0,0,0,date("m"),01,date("Y")));//获得当月天数
	$s_date_s = date("Y-m-01");
	$s_date_e = date("Y-m-").$s_tian;
}

if ($s_shenheleixing != "")
{
	if($s_shenheleixing == '所有监督')
	{
		$temp = $temp." AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004')";
	}
	elseif($s_shenheleixing == '1008')
	{
		$temp = $temp." AND (audit_type = '1008')" ;
	}else{
		$temp = $temp." AND audit_type = '".$s_shenheleixing."'" ;
    }
}

$sql_temp = "$dbtable[xm_item] a left join $dbtable[zs_cert] b on a.zsid=b.id   WHERE a.online='3' AND (((a.audit_type='1008' OR a.audit_type='1005') AND b.certStart >= '".$s_date_s."' AND b.certStart <= '".$s_date_e."') OR ((a.audit_type='1002' OR a.audit_type='1003' OR a.audit_type='1004')  AND a.approvaldate >= '".$s_date_s."' AND a.approvaldate <= '".$s_date_e."'))".$temp;

$s_temp = "&s_date_s=$s_date_s&s_date_e=$s_date_e&";

$rt = $db->get_one("SELECT COUNT(a.id) AS sum FROM $sql_temp");



$sql_setup	= "SELECT a.*,b.certNo FROM $sql_temp ORDER BY a.ht_id DESC,a.iso DESC $limit";
$quer_setup	= $db->query($sql_setup);
while($forum_setup = $db->fetch_array($quer_setup))
{
	if($forum_setup["audit_type"] == '1008' or $forum_setup["audit_type"] == '1005')
	{
		$forum_setup["taskBeginDate"] = "";
	}
	else
	{
		$forum_setup["approvaldate"] = "";
	}

	$sql_htxm  = "SELECT * FROM $dbtable[ht_contract_item] WHERE id='".$forum_setup['htxm_id']."'";
	$quer_htxm = $db->query($sql_htxm);
	if($forum_htxm = $db->fetch_array($quer_htxm))
	{
		if ($forum_setup["iso"] == "E"){
			if($forum_htxm["risk"] == "01"){
				$forum_setup["risk"] = "E:高风险";
			}elseif($forum_htxm["risk"] == "02"){
				$forum_setup["risk"] = "E:中风险";
			}elseif($forum_htxm["risk"] == "03"){
				$forum_setup["risk"] = "E:低风险";
			}
		}
		if ($forum_setup["iso"] == "S"){
			if($forum_htxm["risk"] == "01"){
				$forum_setup["risk"] = "S:一级";
			}elseif($forum_htxm["risk"] == "02"){
				$forum_setup["risk"] = "S:二级";
			}elseif($forum_htxm["risk"] == "03"){
				$forum_setup["risk"] = "S:三级";
			}
		}
		$forum_setup["audit_ver"] = $forum_htxm["audit_ver"];
	}

	$sql_amount	= "SELECT eiregistername,eiman_amount FROM $dbtable[mk_company] WHERE id='".$forum_setup['zuzhi_id']."'";
	$quer_amount = $db->query($sql_amount);

	if ($forum_amount = $db->fetch_array($quer_amount))
	{
		$forum_setup["eiman_amount"]   = $forum_amount["eiman_amount"];
		$forum_setup["eiregistername"] = $forum_amount["eiregistername"];
	}

	$forum_setup["invoicemoney"] = "";
	$forum_setup["invoice"] = "";
	$sql_fei = "SELECT * FROM $dbtable[cw_finance_list_ex]  WHERE xmid='".$forum_setup["id"]."'";
	$quer_fei = $db->query($sql_fei);
	while ($forum_fei = $db->fetch_array($quer_fei))
	{
		$forum_setup["invoicemoney"] = $forum_setup["invoicemoney"] + $forum_fei["invoicemoney"];
		$forum_setup["invoice"] = $forum_setup["invoice"]." ".$forum_fei["invoice"];
	}

   $arr[] = $forum_setup;
}

$width = "600px";
include T_DIR.'header.htm';
include T_DIR.'report/ccaa.htm';
include T_DIR.'footer.htm';
?>