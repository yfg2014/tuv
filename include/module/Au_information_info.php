<?php

/**
 * 人员信息类
 * @2011-04-29
 */


class Au_information_info {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表人员信息-ok
	public function listHr($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_information]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_information]} WHERE 1 $search  ORDER BY id DESC";
		$page = new Page($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	//获取单个人员信息
	public function query($id,$value = array()){
		$db = $this->db;
		
		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[hr_information]}` WHERE id='{$id}'");
		
		return $result;
	}

	//获取多个人员信息
	public function toArray($where,$params = array()){		
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[hr_information]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}
			
		return $result;		
	}

	//新增人员
	public function add($value){
	    $this->savebefore();
		$db = $this->db;
		DBUtil::insert_tb($db,$this->dbtable['hr_information'],$value);
	}

	//修改人员信息
	public function update($id,$params = array()){
        $this->savebefore();
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['hr_information'], $params, "id='".$id."'");
		return $id;
	}

	//删除人员
	public function del($id){
	    $this->savebefore();
		$db = $this->db;
		/*$table = array(
			$this->dbtable['hr_information'],
			
			$this->dbtable['ht_contract_item'],
			$this->dbtable['ht_repeat_contract'],
			$this->dbtable['xm_auditor'],
			$this->dbtable['xm_auditor_plan'],
			$this->dbtable['xm_item'],
			$this->dbtable['xm_task'],
			$this->dbtable['zs_change'],
			$this->dbtable['zs_cert']

			);*/
		DBUtil::Del($db,$this->dbtable['hr_information'],"id='$id'");
		//DBUtil::Del($db,$table,"zuzhi_id='$zuzhi_id'");
	}
	
	  //错误数据处理
   public function savebefore()
   {
     return ;
   }

}


?>