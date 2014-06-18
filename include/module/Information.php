<?php
/*
 * 企业合同项目基本信息
 */
class Information {

	private $db;
	public $dbtable;
	private $id_arr;
	private $width;
	private $height;

	public $company;
	public $contract;
	public $item;
	public $task;
	public $certificate;
	public $finance;

	public function __construct($id_arr= array(),$width = '500',$height = '250',$params = array('company' => array(),'contract' => array(),'item' => array(),'task' => array(),'certificate' => array(),'finance'=>array())){
		//'zuzhi_id'=>$zuzhi_id,'ht_id'=>$ht_id,'xmid'=>$xmid,'taskId'=>$taskId,
		//'company' => array(),'contract' => array(),'item' => array(),'task' => array(),'certificate' => array()
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
		$this->id_arr = $id_arr;
		$this->width = $width;
		$this->height = $height;
		$this->info = null;
		foreach ($params as $k=>$v){
			switch ($k){
				case 'company':
				$this->company = null;
				if($this->id_arr['zuzhi_id'] > '0')$this->CompanyInformation($this->id_arr['zuzhi_id'],$v);
				break;
			case 'contract':
				$this->contract = null;
				if($this->id_arr['ht_id'] != '')$this->ContractInformation(array_unique((array)$this->id_arr['ht_id']),$v);
				break;
			case 'item':
				$this->item = null;
				if($this->id_arr['htxm_id'] > '0')$this->ItemInformation(array_unique((array)$this->id_arr['htxm_id']),$v);
				break;
			case 'certificate':
				$this->certificate = null;
				if($this->id_arr['zuzhi_id'] > '0')$this->CertificateInformation($this->id_arr['zuzhi_id'],$v);
				break;
			case 'task':
				$this->task = null;
				if($this->id_arr['taskId'] > '0')$this->TaskInformation($this->id_arr['taskId'],$v);
				break;
			case 'finance':
			    $this->finance = null;
				if($this->id_arr['ht_id'] != '')$this->FinanceInformation(array_unique((array)$this->id_arr['ht_id']),$v);
				break;
			case 'sampling':
			    $this->sampling = null;
				if($this->id_arr['ht_id'] != '')$this->SamplingInformation(array_unique((array)$this->id_arr['ht_id']),$v);
				break;
			}
		}

		$this->GeneralInformation($params);
	}

/*
 * 企业基本信息
 * @param int $zuzhi_id
 * @return string
 */
	public function CompanyInformation($zuzhi_id,$params){
		$params == array() ? $field = '*' : $field = implode(',',$params);
		$com = $this->db->get_one("SELECT {$field} FROM {$this->dbtable['mk_company']} WHERE id='{$zuzhi_id}'");

		$result = '<table class="c_table" width="100%">';
		$result .= '<tr><td align="right">组织编号：</td><td>'.$com['eientercode'].'</td><td align="right">组织人数：</td><td>'.$com['eiman_amount'].'</td></tr>';
		$result .= '<tr><td align="right">组织名称：</td><td colspan="3"><a href="./index.php?m=company&do=qiyedengji_edit&zuzhi_id='.$com['id'].'" target="_blank">'.$com['eiregistername'].'</a>&nbsp;&nbsp;</td></tr>';
		$result .= '<tr><td align="right">注册地址：</td><td colspan="3">'.$com['eireg_address'].'&nbsp;&nbsp;&nbsp;邮编：'.$com['eiregpostalcode'].'</td></tr>';
		$result .= '<tr><td align="right">通讯地址：</td><td colspan="3">'.$com['eisc_address'].'&nbsp;&nbsp;&nbsp;邮编：'.$com['eiscpostalcode'].'</td></tr>';
		$result .= '<tr><td width="25%" align="right">县/区级地址：</td><td width="25%">'.$com['eiaddress'].'</td>';
		$result .= '<td width="25%" align="right">县/区级代码：</td><td width="25%">'.$com['eiaddress_code'].'</td></tr>';
		$result .= '<tr><td align="right">合同来源：</td><td width="150">'.Cache::cache_htfrom($com['htfrom']).'</td>';
		$result .= '<td align="right">组织联系传真：</td><td>'.$com['eifax'].'</td></tr>';
		$result .= '<tr><td align="right">联 系 人：</td><td width="150">'.$com['eilinkman'].'</td>';
		$result .= '<td align="right">联系人邮箱：</td><td>'.$com['eilinkman_email'].'</td></tr>';
		$result .= '<tr><td align="right">电 &nbsp;&nbsp; 话：</td><td>'.$com['eiphone'].'</td>';
		$result .= '<td align="right">手 &nbsp;&nbsp; 机：</td><td>'.$com['eilinkman_mob'].'</td></tr>';
		$result .= '<tr><td align="right">法 &nbsp;&nbsp; 人：</td><td>'.$com['eifaren'].'</td>';
		$result .= '<td align="right">管理者代表：</td><td>'.$com['eiguandai'].'</td></tr>';
		$result .= '<tr><td align="right">附件管理：</td><td><a href="./index.php?m=company&do=qiyedengji_upload_edit&zuzhi_id='.$com['id'].'" target="_blank"><font color="red">文件上传/下载</font></a></td>';
		$result .= '<td align="right"></td><td></td></tr>';
		$result .= '</table><br/>';


		$sql = "SELECT {$field} FROM {$this->dbtable['mk_company']} WHERE fatherzuzhi_id='{$zuzhi_id}'";
		$query = $this->db->query($sql);

		$result .= '<table class="c_table" width="100%">';
		$result .= '<tr > <td  colspan="3">关联企业信息&gt;&gt;</td></tr>';
		$result .= '<tr bgcolor="#F2F2F2">
		<td width="25%"  align="center">组织编号</td>
		<td width="50%" align="center">组织名称</td>
		<td width="25%" align="center">组织人数</td></tr>';
	   while ($rows = $this->db->fetch_array($query)){
			$result .= '<tr >
		<td width="25%"  align="center">'.$rows[eientercode].'</td>
		<td width="50%" align="center"><a href="./index.php?m=company&do=qiyedengji_edit&zuzhi_id='.$rows[id].'" target="_blank">'.$rows[eiregistername].'</a></td><td width="25%" align="center">'.$rows[eiman_amount].'</td></tr>';
	     }
	    $result .= '</table>';
		$this->company = $result;
	}

/*
 * 合同基本信息
 * @param int $ht_id
 * @return string
 */
	public function ContractInformation($ht_id,$params = array()){
		$params == array() ? $field = '*' : $field = implode(',',$params);
		foreach((array)$ht_id as $v){
			$result .= '<table width="100%" class="c_table"><tr ><td align="left"  colspan="4" >&nbsp;合同编号：'.Cache::cache_htcode($v).'</td></tr>';
			$htrows = $this->db->get_one("SELECT {$field} FROM {$this->dbtable['ht_contract']} WHERE id='{$v}'");
			$result .= '<tr ><td align="left"  colspan="4" >&nbsp;合同备注：'.$htrows['other'].'</td></tr><tr><td colspan="4">';
			$sql = "SELECT * FROM {$this->dbtable['ht_contract_item']} WHERE ht_id='{$v}' ORDER BY kind ASC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
				$htxm = $this->db->get_one("SELECT id FROM {$this->dbtable['xm_item']} WHERE htxm_id='{$rows['id']}' and htxm_id>'0'");
				$htxm['id'] == '' ? $rows['online'] = '<font color="#0000FF">未审批</font>' : $rows['online'] = '<font color="#FF0000">已审批</font>';
				$rows['renzhengfanwei'] = str_replace("\n","<br>",$rows['renzhengfanwei']);
				if($rows['kind'] == '1'){
					$result .= '<table width="99%" class="c_table" style="margin-top:5px">';
					$result .= '<tr><td width="25%"  align="right">认证领域：</td><td width="25%" >'.$rows['iso'].'</td>';
					$result .= '<td width="25%"  align="right">审核类型：</td><td width="25%" >'.Cache::cache_audit_type($rows['audit_type']).'</td></tr>';
					$result .= '<tr><td align="right">风险等级：</td><td>'.Cache::cache_risk($rows['risk']).'</td>';
					$result .= '<td align="right">体系人数：</td><td>'.$rows['iso_people_num'].'</td></tr>';
					$result .= '<tr><td align="right">认可标志：</td><td>'.Cache::cache_mark($rows['mark']).'</td><td></td><td></td></tr>';
					$result .= '<tr><td align="right">审核代码：</td><td>'.$rows['audit_code'].'</td>';
					$result .= '<td align="right">NQA代码：</td><td>'.Cache::cache_nqa_code($rows['audit_code']).'</td></tr>';					
					$result .= '<tr><td align="right">审批范围：</td><td colspan="3"><div style="width:90%; word-wrap: break-word; word-break: break-all;">'.$rows['renzhengfanwei'].'</div></td></tr>';
					$result .= '<tr><td align="right">审批状态：</td><td colspan="3">'.$rows['online'].'</td></tr></table>';
				}else if($rows['kind'] == '2'){
					$result .= '<table width="99%" class="c_table" style="margin-top:5px"><tr><td align="right">认证产品：</td><td colspan="3">'.Cache::cache_product($rows['product']).'</td></tr>';
					$result .= '<tr><td align="right">产品标准：</td><td colspan="3">'.Cache::cache_product_ver($rows['product_ver']).'</td></tr>';
					$result .= '<tr><td align="right">产品关键件：</td><td colspan="3">'.Cache::cache_key_part($rows['key_part']).'</td></tr>';
					$result .= '<tr><td align="right">检测机构：</td><td colspan="3">'.Cache::cache_product_test($rows['product_test']).'</td></tr>';
					$result .= '<tr><td width="25%"  align="right">项目编号：</td><td width="25%" >'.$rows['htxmcode'].'</td>';
					$result .= '<td width="25%" align="right">审核类型：</td><td width="25%" >'.Cache::cache_audit_type($rows['audit_type']).'</td></tr>';
					$result .= '<tr><td align="right">体系人数：</td><td>'.$rows['iso_people_num'].'</td>';
					$result .= '<td align="right">审核代码：</td><td>'.$rows['audit_code'].'</td></tr>';
					$result .= '<tr><td align="right">认可标志：</td><td>'.Cache::cache_mark($rows['mark']).'</td><td align="right"></td><td></td></tr>';
					$result .= '<tr><td align="right">审批范围：</td><td colspan="3"><div style="width:90%; word-wrap: break-word; word-break: break-all;">'.$rows['renzhengfanwei'].'</div></td></tr>';
					$result .= '<tr><td align="right">审批状态：</td><td colspan="3">'.$rows['online'].'</td></tr></table>';
				}
			}
			$result .= '</td></tr></table><br>';
		}
		$this->contract =$result;
	}

