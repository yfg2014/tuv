<?php

/**
 * 抽样
 */
class Sampling {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['ht_sampling'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['ht_sampling'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['ht_sampling'], "id='{$id}'");

		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['ht_sampling']} WHERE id='{$id}'");

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter($params['zuzhi_id'], '产品抽样修改');
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '产品抽样登记');
		}

		return $id;
	}

	public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(a.id) AS sum
							FROM {$this->dbtable['ht_sampling']} a LEFT JOIN {$this->dbtable['ht_contract_item']} b ON a.htxm_id=b.id
							WHERE 1 $search";
		$sql['data'] = "SELECT  a.*,b.audit_code,b.product,b.product_ver,b.renzhengfanwei
					 		FROM {$this->dbtable['ht_sampling']} a LEFT JOIN {$this->dbtable['ht_contract_item']} b ON a.htxm_id=b.id
								WHERE 1  $search ORDER BY id DESC";
		try {
			$page = new listSampling($url, $sql);
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
class listSampling extends Page {

	protected function resultFilter($result) {
		$db = $this->db;

		$Contract = new Contract();
		$rows = $Contract->query($result['ht_id']);
		$result['htdate'] = $rows['htdate'];
		$result['zd_ren'] = $rows['zd_ren'];
		$result['other'] = $rows['other'];
		$result['ps_other'] = $rows['ps_other'];
		$result['audit_code'] = str_replace('；', '<br/>', $result['audit_code']);

		return $result;
	}
}
?>
