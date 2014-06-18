<?php
include("../include/globals.php");
GrepUtil::InitGP(array('corpName','certNo'));

$eiregistername = $certNos = array();

$date = date('Y-m-d');
if($certNo!='')
{
	$szs = $db->query("SELECT eiregistername,eiregistername_e,zs_address,coverFields,certNo,certStart,certEnd,audit_ver,online FROM zs_cert WHERE certNo='$certNo' AND zsprintdate!='0000-00-00' AND certNo!='' AND certEnd>='{$date}' AND (online='01' OR online='03' OR online='04' OR online='05' OR online='0501') ORDER BY certStart DESC,id DESC LIMIT 1");
}
else if($corpName!='')
{
	$szs = $db->query("SELECT eiregistername,eiregistername_e,zs_address,coverFields,certNo,certStart,certEnd,audit_ver,online FROM zs_cert WHERE eiregistername='$corpName' AND zsprintdate!='0000-00-00' AND certEnd>='{$date}' AND certNo!='' AND (online='01' OR online='03' OR online='04' OR online='05' OR online='0501') ORDER BY certStart DESC,id DESC");
}

if($certNo != '' or $corpName != '')
{
	while($zs = $db->fetch_array($szs)){
		$dizhi_t = array();

		$certNos []= $zs['certNo'];
		$online []= Cache::cache_Certification_online($zs['online']);
		$certStart []= date("Y年n月j日",strtotime($zs['certStart']));
		$certEnd []= date("Y年n月j日",strtotime($zs['certEnd']));
		$audit_ver []= Cache::cache_audit_ver($zs['audit_ver']);
		$coverFields  []= $zs['coverFields'];

		$eiregistername[]=$zs['eiregistername'];
		$eiregistername_e[]=$zs['eiregistername_e'];

		$zs_address[]=$zs['zs_address'];

		$dizhi_t []= $zs['zs_address'];
		$dizhi_t = array_unique($dizhi_t);
		$dizhi_t = implode('/',$dizhi_t);

		$eipro_address1 []= $dizhi_t;
	}
}
include("./index.htm");
?>