/*
 * 项目基本信息
 * @param int $ht_id
 * @return string
 */
	public function ItemInformation($htxm_id,$params = array()){
		$params == array() ? $field = '*' : $field = implode(',',$params);
		foreach((array)$htxm_id as $v){
			$result .= '<table class="c_table" width="100%">';
			$result .= '<tr><td align="center">审核类型</td>';
			$result .= '<td align="center">标准<br>版本</td>';
			$result .= '<td align="center">审核代码</td>';
			$result .= '<td align="center">认证范围</td>';
			$result .= '<td align="center">审核员</td>';
			$result .= '<td align="center">记委会<br>意见</td>';
			$result .= '<td align="center">评定<br>结论</td>';
			$result .= '<td align="center">项目<br>状态</td></tr>';

			$sql = "SELECT {$field} FROM {$this->dbtable['xm_item']} WHERE htxm_id='{$v}' and htxm_id>'0' ORDER BY iso DESC,id ASC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
				$rows['ht_id'] !='' && $ht_id = $rows['ht_id']; //任意取1个ht_id防错
				//取审核员信息
				$empName = '';
				$pr_sql = "SELECT empName FROM xm_auditor WHERE taskId='$rows[taskId]' AND taskId>'0'";
				$pr_q = $this->db->query($pr_sql);
				while ($pr = $this->db->fetch_array($pr_q)){
					$empName == '' ? $empName = $pr['empName'] : $empName = $empName.'<br>'.$pr['empName'];
				}

				if ($rows['evaluatother'] != ''){
					$rows['show'] = "<font onmouseover=\"show_block('$rows[id]','','0.5')\" onmouseout=\"hide_block('$rows[id]')\"><img src=\"frontEnd/images/other.png\" /></font>";
				}else{
					$rows['show'] = '';
				}
				$rows['renzhengfanwei'] = str_replace("\n","<br>",$rows['renzhengfanwei']);
				$result .= '<tr><td width="10%" align="center">'.Cache::cache_audit_type($rows['audit_type']).'</td>';
				$result .= '<td width="6%" align="center">'.$rows['audit_ver'].'</td>';
				$result .= '<td width="10%" align="center">'.str_replace('；', '<br/>', $rows['audit_code']).'</td>';
				$result .= '<td width="40%"><div style="width:90%; word-wrap: break-word; word-break: break-all;">'.$rows['renzhengfanwei'].'</div></td>';
				$result .= '<td width="10%" align="center">'.$empName.'</td>';
				$result .= '<td width="6%" align="center">'.$rows['describes'].'</td>';
				$result .= "<td width=\"6%\" align=\"center\"><div class=\"{$rows[id]} show_block\">".$rows['evaluatother']."</div>".$rows['show']."</td>";
				$result .= '<td width="8%" align="center">'.Cache::cache_item_online($rows['online']).'</td></tr>';
			}
			$result .= '<tr><td height="20" colspan="8"><a href="./index.php?m=audit&do=xm_show&ht_id='.$ht_id.'" target="_blank">更多审核项目信息>></a></td></tr>';
			$result .= '</table><br>';
		}
		$this->item = $result;
	}

