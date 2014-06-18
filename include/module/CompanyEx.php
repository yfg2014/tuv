<?php

/**
 * 企业分机构信息类
 * @author Tom
 */


class CompanyEx {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表企业的关联公司
	public function listElement($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[mk_company_ex]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[mk_company_ex]} WHERE 1 $search  ORDER BY id DESC";
		$page = new listCompanyEx($url, $sql);
		$list = $page->getPageData();
		print_r($list);
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}

	//获取企业的关联公司信息
	public function GetCompanyEx($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[mk_company_ex]}` WHERE id='{$id}'");

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[mk_company_ex]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增企业的关联公司
	public function AddCompanyEx($value){
		$db = $this->db;
		
		$value['zd_ren'] = $_SESSION['username'];
		$value['zd_time'] = date("Y-m-d");
		
		DBUtil::insert_tb($db,$this->dbtable['mk_company_ex'],$value);
		$id = $db->insert_id();
		
		LogRW::logWriter($id,'修改企业 -'.Cache::cache_company($value['zuzhi_id']).' 的关联公司-'.$value['eiregistername']);
	}


	//修改企业的关联公司
	public function EditCompanyEx($value,$id){
		$db = $this->db;
		
		$value['zd_ren'] = $_SESSION['username'];
		$value['zd_time'] = date("Y-m-d");
				
		DBUtil::update_tb($db,$this->dbtable['mk_company_ex'],$value,"id='$id'");
		
		LogRW::logWriter($id,'修改企业 -'.Cache::cache_company($value['zuzhi_id']).' 的关联公司-'.$value['eiregistername']);
	}

	//删除企业的关联公司
	public function DelCompanyEx($id){
		$db = $this->db;

		$rows = $db->get_one("SELECT zuzhi_id,eiregistername FROM `{$this->dbtable['mk_company_ex']}` WHERE id='{$id}'");
		DBUtil::Del($db,$this->dbtable['mk_company_ex'],"id='$id'");
		
		LogRW::logWriter($id,'删除企业 '.Cache::cache_company($rows['zuzhi_id']).' 的关联公司 '.$rows['eiregistername']);
	}
	
	//保存数据前数据处理
	public function savebefore() {
		
	}

}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listCompanyEx extends Page {

}
?>