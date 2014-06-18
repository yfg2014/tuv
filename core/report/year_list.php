<?php
!defined('IN_SUPU') && exit('Forbidden');
$f_code = array(
'A1','A2','A3','A4','A5','A6','B1','B2','B3','B4','B5','C1','C2','C3','C4','C5','C6','D1','D2','D3','E1','E2','E3','E4','E5','E6','E7','E8','E9','E10','E11','E12','E13','E14','F1','F2','G1','H1','H2','I1','I2','I3','I4','I5','I6','J1','K1','L1','L2','L3','L4','L5','L6','L7','M1');
$a_code = array('11','12','13','14','15','21','22','23','31','32','33','34','35','36','37','41','42','43','44','45','46','50','51','52','53','54','61','62','63','64','65','71','81','82','00');
$daima_code = array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39');
GrepUtil::InitGP(array('start_date','s_zizheng','s_kind','s_ifmail'));
Power::CkPower('I5S');
if($start_date==''){$start_date=date("Y").'-12-31';}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
TD{
    font-size: 12px;

	text-align:center;
	height:25px;

}
</style>
<LINK href="../include/scss.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="../include/calendar.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../include/calendar.css" title="style" />

<br>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
<tr bgcolor="#FFFFFF">
<td   colspan="23"><br>
<h3>获证组织信息分类统计报表</h3>
<FORM METHOD=POST ACTION="">
证书截止时间：
<INPUT TYPE="text" NAME="start_date" size="9" value="<?php echo $start_date;?>" id="sel2" onfocus="return showCalendar('sel2', 'y-mm-dd');">
&nbsp;
<SELECT NAME="s_zizheng">
	<OPTION VALUE="0" <?php if($s_zizheng=='0'){echo 'selected';}?>>全部
	<OPTION VALUE="1" <?php if($s_zizheng=='1'){echo 'selected';}?>>不含子证
</SELECT>
&nbsp;
<SELECT NAME="s_kind">
	<OPTION VALUE="0" <?php if($s_kind=='0'){echo 'selected';}?>>全部
	<OPTION VALUE="1" <?php if($s_kind=='1'){echo 'selected';}?>>CNAS证书
</SELECT>
&nbsp;
<SELECT NAME="s_ifmail">
	<OPTION VALUE="0" <?php if($s_ifmail=='0'){echo 'selected';}?>>全部
	<OPTION VALUE="1" <?php if($s_ifmail=='1'){echo 'selected';}?>>已邮寄证书
</SELECT>
&nbsp;&nbsp;<INPUT TYPE="submit" value=" 查 询 ">

