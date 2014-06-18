<?php
/**
 * 认证评定
 * @author Tom
 *
 */
class Evaluate {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params) {
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['pd_evaluation_hr'], $params);
		$id = $db->insert_id();
		return $id;
	}

	public function update($id,$params) {
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['pd_evaluation_hr'], $params, "id='{$id}'");
		return $pd;
	}

	public function delete($id) {
		$db = $this->db;

		$rows = $this->query($id,array('zuzhi_id','username'));
		DBUtil::Del($db, $this->dbtable['pd_evaluation_hr'], "id='{$id}'");
		LogRW::logWriter($rows['zuzhi_id'], '评定人删除：'.$rows['username']);

		return true;
	}

	public function query($id,$params = array()) {
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['pd_evaluation_hr']}` WHERE id='{$id}' LIMIT 1");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['pd_evaluation_hr']} WHERE  {$where}");
		while ($rows = $db->fetch_array($q)) {
				$result[] = $rows;
		}

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter($params['zuzhi_id'], '评定人修改：'.$params['username'].' 体系：'.$params['iso']);
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '评定人添加：'.$params['username'].' 体系：'.$params['iso']);
		}

		return $id;
	}

		public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['pd_evaluation_hr']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['pd_evaluation_hr']}`
						WHERE 1 $search ORDER BY id DESC";
		$page = new listHrm($url, $sql);
		$list = $page->getPageData();
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
class listHrm extends Page {

	protected function resultFilter($result) {
		$rows2 = $this->db->get_one("SELECT htxm_id ,audit_type ,zs_if,assessmentdate FROM `{$this->dbtable['pd_xm']}` WHERE id='{$result['pdid']}' LIMIT 1");
		$result['audit_type'] = Cache::cache_audit_type($rows2['audit_type']);
		$result['zs_if'] = Cache::setup_pd_online($rows2['zs_if']);
		$result['assessmentdate'] = Cache::cache_time_value($rows2['assessmentdate']);
		$result['thendate'] = Cache::cache_time_value($result['thendate']);
		$result['rossdate'] = Cache::cache_time_value($result['rossdate']);

		$rows = $this->db->get_one("SELECT htxmcode FROM `{$this->dbtable['ht_contract_item']}` WHERE id='{$rows2['htxm_id']}' LIMIT 1");
		$result['htxmcode'] = $rows['htxmcode'];
		return $result;
	}
}

?>