/*
 * 任务基本信息
 * @param int $taskId
 * @return string
 */
	public function TaskInformation($taskId,$params = array()){
		$params == array() ? $field = '*' : $field = implode(',',$params);

		$result = '<table class="c_table" width="100%">';
		$result .= '<tr><td align="center">审核类型</td>';
		$result .= '<td align="center">标准<br>版本</td>';
		$result .= '<td align="center">审核代码</td>';
		$result .= '<td align="center">认证范围</td>';
		$result .= '<td align="center">评定通过日期</td>';
		$result .= '<td align="center">评定<br>结果</td>';
		$result .= '<td align="center">项目<br>状态</td></tr>';

		$sql = "SELECT {$field} FROM {$this->dbtable['xm_item']} WHERE taskId='{$taskId}' and taskId>'0' ORDER BY iso DESC";
		$query = $this->db->query($sql);
		while ($rows = $this->db->fetch_array($query)){
			$taskBeginDate = $rows['taskBeginDate'];
			$taskBeginHalfDate = $rows['taskBeginHalfDate'];
			$taskEndDate = $rows['taskEndDate'];
			$taskEndHalfDate = $rows['taskEndHalfDate'];
			$rows['renzhengfanwei'] = str_replace("\n","<br>",$rows['renzhengfanwei']);
			$result .= '<tr><td width="10%" align="center" >'.Cache::cache_audit_type($rows['audit_type']).'</td>';
			$result .= '<td width="5%" align="center">'.$rows['audit_ver'].'</td>';
			$result .= '<td width="10%" align="center">'.str_replace('；', '<br/>', $rows['audit_code']).'</td>';
			$result .= '<td width="48%"><div style="width:90%; word-wrap: break-word; word-break: break-all;">'.$rows['renzhengfanwei'].'</div></td>';
			$rows['approvaldate'] == '0000-00-00' && $rows['approvaldate'] = '';
			$result .= '<td width="12%" align="center">'.$rows['approvaldate'].'</td>';
			$rows['zs_if'] == '0' && $rows['zs_if'] = '';
			$rows['zs_if'] == '1' && $rows['zs_if'] = '通过';
			$rows['zs_if'] == '2' && $rows['zs_if'] = '待定';
			$rows['zs_if'] == '3' && $rows['zs_if'] = '不通过';
			$result .= '<td width="7%" align="center">'.$rows['zs_if'].'</td>';
			$result .= '<td width="10%" align="center">'.Cache::cache_item_online($rows['online']).'</td></tr>';
		}

		$result .= '</table>';

		$result .= '<br><table class="c_table" width="100%">';
		$result .= '<tr><td align="center">审核员</td>';
		$result .= '<td align="center">联系电话</td>';
		$result .= '<td align="center">是否组长</td>';
		$result .= '<td align="center">组内身份</td>';
		$result .= '<td align="center">注册资格</td>';
		$result .= '<td align="center">专业代码</td></tr>';
		$sql = "SELECT {$field} FROM {$this->dbtable['xm_auditor']} WHERE taskId='{$taskId}' and taskId>'0' ORDER BY id ASC";
		$query = $this->db->query($sql);
		while ($rows = $this->db->fetch_array($query)){
			$hr = $this->db->get_one("SELECT phone FROM {$this->dbtable['hr_information']} WHERE id='{$rows['empId']}' ORDER BY id ASC");
			$isLeader = $role = $qualification = $audit_code = array();
			$prsql = "SELECT {$field} FROM {$this->dbtable['xm_auditor_plan']} WHERE auditorId='{$rows[id]}' and auditorId>'0' ORDER BY id ASC";
			$prquery = $this->db->query($prsql);
			while ($pr = $this->db->fetch_array($prquery)){
				$pr['isLeader'] == '1' ? $pr['isLeader'] = '是' : $pr['isLeader'] = '否';
				$isLeader []= $pr['iso'].':'.$pr['isLeader'];
				$role []= $pr['iso'].':'.Cache::cache_hr_role($pr['role']);
				$qualification []= $pr['iso'].':'.Cache::cache_hr_reg_qualification($pr['qualification']);
				$audit_code []= $pr['iso'].':'.$pr['audit_code'];
			}
			$isLeader = implode('<br>',(array)$isLeader);
			$role = implode('<br>',(array)$role);
			$qualification = implode('<br>',(array)$qualification);
			$audit_code = implode('<br>',(array)$audit_code);


			$result .= '<tr><td width="12%" align="center">'.$rows['empName'].'</td>';
			$result .= '<td width="12%" align="left" >'.$hr['phone'].'</td>';
			$result .= '<td width="12%" align="left" >'.$isLeader.'</td>';
			$result .= '<td width="12%" align="left" >'.$role.'</td>';
			$result .= '<td width="12%" align="left" >'.$qualification.'</td>';
			$result .= '<td width="42%" align="left" >'.$audit_code.'</td></tr>';

		}

		$result .= '</table>';

		$result .= '<br><table class="c_table" width="100%">';
		$result .= '<tr><td align="center">审核开始时间</td>';
		$result .= '<td align="center">'.$taskBeginDate.' '.$taskBeginHalfDate.'</td>';
		$result .= '<td align="center">审核结束时间</td>';
		$result .= '<td align="center">'.$taskEndDate.' '.$taskEndHalfDate.'</td>';
		$result .= '</tr></table>';

		$this->task = $result;
	}
