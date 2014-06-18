<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(SET_DIR.'setup_htfrom.php');
include(SET_DIR.'setup_province.php');
include(S_DIR.'include/topsearch.php');			
include_once S_DIR.'core/hr/hr_information_search_arr.php';

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$sql_temp	= $TopSearch->SearchSql;
$url		= $baseurl.$TopSearch->SearchUrl;
$SearchHtml	= $TopSearch->SearchHtml;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td{
    font-size: 12px;
    vertical-align: middle;
vnd.ms-excel.numberformat:@;
}
</style>
<table border="1">
	<tr bgcolor="#F2F2F2">
		<td>序号</td>
		<td>人员编号</td>
		<td>行政档案号</td>
		<td>姓名</td>
		<td>易记码</td>
<!--		<td>审核档案号</td>-->
        <td>性别</td>
		<td>出生日期</td>
		<td>人员性质	</td>
		<td>合同类型	</td>
		<td>在职	</td>
		<td>职称</td>
		<td>所属部门</td>
		<td>在职状况</td>	
		
		<td>QMS注册资格</td>	
		<td>QMS证书编号</td>	
		<td>QMS注册时间</td>	
		<td>QMS到期时间</td>	
		<td>EMS注册资格</td>	
		<td>EMS证书编号</td>	
		<td>EMS注册时间</td>	
		<td>EMS到期时间</td>	
		<td>OHSMS注册资格</td>	
		<td>OHSMS证书编号</td>	
		<td>OHSMS注册时间</td>	
		<td>OHSMS到期时间</td>	
		<td>FSMS注册资格</td>	
		<td>FSMS证书编号</td>	
		<td>FSMS注册时间</td>	
		<td>FSMS到期时间</td>	
		
		<td>学历1</td>	
		<td>所学专业1</td>	
		<td>毕业院校1</td>	
		<td>毕业时间1</td>	
		<td>学历2</td>	
		<td>所学专业2</td>	
		<td>毕业院校2</td>	
		<td>毕业时间2</td>	
		<td>学历3</td>	
		<td>所学专业3</td>	
		<td>毕业院校3</td>	
		<td>毕业时间3</td>	
		<td>证件名称</td>
		<td>证件号码</td>
		<td>身份证地址</td>
		<td>入职时间</td>
		<td>合同开始日期</td>
		<td>合同结束日期</td>
		<td>选用类型</td>
		<td>技术特长</td>
		<td>政治面貌</td>	
		<td>所在地区</td>
		<td>通讯地址</td>	
		<td>通讯邮编</td>	
		<td>通讯电话</td>	
		<td>单位电话</td>	
		<td>联系手机</td>
		<td>电子邮件</td>
		<td>离职(辞职)日期</td>
		<td>合同编号</td>
		<td>社保编号</td>	
		<td>专业类别</td>
		<td>人员来源</td>
		<td>联系传真</td>
		<td>外语语种</td>
		<td>外语等级</td>
		<td>保密承诺日期</td>
		<td>组长日期</td>
		<td>备注信息</td>
		
		
	</tr>
