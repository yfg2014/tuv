<?php

/**
 * 企业礼品发放记录
 * @2011-05-4
 */

class CompanyGift {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function list_company_gift($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable['mk_company_gift']} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable['mk_company_gift']} WHERE 1 $search  ORDER BY id DESC";

		$page = new listCompany_gift($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	public function query($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['mk_company_gift']}` WHERE id='{$id}'");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable['mk_company_gift']} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	public function add($value){
		$db = $this->db;
		
		DBUtil::insert_tb($db,$this->dbtable['mk_company_gift'],$value);
		
		$id = $db->insert_id();
		LogRW::logWriter($id,'新增-'.Cache::cache_company($value['zuzhi_id']).'-企业礼品'.$value['gift_name']);
	}
	
	public function update($id,$params = array()){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['mk_company_gift'], $params, "id='".$id."'");
		
		return $id;
		
		LogRW::logWriter($id,'修改-'.Cache::cache_company($params['zuzhi_id']).'-企业礼品');
	}

	public function del($id){
		$db = $this->db;
		$rows = $db->get_one("SELECT zuzhi_id,gift_name FROM `{$this->dbtable['mk_company_gift']}` WHERE id='{$id}'"); 
		DBUtil::Del($db,$this->dbtable['mk_company_gift'],"id='$id'");
		
		return $id;
		
		LogRW::logWriter($id,'删除-'.Cache::cache_company($rows['zuzhi_id']).'-企业礼品'.$rows['gift_name']);
	}

	public function savebefore($func,$value){
	 	
	}
}
/**
 *
 * 扩展分页类，过滤结果
 *
 *
 */
class listCompany_gift extends Page {

	
}

?>