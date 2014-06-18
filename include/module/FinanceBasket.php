<?php
/**
 */

class FinanceBasket {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['cw_finance_basket'], $params);
		$id = $db->insert_id();
		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['cw_finance_basket'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id,$zuzhi_id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['cw_finance_basket'], "id='{$id}'");
		//$this->buckle($zuzhi_id);
		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['cw_finance_basket']} WHERE id='{$id}'");

		return $result;
	}

	public function toArray($where,$params){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['cw_finance_basket']} WHERE {$where}");
		while ($rows = $db->fetch_array($q)) {
			$result []= $rows;
		}

		return $result;
	}

	private function buckle($zuzhi_id){
		$db = $this->db;

		$info = $db->get_one("SELECT SUM(get_money) AS sum FROM {$this->dbtable['mk_company_finance']} WHERE zuzhi_id='{$zuzhi_id}'");
		$fo = $db->get_one("SELECT SUM(basket1+basket2+basket3+basket4) AS sum FROM {$this->dbtable['cw_finance_basket']} WHERE zuzhi_id='{$zuzhi_id}'");
		$mon = $info['sum']-$fo['sum'];
		DBUtil::update_tb($db, $this->dbtable['mk_company'], array('company_money'=>$mon), "id='{$zuzhi_id}'");
	}

	public function listElement($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['cw_finance_basket']}`
							WHERE 1 $search";
		$sql['data'] = "SELECT *
					 		FROM `{$this->dbtable['cw_finance_basket']}`
								WHERE 1 $search ORDER BY id";
		try {
			$page = new listBasket($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
		}

		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;

		return $result;
	}



	public function save($id,$params){
		if((int)$id > 0){
			$id = $this->update($id,$params);
			//$this->buckle($params['zuzhi_id']);
			LogRW::logWriter($params['zuzhi_id'],'合同划拨明细费用修改');
		}elseif ((int)$id == 0) {
			$id = $this->add($params);
			//$this->buckle($params['zuzhi_id']);
			LogRW::logWriter($params['zuzhi_id'],'合同划拨明细费用登记');
		}

		return $id;
	}

}
	class listBasket extends Page {
		protected function resultFilter($result) {
		$db = $this->db;
		$cw_item = $db->get_one("SELECT finance_item FROM {$this->dbtable['cw_finance_item']} WHERE id='{$result['f_item_id']}'");
		$result['finance_item'] = Cache::cache_Finance_item($cw_item['finance_item']);
		return $result;
	}
}
?>