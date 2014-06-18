<?php
set_time_limit(300) ;

header("Content-Type: application/vnd.ms-excel") ;
header("Content-Disposition: attachment; filename=new_monthly_list_error_xls".date("Y-m-d").".xls") ;
header("Pragma: no-cache") ;
header("Expires: 0") ;
!defined('IN_SUPU') && exit('Forbidden') ;
include_once S_DIR.'include/setup/setup_new_strlen.php';

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
TD{
    font-size: 12px;
    vertical-align: middle;
	vnd.ms-excel.numberformat:@;
}
</style>
<table border="1">
    <tr>
	    <td align="center">企业名称</td>
	    <td align="center">证书编号</td>
	    <td align="center">坐标</td>
		<td align="center" width="360">错误原因</td>
    </tr>
<?
$q = mysql_query("SELECT * FROM `sys_report50_new` ") ;
while($rows = mysql_fetch_array($q))
{
	//打印报表时过滤条件
	if($rows['a4'] == ''){$rows['a4'] = '/';}
	if($rows['a6'] == '*********' or $rows['a6'] == '**********'){$rows['a6'] = '000000000';}
	if($rows['a21']=='0000-00-00'){$rows['a21']='';}
	if($rows['a37']=='0000-00-00'){$rows['a37']='';}
	if($rows['a40']=='0000-00-00'){$rows['a40']='';}
	if($rows['a41']=='0000-00-00'){$rows['a41']='';}
	if($rows['a48']=='0000-00-00'){$rows['a48']='';}
	if($rows['a49']=='0000-00-00'){$rows['a49']='';}
	if($rows['a52']=='0000-00-00'){$rows['a52']='';}
	if($rows['a53']=='0000-00-00'){$rows['a53']='';}
	if($rows['a55']=='0000-00-00'){$rows['a55']='';}
	if($rows['a56']=='0000-00-00'){$rows['a56']='';}
	if($rows['a57']=='0000-00-00'){$rows['a57']='';}
	if($rows['a58']=='0000-00-00'){$rows['a58']='';}
	$rows['a18']=='' && $rows['a18']='01';
	$rows['a9'] == '' && $rows['a9'] = '156';
	($rows['a33'] == '1' && $rows['a36']=='') && $rows['a36'] = '';  
	if($rows['a30'] == '04' or $rows['a30'] == '99'){
		$rows['a40']=$rows['a41']=$rows['a42']=$rows['a44']=$rows['a45']=$rows['a46']='';
	}
	if($rows['a33'] == '0'){
		$rows['a34']=$rows['a35']=$rows['a36']=$rows['a37']='';
	}
	if($rows['a50'] != '01'){
		$rows['a30']=$rows['a31']=$rows['a32']=$rows['a33']=$rows['a34']=$rows['a35']=$rows['a36']=$rows['a37']=$rows['a38']=$rows['a39']=$rows['a40']=$rows['a41']=$rows['a42']=$rows['a43']=$rows['a44']=$rows['a45']=$rows['a46']='';
	}

	//企业名称、证书编号 、横坐标
	$eiregistername = $rows['a3'] ;
	$certNo = $rows['a22'] ;
	$row = $rows['id'] + 1 ;
	
	//调用59项函数
	a1($rows['a1']);
	a2($rows['a2']);
	a3($rows['a3']);
	a4($rows['a4']);
	a5($rows['a5']);
	a6($rows['a6']);
	a7($rows['a7']);
	a8($rows['a8']);
	a9($rows['a9']);
	a10($rows['a10']);
	a11($rows['a11'], $rows['a10']);
	a12($rows['a12']);
	a13($rows['a13']);
	a14($rows['a14']);
	a15($rows['a15']);
	a16($rows['a16']);
	a17($rows['a17']);
	a18($rows['a18']);
	a19($rows['a19']);
	a20($rows['a20']);
	a21($rows['a21']);
	a22($rows['a22']);
	a23($rows['a23']);
	a24($rows['a24'], $rows['a23']);
	a25($rows['a25']);
	a26($rows['a26']);
	a27($rows['a27'], $rows['a26']);
	a28($rows['a28']);
	a29($rows['a29']);
	a30($rows['a30']);
	a31($rows['a31'], $rows['a30']);
	a32($rows['a32'], $rows['a30']);
	a33($rows['a33']);
	a34($rows['a34'], $rows['a33']);
	a35($rows['a35'], $rows['a33']);
	a36($rows['a36'], $rows['a33']);
	a37($rows['a37'], $rows['a33']);
	a38($rows['a38'], $rows['a30']);
	a39($rows['a39'], $rows['a30']);
	a40($rows['a40'], $rows['a30']);
	a41($rows['a41'], $rows['a30']);
	a42($rows['a42'], $rows['a30']);
	a43($rows['a59'], $rows['a42']);
	a44($rows['a43']);
	a45($rows['a44'], $rows['a30']);
	a46($rows['a45'], $rows['a44']);
	a47($rows['a46'], $rows['a44']);
	a48($rows['a47']);
	a49($rows['a48']);
	a50($rows['a49']);
	a51($rows['a50']);
	a52($rows['a51'], $rows['a50']);
	a53($rows['a52'], $rows['a50']);
	a54($rows['a53'], $rows['a50']);
	a55($rows['a54'], $rows['a50']);
	a56($rows['a55'], $rows['a50']);
	a57($rows['a56'], $rows['a30']);
	a58($rows['a57']);
	a59($rows['a58']);
	
}