/*
 * 证书基本信息
 * @param int $ht_id
 * @return string
 */
	public function CertificateInformation($zuzhi_id,$params = array()){
		$params == array() ? $field = '*' : $field = implode(',',$params);
		//foreach((array)$ht_id as $v){
			//$result .= '<table width="100%" class="c_table"><tr><td align="left">&nbsp;合同编号：'.Cache::cache_htcode($v).'</td></tr></table>';
			$result .= '<table class="c_table" width="100%">';
			$result .= '<tr><td align="center">证书编号</td>';
			$result .= '<td align="center">审核类型</td>';
			$result .= '<td align="center">标准版本</td>';
			$result .= '<td align="center">注册日期</td>';
			$result .= '<td align="center">到期日期</td>';
			$result .= '<td align="center">上报日期</td>';
			$result .= '<td align="center">证书状态</td></tr>';

			$sql = "SELECT {$field} FROM {$this->dbtable['zs_cert']} WHERE zuzhi_id='{$zuzhi_id}' and zuzhi_id>'0' and zsprintdate!='0000-00-00' ORDER BY id DESC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
				$result .= '<tr><td width="16%" align="center"><a href="./index.php?m=certificate&do=cert_show&zsid='.$rows['id'].'" target="_blank">'.$rows['certNo'].'</a></td>';
				$result .= '<td width="14%" align="center">'.Cache::cache_audit_type($rows['audit_type']).'</td>';
				$result .= '<td width="14%" align="center">'.$rows['audit_ver'].'</td>';
				$result .= '<td width="14%" align="center">'.$rows['certStart'].'</td>';
				$result .= '<td width="14%" align="center">'.$rows['certEnd'].'</td>';
				$result .= '<td width="14%" align="center">'.$rows['zsprintdate'].'</td>';
				$result .= '<td width="14%" align="center">'.Cache::cache_Certification_online($rows['online']).'</td></tr>';
			}
			$result .= '</table><br>';
		//}
		$this->certificate = $result;
	}
