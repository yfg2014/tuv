<?php
include '../include/globals.php';
include_once '../include/setup/setup_audit_type.php';
include_once '../include/setup/setup_audit_ver.php';
include_once '../include/setup/setup_mark.php';
include_once '../include/setup/setup_product_test.php';

GrepUtil::InitGP(array('width','op'));

$audit_ver = $audit_type = $mark = $product_test = '';
foreach($setup_audit_type as $v){
	if ($v['code'] == '1001' || $v['code'] == '1002' || $v['code'] == '1003' || $v['code'] == '1005'){
              $audit_type .= '<option value="'.$v['code'].'">'.$v['msg'].'</option>';
	}
}

foreach ($setup_mark as $v) {
	if ($v[online] == '1'){
          $mark .= '<input type="checkbox" name="mark_t[]" value="'.$v['code'].'" class="mark_t" onclick="mark_ck(this)"/>'.$v['msg'];
	}
}

if($op == '1'){
	foreach ($setup_audit_ver as $v) {
		if ($v['online'] == '1' and $v['code']!='Q1'){
	            $audit_ver .= '<option value="'.$v['code'].'">'.$v['msg'].'</option>';
		}
	}

	$result = '<table width="'.$width.'"  class="tx e_table" style="margin-bottom:10px;">
	      <input type="hidden" name="htxm_id[]" class="id" value="" />
		  <input type="hidden" name="kind[]" value="1"/>
		  <input type="hidden" name="product[]" value=""/>
	  	  <input type="hidden" name="product_ver[]" value=""/>
	  	  <input type="hidden" name="product_ce[]" value=""/>
	  	  <input type="hidden" name="product_mcs[]" value=""/>
	      <input type="hidden" name="key_part[]" value=""/>
	      <input type="hidden" name="product_test[]" value=""/>
	      <input type="hidden" name="manuid[]" value=""/>
	      <input type="hidden" name="proid[]" value=""/>
	      <input type="hidden" name="manu_address[]" value=""/>
      	  <input type="hidden" name="pro_address[]" value=""/>
      	  <!--<input type="hidden" name="UnitNo[]" value=""/>-->
	      <tr>
	        <td align="right"><div class="txcopy" onclick="cp(this);">(<font style="color:red;cursor:pointer;">同上</font>)标准版本：</div></td>
	        <td><select name="audit_ver[]"  class="audit_ver" style="width:60%;" datatype="Require" msg="标准版本不能为空！" >
	            <option value="">-请选择-</option>'.$audit_ver.'
	          </select>
	          &nbsp;<font color="#FF0000">*</font> </td>
	        <td align="right">审核类型：</td>
	        <td><div style="float:left;width:90%;">
	            <select name="audit_type[]" dataType="Require" msg="审核类型不能为空！">
	              <option value="">-请选择-</option>'.$audit_type.'

	            </select>
	            &nbsp;<font color="#FF0000">*</font></div>
	          <div align="right"><img src="frontEnd/images/button_empty.png" border="0" title="删除" class="rm_tx" style="cursor:pointer;"  onclick="rm(this,1);" /></div></td>
	      </tr>
	      <tr>
	        <td width="25%"  align="right">项目编号：</td>
	        <td></td>
	        <td width="25%" align="right">体系人数：</td>
	        <td width="25%"><input name="iso_people_num[]" class="txrs" value="" type="text" style="width:60%;" datatype="Require" msg="体系人数不能为空！" />
	          <font color="#FF0000">*</font> </td>
	      </tr>
	      <tr>
	        <td width="25%" align="right">(<font  onClick="ShowDlog_Contrant(this)" style="color:red;cursor:pointer;">历次显示</font>)复评次数：</td>
	        <td width="25%"><input type="text" name="re_views[]" style="width:60%;" value="" datatype="Custom" regexp="^\d$" msg="请正确输入复评次数！" />
	          <font color="#FF0000">*</font> </td>
	        <td  align="right">机构转入：</td>
	        <td ><select name="zjg[]" class="zjg" style="width:60%;" onblur="zjg(this);">
	            <option value="">否</option>
	            <option value="1">是</option>
	          </select>
	        </td>
	      </tr>
	      <tr>
			<td align="right">删减条款：</td>
        	<td><input name="shanjiangtiaokuan[]" style="width:60%;" value="" /></td>
        	<td align="right">体系运行时间：</td>
        	<td><input name="run_date[]" style="width:60%;" value="" title="日期格式  1980-03-04" onFocus="return showCalendar(this, \'y-mm-dd\');"/></td>
	  	</tr>
	  	<tr>
		<td align="right">上次初/再认证日期：</td>
        <td><input name="auditEnd_date[]" style="width:60%;" value="" title=\'日期格式  1980-03-04\' onFocus="return showCalendar(this, \'y-mm-dd\');" /></td>
        <td align="right">上次证书到期日期：</td>
        <td><input name="certEnd_date[]" style="width:60%;" value="" title=\'日期格式  1980-03-04\' onFocus="return showCalendar(this, \'y-mm-dd\');"/></td>
	  </tr>
	      <tr>
	        <td align="right">认可标志：</td>
	        <td colspan="3">'.$mark.'
	          <input type="hidden" name="mark[]" class="mark" value="" dataType="Require" msg="认可标志不能为空！">
	        <font color="#FF0000">*</font></td>
	      </tr>
	      <tr >
	        <td  align="right">申请范围：</td>
	        <td colspan="3"><textarea class="sqfw" name="qy_renzhengfanwei[]" style="width:95%;height:80px;"></textarea></td>
	      </tr>
	      <tr class="zjg_content" style="display:none;">
	        <td colspan="4"><table width="100%" class="e_table">
	            <tr>
	              <td width="18%" height="20" align="right">原颁证机构：</td>
	              <td width="33%"><input name="zjg_name[]" style="width:168px;" value="" />
	                <font color="#FF0000">*</font></td>
	              <td width="17%" align="right">原注册日期：</td>
	              <td width="34%"><input name="zjg_sdate[]" value="" type="text" style="width:168px;" onclick="showCalendar(this, \'y-mm-dd\');" />
	                <font color="#FF0000">*</font></td>
	            </tr>
	            <tr>
	              <td height="20" align="right">原证书号：</td>
	              <td><input name="zjg_no[]" style="width:168px;" value="" />
	                <font color="#FF0000">*</font></td>
	              <td align="right">原截止日期：</td>
	              <td><input name="zjg_edate[]" value="" type="text" style="width:168px;" onclick="showCalendar(this, \'y-mm-dd\');" />
	                <font color="#FF0000">*</font></td>
	            </tr>
	            <tr>
	              <td height="20" align="right">上次审核日：</td>
	              <td><input name="zjg_assess_date[]" value="" type="text" style="width:168px;" onclick="showCalendar(this, \'y-mm-dd\');" />
	              </td>
	              <td align="right">&nbsp;</td>
	              <td>&nbsp;</td>
	            </tr>
	          </table></td>
	      </tr>
	      <tr>
	        <td colspan="4" style="height:1px;">&nbsp;</td>
	      </tr>
	    </table>
	';
}else{
	foreach ($setup_product_test as $k=>$v) {
	     $product_test .= '<option value="'.$k.'">'.$v.'</option>';
	}

	$result = '<table width="'.$width.'" class="e_table prod" style="margin-bottom:5px;">
      <input type="hidden" name="htxm_id[]" class="id" value="" />
	  <input type="hidden" name="kind[]" value="2"/>
	  <input type="hidden" name="audit_ver[]" value="P1"/>
	  <input type="hidden" name="zjg[]" value=""/>
	  <input type="hidden" name="shanjiangtiaokuan[]" value=""/>
	  <input type="hidden" name="run_date[]" value=""/>
      <tr>
        <td align="right"><div class="prodcopy">(<font style="color:red;cursor:pointer;" onclick="ccp(this);">同上</font>)认证类别：</div></td>
        <td colspan="3">
        	<div style="float:left;width:90%;"><input type="text" name="certifiedproducts[]" class="tpr" value="" readonly="readonly" style="width:89%;" dataType="Require" msg="认证类别不能为空！" />&nbsp;<font color="#FF0000">*</font>&nbsp;<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="certpro(this);" /><input type="hidden" name="product[]" class="ducts" value="" /></div>
        	<div style="float:right;"><img src="frontEnd/images/button_empty.png" border="0" title="删除" class="rm_prod" style="cursor:pointer;"  onclick="rm(this,2);" /></div>
		</td>
	  </tr>
	  <!-- <tr>
        <td align="right">认证单元：</td>
        <td colspan="3">
        	<input type="text" name="UnitNo[]" value="" style="width:85%;" dataType="Require" msg="认证单元不能为空！" />&nbsp;<font color="#FF0000">*</font>
		</td>
	  </tr>-->
	  <tr>
        <td align="right"><div class="prodcopy">(<font style="color:red;cursor:pointer;" onclick="removed(this);">清空</font>)产品指令：</div></td>
        <td colspan="3">
        	<input type="text" name="productstandards[]" class="stand" value="" readonly="readonly" style="width:85%;" dataType="Require" msg="产品指令不能为空！" />&nbsp;<font color="#FF0000">*</font>
			<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="standards(this);" />
			<input type="hidden" name="product_ver[]" class="dards" value="" />
	    </td>
	  </tr>
	  <tr>
        <td align="right">产品关键件：</td>
        <td colspan="3">
        	<input type="text" name="productpieces[]" class="keypie" value="" readonly="readonly" style="width:85%;"  />
			<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="keypieces(this);" />
			<input type="hidden" name="key_part[]" class="pieces" value="" />
		</td>
	  </tr>
	  <tr>
        <td align="right">实施规则：</td>
        <td colspan="3">
        	<input type="text" name="audit_rule_msg[]" class="audit_rule_msg" value="" readonly="readonly" style="width:85%;"  />
			<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="audit_rule(this);" />
			<input type="hidden" name="audit_rule[]" class="audit_rule" value="" />
		</td>
	  </tr>
		<tr>
	    	<td width="25%"  align="right">项目编号：</td>
	        <td></td>
			<td></td>
			<td></td>
		</tr>
	 <tr style="display:none;">
        <td align="right">检测机构：</td>
        <td colspan="3">
        	<input type="text" name="product_test_msg[]" class="product_test_msg" value="" readonly="readonly" style="width:85%;"  />&nbsp;<font color="#FF0000">*</font>
			<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="testcheck(this);" />
			<input type="hidden" name="product_test[]" class="product_test" value=""/></td>
	  </tr>
	  <tr>
		<td  align="right">认证类型：</td>
		<td>
			<select name="product_ce[]" style="width:60%;">
				<option value="">-请选择-</option>
				<option value="1">CE</option>
				<option value="2">MCS</option>
				<option value="3">CE+MCS</option>
			</select>
		</td>
        <td  align="right">审核类型：</td>
        <td >
            <select name="audit_type[]" style="width:60%;" dataType="Require" msg="审核类型不能为空！">
              <option value="">-请选择-</option>'.$audit_type.'
            </select>
            &nbsp;<font color="#FF0000">*</font>
          </td>
      </tr>
      <tr>
        <td width="25%" align="right">复评次数：</td>
        <td width="25%"><input type="text" name="re_views[]" style="width:60%;" value="" /></td>
        <td width="25%" align="right">体系人数：</td>
        <td width="25%"><input name="iso_people_num[]" class="txrs" value="" type="text" style="width:60%;" /></td>
      </tr>
      <tr>
        <td align="right">认可标志：</td>
        <td colspan="3">'.$mark.'
          <input type="hidden" name="mark[]" class="mark" value="" dataType="Require" msg="认可标志不能为空！">
        <font color="#FF0000">*</font></td>
      </tr>
      <tr >
        <td  align="right">认证产品：</td>
        <td colspan="3"><textarea class="sqfw" name="qy_renzhengfanwei[]" style="width:95%;height:85px;"></textarea></td>
      </tr>
	  <tr>
        <td align="right">制造单位：</td>
        <td colspan="3">
        	<input type="text" name="manufacturingunits[]" class="manu" value="" readonly="readonly" style="width:85%;" />
			<input type="hidden" name="manuid[]" class="maid" value="" />
			<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="companyckeck(this,1);" />
		</td>
	  </tr>
	  <tr>
        <td align="right">制造地址：</td>
        <td colspan="3">
        	<input type="text" name="manu_address[]" class="fact" value="" style="width:90%;" />
		</td>
	  </tr>
	  <tr>
        <td align="right">生产单位：</td>
        <td colspan="3">
        	<input type="text" name="productionunit[]" class="duction" value="" readonly="readonly" style="width:85%;" />
			<input type="hidden" name="proid[]" class="prid" value="" />
			<img src="frontEnd/images/sousuo.gif" style="cursor:pointer;" onclick="companyckeck(this,2);" />
		</td>
	  </tr>
	  <tr>
        <td align="right">生产地址：</td>
        <td colspan="3"><input type="text" name="pro_address[]" class="tionadd" value="" style="width:90%;" /></td>
	  </tr>
      <tr>
        <td colspan="4" style="height:1px;">&nbsp;</td>
      </tr>
    </table>
';
}

$wrap = json_encode($result);
echo $wrap;
?>