//检验 59个字段 
function a1($a1){
	global $eiregistername, $certNo, $row ;
	
	$a1 == '' && not_null(1);
	$a1 != '' && str_len(1, $a1);
}

function a2($a2){
	global $eiregistername, $certNo, $row ;
		
	$a2 == '' && not_null(2);
	$a2 != '' && str_len(2, $a2);
}

function a3($a3){
	global $eiregistername, $certNo, $row ;
		
	$a3 == '' && not_null(3);
	$a3 != '' && str_len(3, $a3);
}

function a4($a4){
	global $eiregistername, $certNo, $row ;
		
	$a4 == '' && not_null(4);
	$a4 != '' && str_len(4, $a4);	
}

function a5($a5){
	global $eiregistername, $certNo, $row ;
		
	$a5 != '' && str_len(5, $a5);
}

function a6($a6){
	global $eiregistername, $certNo, $row ;
		
	$a6 == '' && not_null(6);
	$a6 != '' && str_len(6, $a6);		
}

function a7($a7){
	global $eiregistername, $certNo, $row ;
		
	$a7 == '' && not_null(7);
	$a7 != '' && str_len(7, $a7);			
}

function a8($a8){
	global $eiregistername, $certNo, $row ;
		
	$a8 == '' && not_null(8);
	$a8 != '' && str_len(8, $a8);
			
}

function a9($a9){
	global $eiregistername, $certNo, $row ;
		
	$a9 == '' && not_null(9);
	$a9 != '' && str_len(9, $a9);
}

function a10($a10){
	global $eiregistername, $certNo, $row ;
		
	$a10 != '' && str_len(10, $a10);	
}

function a11($a11, $a10){
	global $eiregistername, $certNo, $row, $db ;
		
	$a11 == '' && not_null(11);
	$a11 != '' && str_len(11, $a11);
	if($a11 != '' and $a10 != ''){
		$get_city = $db->get_one("SELECT * FROM  `setup_city` WHERE code='$a10' LIMIT 1") ;
		$get_city['code'] != $get_city['dacode'] ? $eiaddress = Cache::cache_province($get_city['dacode']).$get_city['msg'] : $eiaddress = $get_city['msg'] ;
		if(strstr($a11, $eiaddress) == ''){
			echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行11列</td><td>‘组织通讯/注册地址’不包含‘县区地址’</td></tr>';
		}
	}
}