<?php
$sql = "SELECT * FROM `{$dbtable['hr_information']}` WHERE 1 {$sql_temp} ORDER BY id DESC";
$query	= $db->query($sql);
$rows = array();
while($rows = $db->fetch_array($query))
{

	$arr = $xingzhi = $contract_type= array();
	$rows['sex'] = Cache::cache_sex($rows['sex']);
	$rows['worktype'] = Cache::cache_zaizhi($rows['worktype']);
	$rows['bumen'] = Cache::cache_hr_department($rows['bumen']);
	$rows['working'] = Cache::cache_working($rows['working']);
	$rows['shenfenkind'] = Cache::cache_hr_certificate($rows['shenfenkind']);
	$rows['politics'] = Cache::cache_hr_politics($rows['politics']);
	// 添加的性质和合同类型 	
	$arr = explode(',',$rows['xingzhi']);
	foreach ($arr as $v){
		$xingzhi []= Cache::cache_hr_xingzhi($v);
	}
	$rows['xingzhi'] = implode('；',$xingzhi);
	
	$arr = explode(',',$rows['contract_type']);
	foreach ($arr as $v){
		$contract_type []= Cache::cache_hr_contract_type($v);
	}
	$rows['contract_type'] = implode('；',$contract_type);
	
     $profession = $zc_msg = array();
	$sql2	= "SELECT profession,zc_msg FROM `{$dbtable['hr_profession']}` WHERE hr_id='{$rows['id']}' ORDER BY id DESC";
	$query2	= $db->query($sql2);
	while($result = $db->fetch_array($query2))
	{
		$profession	[]= Cache::cache_hr_profession($result['profession']);
		$zc_msg		[]= $result['zc_msg'];
	}
	$rows['profession'] = implode('；',$profession);
	$rows['zc_msg'] = implode('；',$zc_msg);
    $sql3	= "SELECT qualification,iso,qualification_no, s_date, e_date  FROM `{$dbtable['hr_reg_qualification']}` WHERE hr_id='{$rows['id']}' ";
	$query3	= $db->query($sql3);
	
	$QMS_qualification='';
	$QMS_qualification_no='';
	$QMS_s_date='';
	$QMS_e_date='';
	$audit_code ='';
		$EMS_qualification='';
	$EMS_qualification_no='';
	$EMS_s_date='';
	$EMS_e_date='';

		$OHSMS_qualification='';
	$OHSMS_qualification_no='';
	$OHSMS_s_date='';
	$OHSMS_e_date='';

		$FSMS_qualification='';
	$FSMS_qualification_no='';
	$FSMS_s_date='';
	$FSMS_e_date='';
	   
	while($result = $db->fetch_array($query3))
   {

	$sreach = '';
	switch ($result['qualification'])
	{
		case '1001':
			$sreach = " and qualification = '1001'";
			break;
		case '1002':
		case '1003':
		case '1004':
			$sreach = " and qualification = '1003'";
			break;
		case '1005':
			$sreach = " and qualification = '1005'";
			break;
		case '1006':
			$sreach = " and qualification = '1006'";
			break;
		default:
			$sreach = '';
	}
	$audit_codes = array();
	$sql4	= "SELECT  xiaolei FROM `{$dbtable['hr_audit_code']}` WHERE hr_id='{$rows['id']}' and iso='{$result['iso']}' {$sreach} ORDER BY iso ASC";
	$query4	= $db->query($sql4);
	while($result1 = $db->fetch_array($query4))
	{
		$audit_codes []= $result1['xiaolei'];
	}
	

	$result['qualification'] = Cache::cache_hr_reg_qualification($result['qualification']);

	if($result['iso']=='Q')
	{
	   $QMS_qualification=$result['qualification'];
	   $QMS_qualification_no=$result['qualification_no'];
	   $QMS_s_date=$result['s_date'];
	   $QMS_e_date=$result['e_date'];
	   if(count($audit_codes)>0)
	   {
	     $audit_code = ' Q:'.implode(';',$audit_codes);
	   }
	   
	}
	else if($result['iso']=='E')
	{
	   $EMS_qualification=$result['qualification'];
	   $EMS_qualification_no=$result['qualification_no'];
	   $EMS_s_date=$result['s_date'];
	   $EMS_e_date=$result['e_date'];
	   	if(count($audit_codes)>0)
	   {
	      $audit_code .=' E:'.implode(';',$audit_codes);
	   }
	 }
	else if($result['iso']=='S')
	{	   
	   $OHSMS_qualification=$result['qualification'];
	   $OHSMS_qualification_no=$result['qualification_no'];
	   $OHSMS_s_date=$result['s_date'];
	   $OHSMS_e_date=$result['e_date'];
	    if(count($audit_codes)>0)
	   {
	    $audit_code .=' S:'.implode(';',$audit_codes);
	   }
	 }
	 else if($result['iso']=='F')
	{	   
	   $FSMS_qualification=$result['qualification'];
	   $FSMS_qualification_no=$result['qualification_no'];
	   $FSMS_s_date=$result['s_date'];
	   $FSMS_e_date=$result['e_date'];
	   	   if(count($audit_codes)>0)
	   {
	   $audit_code .=' F:'.implode(';',$audit_codes);
	   }
	 }
   }
   
   $sql5= "SELECT xueli,school,graduate_date,zhuanye FROM `{$dbtable['hr_education']}` WHERE hr_id='{$rows['id']}'";
  
	$query5	= $db->query($sql5);
		$xueli1 = '';
		$school1 	   = '';
		$graduate_date1 = '';
		$zhuanye1 	   = '';
				$xueli2 = '';
		$school2 	   = '';
		$graduate_date2 = '';
		$zhuanye2 	   = '';
				$xueli3 = '';
		$school3 	   = '';
		$graduate_date3 = '';
		$zhuanye3 	   = '';
		
	while($result5 = $db->fetch_array($query5))
	{
	   if($result5['xueli']=='00')
	   {
	   	$xueli1 		   = Cache::cache_hr_education($result5['xueli']);
		$school1 	   = $result5['school'];
		$graduate_date1 = $result5['graduate_date'];
		$zhuanye1 	   = $result5['zhuanye'];
		}
	   elseif($result5['xueli']=='01')
	   {
	   	$xueli2 		   = Cache::cache_hr_education($result5['xueli']);
		$school2 	   = $result5['school'];
		$graduate_date2 = $result5['graduate_date'];
		$zhuanye2 	   = $result5['zhuanye'];
		}
	   elseif($result5['xueli']=='02')
	   {
	   	$xueli3 		   =Cache::cache_hr_education($result5['xueli']);
		$school3 	   = $result5['school'];
		$graduate_date3 = $result5['graduate_date'];
		$zhuanye3 	   = $result5['zhuanye'];
		}

	}
	
?>
	<tr>
		<td><?php echo $rows['id']; ?></td>
		<td><?php echo $rows['idcode']; ?></td>
		<td><?php echo $rows['xz_dangan']; ?></td>
		<td><?php echo $rows['username']; ?></td>
		<td><?php echo $rows['yjm']; ?></td>
		<td><?php echo $rows['sex']; ?></td>
		<td><?php echo $rows['birthday']; ?></td>
		<td><?php echo $rows['xingzhi']; ?></td>
		<td><?php echo $rows['contract_type']; ?></td>
		<td><?php echo $rows['worktype']; ?></td>
		<td><?php echo $rows['zc_msg']; ?></td>
		<td><?php echo $rows['bumen']; ?></td>
		<td><?php echo $rows['working']; ?></td>
		<td><?php echo $QMS_qualification ?></td>
		<td><?php echo $QMS_qualification_no ?></td>
		<td><?php echo $QMS_s_date ?></td>
		<td><?php echo $QMS_e_date ?></td>
		<td><?php echo $EMS_qualification ?></td>
		<td><?php echo $EMS_qualification_no ?></td>
		<td><?php echo $EMS_s_date ?></td>
		<td><?php echo $EMS_e_date ?></td>
		<td><?php echo $OHSMS_qualification ?></td>
		<td><?php echo $OHSMS_qualification_no ?></td>
		<td><?php echo $OHSMS_s_date ?></td>
		<td><?php echo $OHSMS_e_date ?></td>
		<td><?php echo $FSMS__qualification ?></td>
		<td><?php echo $FSMS__qualification_no ?></td>
		<td><?php echo $FSMS__s_date ?></td>
		<td><?php echo $FSMS__e_date ?></td>
		
		<td><?php echo $xueli1  ?></td>
		<td><?php echo $zhuanye1  ?></td>
		<td><?php echo $school1  ?></td>
		<td><?php echo $graduate_date1  ?></td>
		<td><?php echo $xueli2  ?></td>
		<td><?php echo $zhuanye2  ?></td>
		<td><?php echo $school2  ?></td>
		<td><?php echo $graduate_date2  ?></td>
		<td><?php echo $xueli3  ?></td>
		<td><?php echo $zhuanye3  ?></td>
		<td><?php echo $school3  ?></td>
		<td><?php echo $graduate_date3 ?></td>
		<td><?php echo $rows['shenfenkind']; ?></td>
		<td><?php echo $rows['cardid']; ?></td>
		<td><?php echo $rows['cardaddress']; ?></td>
		<td><?php echo $rows['ruzhidate']; ?></td>
		<td><?php echo $rows['pinyongdate']; ?></td>
		<td><?php echo $rows['pinyongover']; ?></td> 
		<td><?php echo $rows['use_lev']; ?></td>
		<td><?php echo $rows['technical']; ?></td>
		<td><?php echo $rows['politics']; ?></td>		
		<td><?php echo $rows['city_msg']; ?></td>
		<td><?php echo $rows['address']; ?></td> 
		<td><?php echo $rows['postcode']; ?></td>
		<td><?php echo $rows['tel']; ?></td>
		<td><?php echo $rows['dwtel']; ?></td>
		<td><?php echo $rows['phone']; ?></td>
		<td><?php echo $rows['email']; ?></td>
		<td><?php echo $rows['lizhidate']; ?></td>
		<td><?php echo $rows['hetonghao']; ?></td>
		<td><?php echo $rows['shebaohao']; ?></td>
		<td><?php echo $audit_code ?></td>
	    <td><?php echo $rows['htfrom']; ?></td>	
		<td><?php echo $rows['fax']; ?></td>
	    <td><?php echo $rows['language']; ?></td>
		<td><?php echo $rows['language_level']; ?></td>	 	
		<td><?php echo $rows['baomidate']; ?></td>	
		<td><?php echo $rows['groupdate']; ?></td>	
		<td><?php echo $row['other']; ?></td>	

		
<?php
}
?>
	</tr>
</table>

















