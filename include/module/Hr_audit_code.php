<?php

/**
 * 人员专业能力类
 * @2011-05-5
 */


class Hr_audit_code {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表人员专业能力信息
	public function list_audit_code($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_audit_code]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_audit_code]} WHERE 1 $search  ORDER BY hr_id ASC,xiaolei ASC";

		$page = new list_audit_code($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	//获取单个人员专业能力
	public function query($id,$value = array()){
		$db = $this->db;
		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[hr_audit_code]}` WHERE id='{$id}'");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[hr_audit_code]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增人员专业能力
	public function add($value){
		$db = $this->db;
	    $this->savebefore("add",$value);
	    $array = array_unique(explode('；',str_replace(';','；',$value['xiaolei'])));
		foreach($array as $v){
			$value['xiaolei'] = $v;
			$dalei = array_unique(explode('.',$value['xiaolei']));
			$value['dalei'] = $dalei[0];
			if($value['dalei'] == ''){
				$value['dalei'] = $value['xiaolei'];
			}
			DBUtil::insert_tb($db,$this->dbtable['hr_audit_code'],$value);
		}
	}

	//修改人员专业能力信息
	public function update($id,$params = array()){
        $this->savebefore("update",$params);
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['hr_audit_code'], $params, "id='".$id."'");
		return $id;
	}

	//删除人员专业能力
	public function del($id){
		$db = $this->db;

		$this->savebefore();
		DBUtil::Del($db,$this->dbtable['hr_audit_code'],"id='$id'");
	}

  //错误数据处理
   public function savebefore($func,$value)
   {
   	     $db = $this->db;
	     switch($func){
		 case 'add':
		 	$array = array_unique(explode('；',str_replace(';','；',$value['xiaolei'])));
		 	$arr = '';
			foreach($array as $v){
			      $rows = $db->get_one("SELECT  id FROM {$this->dbtable['hr_audit_code']} WHERE
				  hr_id='".$value['hr_id']."' and iso='$value[iso]' and  xiaolei ='".$v."' and qualification='$value[qualification]'");
				  if($rows['id'] != ''){
				  	$arr []= $v;
				  }
			}

			if($arr != ''){
				$error [] = implode('；',$arr).'专业代码已经存在!';
			}
		   break;
		 case 'update':
		 	  $rows = $db->get_one("SELECT id FROM {$this->dbtable['hr_audit_code']} WHERE id != '".$value['id']."' and hr_id ='".$value['hr_id']."' and iso='$value[iso]'  and  xiaolei ='".$value['xiaolei']."'  and qualification='$value[qualification]'");
			  if($rows['id'] != ''){
			  $error []= '专业代码已经存在!';}
	     case 'del':
		    break;
			}

		if(!empty($error)){
			Error::ShowError($error);
			}
    }
}



/**
 *
 * 扩展分页类，过滤结果
 *
 *
 */
class list_audit_code extends Page {

	protected function resultFilter($result) {
		$db = $this->db;

		$Hr_information = new Hr_information();
		$rows = $Hr_information->query($result['hr_id']);
		$result['idcode']			= $rows['idcode'];
		$result['username']			= $rows['username'];
		$result['address']			= $rows['address'];
		$result['phone']			= $rows['phone'];
		$result['ability_source']	= Cache::cache_hr_ability_source($result['ability_source']);	//人员能力来源 缓存code转msg

		$msg = $db->get_one("SELECT msg FROM {$this->dbtable['setup_audit_code']} WHERE code='{$result['xiaolei']}'");
		$result['xiaolei_msg'] = $msg['msg'];
		return $result;
	}
}

?>