/*
 * 合同项目收费信息
 * @param int $ht_id
 * @return string
 */
	public function FinanceInformation($ht_id,$params = array()){
		$params == array() ? $field = '*' : $field = implode(',',$params);
		foreach((array)$ht_id as $v){
			$result .= '<table width="100%" class="c_table"><tr><td align="left"  colspan="3">&nbsp;合同编号：'.Cache::cache_htcode($v).'</td></tr>';

			$result .= '<tr><td align="center"  >收费项目</td>';
			$result .= '<td align="center"  >收费类型</td>';
			$result .= '<td align="center" >合同金额</td></tr>';

			$sql = "SELECT {$field} FROM {$this->dbtable['cw_finance_item']} WHERE ht_id='{$v}' and ht_id>'0' ORDER BY id DESC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
				$rows['kind'] == '1' && $rows['kind'] = '体系';
				$rows['kind'] == '2' && $rows['kind'] = '产品';
				$result .= '<tr><td width="30%" align="center">'.Cache::cache_Finance_item($rows['finance_item']).'</td>';
				$result .= '<td width="20%" align="center">'.$rows['kind'].'</td>';
				$result .= '<td width="50%" align="center">'.$rows['contract_money'].'</td></tr>';
			}
			$result .= '</table><table width="100%" class="c_table"><tr><td align="center">体系</td>';
			$result .= '<td align="center">审核类型</td>';
			$result .= '<td align="center">发票号</td>';
			$result .= '<td align="center">发票金额</td>';
			$result .= '<td align="center">开票日期</td>';
			$result .= '<td align="center">是否交完</td></tr>';
			$sql = "SELECT id,audit_type,audit_ver,cw_online FROM {$this->dbtable['xm_item']} WHERE ht_id='{$v}' and ht_id>'0' ORDER BY id DESC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
				$xm_cw[$rows['id']] = array(
					'audit_type'=>Cache::cache_audit_type($rows['audit_type']),
					'audit_ver'=>$rows['audit_ver'],
					'cw_online'=>Cache::cache_Finance_online($rows['cw_online'])
					);
			}
			$sql = "SELECT xmid,invoice,invoicemoney,invoicemoneytime FROM {$this->dbtable['cw_finance_list_ex']} WHERE ht_id='{$v}' and ht_id>'0' ORDER BY id DESC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
				$result .= '<tr><td width="10%" align="center">'.$xm_cw[$rows['xmid']]['audit_ver'].'</td>';
				$result .= '<td width="20%" align="center">'.$xm_cw[$rows['xmid']]['audit_type'].'</td>';
				$result .= '<td width="20%" align="center">'.$rows['invoice'].'</td>';
				$result .= '<td width="20%" align="center">'.$rows['invoicemoney'].'</td>';
				$result .= '<td width="20%" align="center">'.$rows['invoicemoneytime'].'</td>';
				$result .= '<td width="10%" align="center">'.$xm_cw[$rows['xmid']]['cw_online'].'</td></tr>';
			}
			$result .= '</table><br>';
			$v == '' && $result='';
		}
		$this->finance = $result;
	}

