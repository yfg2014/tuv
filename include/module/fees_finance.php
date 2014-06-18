<?php

/**
 * 企业基本信息类
 * @author Tom
 */


class fees_finance {
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
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[xm_item]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[xm_item]} WHERE 1 $search  ORDER BY id DESC";
		$page = new Page($url, $sql);
		$list = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}

	//获取合同财务收费项目信息
	public function GetFinance($ht_id,$value=array()){
		$db = $this->db;
		//$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$result = DBUtil::GetArr($db,$this->dbtable['cw_finance_item'],$value,"ht_id='{$ht_id}'");
		return $result;
	}


	//获取财务收费明细信息
	public function GetFinanceList($ht_id,$value=array()){
		$db = $this->db;
		$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$sql = "SELECT $fields FROM {$this->dbtable['cw_finance_list']} WHERE ht_id='{$ht_id}'";
		$query = $db->query($sql);
		while($rst = $db->fetch_array($query)){
			//4个篮子
			$basket = $db->get_one("SELECT basket1,basket2,basket3,basket4 FROM {$this->dbtable['cw_finance_basket']} WHERE cwid='$rst[id]'");
			$rst['basket1'] = $basket['basket1'];	
			$rst['basket2'] = $basket['basket2'];
			$rst['basket3'] = $basket['basket3'];
			$rst['basket4'] = $basket['basket4'];
			
			$xmid = array();
			$cwlist_sql = $db->query("SELECT xmid FROM {$this->dbtable['cw_finance_list_ex']} WHERE cwid='$rst[id]'");
			while($cwlist = $db->fetch_array($cwlist_sql)){
				$xmid []= $cwlist['xmid'];
			}
			$xmid = implode("','",$xmid);
			$xm_q = $db->query("SELECT iso,audit_type,renzhengfanwei FROM xm_item WHERE id IN('$xmid')");
			while($xm = $db->fetch_array($xm_q)){
				$rst['iso'] []= $xm['iso'];
				$rst['renzhengfanwei'] []= $xm['renzhengfanwei'];
//				$rst['product'] []= Cache::cache_product($xm['product']);
				$rst['audit_type'] []= Cache::cache_audit_type($xm['audit_type']);
			}
			$rst['iso'] = implode('<br>',$rst['iso']);
			$rst['renzhengfanwei'] = implode('<br>',$rst['renzhengfanwei']);
//			$rst['product'] = implode('<br>',$rst['product']);
			$rst['audit_type'] = implode('<br>',$rst['audit_type']);

			$result []= $rst;
		}
		return $result;
	}

	//获取单条财务收费明细信息
	public function GetFinanceOneList($cwid,$value=array()){
		$db = $this->db;
		$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$result = DBUtil::GetOne($db,$this->dbtable['cw_finance_list'],$value,"id='{$cwid}'");

		return $result;
	}
	//获取单条财务收费明细信息
	public function GetFinanceOneListEx($cwid,$value=array()){
		$db = $this->db;
		//$value == array() ? $fields = '*' : $fields = implode(',',$value);
		//$result = DBUtil::GetOne($db,$this->dbtable['cw_finance_list'],$value,"id='{$cwid}'");
		$sql = "SELECT xmid FROM {$this->dbtable['cw_finance_list_ex']} WHERE cwid='$cwid'";
		$query = $db->query($sql);
		while($rst = $db->fetch_array($query)){
			$result []= $rst['xmid'];
		}
		return $result;
	}

	//获取需收费项目信息
	public function GetXmList($where,$value=array()){
		$db = $this->db;
		//$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$result = DBUtil::GetArr($db,$this->dbtable['xm_item'],$value,$where);
		return $result;
	}

	//获取单个财务收费项目信息
	public function GetFinanceItem($FinanceItemId,$value=array()){
		$db = $this->db;
		$value == array() ? $fields = '*' : $fields = implode(',',$value);
		$result = $db->get_one("SELECT {$fields} FROM `{$this->dbtable[cw_finance_item]}` WHERE id='{$FinanceItemId}'");
		return $result;
	}

	//新增财务收费项目信息
	public function AddFeesFinance($value,$zuzhi_id,$xmid=array(),$cw_online_xmid=array()){
		$db = $this->db;
		$ht = $db->get_one("SELECT zuzhi_id,htfrom FROM `{$this->dbtable[ht_contract]}` WHERE id='{$value[ht_id]}'");
		$value['zuzhi_id'] = $ht['zuzhi_id'];
		$value['htfrom'] = $ht['htfrom'];
		$value['zd_ren'] = $_SESSION['username'];
		$value['zd_time'] = date("Y-m-d");
		DBUtil::insert_tb($db,$this->dbtable['cw_finance_list'],$value);
		$cwid= $db->insert_id();
		if(!empty($xmid)){
			foreach($xmid as $v){
				$value['xmid'] = $v;
				$value['cwid'] = $cwid;
				$value_arr []= $value;
			}
			DBUtil::insert_tb($db,$this->dbtable['cw_finance_list_ex'],$value_arr);
			$sql_xmid = implode("','",$xmid);
			$db->query("UPDATE {$this->dbtable['xm_item']} SET cw_online='0' WHERE id IN('$sql_xmid')");
		}

		if(!empty($cw_online_xmid)){
			foreach($cw_online_xmid as $k=>$v){
				if(!in_array($v,$xmid)){
					unset($cw_online_xmid[$k]); //如果交完的xmid不在选择的xmid内，则无效，需注销
				}
			}
			$sql_cw_online_xmid = implode("','",$cw_online_xmid);
			$db->query("UPDATE {$this->dbtable['xm_item']} SET cw_online='1' WHERE id IN('$sql_cw_online_xmid')");
		}
		LogRW::logWriter($zuzhi_id, '添加财务收费项目');
		
		return $cwid;
	}

	//修改财务收费项目信息
	public function EditFessFinance($cwid,$zuzhi_id,$value,$xmid=array(),$cw_online_xmid=array()){
		$db = $this->db;
		$value['up_ren'] = $_SESSION['username'];
		$value['up_time'] = date("Y-m-d");
		DBUtil::update_tb($db,$this->dbtable['cw_finance_list'],$value,"id='$cwid'");
		DBUtil::update_tb($db,$this->dbtable['cw_finance_list_ex'],$value,"cwid='$cwid'");
		if(!empty($xmid)){
			//取财务关联的xmid，删除无选择的xmid
			$sql = "SELECT id,xmid FROM {$this->dbtable['cw_finance_list_ex']} WHERE cwid='$cwid'";
			$query = $db->query($sql);
			while($rst = $db->fetch_array($query)){
				$xmid_temp []= $rst['xmid'];
				$ex_id []= $rst['id'];
			}
			foreach($xmid_temp as $k=>$v){
				if(!in_array($v,$xmid)){
					$db->query("DELETE FROM {$this->dbtable['cw_finance_list_ex']} WHERE id='$ex_id[$k]'");
				}
			}
			$sql_xmid = implode("','",$xmid);
			$db->query("UPDATE {$this->dbtable['xm_item']} SET cw_online='0' WHERE id IN('$sql_xmid')");
		}

		if(!empty($cw_online_xmid)){
			foreach($cw_online_xmid as $k=>$v){
				if(!in_array($v,$xmid)){
					unset($cw_online_xmid[$k]); //如果交完的xmid不在选择的xmid内，则无效，需注销
				}
			}
			$sql_cw_online_xmid = implode("','",$cw_online_xmid);
			$db->query("UPDATE {$this->dbtable['xm_item']} SET cw_online='1' WHERE id IN('$sql_cw_online_xmid')");
		}
		LogRW::logWriter($zuzhi_id, '财务收费项目内容修改');

		return $cwid;
	}

	//删除财务收费项目信息
	public function DelFeesFinance($cwid,$zuzhi_id){
		$db = $this->db;
		$db->query("UPDATE {$this->dbtable['xm_item']} SET cw_online='0' WHERE id in ( select xmid from cw_finance_list_ex where  cwid = '{$cwid}') ");
		DBUtil::Del($db,$this->dbtable['cw_finance_list'],"id='$cwid'");
		DBUtil::Del($db,$this->dbtable['cw_finance_list_ex'],"cwid='$cwid'");
		DBUtil::Del($db,$this->dbtable['cw_finance_basket'],"cwid='$cwid'");
        LogRW::logWriter($zuzhi_id, '财务收费项目删除');
	}

	//保存数据前数据处理
	public function savebefore($value = array(),$zuzhi_id) {
		$db = $this->db;
		if(!empty($error)){
			Error::ShowError($error);
		}
	}
}


?>