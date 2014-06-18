<?php

/**
 * 项目评定
 */
class AssessmentItem {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['pd_xm'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['pd_xm'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['pd_xm'], "id='{$id}'");
		return true;
	}

	public function query($pdid,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['pd_xm']} WHERE id='{$pdid}'");

		return $result;
	}
	
	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = $db->query("SELECT {$field} FROM {$this->dbtable['pd_xm']} WHERE {$where}");
		while ($rows = $db->fetch_array($sql)) {
			if ($rows['htxm_id'] != '')$form = $db->get_one("SELECT mark FROM `{$this->dbtable['ht_contract_item']}` WHERE id='{$rows['htxm_id']}' LIMIT 1");
			$rows['mark'] = $form['mark'];
			$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
			$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
			$rows['assessmentdate'] = Cache::cache_time_value($rows['assessmentdate']);
			$rows['approvaldate'] = Cache::cache_time_value($rows['approvaldate']);
			$result[] = $rows;
		}

		return $result;
	}
	
	public function buildItem($xmid)
	{
		$db = $this->db;
		$rows = $db->get_one("SELECT * FROM {$this->dbtable['xm_item']} WHERE id='{$xmid}'");
		
		$params = array(
			'xmid' => $rows['id'],
			'htxm_id' => $rows['htxm_id'],
			'taskId' => $rows['taskId'],
			'zsid' => $rows['zsid'],
			'ht_id' => $rows['ht_id'],
			'zuzhi_id' => $rows['zuzhi_id'],
			'htfrom' => $rows['htfrom'],
			'renzhengfanwei' => $rows['renzhengfanwei'],
			'iso' => $rows['iso'],
			'audit_ver' => $rows['audit_ver'],
			'audit_code' => $rows['audit_code'],
			'audit_type' => $rows['audit_type'],
			'product' => $rows['product'],
			'product_ver' => $rows['product_ver'],
			'to_assess_date' => $rows['to_assess_date'],
			'ifchangecert' => $rows['ifchangecert'],
			'kind' => $rows['kind'],
		);
		
		$this->save($xmid,$params);
	}
	
	public function backItem($xmid)
	{
		$db = $this->db;
		$rows = $db->get_one("SELECT * FROM {$this->dbtable['pd_xm']} WHERE xmid='{$xmid}'");
		if ((int)$rows['id'] > 0) {
			$this->delete($rows['id']);
			LogRW::logWriter($rows['zuzhi_id'], '项目评定删除：'.$rows['iso'].' '.Cache::cache_audit_type($rows['audit_type']));
		}
	}

	public function save($xmid,$params){
		$db = $this->db;
		$rows = $db->get_one("SELECT * FROM {$this->dbtable['pd_xm']} WHERE xmid='{$xmid}'");
		if ((int)$rows['id'] > 0 && $rows['audit_type'] != '1007') {
			$id = $this->update($rows['id'],$params);
			LogRW::logWriter($params['zuzhi_id'], '项目评定修改：'.$params['iso'].' '.Cache::cache_audit_type($params['audit_type']));
		} elseif ((int)$rows['id'] == 0 && $rows['audit_type'] != '1007') {
			$id = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '项目评定添加：'.$params['iso'].' '.Cache::cache_audit_type($params['audit_type']));
		}

		return $id;
	}

	public function listElement($params = array(),$order = ' ORDER BY id DESC') {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['pd_xm']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['pd_xm']}`
						WHERE 1 $search $order";
		try {
			$page = new ListAssessment($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			exit($e->error_msg());
		}
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class ListAssessment extends Page {

	protected function resultFilter($result) {
		$result['zs_if'] = Cache::setup_pd_online($result['zs_if']);
		$result['assessmentdate'] = Cache::cache_time_value($result['assessmentdate']);
		$result['to_assess_date'] = Cache::cache_time_value($result['to_assess_date']);
		
		if ($result['xmid'] != '0'){
			$rows = $this->db->get_one("SELECT * FROM `{$this->dbtable['xm_item']}` WHERE id='{$result['xmid']}' LIMIT 1");
			$result['status'] = Cache::cache_item_online($rows['online']);
			$result['auditplandate'] = Cache::cache_time_value($rows['auditplandate']);
			$result['finalItemDate'] = Cache::cache_time_value($rows['finalItemDate']);						
			$result['taskBeginDate'] = Cache::cache_time_value($rows['taskBeginDate']);
			$result['taskBeginHalfDate'] = Cache::cache_time_online($rows['taskBeginHalfDate']);
			$result['taskEndDate'] = Cache::cache_time_value($rows['taskEndDate']);
			$result['taskEndHalfDate'] = Cache::cache_time_online($rows['taskEndHalfDate']);
			$result['zsprintdate'] = Cache::cache_time_value($rows['zsprintdate']);					
			$result['zl_okdate'] = Cache::cache_time_value($rows['zl_okdate']);
			$result['archivedate'] = Cache::cache_time_value($rows['archivedate']);
			$result['xm_yanqi'] = Cache::cache_iswhether($rows['xm_yanqi']);
			$result['cw_online'] = $rows['cw_online'] == '1' ? '是' : '否'; 
			
			$rows_cw = $this->db->get_one("SELECT SUM(invoicemoney) AS invoicemoney FROM `{$this->dbtable['cw_finance_list_ex']}` WHERE xmid='{$result['xmid']}' LIMIT 1");
			$result['invoicemoney'] = $rows_cw['invoicemoney'];
		}
		if ($result['taskId'] != '0'){
			$rows = $this->db->get_one("SELECT auditorId FROM `{$this->dbtable['xm_auditor_plan']}` WHERE taskId='{$result['taskId']}' AND iso='{$result['iso']}' AND isLeader='1' LIMIT 1");
			$rows = $this->db->get_one("SELECT empName FROM `{$this->dbtable['xm_auditor']}` WHERE id='{$rows['auditorId']}' LIMIT 1");
			$result['empName'] = $rows['empName'];
		}
		return $result;
	}
}
?>