<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=CCAA-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/cache_audit_type.php';
GrepUtil::InitGP(array('page','s_date_s','s_date_e','s_shenheleixing'));

if($s_date_s == "" or $s_date_e == ""){
	$s_tian = date("t",mktime(0,0,0,date("m"),01,date("Y")));//获得当月天数
	$s_date_s = date("Y-m-01");
	$s_date_e = date("Y-m-").$s_tian;
}


$sql_temp = "$dbtable[xm_item] a left join $dbtable[zs_cert] b on a.zsid=b.id   WHERE a.online='3' AND
(
	(
	(a.audit_type='1008' OR a.audit_type='1005') AND b.certStart >= '".$s_date_s."' AND b.certStart <= '".$s_date_e."'
	)
OR
	(
	(a.audit_type='1002' OR a.audit_type='1003' OR a.audit_type='1004')  AND a.approvaldate >= '".$s_date_s."' AND a.approvaldate <= '".$s_date_e."'
	)
)".$temp;

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
TD{
    font-size: 12px;
    vertical-align: middle;
vnd.ms-excel.numberformat:@;
}
</style>
<table border="1">
        <tr bgcolor="#F2F2F2">
          <td>组织名称</td>
		  <td>组织人数</td>
          <td>证书类型</td>
          <td>风险</td>
		  <td>证书编号</td>
		  <td>收费金额</td>
		  <td>发票号</td>
		  <td>分支机构</td>
          <td>其它说明</td>
          <td>审核类型</td>
        </tr>
<?php

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
 
    //产品 不用报证书类型
    if($forum_setup['iso'] == 'P'){ $forum_setup['iso'] = "";}
	//审核类型
	$forum_setup["audit_type"] = Cache::cache_audit_type($forum_setup[audit_type]);

?>
        <tr bgcolor="#FFFFFF">
          <td height="20"><?php echo $forum_setup['eiregistername']; ?></td>
		  <td><?php echo $forum_setup['eiman_amount']; ?></td>
		  <td><?php echo $forum_setup['iso']; ?></td>
          <td><?php echo $forum_setup['risk']; ?></td>
          <td style="vnd.ms-excel.numberformat:@;"><?php echo $forum_setup['certNo']; ?></td>
          <td><?php echo $forum_setup['invoicemoney']; ?></td>
          <td style="vnd.ms-excel.numberformat:@;"><?php echo $forum_setup['invoice']; ?></td>
		  <td> </td>
          <td> </td>
          <td><?php echo $forum_setup['audit_type']; ?></td>
        </tr>
<?php
}
?>

</table>