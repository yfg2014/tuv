<?php
/**
 * 维护项目
 */

class MaintenanceItem {
	private $db;
	private $Task;
	private $Item;
	private $Complex;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
		$Company = new Company();
		$ContractItem = new ContractItem();
		$Item = new Item();
		$Task = new Task();
		$Complex = new Complex();
		$Certificate = new Certificate();
		$AssessmentItem = new AssessmentItem();
		$this->Company = $Company;
		$this->ContractItem = $ContractItem;
		$this->Task = $Task;
		$this->Item = $Item;
		$this->Complex = $Complex;
		$this->Certificate = $Certificate;
		$this->AssessmentItem = $AssessmentItem;
		$this->zsid = '';
	}

	private function Converter($time,$i) {
		$rows = explode('-',$time);
		$mktime = mktime(0,0,0,$rows['1']+$i,$rows['2'],$rows['0']);
		$result = date("Y-m-d",$mktime);

		return $result;
	}

	private function GetPlanningDate($xmid) {
		$value = $this->Item->query($xmid);
		if($value['actualtaskEndDate'] < $value['finalItemDate'] or $value['finalItemDate']=='0000-00-00') {
			//监督审核的时候
			$PlanDate = $this->Converter($value['actualtaskEndDate'],9); //审核结束时间+9月
			$FinalDate = $this->Converter($value['actualtaskEndDate'],12); //审核结束时间+12月
		}else{
			//暂停的时候
			$PlanDate = $this->Converter($value['finalItemDate'],9); //最后监审时间+9月
			$FinalDate = $this->Converter($value['finalItemDate'],12); //最后监审时间+12月
		}
		
		$result['auditplandate'] = $PlanDate;
		$result['finalItemDate'] = $FinalDate;	

		return $result;

	}

	private function AuditType($xmid) {
		$rows = $this->Item->query($xmid,array('audit_type','kind'));

		$result = '';
		if ($rows['audit_type'] == '1008' || $rows['audit_type'] == '1005' || $rows['audit_type'] == '1001') {
			$result = '1002';
		}elseif ($rows['audit_type'] == '1002') {
			$result = '1003';
		}elseif ($rows['audit_type'] == '1003' && $rows['kind'] == '2') {
			$result = '1004';
		}elseif ($rows['audit_type'] == '1003' || $rows['audit_type'] == '1004') {
			$result = '01';
		}

		return $result;
	}

	private function NextItem ($xmid,$audit_type) {
		$rows = $this->Item->query($xmid);
		$value = $this->GetPlanningDate($xmid);

		$params = array(
			'zuzhi_id' => $rows['zuzhi_id'],
			'ht_id' => $rows['ht_id'],
			'htxm_id' => $rows['htxm_id'],
			'zsid' => $this->zsid,
			'htfrom' => $rows['htfrom'],
			'iso' => $rows['iso'],
			'audit_ver' => $rows['audit_ver'],
			'renzhengfanwei' => $rows['renzhengfanwei'],
			'audit_code' => $rows['audit_code'],
			'audit_type' => $audit_type,
			'auditplandate' => $value['auditplandate'],
			'finalItemDate' => $value['finalItemDate'],
			'kind' => $rows['kind'],
			'product' => $rows['product'],
			'product_ver' => $rows['product_ver'],
			'online' => '5',
			'cj_ren' => $_SESSION['username'],
			'cj_time' => date("Y-m-d"),
		);

		$result = $this->Item->add($params);

		return $result;
	}

	private function ComplexItem($xmid) {
		$rows = $this->Item->query($xmid);
		$zs = $this->Certificate->query($rows['zsid'],array('certNo','certEnd','online'));
		$qy = $this->db->get_one("SELECT eiarea_code FROM {$this->Company->dbtable['mk_company']} WHERE id='$rows[zuzhi_id]'");
		$xm_item = $this->db->get_one("SELECT taskEndDate FROM {$this->dbtable['xm_item']} WHERE htxm_id='$rows[htxm_id]' AND audit_type!='1007' ORDER BY id ASC LIMIT 1");
		$params = array(
			'ht_id' => $rows['ht_id'],
			'xmid' => $rows['id'],
			'zuzhi_id' => $rows['zuzhi_id'],
			'htxm_id' => $rows['htxm_id'],
			'eiarea_code' => $qy['eiarea_code'],
			'zsid' => $this->zsid,
			'certNo' => $zs['certNo'],
			'htfrom' => $rows['htfrom'],
			'iso' => $rows['iso'],
			'audit_ver' => $rows['audit_ver'],
			'zsFinallyDate' => $zs['certEnd'],
			'actualtaskEndDate' => $rows['actualtaskEndDate'],
			'kind' => $rows['kind'],
			'zs_online' => $zs['online'],
			's_audit_date' =>$xm_item['taskEndDate'],
			'zd_ren' => $_SESSION['username'],
			'zd_date' => date("Y-m-d"),
		);

		$result = $this->Complex->save('', $params);

		return $result;
	}

	private function NewCertificate($pdid){
		$rows = $this->db->get_one("SELECT id,zsprintdate FROM {$this->Certificate->dbtable['zs_cert']} WHERE pdid='{$pdid}'");
		if($rows['zsprintdate'] == '0000-00-00' or $rows['zsprintdate'] == ''){
			$result = $this->AssessmentItem->query($pdid);
			$htxm = $this->ContractItem->query($result['htxm_id']);
			$com = $this->Company->GetCompany($result['zuzhi_id']);
			if($result['ifchangecert'] == '1' && $result['zsid'] != '0')
			{
				$zs = $this->Certificate->query($result['zsid'],array('certNo','certStart','certEnd','firstDate','renewaldate'));
				 $result['renewaldate'] = $result['approvaldate'];
				 $result['certNo'] = $zs['certNo'];
				 $result['firstDate'] = $zs['firstDate'];
				 $result['approvaldate'] = $zs['certStart'];
				 $result['certEnd'] = $zs['certEnd'];
			}

			$params = array(
				'pdid' => $pdid,
				'xmid' => $result['xmid'],
				'htxm_id' => $result['htxm_id'],
				'zuzhi_id' => $result['zuzhi_id'],
				'ht_id' => $result['ht_id'],
				'taskId' => $result['taskId'],
				'htfrom' => $result['htfrom'],
				'audit_type' => $result['audit_type'],
				'fatherzuzhi_id' => $result['zuzhi_id'],
				'eiregistername' => $com['eiregistername'],
				'eiregistername_e'=> $com['eiregistername_e'],
				'manu_company' => Cache::cache_company($htxm['manuid']),
				'pro_company' => Cache::cache_company($htxm['proid']),
				'zs_address'=> $com['zs_address'] == '' ? $com['eireg_address'] : $com['zs_address'],
				'zs_address_e' => $com['zs_address_e'] == '' ? $com['eireg_address_e'] : $com['zs_address_e'],
				'zs_postalcode'=>$com['zs_postalcode'] == '' ? $com['eiregpostalcode'] : $com['zs_postalcode'],
				'manu_address'=> $htxm['manu_address'],
				'pro_address'=> $htxm['pro_address'],
				'audit_code' => $result['audit_code'],
				'kind' => $result['kind'],
				'iso' => $result['iso'],
				'mark' => $htxm['mark'],
				'audit_ver' => $result['audit_ver'],
				'firstDate' => $result['firstDate'],
				'certStart' => $result['approvaldate'],
				'certEnd' => $result['certEnd'],
				'coverFields' => $result['renzhengfanwei'],
				'certNo' => $result['certNo'],
				'renewaldate' => $result['renewaldate'],
				'product' => $result['product'],
				'product_ver' => $result['product_ver'],
				'online' => '01',
			);

			$id = $this->Certificate->save($rows['id'],$params);
			$this->AssessmentItem->update($pdid,array('zsid'=>$id));
			$this->Item->update($result['xmid'],array('zsid'=>$id));
			DBUtil::update_tb($this->db, $this->dbtable['ht_repeat_contract'], array('zsid'=>$id), "htxm_id='{$result['htxm_id']}'");
			if($result['audit_type'] == '1008')
			{
				DBUtil::update_tb($this->db, $this->dbtable['xm_item'], array('zsid'=>$id), "htxm_id='{$result['htxm_id']}' and audit_type='1007'");
			}
			$this->ContractItem->update($result['htxm_id'],array('zsid'=>$id));
			if($result['ifchangecert'] == '1')
			{
				DBUtil::update_tb($this->db, $this->dbtable['xm_item'], array('zsid'=>$id), "htxm_id='{$result['htxm_id']}' and online='5'");
			}
			$this->zsid = $id;
		}else{
			$this->zsid = $rows['id'];
		}
	}

	private function BackCertificate($pdid)
	{
		$zs = $this->db->get_one("SELECT id,zsprintdate FROM {$this->Certificate->dbtable['zs_cert']} WHERE pdid='{$pdid}'");
		if($zs['zsprintdate'] == '0000-00-00' || $zs['zsprintdate'] == '')
		{
			DBUtil::Del($this->db, $this->Certificate->dbtable['zs_cert'], "id='{$zs['id']}'");
			$rows = $this->AssessmentItem->query($pdid);
			$result = $this->db->get_one("SELECT zsid FROM {$this->dbtable['xm_item']} WHERE htxm_id='{$rows['htxm_id']}' and zsid!='{$rows['id']}' ORDER BY id DESC");
			if($result['zsid'] > '0')
			{
				$this->AssessmentItem->update($pdid,array('zsid'=>$result['zsid']));
				$this->Item->update($rows['xmid'],array('zsid'=>$result['zsid']));
				$this->ContractItem->update($rows['htxm_id'],array('zsid'=>$result['zsid']));
				DBUtil::update_tb($this->db, $this->dbtable['ht_repeat_contract'], array('zsid'=>$result['zsid']), "htxm_id='{$rows['htxm_id']}'");
				DBUtil::update_tb($this->db, $this->dbtable['xm_item'], array('zsid'=>$result['zsid']), "htxm_id='{$rows['htxm_id']}' and online='5'");
			}
		}
	}

	public function Maintenance($pdid) {
		$assessment = $this->AssessmentItem->query($pdid,array('xmid','zsid','audit_type','ifchangecert','audit_code','renzhengfanwei'));

		if($assessment['ifchangecert'] == '1'){
			$this->NewCertificate($pdid);
		}elseif($assessment['audit_type'] == '1008' || $assessment['audit_type'] == '1001' || $assessment['audit_type'] == '1005'){
			$this->NewCertificate($pdid);
		}else{
			$this->zsid = $assessment['zsid'];
		}

		if($assessment['xmid'] != '0' && $assessment['xmid'] != ''){
			$rows = $this->Item->query($assessment['xmid'],array('zuzhi_id','htxm_id','iso','product'));
			$audit_type = $this->AuditType($assessment['xmid']);

			$result = '';
			if ($audit_type == '1002' || $audit_type == '1003' || $audit_type == '1004') {
				$result = $this->db->get_one("SELECT id,online FROM {$this->dbtable['xm_item']} WHERE htxm_id='{$rows['htxm_id']}' and audit_type='{$audit_type}'");
				if ($result == '') {
					$this->NextItem($assessment['xmid'],$audit_type);
					LogRW::logWriter($rows['zuzhi_id'], '生成监督提醒：'.$rows['iso'].' '.Cache::cache_product($rows['product']).' '.Cache::cache_audit_type($audit_type));
				}elseif($result['online'] == '9'){
					$value = $this->GetPlanningDate($assessment['xmid']);
					DBUtil::update_tb($this->db, $this->dbtable['xm_item'], array('audit_code' => $assessment['audit_code'], 'renzhengfanwei' => $assessment['renzhengfanwei'], 'auditplandate' => $value['auditplandate'],'finalItemDate' => $value['finalItemDate'],'online'=>'5'), "id='{$result['id']}'");
					LogRW::logWriter($rows['zuzhi_id'], '恢复监督提醒：'.$rows['iso'].' '.Cache::cache_product($rows['product']).' '.Cache::cache_audit_type($audit_type));
				}
			}elseif ($audit_type == '01') {
				$result = $this->Complex->toArray("htxm_id='{$rows['htxm_id']}'",array('id'));
				if ($result == '') {
					$this->ComplexItem($assessment['xmid']);
					LogRW::logWriter($rows['zuzhi_id'], '生成下再认证提醒'.$rows['iso'].' '.Cache::cache_product($rows['product']));
				}
			}
			return true;
		}
	}

	public function re_assess($pdid) {
		$db = $this->db;
		$assessment = $this->AssessmentItem->query($pdid,array('xmid','zsid','audit_type','ifchangecert'));
		$this->BackCertificate($pdid);

		if($assessment['xmid'] != '0' && $assessment['xmid'] != ''){
			$rows = $this->Item->query($assessment['xmid'],array('zuzhi_id','htxm_id','iso','product'));
			$audit_type = $this->AuditType($assessment['xmid']);

			if ($audit_type == '1002' || $audit_type == '1003' || $audit_type == '1004') {
				$result = $db->get_one("SELECT id,audit_type,product FROM `{$this->Item->dbtable['xm_item']}` WHERE htxm_id='{$rows['htxm_id']}' and audit_type='{$audit_type}' and online='5' LIMIT 0,1");
				if ($result['id'] != '') {
					DBUtil::update_tb($this->db, $this->dbtable['xm_item'], array('online'=>'9'), "id='{$result['id']}'");
					//DBUtil::Del($db, $this->Item->dbtable['xm_item'], "id='{$result['id']}'");
					LogRW::logWriter($rows['zuzhi_id'], '撤销监督提醒：'.$rows['iso'].' '.Cache::cache_product($result['product']).' '.Cache::cache_audit_type($result['audit_type']));
				}
			}elseif ($audit_type == '01') {
				$result = $db->get_one("SELECT id,certNo FROM `{$this->Item->dbtable['ht_repeat_contract']}` WHERE htxm_id='{$rows['htxm_id']}' LIMIT 0,1");
				if ($result['id'] != '') {
					DBUtil::Del($db, $this->Item->dbtable['ht_repeat_contract'], "id='{$result['id']}'");
					LogRW::logWriter($rows['zuzhi_id'], '删除再认证提醒：'.Cache::cache_audit_type($rows['audit_type']).' '.Cache::cache_product($rows['product']).' '.$result['certNo']);
				}
			}
			return true;
		}
	}

}
?>