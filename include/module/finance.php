<?php

/**
 * 企业基本信息类
 * @author Tom
 */


class finance {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表企业信息
	public function listElement($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[ht_contract]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[ht_contract]} WHERE 1 $search  ORDER BY id DESC";
		$page = new listfinance($url, $sql);
		$list = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}

	public function listFinance($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[cw_finance_item]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[cw_finance_item]} WHERE 1 $search  ORDER BY id DESC";
		$page = new ListCost($url, $sql);
		$list = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['cw_finance_item']} WHERE id='{$id}'");

		return $result;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['cw_finance_item'], $params, "id='{$id}'");

		return $id;
	}

	//获取合同财务收费项目信息
	public function GetFinance($ht_id,$value){
		$db = $this->db;
		//$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$result = DBUtil::GetArr($db,$this->dbtable['cw_finance_item'],$value,"ht_id='{$ht_id}'");
		return $result;
	}

	//获取单个财务收费项目信息
	public function GetFinanceItem($FinanceItemId,$value){
		$db = $this->db;
		$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$result = $db->get_one("SELECT {$fields} FROM `{$this->dbtable[cw_finance_item]}` WHERE id='{$FinanceItemId}'");
		return $result;
	}

	//新增财务收费项目信息
	public function AddFinance($value,$zuzhi_id){
		$db = $this->db;
		$ht = $db->get_one("SELECT zuzhi_id,htfrom FROM `{$this->dbtable[ht_contract]}` WHERE id='{$value[ht_id]}'");
		$value['zuzhi_id'] = $ht['zuzhi_id'];
		$value['htfrom'] = $ht['htfrom'];
		$value['zd_ren'] = $_SESSION['username'];
		$value['zd_time'] = date("Y-m-d");
		DBUtil::insert_tb($db,$this->dbtable['cw_finance_item'],$value);
		LogRW::logWriter($zuzhi_id, '添加合同收费项目');
	}

	//修改财务收费项目信息
	public function EditFinance($cwid,$zuzhi_id,$value){
		$db = $this->db;
		$value['up_ren'] = $_SESSION['username'];
		$value['up_time'] = date("Y-m-d");
		DBUtil::update_tb($db,$this->dbtable['cw_finance_item'],$value,"id='$cwid'");
		LogRW::logWriter($zuzhi_id, '合同收费项目内容修改');
	}

	//删除财务收费项目信息
	public function DelFinance($cwid,$zuzhi_id){
		$db = $this->db;
		DBUtil::Del($db,$this->dbtable['cw_finance_item'],"id='$cwid'");
		LogRW::logWriter($zuzhi_id, '合同收费项目删除');
	}

	//保存数据前数据处理
	public function savebefore($value = array(),$zuzhi_id) {
		$db = $this->db;
		if(!empty($error)){
			Error::ShowError($error);
		}
	}
}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listfinance extends Page {
	protected function resultFilter($result) {
		$db = $this->db;
		$finance = new finance();
		$finance_item_arr = DBUtil::GetArr($db,$finance->dbtable['cw_finance_item'],array('finance_item','contract_money','other'),"ht_id='$result[id]'");
		foreach($finance_item_arr as $v){
			$v['finance_item'] = Cache::cache_Finance_item($v['finance_item']);
			$result['finance_item'] == '' ? $result['finance_item'] = $v['finance_item'] : $result['finance_item'] = $result['finance_item'].'<br/>'.$v['finance_item'];
			$result['contract_money'] == '' ? $result['contract_money'] = $v['contract_money'] : $result['contract_money'] = $result['contract_money'].'<br/>'.$v['contract_money'];
			$result['other'] == '' ? $result['other'] = $v['other'] : $result['other'] = $result['other'].'<br/>'.$v['other'];
		}
		//取合同项目号
		$htxmcode = array();
		$num = 0;
		$htxm_sql = $db->query("SELECT htxmcode,kind FROM {$finance->dbtable[ht_contract_item]} WHERE ht_id='$result[id]'");
		while($htxm = $db->fetch_array($htxm_sql)){
			$htxmcode []= $htxm['htxmcode'];
			if($htxm['kind'] == '2')
			{
				$num = $num + 1;
			}
		}
		$result['cp'] = $num;
		$result['htxmcode'] = implode('<br>',$htxmcode);
		return $result;
	}
}

class ListCost extends Page {
	protected function resultFilter($result) {
		$db = $this->db;
		$ht = $db->get_one("SELECT iso FROM ht_contract WHERE id='$result[ht_id]'");
		$result['ht_iso'] = $ht['iso'];
		return $result;
	}
}
?>