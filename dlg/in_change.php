<?php
include '../include/globals.php';
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/module/Change.php';
include_once S_DIR.'include/setup/setup_changeitem.php';
include_once S_DIR.'include/setup/setup_zs_stop.php';
include_once S_DIR.'include/setup/setup_zs_revocation.php';
include_once SET_DIR.'setup_organize_information.php';

GrepUtil::InitGP(array('width','type','zsid','bgTaskId'));

$Certificate = new Certificate();
if($bgTaskId == '' || $bgTaskId == '0')
{	
	$zsid = str_replace(",","','",$zsid);
	$rows = $Certificate->toArray("id IN('{$zsid}') ORDER BY iso DESC");

}else{
	$Change = new Change(); 
	$rows01 = $Change->toArray("bgTaskId='{$bgTaskId}' AND changeitem='01'");
}

if($type == '01'){ 		
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">新组织名称：</font></td>
	        <td width="80%"><input id="input01" name="eiregistername" style="width:80%;" value="'.$cg['eiregistername'].'"> <font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0201'){
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="13%"><font color="#003399">新注册地址：</font></td>
	        <td width="87%"><input id="input0201" name="eireg_address" style="width:70%;" value="'.$cg['eireg_address'].'">&nbsp;&nbsp;<font color="#003399">邮编：</font><input type="text" name="eiregpostalcode" id="eiregpostalcode" value="'.$cg['eiregpostalcode'].'"  style="width:15%;"/> <img src="frontEnd/images/sousuo.gif" onClick="postcode_dlg(1);" style="cursor:pointer;"><font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0202'){
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="13%"><font color="#003399">新通讯地址：</font></td>
	        <td width="87%"><input id="input0202" name="eisc_address" style="width:70%;" value="'.$cg['eisc_address'].'">&nbsp;&nbsp;<font color="#003399">邮编：</font><input type="text" name="eiscpostalcode" id="eiscpostalcode" value="'.$cg['eiscpostalcode'].'"  style="width:15%;"/> <img src="frontEnd/images/sousuo.gif" onClick="postcode_dlg(2);" style="cursor:pointer;"><font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0203'){
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="13%"><font color="#003399">新生产地址：</font></td>
	        <td width="87%"><input id="input0203" name="eipro_address" style="width:70%;" value="'.$cg['eipro_address'].'">&nbsp;&nbsp;<font color="#003399">邮编：</font><input type="text" name="eipropostalcode" id="eipropostalcode" value="'.$cg['eipropostalcode'].'"  style="width:15%;"/> <img src="frontEnd/images/sousuo.gif" onClick="postcode_dlg(3);" style="cursor:pointer;"><font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0204'){
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">新组织人数：</font></td>
	        <td width="80%"><input id="input0204" name="eiman_amount" style="width:20%;" value="'.$cg['eiman_amount'].'"> <font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0205'){
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">新体系人数：</font></td>
	        <td width="80%"><input id="input0205" name="iso_people_num" style="width:20%;" value="'.$cg['iso_people_num'].'"> <font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0206'){
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">新法人：</font></td>
	        <td width="80%"><input id="input0206" name="eifaren" style="width:20%;" value="'.$cg['eifaren'].'"> <font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '0207'){
	$selected='';
	foreach($setup_organize_information as $v){
		$information .= '<option value="'.$v['code'].'" >'.$v['code'].'-'.$v['msg'].'</option>';
	}
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">新组织性质：</font></td>
	        <td width="80%">
			<select id="input0207" name="eikind" style="width:140px;">
				<option value="">-请选择-</option>
				'.$information.'
	        </select>
			<font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '03'){
	foreach($setup_zs_stop as $v){
		$stop .= '<option value="'.$v['code'].'" >'.$v['msg'].'</option>';
	}
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">暂停原因：</font></td>
			<td width="30%" align="left">
			<select id="zt_changereason" name="zt_changereason" style="width:140px;">
				<option value="" ></option>
				'.$stop.'
			</select>
			</td>
			<td align="right" width="20%"><span style="cursor:pointer;color:red;" onclick="get_zs_zanting_edate();">(默认)</span><font color="#003399">暂停到期时间：</font></td>
			<td align="left" width="30%"><input id="zs_zanting_edate" name="zs_zanting_edate" style="width:80%;" value="'.$cg['zs_change_date'].'" onfocus="showCalendar(this, \'y-mm-dd\');">
			<font color="#FF0000">*</font></td>
		</tr>
	</table>';
}elseif($type == '05'){
	foreach($setup_zs_revocation as $v){
		$revocation .= '<option value="'.$v['code'].'" >'.$v['msg'].'</option>';
	}
	$result = '<table class="c_table" width="'.$width.'" style="margin-top:10px;margin-bottom:10px;">
		<tr>
			<td height="24" align="right" width="20%"><font color="#003399">撤销原因：</font></td>
			<td width="80%" align="left">
			<select id="cx_changereason" name="cx_changereason" style="width:140px;">
				<option value="" ></option>
				'.$revocation.'
			</select>
			</td>	
		</tr>
	</table>';
}

$wrap = json_encode($result);
echo $wrap;
?>
