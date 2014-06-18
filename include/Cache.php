<?php

/**
 * 缓存
 * @author Tom
 *
 */
class Cache {

	/**
	 * 合同状态
	 */
	static function cache_ht_online($content,$convertType = 1){
		global $cache_ht_online;
		if(empty($cache_ht_online)){
			include_once(SET_DIR.'setup_ht_online.php');
		}
		foreach ($cache_ht_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 项目状态
	 */
	static function cache_item_online($content,$convertType = 1){
		global $cache_item_online;
		if(empty($cache_item_online)){
			include_once(SET_DIR.'setup_item_online.php');
		}
		foreach ($cache_item_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 任务状态
	 */
	static function cache_task_online($content,$convertType = 1){
		global $cache_task_online;
		if(empty($cache_task_online)){
			include_once(SET_DIR.'setup_task_online.php');
		}
		foreach ($cache_task_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 注册资格状态
	 */
	static function cache_hr_reg_qualification($content,$convertType = 1){
		global $setup_hr_reg_qualification;
		if(empty($setup_hr_reg_qualification)){
			include_once(SET_DIR.'setup_hr_reg_qualification.php');
		}
		foreach ($setup_hr_reg_qualification as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 组内身份
	 */
	static function cache_hr_role($content,$convertType = 1){
		global $setup_role;
		if(empty($setup_role)){
			include_once(SET_DIR.'setup_role.php');
		}
		foreach ($setup_role as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 组织上传文件类型
	 */
	static function cache_company_uploadfile($content,$convertType = 1){
		global $setup_zuzhi_uploadfile;
		if(empty($setup_zuzhi_uploadfile)){
			include_once(SET_DIR.'setup_zuzhi_uploadfile.php');
		}
		foreach ($setup_zuzhi_uploadfile as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 上传文件类型
	 */
	static function cache_uploadfile($content,$convertType = 1){
		global $setup_uploadfile;
		if(empty($setup_uploadfile)){
			include_once(SET_DIR.'setup_law_uploadfile.php');
		}
		foreach ($setup_uploadfile as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 评定结果
	 */
	static function setup_pd_online($content,$convertType = 1){
		global $setup_pd_online;
		if(empty($setup_pd_online)){
			include_once(SET_DIR.'setup_pd_online.php');
		}
		foreach ($setup_pd_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 合同来源
	 */
	static function cache_htfrom($content,$convertType = 'msg'){
		global $setup_htfrom;
		if(empty($setup_htfrom)){
			include_once(SET_DIR.'setup_htfrom.php');
		}
		foreach ($setup_htfrom as $k=>$v){
			if((string)$content == (string)$k){
				return $v[$convertType];
			}
		}
	}

	/**
	 * 风险等级
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_risk($content,$convertType = 1){
		global $setup_risk;
		if(empty($setup_risk)){
			include_once(SET_DIR.'setup_risk.php');
		}
		foreach ($setup_risk as $k=>$v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 认可标志
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_mark($content,$convertType = 1){
		global $setup_mark;
		if(empty($setup_mark)){
			include_once(SET_DIR.'setup_mark.php');
		}
		foreach ($setup_mark as $k=>$v){
			if($convertType == 1 && strstr($content,$k)){
				$content = str_replace($v['code'], $v['msg'], $content);
			} elseif ($convertType == 0 && strstr($content,$v['msg'])) {
				$content = str_replace($v['msg'], $v['code'], $content);
			}
		}
		return $content;
	}

	/**
	 * 人员能力来源
	 * @2011-5-5
	 */
	static function cache_hr_ability_source($content,$convertType = 1){
		global $setup_hr_ability_source;
		if(empty($setup_hr_ability_source)){
			include_once(SET_DIR.'setup_hr_ability_source.php');
		}
		foreach ($setup_hr_ability_source as $v){
			if($convertType == 1 && $content == $v['code']){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $v['code'];
			}
		}
	}

	/**
	 * 获取人员姓名
	 * @param mixed $hr_id
	 * @return mixed $result
	 */
	static function cache_username($hr_id) {
		global $db,$dbtable;
		$hr = $db->get_one("SELECT username FROM `{$dbtable['hr_information']}` WHERE id='{$hr_id}'");
		$result = $hr['username'];
		return $result;
	}

	/**
	 * 部门
	 * @2011-5-5
	 */
	static function cache_hr_department($content,$convertType = 1){
		global $setup_hr_department;
		if(empty($setup_hr_department)){
			include_once(SET_DIR.'setup_hr_department.php');
		}
		foreach ($setup_hr_department as $v){
			if($convertType == 1 && $content == $v['code']){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $v['code'];
			}
		}
	}

	/**
	 * 审核类型
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_audit_type($content,$convertType = 1){
		global $setup_audit_type;
		if(empty($setup_audit_type)){
			include_once(SET_DIR.'setup_audit_type.php');
		}
		foreach ($setup_audit_type as $v){
			if($convertType == 1 && $content == $v['code']){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $v['code'];
			}
		}
	}	
	
	/**
	 * nqa审核代码
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_nqa_code($content,$convertType = 1){	
		global $db,$dbtable;
		
		$arr = explode('；', $content);
		foreach($arr as $v){
			$get_code = $db->get_one("SELECT code FROM `{$dbtable['setup_audit_code']}` WHERE shangbao='$v' LIMIT 1");
			$nqa_code_arr []= $get_code['code'];
		}
		$result = implode('；', $nqa_code_arr); 
		
		return $result;
	}

	/**
	 * 代码字符转换
	 * @param mixed $content
	 * @return mixed
	 */
	static function cache_audit_code($content){
		$content = str_replace(';','；',$content);
		$content = str_replace(',','；',$content);
		$content = str_replace('，','；',$content);
		$content = str_replace('/','；',$content);
		$content = str_replace('、','；',$content);

		return $content;
	}

	/**
	 * 认证领域
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_audit_sys($content,$convertType = 1){
		global $setup_audit_iso;
		if(empty($setup_audit_iso)){
			include_once(SET_DIR.'setup_audit_iso.php');
		}
		foreach ($setup_audit_iso as $v){
			if($convertType == 1 && $content == $v['code']){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $v['code'];
			}elseif ($convertType == 'cn' && $content == $v['code']) {
				return $v['msg_cn'];
			}
		}
	}

	/**
	 * 认证体系版本
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_audit_ver($content,$convertType = 1){
		global $setup_audit_ver;
		if(empty($setup_audit_ver)){
			include_once(SET_DIR.'setup_audit_ver.php');
		}
		foreach ($setup_audit_ver as $v){
			if($convertType == 1 && $content == $v['code']){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $v['code'];
			}
		}
	}

	/**
	 * 认证产品
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_product($content,$convertType = '1'){
		global $setup_product;
		if(empty($setup_product)){
			include_once(SET_DIR.'setup_product.php');
		}
		foreach ($setup_product as $k=>$v){
			if($convertType == '1' && $content == $v['code']){
				$content = $v['msg'];
			} elseif ($convertType == '2' && $content == $v['code']){
				$content =  $v['rules'];
			}elseif ($convertType == '0' && $content == $v['msg']){
				$content = $v['code'];
			}
		}
		return $content;
	}

	/**
	 * 产品标准
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_product_ver($content,$convertType = 1){
		global $setup_product_ver;
		if(empty($setup_product_ver)){
			include_once(SET_DIR.'setup_product_ver.php');
		}
		$content = str_replace('；', ',', $content);
		$content_arr = explode(',',$content);
		foreach((array)$content_arr as $pv){
			foreach ($setup_product_ver as $k=>$v){
				if($convertType == 1 && $pv == $v['code']){
					$content_msg []= $v['ver'];
				}elseif ($convertType == 0 && $pv == $v['msg']){
					$content_msg []= $v['code'];
				}
			}
		}
		$content_msg != '' && $content = implode('；',$content_msg);
		return $content;
	}

	/**
	 * 产品关键件
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_key_part($content,$convertType = 1){
		global $setup_key_part;
		if(empty($setup_key_part)){
			include_once(SET_DIR.'setup_key_part.php');
		}
		$content = str_replace(',', '；', $content);
		foreach ($setup_key_part as $k=>$v){
			if($convertType == 1 && strstr($content,$v['code'])){
				$content = str_replace($v['code'], $v['msg'], $content);
			} elseif ($convertType == 0 && strstr($content,$v['msg'])) {
				$content = str_replace($v['msg'], $v['code'], $content);
			}
		}
		return $content;
	}
	/**
	 * 实施规则
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_audit_rule($content,$convertType = 1){
		global $setup_audit_rule;
		if(empty($setup_audit_rule)){
			include_once(SET_DIR.'setup_audit_rule.php');
		}
		foreach ($setup_audit_rule as $v){
			if($convertType == 1 && $content == $v['code']){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $v['code'];
			}
		}
	}

	/**
	 * 检测机构
	 * @param mixed $content
	 * @return mixed
	 */
	static function cache_product_test($content,$convertType = 1){
		global $setup_product_test;
		if(empty($setup_product_test)){
			include_once(SET_DIR.'setup_product_test.php');
		}
		foreach ($setup_product_test as $k=>$v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
		return $content;
	}

	/**
	 * 自愿性认证活动
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_authentication_activity($content,$convertType = 1){
		global $setup_authentication_activity;
		if(empty($setup_authentication_activity)){
			include_once(SET_DIR.'setup_authentication_activity.php');
		}
		foreach ($setup_authentication_activity as $k=>$v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 证书状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_Certification_online($content,$convertType = 1){
		global $setup_certificate_online;
		if(empty($setup_certificate_online)){
			include_once(SET_DIR.'setup_certificate_online.php');
		}
		foreach ($setup_certificate_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 变更状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_changeitem($content,$convertType = 1){
		global $setup_changeitem;
		if(empty($setup_changeitem)){
			include_once(SET_DIR.'setup_changeitem.php');
		}
		foreach ($setup_changeitem as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 认证类型状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_type_online($content,$convertType = 1){
		global $setup_type_online;
		if(empty($setup_type_online)){
			include_once(SET_DIR.'setup_type_online.php');
		}
		foreach ($setup_type_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 劳务费状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_labor_costs_online($content,$convertType = 1){
		global $setup_labor_costs_online;
		if(empty($setup_labor_costs_online)){
			include_once(SET_DIR.'setup_labor_costs_online.php');
		}
		foreach ($setup_labor_costs_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 收费状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_Finance_online($content,$convertType = 1){
		global $cache_Finance_online;
		if(empty($cache_Finance_online)){
			include_once(SET_DIR.'setup_Finance_online.php');
		}
		foreach ($cache_Finance_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 收费项目
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_Finance_item($content,$convertType = 1){
		global $setup_finance_item;
		if(empty($setup_finance_item)){
			include_once(SET_DIR.'setup_finance_item.php');
		}
		foreach ($setup_finance_item as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 系统设置状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_setup_online($content,$convertType = 1) {
		$cache_setup_online = array('0' => '停用', '1' => '启用');
		foreach ($cache_setup_online as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 系统上下午时间状态
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_time_online($content,$convertType = 1) {
		return $content;
		/*$cache_time_online = array('0' => '上午', '1' => '下午');
		foreach ($cache_time_online as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}*/
	}

	/**
	 * 组织名称查询
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_company($zuzhi_id) {
		global $db,$dbtable;
		$zuzhi = $db->get_one("SELECT eiregistername FROM `{$dbtable['mk_company']}` WHERE id='{$zuzhi_id}'");
		$result = $zuzhi['eiregistername'];
		return $result;
	}

	/**
	 * 组织ID查询
	 * @param mixed $content
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_company_id($eientercode) {
		global $db,$dbtable;
		$zuzhi = $db->get_one("SELECT id FROM `{$dbtable['mk_company']}` WHERE id='{$eientercode}'");
		$result = $zuzhi['id'];
		return $result;
	}

	/**
	 * 合同编号查询
	 * @return mixed
	 */
	static function cache_htcode($ht_id) {
		global $db,$dbtable;
		$ht = $db->get_one("SELECT htcode FROM `{$dbtable['ht_contract']}` WHERE id = '$ht_id'");
		return $ht['htcode'];
	}
	/**
	 * 合同项目编号查询
	 * @return mixed
	 */
	static function cache_htxmcode($htxm_id) {
		global $db,$dbtable;
		$htxm = $db->get_one("SELECT htxmcode FROM `{$dbtable['ht_contract_item']}` WHERE id = '$htxm_id'");
		return $htxm['htxmcode'];
	}

	/**
	 * 任务时间查询
	 * @param date $one
	 * @param date $two
	 * @param integer $convertType
	 * @return mixed
	 */
	static function cache_tasktime($one,$two,$convertType = 1) {
		global $db,$dbtable;
		if ($convertType == 1){
			$sql = "SELECT id FROM `{$dbtable['xm_task']}` WHERE taskBeginDate>='".$one."' and taskBeginDate<='".$two."'";
		}else{
			$sql = "SELECT id FROM `{$dbtable['xm_task']}` WHERE taskEndDate>='".$one."' and taskEndDate<='".$two."'";
		}
		$query = $db->query($sql);
		while ($rows = $db->fetch_array($sql)) {
			$arr []= $rows['id'];
		}
		$result = implode('\',\'',$arr);
		return $result;
	}

	/**
	 * 过滤时间 '0000-00-00'
	 * @return mixed
	 */
	static function cache_time_value($time) {
		if ($time == '0000-00-00'){
			$result = '';
		}else{
			$result = $time;
		}
		return $result;
	}

	/**
	 * 是与否转换
	 * @return array
	 */
	static function cache_iswhether($content,$convertType=1) {
		$cache_iswhether = array('0' => '否', '1' => '是');
		foreach ($cache_iswhether as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 暂停原因
	 * @return array
	 */
	static function cache_setup_stop($content,$convertType=1) {
		global $setup_zs_stop;
		if(empty($setup_zs_stop)){
			include_once(SET_DIR.'setup_zs_stop.php');
		}
		foreach ($setup_zs_stop as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 撤销原因
	 * @return array
	 */
	static function cache_setup_revocation($content,$convertType=1) {
		global $setup_zs_revocation;
		if(empty($setup_zs_revocation)){
			include_once(SET_DIR.'setup_zs_revocation.php');
		}
		foreach ($setup_zs_revocation as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 性别显示
	 * @return array
	 */
	static function cache_sex($content,$convertType=1) {
		$cache_sex = array('01' => '男', '02' => '女');
		foreach ($cache_sex as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 政治面貌
	 * @return array
	 */
	static function cache_hr_politics($content,$convertType=1) {
		global $setup_hr_politics;
		if(empty($setup_hr_politics)){
			include_once(SET_DIR.'setup_hr_politics.php');
		}
		foreach ($setup_hr_politics as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 证件类型
	 * @return array
	 */
	static function cache_hr_certificate($content,$convertType=1) {
		global $setup_hr_certificate;
		if(empty($setup_hr_certificate)){
			include_once(SET_DIR.'setup_hr_certificate.php');
		}
		foreach ($setup_hr_certificate as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 人员性质
	 * @return array
	 */
	static function cache_hr_xingzhi($content,$convertType=1) {
		global $setup_hr_xingzhi;

		if(empty($setup_hr_xingzhi)){
			include_once(SET_DIR.'setup_hr_xingzhi.php');
		}
		foreach ($setup_hr_xingzhi as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}
	/**
	 * 人员合同类型
	 * @return array
	 */
	static function cache_hr_contract_type($content,$convertType=1) {
		global $setup_hr_contract_type;
		if(empty($setup_hr_contract_type)){
			include_once(SET_DIR.'setup_hr_contract_type.php');
		}
		foreach ($setup_hr_contract_type as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 在职离职显示
	 * @return array
	 */
	static function cache_working($content,$convertType=1) {
		$cache_sex = array('0' => '离职', '1' => '在职');
		foreach ($cache_sex as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 专职兼职显示
	 * @return array
	 */
	static function cache_zaizhi($content,$convertType=1) {
		$cache_sex = array('01' => '兼职', '02' => '专职','08' => '其他');
		foreach ($cache_sex as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

/**
	 * 审核员资格状态
	 * @return array
	 */
	static function cache_hr_reg_qualification_online($content,$convertType=1) {
		global $setup_hr_reg_qualification_online;
		if(empty($setup_hr_reg_qualification_online)){
			include_once(SET_DIR.'setup_hr_reg_qualification_online.php');
		}
		foreach ($setup_hr_reg_qualification_online as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}



	/**
	 * 职称等级
	 * @return array
	 */
	static function cache_hr_profession($content,$convertType=1) {
		global $setup_hr_profession;
		if(empty($setup_hr_profession)){
			include_once(SET_DIR.'setup_hr_profession.php');
		}
		foreach ($setup_hr_profession as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 学历
	 * @return array
	 */
	static function cache_hr_education($content,$convertType=1) {
		global $setup_hr_education;
		if(empty($setup_hr_education)){
			include_once(SET_DIR.'setup_hr_education.php');
		}
		foreach ($setup_hr_education as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 审核员选用类型
	 * @return array
	 */
	static function cache_hr_use_lev($content,$convertType=1) {
		global $setup_hr_use_lev;
		if(empty($setup_hr_use_lev)){
			include_once(SET_DIR.'setup_hr_use_lev.php');
		}
		foreach ($setup_hr_use_lev as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 加载缓存为全局数组
	 * @param string $file
	 * @param string $alias
	 */
	static function load($file,$alias=null) {
		if ($alias == null) {
			$GLOBALS[$file] = self::readCache($file);
		}else {
			$GLOBALS[$alias] = self::readCache($file);
		}
	}

	/**
	 * 省份
	 * @return array
	 */
	static function cache_province($content,$convertType=1) {
		global $setup_province;
		if(empty($setup_province)){
			include_once(SET_DIR.'setup_province.php');
		}
		foreach ($setup_province as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v['msg'];
			} elseif ($convertType == 0 && $content == $v['msg']) {
				return $k;
			}
		}
	}

	/**
	 * 检查判定
	 * @return array
	 */
	static function cache_sampling_online($content,$convertType=1) {
		global $setup_sampling_online;
		if(empty($setup_sampling_online)){
			include_once(SET_DIR.'setup_sampling_online.php');
		}
		foreach ($setup_sampling_online as $k => $v) {
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}

	/**
	 * 菜单
	 * @return array
	 */
	static function cache_menu($content,$convertType=1) {
		global $menu_arr;
		if(empty($menu_arr)){
			include_once(S_DIR.'conf/config.inc.php');
		}
		foreach ($menu_arr as $k => $v){
			if($convertType == 1 && $content == $k){
				return $v;
			} elseif ($convertType == 0 && $content == $v) {
				return $k;
			}
		}
	}
}
?>