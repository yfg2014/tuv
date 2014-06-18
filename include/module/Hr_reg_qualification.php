<?php

/**
 * 注册资格类
 * @2011-05-4
 */

class Hr_reg_qualification {
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
	     $this->savebefore("add",$value);
		$db = $this->db;
		DBUtil::insert_tb($db,$this->dbtable['hr_reg_qualification'],$value);
		$newid = mysql_insert_id();
		$this->update_new_zgid($newid);
	}
	
	//修改注册资格信息
	public function update($id,$params = array()){
	    $this->savebefore("update",$params);
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['hr_reg_qualification'], $params, "id='".$id."'");
		$this->update_new_zgid($id);
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
	//更新 hr_audit_code 表 无效的 zg_id
	public function update_new_zgid($zg_id){
		$db = $this->db;
		$zg	= $this->query($zg_id);
		if($zg['online']=='1'){
			if($zg['qualification'] == '1002' or $zg['qualification'] == '1003' or $zg['qualification'] == '1004'){
				$array_id = $db->query("select zg_id FROM {$this->dbtable[hr_audit_code]} WHERE hr_id='$zg[hr_id]' and (qualification='1002' or qualification='1003' or qualification='1004') and iso='$zg[iso]' and zg_id!='$zg_id'");
			}else{
				$array_id = $db->query("select zg_id FROM {$this->dbtable[hr_audit_code]} WHERE hr_id='$zg[hr_id]' and qualification='$zg[qualification]' and iso='$zg[iso]' and zg_id!='$zg_id'");	
			}
			while($id_arr = $db->fetch_array($array_id)){
				$old_zg_id	[]= $id_arr['zg_id'];	
			}
			$old_zg_id = implode("','",$old_zg_id);
			if($old_zg_id !=''){
				DBUtil::update_tb($db, $this->dbtable['hr_audit_code'], array('zg_id'=>$zg_id,'qualification'=>$zg[qualification]), "zg_id IN ('$old_zg_id')");
			}else{
				DBUtil::update_tb($db, $this->dbtable['hr_audit_code'], array('qualification'=>$zg[qualification]), "zg_id='$zg_id' AND qualification!='$zg[qualification]'");
			}
		}
	}
	//错误数据处理
	public function savebefore($func,$value){
		 $db = $this->db;
		 if($value['online'] == '1'){
			switch($func){
				case 'add':
					if($value['qualification'] == '1002' or $value['qualification'] == '1003' or $value['qualification'] == '1004'){
						$rows = $db->get_one("select id FROM {$this->dbtable[hr_reg_qualification]} WHERE hr_id='$value[hr_id]' and " .
							"(qualification='1002' or qualification='1003' or qualification='1004') and iso='$value[iso]' and online='1'");	
					}else{
						$rows = $db->get_one("select id FROM {$this->dbtable[hr_reg_qualification]} WHERE hr_id='$value[hr_id]' and " .
							"qualification='$value[qualification]' and iso='$value[iso]' and online='1'");						
					}
					if($rows['id'] != ''){ $error []= '该专业资格已经存在!';}
				    break;
				case 'update':
					if($value['qualification'] == '1002' or $value['qualification'] == '1003' or $value['qualification'] == '1004'){
						$rows = $db->get_one("select id FROM {$this->dbtable[hr_reg_qualification]} WHERE hr_id='$value[hr_id]' and " .
							"(qualification='1002' or qualification='1003' or qualification='1004') and iso='$value[iso]' and online='1' and id!='$value[id]'");	
					}else{
						$rows = $db->get_one("select id FROM {$this->dbtable[hr_reg_qualification]} WHERE hr_id='$value[hr_id]' and " .
							"qualification='$value[qualification]' and iso='$value[iso]' and online='1' and id!='$value[id]'");						
					}
					if($rows['id'] != ''){ $error []= '该专业资格已经存在!';}
					break;
			}
			if(!empty($error)){
				Error::ShowError($error);
			}	 	
		 }
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
		
		$Date_1=date("Y-m-d");
		$Date_2=$result['e_date'];
		$d1=strtotime($Date_1);
		$d2=strtotime($Date_2);
		$result['tianshu']=round(($d2-$d1)/3600/24);
		
		$Hr_information = new Hr_information();
		$rows = $Hr_information->query($result['hr_id']);
		$result['idcode']		= $rows['idcode'];
		$result['username']		= $rows['username'];
		$result['mark']			= Cache::cache_mark($result['mark']);							//认可机构 缓存编码转字符
		$result['qualification']= Cache::cache_hr_reg_qualification($result['qualification']);	//资格 缓存编码转字符
		$result['sex']				= $rows['sex'];
		$result['phone']			= $rows['phone'];
		$result['worktype']			= Cache::cache_zaizhi($rows['worktype']); //专兼职
		$result['htfrom']			= Cache::cache_htfrom($rows['htfrom']);  //合同来源
		return $result;
	}
}

?>