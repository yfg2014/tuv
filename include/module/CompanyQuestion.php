<?php

/**
 * 企业Question
 */
class CompanyQuestion {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['mk_company_question'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['mk_company_question'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['mk_company_question'], "id='{$id}'");

		return true;
	}

	public function query($id,$other = array()){
		$db = $this->db;

		$other == array() ? $field = '*' : $field = implode(',', $other);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['mk_company_question']} WHERE id='{$id}'");

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter($params['zuzhi_id'], '组织问题修改');
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '组织问题登记');
		}

		return $id;
	}

	public function listQuestion($params = array()) {

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(id) AS sum FROM {$this->dbtable['mk_company_question']} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable['mk_company_question']} WHERE 1  $search ORDER BY id DESC";
		try {
			$page = new listQuestion($url, $sql);
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
class listQuestion extends Page {

	protected function resultFilter($result) {
		$db = $this->db;

		$rows = $db->get_one("SELECT * FROM {$this->dbtable['mk_company']} WHERE id='{$result['zuzhi_id']}'");
		$result['eilinkman'] = $rows['eilinkman'];
		$result['eilinkman_mob'] = $rows['eilinkman_mob'];
        $result['eifax'] = $rows['eifax'];
		return $result;
	}
}
?>