</FORM>
</td>
</tr>
</table>
<?php
//if($s_zizheng=='1'){$temp_sql .= "and z.certNo NOT LIKE '%-%'";}
if($s_zizheng=='1'){$temp_sql .= " and z.zuzhi_id=z.fatherzuzhi_id ";}
if($s_kind=='1'){$temp_sql .= " and z.mark='01' ";}
if($s_ifmail=='1'){$temp_sql .= " and z.maildate!='0000-00-00' ";}
$htxm_id=array();
//统计TOP
$sql = "SELECT zuzhi_id,htxm_id,audit_ver,online,zs_change_date,certEnd  FROM $dbtable[zs_cert] z WHERE certEnd>='$start_date' $temp_sql ORDER BY zuzhi_id DESC ";
$query	=$db->query($sql);
while($zs=$db->fetch_array($query)){
	$zs_top[$zs['audit_ver']][$zs['online']]++;
	if($zs['audit_ver']=='P1'){
		$zs_top['pro'][$zs['online']][$zs['zuzhi_id']] = 1;
	}
}
//QMS
$q2_yx_num = $zs_top['Q2']['01']+$zs_top['Q2']['02']+$zs_top['Q2']['04'] + $zs_top['QY']['01']+$zs_top['QY']['02']+$zs_top['QY']['04'];
$q2_zt_num = $zs_top['Q2']['03'] + $zs_top['QY']['03'];
$q2_cx_num = $zs_top['Q2']['05'] + $zs_top['QY']['05'];
$q2_zx_num = $zs_top['Q2']['0501'] + $zs_top['QY']['0501'];
//QT
$qt_yx_num = $zs_top['QT']['01']+$zs_top['QT']['02']+$zs_top['QT']['04'];
$qt_zt_num = $zs_top['QT']['03'];
$qt_cx_num = $zs_top['QT']['05'];
$qt_zx_num = $zs_top['QT']['0501'];
//QY
$qy_yx_num = $zs_top['QY']['01']+$zs_top['QY']['02']+$zs_top['QY']['04'];
$qy_zt_num = $zs_top['QY']['03'];
$qy_cx_num = $zs_top['QY']['05'];
$qy_zx_num = $zs_top['QY']['0501'];
//QJ
$qj_yx_num = $zs_top['QJ']['01']+$zs_top['QJ']['02']+$zs_top['QJ']['04'];
$qj_zt_num = $zs_top['QJ']['03'];
$qj_cx_num = $zs_top['QJ']['05'];
$qj_zx_num = $zs_top['QJ']['0501'];
//EMS
$e2_yx_num = $zs_top['E2']['01']+$zs_top['E2']['02']+$zs_top['E2']['04'];
$e2_zt_num = $zs_top['E2']['03'];
$e2_cx_num = $zs_top['E2']['05'];
$e2_zx_num = $zs_top['E2']['0501'];
//OHSMS
$s1_yx_num = $zs_top['S1']['01']+$zs_top['S1']['02']+$zs_top['S1']['04'];
$s1_zt_num = $zs_top['S1']['03'];
$s1_cx_num = $zs_top['S1']['05'];
$s1_zx_num = $zs_top['S1']['0501'];
//FSMS
$f1_yx_num = $zs_top['F1']['01']+$zs_top['F1']['02']+$zs_top['F1']['04'];
$f1_zt_num = $zs_top['F1']['03'];
$f1_cx_num = $zs_top['F1']['05'];
$f1_zx_num = $zs_top['F1']['0501'];
//自愿性产品
$pro_yx_num = $zs_top['P1']['01']+$zs_top['P1']['02']+$zs_top['P1']['04'];
$pro_zt_num = $zs_top['P1']['03'];
$pro_cx_num = $zs_top['P1']['05'];
$pro_zx_num = $zs_top['P1']['0501'];
$pro_company_num = count($zs_top['pro']['01']) + count($zs_top['pro']['02']) + count($zs_top['pro']['04']);
?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
<?php
echo"<tr bgcolor=#FFFFFF><td>QMS认证</td><td>GB/T19001-2008有效证书：$q2_yx_num </td><td> 暂停证书：$q2_zt_num </td><td>撤销证书：$q2_cx_num</td><td> 注销证书：$q2_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>QMS认证</td><td>TL9000：$qt_yx_num </td><td> 暂停证书：$qt_zt_num </td><td>撤销证书：$qt_cx_num</td><td> 注销证书：$qt_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>QMS认证</td><td>工程建设施工企业质量管理体系认证有效证书：$qj_yx_num </td><td> 暂停证书：$qj_zt_num </td><td>撤销证书：$qj_cx_num</td><td> 注销证书：$qj_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>QMS认证</td><td>医疗器械认证有效证书：$qy_yx_num </td><td> 暂停证书：$qy_zt_num </td><td>撤销证书：$qy_cx_num</td><td> 注销证书：$qy_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>EMS认证</td><td>GB/T24001-2004有效证书：$e2_yx_num </td><td> 暂停证书：$e2_zt_num </td><td>撤销证书：$e2_cx_num</td><td> 注销证书：$e2_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>OHSMS认证</td><td>GB/T28001-2001有效证书：$s1_yx_num </td><td> 暂停证书：$s1_zt_num </td><td>撤销证书：$s1_cx_num</td><td> 注销证书：$s1_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>FSMS认证</td><td>GB/T28001-2001有效证书：$f1_yx_num </td><td> 暂停证书：$f1_zt_num </td><td>撤销证书：$f1_cx_num</td><td> 注销证书：$f1_zx_num</td></tr>";
echo"<tr bgcolor=#FFFFFF><td>自愿性产品认证</td><td>产品有效企业数：$pro_company_num 证书：$pro_yx_num </td><td> 暂停证书：$pro_zt_num </td><td>撤销证书：$pro_cx_num</td><td> 注销证书：$pro_zx_num</td></tr>";
?>
</table>
<?php
$sql_setup	="select z.id,z.certNo,z.eiregistername,z.audit_ver,z.zuzhi_id,z.audit_code,z.iso,b.eiarea_code,z.ht_id from $dbtable[zs_cert] z left join $dbtable[mk_company] b on z.zuzhi_id=b.id where certEnd>='$start_date' and z.audit_ver!='Q1'  and (z.online='01' or z.online='04' or z.online='02') $temp_sql order by z.zuzhi_id desc ";
$quer_setup	= $db->query($sql_setup);
while($zs = $db->fetch_array($quer_setup)){
	$zs['iso'] == 'QT' && $zs['iso'] = 'Q';
	$zs['iso'] == 'QY' && $zs['iso'] = 'Q';
	$zs['iso'] == 'QJ' && $zs['iso'] = 'Q';
	//代码分类
	$daima = $daima_temp = array();
	$daima_temp2 = $zs["audit_code"];
	/*if(strpos($daima_temp2,'481')!==false){
		$daima_temp2 = '19.00.01';
	}*/
	$daima_temp=explode('；',$daima_temp2);
	foreach($daima_temp as $dvalue){
		$daima=explode('.',$dvalue);
		if($daima['0']!=''){
			$zs_code[$zs['iso']][$daima['0']]++;
			if($zs['iso'] == 'F' and !in_array($daima['0'],$f_code)){
				$debug_f_daima []= $daima['0'].' ： '.$zs['certNo'];
			}
			if(($zs['iso'] == 'Q' or $zs['iso'] == 'E' or $zs['iso'] == 'S') and !in_array($daima['0'],$daima_code)){
				$debug_daima []= $daima['0'].' ： '.$zs['certNo'];
			}
		}
	}
	//地域分类
	$zs_area[$zs['iso']][$zs['eiarea_code']]++;

	$temp_code = substr($zs['eiarea_code'],0,2);
	if(!in_array($temp_code,$a_code)){
		$debug_eiregistername []= $zs['eiregistername'];
	}
}
//取地域中文名称
$eiarea_sql =  $db->query("SELECT code,msg FROM setup_province  WHERE 1");
while($eiarea_arr = $db->fetch_array($eiarea_sql)){
	$eiarea_db [$eiarea_arr['code']]= $eiarea_arr['msg'];
}
?>