/*
 * 产品抽样模块
 * @param int $ht_id
 * @return string
 */
	public function SamplingInformation($ht_id,$params = array()){
		$params == array() ? $field = '*' : $field = implode(',',$params);
		$num = 0;
		foreach((array)$ht_id as $v){
			$result .= '<table width="100%" class="c_table"><tr ><td align="left"  colspan="4" >&nbsp;合同编号：'.Cache::cache_htcode($v).'</td></tr><tr><td colspan="4">';

			$sql = "SELECT * FROM {$this->dbtable['ht_sampling']} WHERE ht_id='{$v}' and ht_id>'0' ORDER BY id ASC";
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query)){
					$num++;
					if($rows['samplingtrue'] == '0')
					{
						$rows['samplingtrue'] = '<font color="red">不合格</font>';
					}elseif($rows['samplingtrue'] == '1')
					{
						$rows['samplingtrue'] = '<font color="blue">合格</font>';
					}
					$htxm = $this->db->get_one("SELECT * FROM {$this->dbtable['ht_contract_item']} WHERE id='{$rows['htxm_id']}'");
					$result .= '<table width="99%" class="c_table" style="margin-top:5px"><tr><td align="right">&nbsp;<font color="red" style="font-size:14px;" class="num">'.$num.'</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;认证产品：</td><td colspan="3">'.Cache::cache_product($htxm['product']).'</td></tr>';
					$result .= '<tr><td align="right">产品标准：</td><td colspan="3">'.Cache::cache_product_ver($htxm['product_ver']).'</td></tr>';
					$result .= '<tr><td align="right">产品关键件：</td><td colspan="3">'.Cache::cache_key_part($htxm['key_part']).'</td></tr>';
					$result .= '<tr><td align="right">检测机构：</td><td colspan="3">'.Cache::cache_product_test($htxm['product_test']).'</td></tr>';
					$result .= '<tr><td width="25%" align="right">认可标志：</td><td>'.Cache::cache_mark($htxm['mark']).'</td>';
					$result .= '<td width="25%" align="right">检查类型：</td><td width="25%" >'.Cache::cache_audit_type($htxm['audit_type']).'</td></tr>';
					$result .= '<tr><td align="right">体系人数：</td><td>'.$htxm['iso_people_num'].'</td>';
					$result .= '<td align="right">专业代码：</td><td>'.$htxm['audit_code'].'</td></tr>';
					$result .= '<tr><td align="right">审批范围：</td><td colspan="3"><div style="width:90%; word-wrap: break-word; word-break: break-all;">'.$htxm['renzhengfanwei'].'</div></td></tr>';
					$result .= '<tr><td align="right">出厂批号：</td><td>'.$rows['samplingcode'].'</td>';
					$result .= '<td align="right">抽样日期：</td><td>'.$rows['samplingdate'].'</td></tr>';
					$result .= '<tr><td align="right">检测项目：</td><td>'.$rows['samplingxm'].'</td>';
					$result .= '<td align="right">样品确认方式：</td><td>'.$rows['samplingmobe'].'</td></tr>';
					$result .= '<tr><td align="right">抽样基数：</td><td>'.$rows['samplingbase'].'</td>';
					$result .= '<td align="right">抽样数量：</td><td>'.$rows['samplingquantity'].'</td></tr>';
					$result .= '<tr><td align="right">检测报告编号：</td><td>'.$rows['testreportcode'].'</td>';
					$result .= '<td align="right">报告日期：</td><td>'.$rows['testreportdate'].'</td></tr>';
					$result .= '<tr><td align="right">检测类别：</td><td>'.$rows['samplingclass'].'</td>';
					$result .= '<td align="right">是否合格：</td><td>'.$rows['samplingtrue'].'</td></tr>';
					$result .= '<tr><td align="right">备注：</td><td colspan="3">'.$rows['other'].'</td></tr></table>';
			}
			$result .= '</td></tr></table><br>';
		}

		$result .= '</td></tr></table><br>';
		$this->sampling = $result;
	}

	public function GeneralInformation($params = array()){
		$width = $this->width;
		$height = $this->height;

		$result = '';
		foreach ($params as $k=>$v){
			switch ($k){
				case 'company':
					$li .= '<li><a href="#tag1">组织信息</a><li>';
					$result .= '<div id="tag1">'.$this->company.'</div>';
					break;
				case 'contract':
					$li .= '<li><a href="#tag2">合同信息</a><li>';
					$result .= '<div id="tag2">'.$this->contract.'</div>';
					break;
				case 'item':
					$li .= '<li><a href="#tag3">历次检查信息</a><li>';
					$result .= '<div id="tag3">'.$this->item.'</div>';
					break;
				case 'certificate':
					$li .= '<li><a href="#tag4">证书信息</a><li>';
					$result .= '<div id="tag4">'.$this->certificate.'</div>';
					break;
				case 'task':
					$li .= '<li><a href="#tag5">任务信息</a><li>';
					$result .= '<div id="tag5">'.$this->task.'</div>';
					break;
				case 'finance':
					$li .= '<li><a href="#tag6">财务收费</a><li>';
					$result .= '<div id="tag6">'.$this->finance.'</div>';
					break;
				case 'sampling':
					$li .= '<li><a href="#tag7">产品抽样</a><li>';
					$result .= '<div id="tag7">'.$this->sampling.'</div>';
					break;
			}
		}

		$result = '<div class="usual" style="width:'.$width.';height:'.$height.';"><ul class="idTabs">'.$li.'</ul>'.$result.'</div>';
		$this->info = $result;
	}
}

?>