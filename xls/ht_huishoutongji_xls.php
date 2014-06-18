<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/topsearch.php');
include(S_DIR.'include/module/Hr_information.php');
include(SET_DIR.'setup_htfrom.php');
include(SET_DIR.'setup_province.php');


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
	'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxm_id',
		'sql_kind'=>'in'
	  ),
	'taskBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskBeginDate1',
	  'msg'=>'审核开始时间',
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
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'70px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
		'to_assess_date1'=>array(
	  'kind'=>'date1',
	  'name'=>'to_assess_date1',
	  'msg'=>'转技委会日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'to_assess_date',
	  'sql_kind'=>'>='
	),
	  'to_assess_date2'=>array(
	  'kind'=>'date2',
	  'name'=>'to_assess_date2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'to_assess_date',
	  'sql_kind'=>'<='
	),
	'br1'=>array(
	'kind'=>'br'
	),
	'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'ht_id',
	  'sql_kind'=>'='
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	  'zl_okdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'zl_okdate1',
	  'msg'=>'资料收回时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	  'zl_okdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'zl_okdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'70px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
	),
	  'xmonline'=>array(
	  'kind'=>'hidden',
	  'name'=>'xmonline',
	  'msg'=>'',
	  'width'=>'',
	  'arr'=>'',
	  'sql_field'=>'xmonline',
	  'sql_kind'=>'='
	),
	 'zlok'=>array(
	  'kind'=>'hidden',
	  'name'=>'zlok',
	  'sql_field'=>'zlok',
	)
);

if($zlok == ''){
	$sql_temp = $sql_temp." AND zl_okdate='0000-00-00'";
	$field_t = '资料未收回';
	$field = '资料已收回';
}
elseif($zlok == '1'){
	$sql_temp = $sql_temp." AND zl_okdate != '0000-00-00'";
	$field_t = '资料已收回';
	$field = '资料未收回';
}
$sql_temp = " and audit_type!='1007' and online='3'".$sql_temp;
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td{
    font-size: 12px;
    vertical-align: middle;
vnd.ms-excel.numberformat:@;
}
</style>

<?php
if(empty($zl_okdate1)){
	echo "<table><tr><td align=’center‘>请输入要查询月的时间，否则无法打印</td></tr></table>";
	exit;
}
$start = explode("-",$zl_okdate1);
$zl_okdate1 = $start[0].'-'.$start[1].'-01';
$zl_okdate2 = $start[0].'-'.$start[1].'-31';
	$xm_sql	= "SELECT audit_ver,audit_type,zl_okdate FROM `{$dbtable['xm_item']}` WHERE 1 {$sql_temp} AND online='3' AND zl_okdate >= '{$zl_okdate1}' AND zl_okdate <= '{$zl_okdate2}'  ORDER BY id DESC";
	$xm_q = $db->query($xm_sql);
	$num = array();
	while($xm = $db->fetch_array($xm_q)){
		$day_t = (int)date("d",strtotime($xm['zl_okdate']));
		$xm['audit_ver'] == 'QJ' && $xm['audit_ver'] == 'Q2';
		$xm['audit_type'] == '1003' && $xm['audit_type'] == '1002';
		$xm['audit_type'] == '1004' && $xm['audit_type'] == '1002';
		$num[$xm['audit_ver']][$xm['audit_type']][$day_t]++;
	}
		echo '<table border="1">
					<tr>
						<td colspan=33 align="center">北京恩格威认证中心</td>
					</tr>
					<tr>
						<td colspan=33 align="center">回收统计表</td>
					</tr>
					<tr>
					<td colspan=33>打印时'.$zl_okdate1.'到'.$zl_okdate2.'间</td>
					</tr>	
		';
	foreach($num as $k1=>$v1){
		if($k1 == 'Q2'){
			echo "<tr>
				<td>审核标准：".Cache::cache_audit_ver($k1)."</td>
			 </tr>";
			echo "<tr><td>审核类型</td>";
			for($i=1;$i<=31;$i++){							
				echo "<td>".$i."</td>";
			}		
			echo "<td>本月合计</td></tr>";	
			foreach($v1 as $k2=>$v2){
				if($k2 == '1008'){
					echo "<tr><td>初审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};					
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";	
				}
				if($k2 == '1005'){
					echo "<tr><td>复审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};									
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
				if($k2 == '1002'){
					echo "<tr><td>监督</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};								
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
			}
			echo "<tr><td colspan=33></td></tr>";
		}
		if($k1 == 'E2'){
			echo "<tr>
				<td>审核标准：".Cache::cache_audit_ver($k1)."</td>
			 </tr>";
			echo "<tr><td>审核类型</td>";
			for($i=1;$i<=31;$i++){							
				echo "<td>".$i."</td>";
			}		
			echo "<td>本月合计</td></tr>";	
			foreach($v1 as $k2=>$v2){
				if($k2 == '1008'){
					echo "<tr><td>初审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};						
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";	
				}
				if($k2 == '1005'){
					echo "<tr><td>复审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};									
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
				if($k2 == '1002'){
					echo "<tr><td>监督</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};								
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
			}
			echo "<tr><td colspan=33></td></tr>";
		}
		if($k1 == 'S1'){
			echo "<tr>
				<td>审核标准：".Cache::cache_audit_ver($k1)."</td>
			 </tr>";
			echo "<tr><td>审核类型</td>";
			for($i=1;$i<=31;$i++){							
				echo "<td>".$i."</td>";
			}		
			echo "<td>本月合计</td></tr>";	
			foreach($v1 as $k2=>$v2){
				if($k2 == '1008'){
					echo "<tr><td>初审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};						
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";	
				}
				if($k2 == '1005'){
					echo "<tr><td>复审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};									
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
				if($k2 == '1002'){
					echo "<tr><td>监督</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};								
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
			}
			echo "<tr><td colspan=33></td></tr>";
		}
		if($k1 == 'F1'){
			echo "<tr>
				<td>审核标准：".Cache::cache_audit_ver($k1)."</td>
			 </tr>";
			echo "<tr><td>审核类型</td>";
			for($i=1;$i<=31;$i++){							
				echo "<td>".$i."</td>";
			}		
			echo "<td>本月合计</td></tr>";	
			foreach($v1 as $k2=>$v2){
				if($k2 == '1008'){
					echo "<tr><td>初审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};						
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";	
				}
				if($k2 == '1005'){
					echo "<tr><td>复审</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};									
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
				if($k2 == '1002'){
					echo "<tr><td>监督</td>";
					$total = 0;
					for($i=1;$i<=31;$i++){	
						if(empty($v2[$i])){$v2[$i] = '&nbsp;';};								
						echo "<td>".$v2[$i]."</td>";
						$total = $total+$v2[$i];
					}		
					echo "<td>".$total."</td></tr>";
				}
			}
			echo "<tr><td colspan=33></td></tr>";
		}
	}
	
?>		
</table>

