<br>
<table align="center"   ><tr valign="top"><td>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
<tr bgcolor=#FFFFFF><td>分类代码</td><td>QMS证书</td><td>EMS证书</td><td>OHSMS证书</td><td>产品证书</td><td>FSMS证书</td></tr>
<?php
foreach($f_code as $k=>$v){
	$i = $k + 1;
	$i<10 ? $ii = '0'.$i : $ii = $i;
	$i>39 ? $ii='' : $ii = $i;
	echo"<tr bgcolor=\"#FFFFFF\"><td>$ii</td><td>".$zs_code['Q'][$ii]."</td><td>".$zs_code['E'][$ii]."</td><td>".$zs_code['S'][$ii]."</td><td>".$zs_code['P'][$ii]."</td><td style=\"text-align:left;padding-left:3px;\">".$v.'： '.$zs_code['F'][$v]."</td></tr>";
	$q_all = $zs_code['Q'][$ii] + $q_all;
	$e_all = $zs_code['E'][$ii] + $e_all;
	$s_all = $zs_code['S'][$ii] + $s_all;
	$f_all = $zs_code['F'][$v] + $f_all;
	$p_all = $zs_code['P'][$ii] + $p_all;
}
echo"<tr bgcolor=\"#FFFFFF\"><td>总计：</td><td>$q_all</td><td>$e_all</td><td>$s_all</td><td>$p_all</td><td>$f_all</td></tr>";
?>
</table>
</td><td>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
<tr bgcolor=#FFFFFF><td>地域代码</td><td>地域名称</td><td>QMS证书</td><td>EMS证书</td><td>OHSMS证书</td><td>产品证书</td><td>FSMS证书</td></tr>
<?php
//地域分类
//for($k=11;$k<=82;$k++)
foreach($a_code as $v)
{
	$q_num=$e_num=$s_num=$f_num=0;
	$area_code_k = $v.'0000';
	$eiarea_db_msg = $eiarea_db[$area_code_k];
	echo show_erea($area_code_k);
	$q_all2 = $zs_area['Q'][$area_code_k] + $q_all2;
	$e_all2 = $zs_area['E'][$area_code_k] + $e_all2;
	$s_all2 = $zs_area['S'][$area_code_k] + $s_all2;
	$f_all2 = $zs_area['F'][$area_code_k] + $f_all2;
	$p_all2 = $zs_area['P'][$area_code_k] + $p_all2;
}

echo"<tr bgcolor=#FFFFFF><td>总计：</td><td></td><td>$q_all2</td><td>$e_all2</td><td>$s_all2</td><td>$p_all2</td><td>$f_all2</td></tr>";

function show_erea($area_code_k){
	global $zs_area,$eiarea_db;
	$msg = "<tr bgcolor=#FFFFFF><td>$area_code_k</td><td>$eiarea_db[$area_code_k]</td><td>{$zs_area[Q][$area_code_k]}</td><td>{$zs_area[E][$area_code_k]}</td><td>{$zs_area[S][$area_code_k]}</td><td>{$zs_area[P][$area_code_k]}</td><td>{$zs_area[F][$area_code_k]}</td></tr>";
	return $msg;
}
?>
</table>
</td></tr></table>
<br>
<br>
<table align="left">
<tr><td style="text-align:left">
<?php
echo'县区地址错误企业：<br><pre>';
print_r(array_unique($debug_eiregistername));
echo'</pre><hr>';
echo'Q E S体系代码错误证书：<br><pre>';
print_r(array_unique($debug_daima));
echo'</pre><hr>';
echo'F体系代码错误证书：<br><pre>';
print_r(array_unique($debug_f_daima));
echo'</pre><hr>';
?>
</td></tr>
</table>