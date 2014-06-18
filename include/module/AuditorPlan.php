<?php

/**
 * 任务计划中审核人员对应每个的体系的信息
 * @author Tom
 *
 */
class AuditorPlan {
	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	/**
	 * 添加审核派人详细信息（每个体系每个审核人员一条）
	 * @param array $params
	 * @return AuditorPlan $AuditorPlan
	 */
	public function add($params) {
		$this->savebefore();
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['xm_auditor_plan'], $params);
		$id = $db->insert_id();

		return $id;
	}

	/**
	 * 更新审核人员信息（每人每体系一条）
	 * @param integer $id
	 * @param array $params
	 * @return AuditorPlan $AuditorPlan
	 */
	public function update($id, $params) {
		$this->savebefore();
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['xm_auditor_plan'], $params, "id='{$id}'");
		return $id;
	}

	/**
	 * 删除审核人员信息（每人每体系一条）
	 * @param integer $id
	 * @return boolean
	 */
	public function delete($id) {
		$this->savebefore();
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['xm_auditor_plan'], "id='{$id}'");

		return true;
	}
	
	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['xm_auditor_plan']}` WHERE id='{$id}' LIMIT 1");

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['xm_auditor_plan']} WHERE {$where}");
		while ($rows = $db->fetch_array($q)) {
			$result[] = $rows;
		}

		return $result;
	}

	public function save($id,$params) {
		if ($id != '') {
			$id = $this->update($id,$params);
		} else {
			$id = $this->add($params);
		}

		return $id;
	}
	
	public function listAuditorPlan($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_auditor_plan']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_auditor_plan']}`
						WHERE 1 $search ORDER BY taskId DESC";
		$page = new listAuditorPlan($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;

		return $result;
	}
	
	public function listAuditorEval($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_auditor_plan']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_auditor_plan']}`
						WHERE 1 $search ORDER BY taskId DESC";
		$page = new listAuditorEval($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;

		return $result;
	}

	public function savebefore(){
	}

}
/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listAuditorPlan extends Page {

	protected function resultFilter($result) {
		$db = $this->db;
		
		$rows = $this->db->get_one("SELECT taskBeginDate,taskEndDate FROM `{$this->dbtable['xm_auditor']}` WHERE id='{$result['auditorId']}' LIMIT 1");
		$result['taskBeginDate'] = $rows['taskBeginDate'];
		$result['taskEndDate'] = $rows['taskEndDate'];
		
		$rows = $this->db->get_one("SELECT empName FROM `{$this->dbtable['xm_auditor_plan']}` WHERE taskId='{$result['taskId']}' AND iso='{$result['iso']}' AND evaluate='2' and empId!='{$result['empId']}' LIMIT 1");
		$result['pjempName'] = $rows['empName'];
		$result['role'] = Cache::cache_hr_role($result['role']);
		return $result;
	}
}

class listAuditorEval extends Page {

	protected function resultFilter($result) {
		$db = $this->db;
		
		$rows = $this->db->get_one("SELECT taskBeginDate,taskEndDate FROM `{$this->dbtable['xm_auditor']}` WHERE id='{$result['auditorId']}' LIMIT 1");
		$result['taskBeginDate'] = $rows['taskBeginDate'];
		$result['taskEndDate'] = $rows['taskEndDate'];
		
		$aff = '';
		$query = $db->query("SELECT role,empName,evaluate_date,remark FROM `{$this->dbtable['xm_auditor_plan']}` WHERE taskId='{$result['taskId']}' AND iso='{$result['iso']}' AND evaluate='1' and empId!='{$result['empId']}'");
		while($arr = $db->fetch_array($query)){
			$aff []= $arr['empName'].' '.Cache::cache_hr_role($arr['role']);
			$result['evaluate_date'] = $arr['evaluate_date'];
			$result['remark'] .= $arr['remark'];
		}
		$result['pjempName'] = implode('<br/>',$aff);
		
		return $result;
	}
}
?>