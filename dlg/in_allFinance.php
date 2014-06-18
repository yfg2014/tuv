<?php
include '../include/globals.php';
include '../include/module/fees_finance.php';
GrepUtil::InitGP(array('zuzhi_id','ht_id'));

if($zuzhi_id != ""){
		$s = new fees_finance();
		$where = "zuzhi_id='{$zuzhi_id}' and zsid in(SELECT id FROM $dbtable[zs_cert] where zuzhi_id='{$zuzhi_id}' and online!='01' and online!='04' or online!='03') ORDER BY ht_id DESC";
		$xm_item =  $s->GetXmList($where,array('id','audit_type','iso','renzhengfanwei','cw_online'));
		$result .= '<br /><table width="100%" class="e_table">';
		foreach ($xm_item as $k=>$v) {
			$kk = $k.'k';
			if($v['audit_type']!='1007'){
			$v['audit_type'] = Cache::cache_audit_type($v['audit_type']);
//			$v['product'] = Cache::cache_product($v['product']);
			if($v['cw_online'] == '1'){
				$xmid_checked = 'checked="checked"';
				$xmid_disabled = 'disabled="disabled"';
				$cw_online_xmid_checked = 'checked="checked"';
				$cw_online_xmid_disabled = 'disabled="disabled"';
				$group_ck = '';
			}else{
				$xmid_checked = '';
				$xmid_disabled = '';
				$cw_online_xmid_disabled = '';
				$cw_online_xmid_checked = '';
				$group_ck = 'class="group_ck"';
			}
			if(in_array($v['id'],$cwxmid)){
				$xmid_checked = 'checked="checked"';
				$xmid_disabled = '';
				$cw_online_xmid_disabled = '';
				$group_ck = 'class="group_ck"';
			}
			if ($v['renzhengfanwei'] != ''){
				$v['show'] = "<font onmouseover=\"show_block('$kk')\" onmouseout=\"hide_block('$kk')\"><img src=\"frontEnd/images/other.png\" /></font>";
			}else{
				$v['show'] = '';
			}
			$result .= '<tr>  			
				<td align="center">
				<input type="checkbox" name="xmid[]" value="'.$v['id'].'"'.$group_ck.$xmid_checked.$xmid_disabled.' />
				</td>
		        <td align="center">'.$v['iso'].'</td>		        
		        <td align="center">'.$v['audit_type'].'</td>
		        <td align="center" width="10%"><div class="'.$kk.' show_block">'.$v['renzhengfanwei'].'</div>'.$v['show'].'</td>
				<td align="center">
				<input type="checkbox" name="cw_online_xmid[]" value="'.$v['id'].'"'.$cw_online_xmid_checked.$cw_online_xmid_disabled.' />
				</td>
    		</tr>
			';
			}
		}
		$result .= '</table>';
}

$wrap = json_encode($result);
echo $wrap;
?>
