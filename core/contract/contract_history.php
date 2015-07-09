<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
GrepUtil::InitGP(array('zuzhi_id','audit_ver'));
$iso = $setup_audit_ver[$audit_ver]['iso'];
$rows = $db->query("SELECT iso,ht_id,audit_type  FROM ht_contract_item WHERE zuzhi_id='{$zuzhi_id}' and iso ='{$iso}'");
while($result = $db->fetch_array($rows)){
	$ht = $db->get_one("SELECT htdate FROM ht_contract WHERE id='$result[ht_id]'");
	$result['htdate'] = $ht['htdate'];
	$history_audit []= $result;
}
include TEMP.'header.htm';
?>


<br>
<table  width="200" class="e_table" >
        <tr>
		<td align="center">体系</th>
		<td align="center">审核类型</th>
		<td align="center">合同申请时间</th>
        </tr>
<?php
foreach($history_audit as $v){
$v['audit_type'] = Cache::cache_audit_type($v['audit_type']);
?>
        <tr >
		<td  align="center"><?php echo $v['iso']?></td>
        <td  align="center"><?php echo $v['audit_type']?></td>
		<td  align="center"><?php echo $v['htdate']?></td>
        </tr>

<?php
}
?>
</table>
<?php
include TEMP.'footer.htm';
?>