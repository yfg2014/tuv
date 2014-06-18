<?php

/**
 * 注册资格类
 * @2011-05-4
 */


class Au_reg_qualification {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表注册资格信息
	public function list_reg_qualification($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_reg_qualification]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_reg_qualification]} WHERE 1 $search  ORDER BY id DESC";

		$page = new listreg_qualification($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	//获取单个注册资格
	public function query($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[hr_reg_qualification]}` WHERE id='{$id}'");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[hr_reg_qualification]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增注册资格
	public function add($value){
	     $this->savebefore();
		$db = $this->db;
		DBUtil::insert_tb($db,$this->dbtable['hr_reg_qualification'],$value);
	}
	//错误数据处理
	public function savebefore()
	{
	}
	//修改注册资格信息
	public function update($id,$params = array()){
	     $this->savebefore();
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['hr_reg_qualification'], $params, "id='".$id."'");
		return $id;
	}

	//删除注册资格
	public function del($id){
	     $this->savebefore();
		$db = $this->db;
		/*$table = array(
			$this->dbtable['hr_reg_qualification'],

			$this->dbtable['ht_contract_item'],
			$this->dbtable['ht_repeat_contract'],
			$this->dbtable['xm_auditor'],
			$this->dbtable['xm_auditor_plan'],
			$this->dbtable['xm_item'],
			$this->dbtable['xm_task'],
			$this->dbtable['zs_change'],
			$this->dbtable['zs_cert']

			);*/
		DBUtil::Del($db,$this->dbtable['hr_reg_qualification'],"id='$id'");
		//DBUtil::Del($db,$table,"zuzhi_id='$zuzhi_id'");
	}


}
/**
 *
 * 扩展分页类，过滤结果
 *
 *
 */
class listreg_qualification extends Page {

	protected function resultFilter($result) {
		$db = $this->db;

		$Hr_information = new Au_information_info();
		$rows = $Hr_information->query($result['hr_id']);
		$result['idcode']		= $rows['idcode'];
		$result['username']		= $rows['username'];
		$result['mark']			= Cache::cache_mark($result['mark']);							//认可机构 缓存编码转字符
		$result['qualification']= Cache::cache_hr_reg_qualification($result['qualification']);	//资格 缓存编码转字符
		return $result;
	}
}

?>