function a12($a12){
	global $eiregistername, $certNo, $row ;
		
	$a12 == '' && not_null(12);
	$a12 != '' && str_len(12, $a12);	
}

function a13($a13){
	global $eiregistername, $certNo, $row ;
		
	$a13 == '' && not_null(13);
	$a13 != '' && str_len(13, $a13);	
}

function a14($a14){
	global $eiregistername, $certNo, $row ;
		
	$a14 == '' && not_null(14);
	$a14 != '' && str_len(14, $a14);	
}

function a15($a15){
	global $eiregistername, $certNo, $row ;
		
	$a15 == '' && not_null(15);
	$a15 != '' && str_len(15, $a15);	
}

function a16($a16){
	global $eiregistername, $certNo, $row ;
		
	$a16 == '' && not_null(16);
	$a16 != '' && str_len(16, $a16);	
}

function a17($a17){
	global $eiregistername, $certNo, $row ;
		
	$a17 == '' && not_null(17);
	$a17 != '' && str_len(17, $a17);	
	if (($a17 != '') and (!preg_match("/^[-\+]?\d+(\.\d+)?$/", $a17))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行17列</td><td>数据错误，非法字符</td></tr>';
	} 	
}

function a18($a18){
	global $eiregistername, $certNo, $row ;
		
	$a18 == '' && not_null(18);
	$a18 != '' && str_len(18, $a18);
	if($a18 != '' and !in_array($a18, array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行18列</td><td>数据错误，范围(01,02,03,04,05,06,07,08,09,10,11,99)</td></tr>';
	}		
}

function a19($a19){
	global $eiregistername, $certNo, $row ;
		
	$a19 == '' && not_null(19);
	$a19 != '' && str_len(19, $a19);	
}

function a20($a20){
	global $eiregistername, $certNo, $row ;
		
	$a20 == '' && not_null(20);
	$a20 != '' && str_len(20, $a20);	
}

function a21($a21){
	global $eiregistername, $certNo, $row ;
		
	$a21 == '' && not_null(21);
	$a21 != '' && qy_daima(21, $a21);
}

function a22($a22){
	global $eiregistername, $certNo, $row ;
		
	$a22 == '' && not_null(22);
	$a22 != '' && str_len(22, $a22);	
}

function a23($a23){
	global $eiregistername, $certNo, $row ;
		
	$a23 == '' && not_null(23);
	$a23 != '' && str_len(23, $a23);	
	if($a23 != '' and !in_array($a23, array("0", "1"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行23列</td><td>数据错误，范围(1,2)</td></tr>';
	}
}

function a24($a24, $a23){
	global $eiregistername, $certNo, $row ;
		
	$a24 != '' && str_len(24, $a24);
	if($a23 == '1' and $a24 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行24列</td><td>当23栏为“1”，此项不能为空（必填）</td></tr>';
	}
}

function a25($a25){
	global $eiregistername, $certNo, $row ;
		
	$a25 == '' && not_null(25);
	$a25 != '' && str_len(25, $a25);
	if($a25 != '' and !in_array($a25, array("0", "1"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行25列</td><td>数据错误，范围(1,2)</td></tr>';
	}	
}

function a26($a26){
	global $eiregistername, $certNo, $row ;
		
	$a26 == '' && not_null(26);
	$a26 != '' && str_len(26, $a26);	
}

function a27($a27, $a26){
	global $eiregistername, $certNo, $row ;
		
	$a27 != '' && str_len(27, $a27);	
	if($a26 == 'Z99999' and $a27 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行27列</td><td>当26栏为“Z99999”，此项不能为空（必填）</td></tr>';
	}	
}

function a28($a28){
	global $eiregistername, $certNo, $row ;
		
	$a28 != '' && str_len(28, $a28);	
}

function a29($a29){
	global $eiregistername, $certNo, $row ;
		
	$a29 == '' && not_null(29);
	$a29 != '' && str_len(29, $a29);	
}

function a30($a30){
	global $eiregistername, $certNo, $row ;
		
	$a30 == '' && not_null(30);
	$a30 != '' && str_len(30, $a30);
	if($a30 != '' and !in_array($a30, array("01", "02", "03", "04", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行30列</td><td>数据错误，范围(01,02,03,04,99)</td></tr>';
	}
}

function a31($a31, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a31 != '' && str_len(31, $a31);
	if($a30 == '04' and $a31 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行31列</td><td>当30栏为“04”，此项不能为空（必填）</td></tr>';
	}	
}

function a32($a32, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a32 != '' && str_len(32, $a32);	
	if($a32 != '' and !in_array($a32, array("01", "02", "03"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行32列</td><td>数据错误，范围(01,02,03)</td></tr>';
	}
	if($a30 == '04' and $a32 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行32列</td><td>当30栏为“04”，此项不能为空（必填）</td></tr>';
	}	
}

function a33($a33){
	global $eiregistername, $certNo, $row ;
		
	$a33 == '' && not_null(33);
	$a33 != '' && str_len(33, $a33);	
	if($a33 != '' and !in_array($a33, array("0", "1"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行33列</td><td>数据错误，范围(1,2)</td></tr>';
	}
}

function a34($a34, $a33){
	global $eiregistername, $certNo, $row ;
		
	$a34 != '' && str_len(34, $a34);
	if($a34 != '' and !in_array($a34, array("01", "02", "03", "04", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行34列</td><td>数据错误，范围(01,02,03,04,99)</td></tr>';
	}
	if($a33 == '04' and $a34 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行34列</td><td>当33栏为“1”，此项不能为空（必填）</td></tr>';
	}	
}

function a35($a35, $a33){
	global $eiregistername, $certNo, $row ;
		
	$a35 != '' && str_len(35, $a35);
	if($a33 == '04' and $a35 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行35列</td><td>当33栏为“1”，此项不能为空（必填）</td></tr>';
	}	
}

function a36($a36, $a33){
	global $eiregistername, $certNo, $row ;
		
	$a36 != '' && str_len(36, $a36);
	if($a33 == '04' and $a36 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行36列</td><td>当33栏为“1”，此项不能为空（必填）</td></tr>';
	}	
}

function a37($a37, $a33){
	global $eiregistername, $certNo, $row ;
	
	if($a33 == '04' and $a37 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行37列</td><td>当33栏为“1”，此项不能为空（必填）</td></tr>';
	}
}

function a38($a38, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a38 != '' && str_len(38, $a38);	
	if($a30 == '02' and $a38 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行38列</td><td>当30栏为“02”，此项不能为空（必填）</td></tr>';
	}
}

function a39($a39, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a39 != '' && str_len(39, $a39);
	if($a30 == '03' and $a39 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行39列</td><td>当30栏为“02”，此项不能为空（必填）</td></tr>';
	}	
}

function a40($a40, $a30){
	global $eiregistername, $certNo, $row ;
	
	if(($a30 == '01' or $a30 == '02' or $a30 == '03') and $a40 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行40列</td><td>当30栏为“01/02/03”，此项不能为空（必填）</td></tr>';
	}	
}

function a41($a41, $a30){
	global $eiregistername, $certNo, $row ;
		
	if(($a30 == '01' or $a30 == '02' or $a30 == '03') and $a41 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行41列</td><td>当30栏为“01/02/03”，此项不能为空（必填）</td></tr>';
	}
}

function a42($a42, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a42 != '' && str_len(42, $a42);
	if(($a30 == '01' or $a30 == '02' or $a30 == '03') and $a42 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行42列</td><td>当30栏为“01/02/03”，此项不能为空（必填）</td></tr>';
	}	
}

function a43($a43, $a42){
	global $eiregistername, $certNo, $row ;
		
	$a43 != '' && str_len(43, $a43);
	if($a43 != '' and !in_array($a43, array("0", "1"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行43列</td><td>数据错误，范围(1,2)</td></tr>';
	}
	if($a42 != '' and $a43 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行43列</td><td>当42项非空时，此项不能为空（必填）</td></tr>';
	}		
}

function a44($a44, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a44 != '' && str_len(44, $a44);		
}

function a45($a45, $a30){
	global $eiregistername, $certNo, $row ;
		
	$a45 != '' && str_len(45, $a45);
	if(($a30 == '01' or $a30 == '02' or $a30 == '03') and $a45 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行45列</td><td>当30栏为“01/02/03”，此项不能为空（必填）</td></tr>';
	}	
	if (($a45 != '') and (!preg_match("/^[-\+]?\d+(\.\d+)?$/", $a45))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行45列</td><td>数据错误，非法字符</td></tr>';
	} 
}

function a46($a46, $a45){
	global $eiregistername, $certNo, $row ;
		
	$a46 != '' && str_len(46, $a46);
	if($a46 != '' and !in_array($a46, array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行46列</td><td>数据错误，范围(01,02,03,04,05,06,07,08,09,10,11,99)</td></tr>';
	}
	if($a45 != '' and $a46 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行46列</td><td>当45项非空时，此项不能为空（必填）</td></tr>';
	}		
}

function a47($a47, $a45){
	global $eiregistername, $certNo, $row ;
		
	$a47 != '' && str_len(47, $a47);	
	if($a45 != '' and $a47 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行47列</td><td>当45项非空时，此项不能为空（必填）</td></tr>';
	}	
}

function a48($a48){
	global $eiregistername, $certNo, $row ;
		
	$a48 != '' && str_len(48, $a48);
	if($a48 != '' and !in_array($a48, array("01", "02", "03"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行48列</td><td>数据错误，范围(01,02,03)</td></tr>';
	}	
}

function a49($a49){
	global $eiregistername, $certNo, $row ;
		
	$a49 == '' && not_null(49);
}

function a50($a50){
	global $eiregistername, $certNo, $row ;
		
	$a50 == '' && not_null(50);
}

function a51($a51){
	global $eiregistername, $certNo, $row ;
		
	$a51 == '' && not_null(51);
	$a51 != '' && str_len(51, $a51);	
	if($a51 != '' and !in_array($a51, array("01", "02", "03", "04", "05", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行51列</td><td>数据错误，范围(01,02,03,04,05,99)</td></tr>';
	}
}

function a52($a52, $a51){
	global $eiregistername, $certNo, $row ;
		
	$a52 != '' && str_len(52, $a52);
	if($a52 != '' and !in_array($a52, array("01", "02", "03", "04", "05", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行52列</td><td>数据错误，范围(01,02,03,04,05,99)</td></tr>';
	}
	if($a51 == '02' and $a52 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行52列</td><td>当51栏为“02”，此项不能为空（必填）</td></tr>';
	}
}

function a53($a53, $a51){
	global $eiregistername, $certNo, $row ;
		
	if($a51 == '02' and $a53 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行53列</td><td>当51栏为“02”，此项不能为空（必填）</td></tr>';
	}
}

function a54($a54, $a51){
	global $eiregistername, $certNo, $row ;
	
	if($a51 == '02' and $a54 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行54列</td><td>当51栏为“02”，此项不能为空（必填）</td></tr>';
	}
}

function a55($a55, $a51){
	global $eiregistername, $certNo, $row ;
		
	$a55 != '' && str_len(55, $a55);
	if($a55 != '' and !in_array($a55, array("01", "02", "03", "04", "05", "06", "07", "08", "98", "99"))){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行55列</td><td>数据错误，范围(01,02,03,04,05,06,07,08,98,99)</td></tr>';
	}
	if($a51 == '03' and $a55 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行55列</td><td>当51栏为“03”，此项不能为空（必填）</td></tr>';
	}
}

function a56($a56, $a51){
	global $eiregistername, $certNo, $row ;
		
	if($a51 == '03' and $a56 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行56列</td><td>当51栏为“03”，此项不能为空（必填）</td></tr>';
	}
}

function a57($a57, $a30){
	global $eiregistername, $certNo, $row ;
	
	if(($a30 == '03' or $a30 == '04') and $a57 == ''){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行57列</td><td>当30栏为“03/04”，此项不能为空（必填）</td></tr>';
	}
}

function a58($a58){
	global $eiregistername, $certNo, $row ;
		
	$a58 == '' && not_null(58);
}

function a59($a59){
	global $eiregistername, $certNo, $row ;
	
	$a59 == '' && not_null(59);
}


//检验 必填项 function
function not_null($col){
	global $eiregistername, $certNo, $row ;
	
	echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行'.$col.'列</td><td>不能为空（必填项）</td></tr>';
}

//检验 字段长度 function
function str_len($col, $str_val){
	global $eiregistername, $certNo, $row, $setup_new_strlen ;
	
	if(strlen(iconv("utf-8", "gbk//IGNORE", $str_val)) > $setup_new_strlen[$col]){
		echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行'.$col.'列</td><td>数据长度不能大于'.$setup_new_strlen[$col].'</td></tr>';
	}
}

//检验 组织机构代码
function qy_daima($col, $daima){
	global $eiregistername, $certNo, $row ;
	
    if(!preg_match("/[^0-9A-Z-]/", $daima)){
    	echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行'.$col.'列</td><td>必须是数字和大写字母组成</td></tr>';
    }else if(strlen($daima) > 10){
        echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行'.$col.'列</td><td>组织机构代码必须10位</td></tr>';
    }else if(!substr($daima, 8, 1)){
    	echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行'.$col.'列</td><td>第9位必须为横杠</td></tr>';
    }else{
        $yinzi = array(3, 7, 9, 10, 5, 8, 4, 2);
        for($i = 0; $i < 10; $i++){
            $v = substr($daima, $i, 1);

            if($i == 9){
                $lastv = $v;
            }

            if($i < 8)
		    {
                switch($v)
                {
                case 'A' : $v = 10; break;
                case 'B' : $v = 11; break;
                case 'C' : $v = 12; break;
                case 'D' : $v = 13; break;
                case 'E' : $v = 14; break;
                case 'F' : $v = 15; break;
                case 'G' : $v = 16; break;
                case 'H' : $v = 17; break;
                case 'I' : $v = 18; break;
                case 'J' : $v = 19; break;
                case 'K' : $v = 20; break;
                case 'L' : $v = 21; break;
                case 'M' : $v = 22; break;
                case 'N' : $v = 23; break;
                case 'O' : $v = 24; break;
                case 'P' : $v = 25; break;
                case 'Q' : $v = 26; break;
                case 'R' : $v = 27; break;
                case 'S' : $v = 28; break;
                case 'T' : $v = 29; break;
                case 'U' : $v = 30; break;
                case 'V' : $v = 31; break;
                case 'W' : $v = 32; break;
                case 'X' : $v = 33; break;
                case 'Y' : $v = 34; break;
                case 'Z' : $v = 35; break;
                default : $v = $v;
                }
                $n = $v * $yinzi[$i] + $n;
		    }

	     }

        $xiaoyanma = 11 - $n%11;
        
        if($xiaoyanma == 10){
            $xiaoyanma = 'X';
        }else if($xiaoyanma == 11){
            $xiaoyanma = 0;
        }
        
        if($lastv != $xiaoyanma){	
        	echo '<tr><td>'.$eiregistername.'</td><td>'.$certNo.'</td><td>'.$row.'行'.$col.'列</td><td>最后一位效验码不对</td></tr>';
        }
    }
}
?>
</table>