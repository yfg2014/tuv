<?php

/**
 * 企业基本信息类
 * @author Tom
 */


class Company {
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
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[mk_company]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[mk_company]} WHERE 1 $search  ORDER BY id DESC";
		$page = new listCompany($url, $sql);
		$list = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}

	//获取企业信息
	public function GetCompany($zuzhi_id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[mk_company]}` WHERE id='{$zuzhi_id}'");

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[mk_company]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增企业
	public function AddCompany($value,$zuzhi_id,$zz_value = array()){
		$db = $this->db;
		$this->savebefore($zuzhi_id,'add',$value);
		$value['zd_ren'] = $_SESSION['username'];
		$value['zd_time'] = date("Y-m-d");
		$value['online'] = '1';
		DBUtil::insert_tb($db,$this->dbtable['mk_company'],$value);
		$id = $db->insert_id();
		//添加企业资质
		if(!empty($zz_value)){
			$this->Qualification($id,$zz_value);
		}
		DBUtil::update_tb($db,$this->dbtable['mk_company'],array('eientercode'=>$id),"id='$id'");
		LogRW::logWriter($id,'添加企业-'.$id.'-'.$value['eiregistername']);
		return $id;
	}

	//添加子公司
	public function AddChild($value,$zuzhi_id){
		$db = $this->db;
		$this->savebefore($zuzhi_id,'add',$value);
		$value['zd_ren'] = $_SESSION['username'];
		$value['zd_time'] = date("Y-m-d");
		$value['online'] = '1';
		$value['fatherzuzhi_id'] = $zuzhi_id;
		DBUtil::insert_tb($db,$this->dbtable['mk_company'],$value);
		$id = $db->insert_id();
		DBUtil::update_tb($db,$this->dbtable['mk_company'],array('eientercode'=>$id),"id='$id'");
		DBUtil::update_tb($db,$this->dbtable['mk_company'],array('havechild'=>'1'),"id='$zuzhi_id'");
		LogRW::logWriter($zuzhi_id,'新增关联公司 '.$id.' '.$value['eiregistername']);
	}

	//修改企业信息
	public function EditCompany($value,$zuzhi_id,$zz_value = array()){
		$db = $this->db;
		$this->savebefore($zuzhi_id,'update',$value);
		$value['up_ren'] = $_SESSION['username'];
		$value['up_time'] = date("Y-m-d");

		DBUtil::update_tb($db,$this->dbtable['mk_company'],$value,"id='$zuzhi_id'");
		//添加企业资质
		if(!empty($zz_value)){
			$this->Qualification($zuzhi_id,$zz_value);
		}
		$table = array(
//			$this->dbtable['mk_company_question'],
			$this->dbtable['mk_company_file'],
			$this->dbtable['mk_company_gift'],
			$this->dbtable['ht_contract'],
			$this->dbtable['ht_contract_item'],
			$this->dbtable['ht_repeat_contract'],
			$this->dbtable['ht_sampling'],
			$this->dbtable['xm_auditor'],
			$this->dbtable['xm_auditor_plan'],
			$this->dbtable['xm_item'],
			$this->dbtable['xm_task'],
			$this->dbtable['pd_evaluation_hr'],
			$this->dbtable['zs_change'],
			$this->dbtable['xm_rzlx'],
			$this->dbtable['zs_cert'],
			$this->dbtable['ht_sampling'],
			$this->dbtable['cw_finance_item'],
			$this->dbtable['cw_finance_list'],
			$this->dbtable['cw_finance_list_ex'],
			$this->dbtable['pd_evaluation_files'],
			$this->dbtable['pd_evaluation_hr'],
			$this->dbtable['pd_xm'],
			$this->dbtable['zs_change_task'],
			$this->dbtable['cw_finance_basket'],
			$this->dbtable['mk_company_gift'],
			$this->dbtable['xm_task_file'],
			);
		foreach($table as $v){
			DBUtil::update_tb($db,$v,array('htfrom'=>$value['htfrom']),"zuzhi_id='$zuzhi_id'");
		}
		LogRW::logWriter($zuzhi_id,'修改企业 '.$value['eiregistername']);
	}

	//删除企业
	public function DelCompany($zuzhi_id){
		$db = $this->db;
		$this->savebefore($zuzhi_id,'del');
		$table = array(
			$this->dbtable['mk_company_qualification'], //企业资质表
			$this->dbtable['mk_company_ex'], //分公司表
			$this->dbtable['ht_contract'], //合同表
//			$this->dbtable['ht_sampling'],
			$this->dbtable['ht_contract_item'], //关联的合同项目
			$this->dbtable['ht_repeat_contract'], //合同复评提示
			$this->dbtable['xm_auditor'], //审核员审核计划表
			$this->dbtable['xm_auditor_plan'], //审核员派人项目分配表
			$this->dbtable['xm_item'], //审核项目总表
			$this->dbtable['xm_task'], //审核任务表
			$this->dbtable['zs_change'], //证书变更表
			$this->dbtable['zs_cert'], //证书表
			$this->dbtable['ht_sampling'], //抽样表
			$this->dbtable['cw_finance_item'], //财务收费
			$this->dbtable['cw_finance_list'], //财务收费明细
			$this->dbtable['cw_finance_list_ex'], //财务收费明细
			$this->dbtable['pd_evaluation_files'], //评定案卷表
			$this->dbtable['pd_evaluation_hr'], //评定人员表
			$this->dbtable['pd_xm'], //评定项目表
			$this->dbtable['zs_change_task'], //评定项目表
			$this->dbtable['cw_finance_basket'],
			$this->dbtable['mk_company_complaint'],
			$this->dbtable['mk_company_finance'],
			$this->dbtable['mk_company_gift'],
			$this->dbtable['mk_company_qualification'],
			$this->dbtable['xm_task_file'],
			);
		$rows = $db->get_one("SELECT fatherzuzhi_id,eiregistername FROM `{$this->dbtable['mk_company']}` WHERE id='{$zuzhi_id}'");
		DBUtil::Del($db,$this->dbtable['mk_company'],"id='$zuzhi_id'");
		if ($rows['fatherzuzhi_id'] != '0'){
			$result = $db->get_one("SELECT id FROM `{$this->dbtable['mk_company']}` WHERE fatherzuzhi_id='{$rows['fatherzuzhi_id']}' LIMIT 1");
			if ($result['id'] == ''){
				DBUtil::update_tb($db,$this->dbtable['mk_company'],array('havechild'=>'0'),"id='".$rows['fatherzuzhi_id']."'");
			}
		}
		DBUtil::Del($db,$table,"zuzhi_id='$zuzhi_id'");
		LogRW::logWriter($zuzhi_id,'删除企业 '.$rows['eiregistername']);
	}
	//企业资质
	public function Qualification($zuzhi_id,$zz_value){
		$db = $this->db;
		foreach($zz_value as $val){
			$val['zuzhi_id'] = $zuzhi_id;
			if($val['id'] > 0){
				DBUtil::update_tb($db,$this->dbtable['mk_company_qualification'],$val,"id='$val[id]'");
				LogRW::logWriter($val['id'],'修改企业资质'.$val['id'] .'-'.Cache::cache_company($zuzhi_id));
			}else{
				DBUtil::insert_tb($db,$this->dbtable['mk_company_qualification'],$val);
				$id = $db->insert_id();
				LogRW::logWriter($id,'添加企业资质'.$id.'-'.Cache::cache_company($zuzhi_id));
			}
		}
	}

	//保存数据前数据处理
	public function savebefore($zuzhi_id,$content,$value = array()) {
		$db = $this->db;
		switch ($content) {
			case 'add':
				$zhixiashi = array('441900','442000','110100','120100','310100','500100','820000','810000','710000','000000');
				$eiaddress_code = $value['eiaddress_code'];
//				if ((!in_array($eiaddress_code,$zhixiashi) && substr($eiaddress_code,4,2) == '00') || strlen($eiaddress_code) != 6) {
//					$error []= '错误的县区地址';
//				}
//				if (!strstr($value['eipro_address'],$value['eiaddress'])){
//					$error []= '生产地址不包含县/区级地址';
//				}
//				if (strlen($value['eidaima']) != '10'){
//					$error []= '组织机构代码为10位';
//				}
//				if (!preg_match("/^[0-9A-Z]{8}[-][0-9A-Z]{1}$/",$value['eidaima'])){
//					$error []= '组织机构代码必须是数字和大写字母组成';
//				}
               if($value['childkind']==1 || $value['childkind']==0 || $value['childkind']=='')
			   {
					$rows = $db->get_one("SELECT id FROM {$this->dbtable['mk_company']} WHERE eidaima = '{$value['eidaima']}' and eidaima!='**********' LIMIT 1");
					if ($rows['id'] != ''){
						$error []= '组织机构代码已存在';
					}
					$rows = $db->get_one("SELECT id FROM {$this->dbtable['mk_company']} WHERE eiregistername = '{$value['eiregistername']}' LIMIT 1");
					if ($rows['id'] != ''){
						$error []= '组织名称已存在';
					}
				}
			break;
			case 'update':
				$zhixiashi = array('441900','442000','110100','120100','310100','500100','820000','810000','710000','000000');
				$eiaddress_code = $value['eiaddress_code'];
//				if ((!in_array($eiaddress_code,$zhixiashi) && substr($eiaddress_code,4,2) == '00') || strlen($eiaddress_code) != 6) {
//					$error []= '错误的县区地址';
//				}
//				if (!preg_match("/{$value['eiaddress']}/",$value['eisc_address'])){
//					$error []= '生产地址不包含县/区级地址';
//				}
			   if($value['childkind']==1 || $value['childkind']==0 || $value['childkind']=='')
                {
					$rows = $db->get_one("SELECT id FROM {$this->dbtable['mk_company']} WHERE eidaima = '{$value['eidaima']}' and eidaima!='**********' and id!='{$zuzhi_id}' LIMIT 1");
					if ($rows['id'] != ''){
						$error []= '组织机构代码已存在';
					}
					$rows = $db->get_one("SELECT id FROM {$this->dbtable['mk_company']} WHERE eiregistername = '{$value['eiregistername']}' and id!='{$zuzhi_id}' LIMIT 1");
					if ($rows['id'] != ''){
						$error []= '组织名称已存在';
					}
				}
			break;
			case 'del':
				$rows = $db->get_one("SELECT id FROM {$this->dbtable['mk_company']} WHERE fatherzuzhi_id='{$zuzhi_id}' LIMIT 1");
				if ($rows['id'] != ''){
					$error []= '先删除子公司，再删除主公司';
				}
		}

		if(!empty($error)){
			Error::ShowError($error);
		}
	}

	public function listChangename($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['mk_company_changename']}`
							WHERE 1 $search";
		$sql['data'] = "SELECT *
					 		FROM `{$this->dbtable['mk_company_changename']}`
							WHERE 1 $search  ORDER BY id DESC";
		try {
			$page = new listCompany($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
		}
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;

		return $result;
	}
}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listCompany extends Page {
	protected function resultFilter($result) {
		$db = $this->db;
		//关联公司
		$company = new Company();
		if ($result['havechild'] == '1' && $result['fatherzuzhi_id'] == '0') {
			$result['havechild'] = '<a href="#" onmouseover="show_block('.$result['id'].',100,100)" onmouseout="hide_block('.$result['id'].')"><font color="blue">有</font></a>';
			$result['subsidiaries'] = DBUtil::GetArr($db,$company->dbtable['mk_company'],'',"fatherzuzhi_id='$result[id]'");
		}elseif ($result['havechild'] == '1' && $result['fatherzuzhi_id'] != '0') {
			$result['havechild'] = '是&nbsp;<a href="#" onclick="show_child('.$result['id'].')"><font color="blue">有</font></a>';
			$result['subsidiaries'] = DBUtil::GetArr($db,$company->dbtable['mk_company'],'',"fatherzuzhi_id='$result[id]'");
		}elseif($result['havechild'] == '0' && $result['fatherzuzhi_id'] != '0'){
			$result['havechild'] = '是';
		}else{
			$result['havechild'] = '无';
		}

		//分场所
		$company_ex = $db->get_one("SELECT id FROM `{$this->dbtable[mk_company_ex]}` WHERE zuzhi_id='{$result[id]}' LIMIT 1");
		$company_ex['id'] != '' ? $result['have_company_ex'] = '<font color="blue">有</font>' : $result['have_company_ex'] = '无' ;

		return $result;
	}
}
?>