<?php
set_time_limit(0);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=authentication_activity".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Auditor.php';
include_once S_DIR.'include/module/Hr_information.php';
include_once S_DIR.'include/module/Report_information.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once SET_DIR.'setup_upplan_online.php';
include(S_DIR.'include/topsearch.php');

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),

	'taskBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskBeginDate1',
	  'msg'=>'审核日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'>='
	),
	'taskBeginDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'taskBeginDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'<='
	),
	'upplan_online'=>array(
	  'kind'=>'hidden',
	  'name'=>'upplan_online',
	  'msg'=>'',
	  'width'=>'',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	)
);
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL

if($upplan_online == ''){
	$sql_temp = $sql_temp." AND (upplan='0' OR upplan='2') AND taskBeginDate>='".date("Y-m-d")."'";
}else if($upplan_online == '1'){
	$sql_temp = $sql_temp." AND (upplan='1' OR upplan='3')";
}else if($upplan_online == '2'){
	$sql_temp = $sql_temp." AND (upplan='2')";
}else if($upplan_online == '3'){
	$sql_temp = $sql_temp." AND (upplan='3')";
}


?>
<style>
<!--
td{
	vnd.ms-excel.numberformat:@;
}
//-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table border="1">
        <tr bgcolor="#F2F2F2">
		<td>认证机构号</td>
    	<td>组织机构代码</td>
    	<td>组织名称</td>
        <td>组织联系人</td>
        <td>组织联系电话</td>
        <td>审核类型</td>
        <td>认证活动类型</td>
        <td>审核开始日期</td>
        <td>审核结束日期</td>
        <td>行政区划</td>
        <td>详细地址</td>
        <td>审核组成员</td>
        <td>技术专家</td>
        </tr>
<?php
$sql = "SELECT * FROM xm_item WHERE online='3' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004' OR audit_type='1005' OR audit_type='1007' OR audit_type='1008') $sql_temp ORDER BY id DESC";
$rows_q = $db->query($sql);

