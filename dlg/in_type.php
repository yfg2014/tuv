<?php
include '../include/globals.php';
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/TypeChange.php';
include_once S_DIR.'include/setup/setup_type_online.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_product_ver.php';

GrepUtil::InitGP(array('width','type','xmid','bgTaskId'));

$Item = new Item();
if($bgTaskId == '' || $bgTaskId == '0')
{	
	$xmid = str_replace(",","','",$xmid);
	$xm = $Item->toArray("id IN('{$xmid}') ORDER BY iso DESC");
	$type03 = $type06 = $xm;
}else{
	$TypeChange = new TypeChange();
	$type03 = $TypeChange->toArray("bgTaskId='{$bgTaskId}' AND renzhengleixing='03'");

	$type06 = $TypeChange->toArray("bgTaskId='{$bgTaskId}' AND (renzhengleixing='06' or renzhengleixing='07') ORDER BY renzhengleixing ASC");
	
}

if($type == '03'){
	if($type03 == ''){
		$xm = $Item->toArray("id IN(SELECT base_xmid FROM xm_rzlx WHERE bgTaskId='{$bgTaskId}') ORDER BY iso DESC");
		$type03 = $xm;
	}
	foreach($type03 as $v){
		if($xm != '')
		{
			$v['xmid'] = $v['id'];
			$v['audit_ver_bf'] = $v['audit_ver'];
			$v['product_ver_bf'] = $v['product_ver'];
		}else{
			$v['bgid'] = $v['id'];
		}
		if($v['iso'] != 'P')
		{			
           	$audit_ver = '&nbsp; '.$v['audit_ver'].' <input type="hidden" name="audit_ver_bf" value="'.$v['audit_ver_bf'].'">';
           	
           	foreach ($setup_audit_ver as $vk) {
				if ($vk['iso'] == $v['iso'] and $vk['code'] != $v['audit_ver']) {
					$audit_ver_af = '<input type="radio" name="audit_ver_af" value="'.$vk['code'].'" checked="checked" />'.$vk['msg'].' &nbsp;';
				}
			}  
		}else{
			$v['product_ver_msg'] = Cache::cache_product_ver($v['product_ver']);
			$audit_ver = '&nbsp; '.$v['product_ver_msg'].' <input type="hidden" name="product_ver_bf[]" value="'.$v['product_ver_bf'].'" />';
			
			foreach ($setup_product_ver as $vk) {
				if ($vk['product'] == $v['product'] and $vk['code'] != $v['product_ver']) {
					$audit_ver_af = '<input type="radio" name="product_ver_af[]" value="'.$vk['code'].'" checked="checked" />'.$vk['msg'].' &nbsp;';
				}
			}
		}
		$result = $result.'
		<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
			<input type="hidden" name="id03[]" value="'.$v['bgid'].'" />
			<input type="hidden" name="xmid03[]" value="'.$v['xmid'].'" />
			<tr bgcolor="#FFFFFF">
		        <td width="20%" height="25" align="right"><font color="#003399">原标准：</font></td>
		        <td width="80%">'.$audit_ver.'
		        </td>
		    </tr>
		    <tr bgcolor="#FFFFFF">
		        <td height="25" align="right"><font color="#003399">新标准：</font></td>
		        <td>&nbsp;'.$audit_ver_af.'</td>
		    </tr>
		</table>';
	}
}elseif($type == '06'){		
//范围变更 
	if($type06 == ''){
		$xm = $Item->toArray("id IN(SELECT base_xmid FROM xm_rzlx WHERE bgTaskId='{$bgTaskId}') ORDER BY iso DESC");
		$type06 = $xm;
	}
	foreach($type06 as $v){
		if($xm != '')
		{
			$v['xmid'] = $v['id'];
			$v['audit_code_af'] = $v['audit_code_bf'] = $v['audit_code'];
			$v['renzhengfanwei_bf'] = $v['renzhengfanwei_af'] = $v['renzhengfanwei'];
		}else{
			$v['bgid'] = $v['id'];
		}
		if($v['iso'] == 'P'){
			$v['product'] = Cache::cache_product($v['product']);
			$product = '<td width="20%" height="25" align="right"><font color="#003399">认证产品：</font></td><td>'.$v['product'].'</td>';
		}else{
			$product = '<td width="20%" height="25" align="right"><font color="#003399">认证领域：</font></td><td>'.$v['iso'].'</td>';
		}
		
		$sel1 = $sel2 = '';
		switch($v['changerange'])
		{
			case '1': $sel1 = 'selected="selected"';break;
			case '2': $sel2 = 'selected="selected"';break;
		}

		$result = $result.'
		<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
			<input type="hidden" name="id06[]" value="'.$v['bgid'].'" />
			<input type="hidden" name="xmid06[]" value="'.$v['xmid'].'" />
			<tr bgcolor="#FFFFFF">'.$product.'</tr>
		    <tr bgcolor="#F2F2F2">
		        <td width="20%" height="25" align="right"><font color="#003399">扩大缩小：</font></td>
		        <td width="80%">
		            <select name="changerange[]" width="100">
        				<option value="0">不变</option>
						<option value="1" '.$sel1.'>扩大</option>
						<option value="2" '.$sel2.'>缩小</option>
        			</select> <font color="#FF0000">*</font>
		        </td>
		    </tr>
		    <tr bgcolor="#FFFFFF">
		        <td height="25" align="right"><font color="#003399">原来代码：</font></td>
		        <td><input name="audit_code_bf[]" type="text" style="width:400px;" readonly="readonly" value="'.$v['audit_code_bf'].'" /></td>
		    </tr>
		    <tr bgcolor="#FFFFFF">
		        <td height="25" align="right"><font color="#003399">扩缩代码：</font></td>
		        <td><input name="audit_code_cg[]" type="text" style="width:400px;" value="'.$v['audit_code_cg'].'" /></td>
		    </tr>
			<tr bgcolor="#FFFFFF">
		        <td height="25" align="right"><font color="#003399">变更后代码：</font></td>
		        <td><input name="audit_code_af[]"  type="text" style="width:400px;" value="'.$v['audit_code_af'].'" />
		          <font color="#FF0000">*</font></td>
		    </tr>
			<tr bgcolor="#FFFFFF">
			<td height="10" colspan="2">&nbsp; </td>
			</tr>
		    <tr bgcolor="#FFFFFF">
		        <td height="17" align="right"><font color="#003399">原来范围：</font></td>
		        <td><textarea name="renzhengfanwei_bf[]" readonly="readonly" style="width:450px;height:80px;">'.$v['renzhengfanwei_bf'].'</textarea></td>
		    </tr>
		    <tr bgcolor="#FFFFFF">
		        <td height="17" align="right"><font color="#003399">扩缩范围：</font></td>
		        <td><textarea name="renzhengfanwei_cg[]" style="width:450px;height:80px;">'.$v['renzhengfanwei_cg'].'</textarea></td>
		    </tr>
		    <tr bgcolor="#FFFFFF">
		        <td height="30" align="right"><font color="#003399">变更后范围：</font></td>
		        <td><textarea name="renzhengfanwei_af[]" style="width:450px;height:80px;">'.$v['renzhengfanwei_af'].'</textarea>
		          <font color="#FF0000">*</font></td>
		    </tr>
		    <tr bgcolor="#FFFFFF">
		    	<td height="25" colspan="2"><span style="color:#FF0000;">&nbsp;说明：专业代码之间用 ； 分隔。</span></td>
		    </tr>
		</table>';
	}
}

$wrap = json_encode($result);
echo $wrap;
?>