while($result = $db->fetch_array($rows_q))
{
		$com = $db->get_one("SELECT * FROM `{$dbtable['mk_company']}` WHERE id='{$result['zuzhi_id']}'");
		$result['eidaima'] = $com['eidaima'];
		$result['eilinkman'] = $com['eilinkman'];
		$result['eiphone'] = substr($com['eiphone'],0,20);
		$result['eiaddress_code'] = $com['eiaddress_code'];
		$result['eiregistername'] = $com['eiregistername'];
		$result['htfrom'] = Cache::cache_htfrom($result['htfrom']);
		if($com['eipro_address']!='')
		{
			$result['eisc_address'] = $com['eipro_address'];
		}
		elseif($com['eireg_address']!='')
		{
			$result['eisc_address'] = $com['eireg_address'];
		}
		elseif($com['eisc_address']!='')
		{
			$result['eisc_address'] = $com['eisc_address'];
		}
		switch($result['taskBeginHalfDate'])
		{
			case $result['taskBeginHalfDate']<='12:00:00' : $result['taskBeginHalfDate']='A';break;
			case $result['taskBeginHalfDate']>'12:00:00' : $result['taskBeginHalfDate']='P';break;
		}
		switch($result['taskEndHalfDate'])
		{
			case $result['taskEndHalfDate']<='12:00:00' : $result['taskEndHalfDate']='A';break;
			case $result['taskEndHalfDate']>'12:00:00' : $result['taskEndHalfDate']='P';break;
		}
		$result['taskBeginDate'] = $result['taskBeginDate']." ".$result['taskBeginHalfDate'];
		$result['taskEndDate'] = $result['taskEndDate']." ".$result['taskEndHalfDate'];
		switch($result['audit_type'])
		{
			case '1008' : $result['audit_type']='01';break;
			case '1007' : $result['audit_type']='04';break;
			case '1005' : $result['audit_type']='02';break;
			case '1002' : $result['audit_type']='03';break;
			case '1003' : $result['audit_type']='03';break;
			case '1004' : $result['audit_type']='03';break;
			default : $result['audit_type']='';
		}

		$Auditor = new Auditor();
		$Hr_information = new Hr_information();
		$experts = $people = array();
		$arr = $Auditor->toArray("taskId='{$result['taskId']}'");
		$pr_q = $db->query("SELECT * FROM xm_auditor_plan WHERE taskId='{$result['taskId']}' AND iso='{$result['iso']}' AND role!='1000' ORDER BY isLeader DESC");
		while($pr = $db->fetch_array($pr_q)){
			$pr_n = $db->get_one("SELECT empId,empName FROM xm_auditor WHERE id='$pr[auditorId]'");

			if($pr['role'] == '1004'){
				$rows = $Hr_information->query($pr_n['empId'],array('shenfenkind','cardid'));
				switch($rows['shenfenkind'])
				{
					case '00' : $rows['shenfenkind']='1';break;
					case '01' : $rows['shenfenkind']='2';break;
					case '02' : $rows['shenfenkind']='3';break;
					case '03' : $rows['shenfenkind']='4';break;
					case '04' : $rows['shenfenkind']='5';break;
					case '05' : $rows['shenfenkind']='6';break;
					default : $rows['shenfenkind']='';
				}
				$experts []=$pr_n['empName'].",".$rows['shenfenkind'].",".$rows['cardid'];
			}else{
				$people []= $pr_n['empName'];
			}
		}

		/*foreach ($arr as $v){
			$iso = explode(',', $v['iso']);
			$qualification = explode(',', $v['qualification']);
			$isLeader = explode(',', $v['isLeader']);
			foreach ($iso as $k => $vl){
				if($role[$k] != '1000'){
					if ($result['iso'] == $vl && $role[$k] == '1004'){
						$rows = $Hr_information->query($v['empId'],array('shenfenkind','cardid'));
						switch($rows['shenfenkind'])
						{
							case '00' : $rows['shenfenkind']='1';break;
							case '01' : $rows['shenfenkind']='2';break;
							case '02' : $rows['shenfenkind']='3';break;
							case '03' : $rows['shenfenkind']='4';break;
							case '04' : $rows['shenfenkind']='5';break;
							case '05' : $rows['shenfenkind']='6';break;
							default : $rows['shenfenkind']='';
						}
						$experts []=$v['empName']."，".$rows['shenfenkind']."，".$rows['cardid'];
					}else if ($result['iso'] == $vl && $role[$k] != '1004' && $isLeader[$k] != '1'){
						$people []= $v['empName'];
					}else if ($result['iso'] == $vl && $role[$k] != '1004' && $isLeader[$k] == '1') {
						$empName = $v['empName'].'组长';
					}
				}
			}
		}*/
		//array_unshift($people,$empName);
		//$people ['empName']= $empName;
		//krsort($people);
		$result['experts'] = implode(",",$experts);
		$result['people'] = implode(",",$people);
		$result['iso'] = Cache::cache_authentication_activity($result['iso']);

?>
        <tr bgcolor="#FFFFFF">
		<Td>CNCA-R-2002-041</td>
    	<td ><?php echo $result['eidaima'];?></td>
        <td><?php echo $result['eiregistername'];?></td>
        <td><?php echo $result['eilinkman'];?></td>
        <td><?php echo $result['eiphone'];?></td>
        <td><?php echo $result['iso'];?></td>
        <td ><?php echo $result['audit_type'];?></td>
        <td><?php echo $result['taskBeginDate'];?></td>
        <td><?php echo $result['taskEndDate'];?></td>
        <td><?php  echo $result['eiaddress_code'];?></td>
        <td><?php echo $result['eisc_address'];?></td>
        <td><?php echo $result['people'];?></td>
        <td ><?php echo $result['experts'];?></td>
        </tr>
<?php
}
?>

</table>