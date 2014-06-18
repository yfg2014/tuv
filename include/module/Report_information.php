<?php
/*
 * 报表信息
 */

class Report_information {
	private $db;
	public $dbtable;
	public $audorgid;

	public function __construct() {
		global $db,$dbtable,$audorgid;
		$this->db = $db;
		$this->dbtable = $dbtable;
		$this->audorgid = $audorgid;
		$this->count = null;
	}

	/*
	 * 自愿性认证活动查询
	 */
	public function authentication_activity_list($params = array()) {
		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
						FROM `{$this->dbtable['xm_item']}`
						WHERE online='3' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004' OR audit_type='1005' OR audit_type='1007' OR audit_type='1008') $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_item']}`
						WHERE online='3' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004' OR audit_type='1005' OR audit_type='1007' OR audit_type='1008') $search ORDER BY id DESC";

		$page = new listAuthentication($url, $sql);
		$list = $page->getPageData();

		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	/*
	 * 证书上报信息
	 */
	public function Certificate_reported ($SQL,$params = array()){
		$begindate = $params['begindate'];
		$enddate = $params['enddate'];
		$query = $this->db->query($SQL);
		while ($rows = $this->db->fetch_array($query))
		{
			$value = array();
			$value['a1'] = $this->audorgid; //认证机构编号
			//$value['a2'] = str_replace('|', '；', $rows['mark']); 适用于一证多标
			$value['a2'] = $rows['mark']; //认可标志
			$value['a3'] = $rows['eiregistername']; //组织名称
			$value['a4'] = $rows['eiregistername_e']; //组织英文名称
			//$value['a8'] = $rows['eisc_address'];
			//$value['a9'] = $rows['eiscpostalcode'];
			$value['a18'] = $rows['firstDate']; //初评获证日
			$value['a19'] = $rows['certNo']; //证书编号
			$value['a38'] = $rows['certStart']; //证书注册日期
			$value['a39'] = $rows['certEnd']; //证书到期时间
			if ($rows['zuzhi_id'] != $rows['fatherzuzhi_id'])
			{
				$value['a20'] = '是'; //与父ID不同则为子证书
				$value['a21'] = '否'; //默认非多场所
			}else{
				$value['a20'] = '否'; //与父ID相同则为主证书
				$value['a21'] = '否'; //默认非多场所
			}
			$value['a22'] = $rows['audit_ver']; //审核标准版本
			$value['a26'] = $rows['coverFields']; //证书覆盖范围
			switch($rows['audit_type']) //认证类型-审核类型
			{
				case '1008' : $value['a27']='01';break;
				case '1005' : $value['a27']='02';break;
				case '1002' : $value['a27']='05';break;
				case '1003' : $value['a27']='05';break;
				case '1004' : $value['a27']='05';break;
				default : $value['a27']='';
			}

			$query1 = $this->db->query("SELECT renzhengleixing FROM `{$this->dbtable['xm_rzlx']}` WHERE xmid='{$rows['xmid']}'");
			while ($rzlx = $this->db->fetch_array($query1)) //追加变更产生的认证类型(扩大缩小换版)
			{
				if ($value['a27']== ''){
					$value['a27'] = $rzlx['renzhengleixing'];
				}else{
					$value['a27'] = $value['a27'].'；'.$rzlx['renzhengleixing'];
				}
			}

			switch($rows['audit_type']) //监督次数
			{
				case '1002' : $value['a29']='1';break;
				case '1003' : $value['a29']='2';break;
				case '1004' : $value['a29']='3';break;
				default : $value['a29']='';
			}

			$com = $this->db->get_one("SELECT * FROM `{$this->dbtable['mk_company']}` WHERE id='{$rows['zuzhi_id']}'");
			//$value['a5'] = $com['eiregistername_y']; //组织原名 变更时才用到
			$value['a6'] = $com['eidaima']; //组织机构代码
			$value['a8'] = $com['eisc_address']; //通讯地址
			$value['a9'] = $com['eiscpostalcode']; //通讯地址邮编
			$value['a10'] = $com['eiphone']; //组织联系电话
			$value['a11'] = $com['eifax']; //组织联系传真
			$value['a12'] = $com['eifaren']; //组织法人
			$value['a13'] = $com['eikind']; //组织性质
			$value['a14'] = $com['eizhuceziben']; //注册资本
			$value['a16'] = $com['eiman_amount']; //组织总人数

			$htxm = $this->db->get_one("SELECT risk,iso_people_num,re_views FROM `{$this->dbtable['ht_contract_item']}` WHERE id='{$rows['htxm_id']}'");
			$value['a17'] = $htxm['iso_people_num']; //体系人数
			$value['a28'] = $htxm['re_views']; //复评次数
			$value['a36'] = $htxm['risk']; //风险登记

			if(strstr($value['a27'],'02') or strstr($value['a27'],'03') or strstr($value['a27'],'04') or strstr($value['a27'],'05'))
			{
				$value['a40'] = $rows['renewaldate']; //换证日期
				$value['a41'] = $htxm['certNo_y']; //上次发证证书编号
			}
			if(strstr($value['a27'],'04'))
			{
				$value['a41'] = $htxm['zjg_no']; //转机构原证书编号
				$value['a42'] = $htxm['zjg_name']; //转机构名称
			}

			$xm = $this->db->get_one("SELECT audit_code,taskBeginDate,taskEndDate FROM `{$this->dbtable['xm_item']}` WHERE id='{$rows['xmid']}' LIMIT 1");
			$value['a25']= $xm['audit_code']; //审核专业代码
			$value['a32'] = $xm['taskBeginDate'].'；'.$xm['taskEndDate']; //审核开始结束时间
			if ($rows['audit_type'] == '1008'){
				$xm = $this->db->get_one("SELECT taskId,taskBeginDate,taskEndDate FROM `{$this->dbtable['xm_item']}` WHERE htxm_id='{$rows['htxm_id']}' and audit_type='1007' LIMIT 1");
				$value['a32'] = $xm['taskBeginDate'].'；'.$xm['taskEndDate'].'；'.$value['a32']; //如果是2阶段审核则追加1阶段审核开始结束时间
			}

			$task = $this->db->get_one("SELECT zrd FROM `{$this->dbtable['xm_task']}` WHERE id='{$rows['taskId']}'");
			$value['a33'] = $task['zrd']; //审核人日，默认取总人日
			if ($rows['audit_type'] == '1008'){
				$task = $this->db->get_one("SELECT zrd FROM `{$this->dbtable['xm_task']}` WHERE id='{$xm['taskId']}'");
				$value['a33'] += $task['zrd']; //如果是2阶段则追加1阶段总人日
			}

			$sql2 = "SELECT * FROM `{$this->dbtable['xm_auditor']}` WHERE taskId='{$rows['taskId']}'";
			$query2 = $this->db->query($sql2);
			while ($result = $this->db->fetch_array($query2)){
				$sql3 = "SELECT * FROM `{$this->dbtable['xm_auditor_plan']}` WHERE auditorId='{$result['id']}' and iso='{$rows['iso']}' ORDER BY isLeader DESC"; //组长优先最前面
				$query3 = $this->db->query($sql3);
				if ($row = $this->db->fetch_array($query3)){
					if ($row['isLeader'] == '1'){
						$value['a31'] = $result['empName'].'(组长)'.$value['a31']; //参与审核的审核员及专家
					}else{
						if ($row['qualification'] == '1005'){
							$value['a31'] = $value['a31'].'；'.$result['empName'].'(技术专家)';
						}else{
							$value['a31'] = $value['a31'].'；'.$result['empName'];
						}
					}
				}
			}

			$pduser = array();
			$sql2 = "SELECT username FROM `{$this->dbtable['pd_evaluation_hr']}` WHERE xmid='{$rows['xmid']}' AND usertype='1'";
			$query2 = $this->db->query($sql2);
			while ($result = $this->db->fetch_array($query2)){
				$pduser []= $result['username'];
			}
			$value['a34'] = implode('；',$pduser);

			$msg = explode("-",$begindate);
			$oscym = date("Y-m",mktime(0,0,0,$msg[1]+1,01,$msg[0]));
			$value['a47'] = $oscym;
			$value['a48'] = $begindate;
			$value['a49'] = $enddate;

			DBUtil::Insert_tb($this->db, $this->dbtable['sys_report50'], $value);
		}
	}

	/*
	 * 证书变更上报信息
	 */

	public function Certificate_change_reported ($SQL,$params = array()){
		$begindate = $params['begindate'];
		$enddate = $params['enddate'];
		$query = $this->db->query($SQL);
		while ($rows = $this->db->fetch_array($query))
		{
			$value = array();
			$value['a1'] = $this->audorgid;
			//$value['a2'] = str_replace('|', '；', $rows['mark']); //一证多标适用
			$value['a2'] = $rows['mark'];
			$value['a3'] = $rows['eiregistername'];
			$value['a4'] = $rows['eiregistername_e'];

			$value['a18'] = $rows['firstDate'];
			$value['a19'] = $rows['certNo'];
			$value['a38'] = $rows['certStart'];
			$value['a39'] = $rows['certEnd'];
			if ($rows['zuzhi_id'] != $rows['fatherzuzhi_id'])
			{
				$value['a20'] = '是';
				$value['a21'] = '否';
			}else{
				$value['a20'] = '否';
				$value['a21'] = '否';
			}
			$value['a22'] = $rows['audit_ver'];
			$value['a26'] = $rows['coverFields'];
			switch($rows['audit_type'])
			{
				case '1008' : $value['a27']='01';break;
				case '1005' : $value['a27']='02';break;
				case '1002' : $value['a27']='05';break;
				case '1003' : $value['a27']='05';break;
				case '1004' : $value['a27']='05';break;
				default : $value['a27']='';
			}

			$query1 = $this->db->query("SELECT renzhengleixing FROM `{$this->dbtable['xm_rzlx']}` WHERE xmid='{$rows['xmid']}'");
			while ($rzlx = $this->db->fetch_array($query1))
			{
				if ($value['a27']== ''){
					$value['a27'] = $rzlx['renzhengleixing'];
				}else{
					$value['a27'] = $value['a27'].'；'.$rzlx['renzhengleixing'];
				}
			}

			switch($rows['audit_type'])
			{
				case '1002' : $value['a29']='1';break;
				case '1003' : $value['a29']='2';break;
				case '1004' : $value['a29']='3';break;
				default : $value['a29']='';
			}

			$com = $this->db->get_one("SELECT * FROM `{$this->dbtable['mk_company']}` WHERE id='{$rows['zuzhi_id']}'");
			if ($rows['changeitem'] == '01'){ //更名变更才取组织原名
				$value['a5'] = $rows['eiregistername_y'];
			}
			$value['a6'] = $com['eidaima'];
			$value['a8'] = $com['eisc_address'];
			$value['a9'] = $com['eiscpostalcode'];
			$value['a10'] = $com['eiphone'];
			$value['a11'] = $com['eifax'];
			$value['a12'] = $com['eifaren'];
			$value['a13'] = $com['eikind'];
			$value['a14'] = $com['eizhuceziben'];
			$value['a16'] = $com['eiman_amount'];

			$htxm = $this->db->get_one("SELECT risk,iso_people_num,re_views FROM `{$this->dbtable['ht_contract_item']}` WHERE id='{$rows['htxm_id']}'");
			$value['a17'] = $htxm['iso_people_num'];

			if(strstr($value['a27'],'02') or strstr($value['a27'],'03') or strstr($value['a27'],'04') or strstr($value['a27'],'05'))
			{
				$value['a40'] = $rows['renewaldate']; //换证日期
				$value['a41'] = $htxm['certNo_y']; //上次发证证书编号
			}
			if(strstr($value['a27'],'04'))
			{
				$value['a41'] = $htxm['zjg_no'];
				$value['a42'] = $htxm['zjg_name'];
			}

			$xm = $this->db->get_one("SELECT audit_code FROM `{$this->dbtable['xm_item']}` WHERE id='{$rows['xmid']}' LIMIT 1");
			$value['a25']= $xm['audit_code'];

			$value['a43'] = $rows['changeitem']; //变更类型

			if ($rows['changeitem'] == '03'){
				$value['a44'] = $rows['cg_reason']; //暂停原因
			}else if ($rows['changeitem'] == '05'){
				$value['a45'] = $rows['cg_reason']; //撤销原因
			}
			$value['a46'] = $rows['change_date']; //变更日期

			$msg = explode("-",$begindate);
			$oscym = date("Y-m",mktime(0,0,0,$msg[1]+1,01,$msg[0]));
			$value['a47'] = $oscym;
			$value['a48'] = $begindate;
			$value['a49'] = $enddate;

			//处理变更的审核记录
			$cg_arr = array('03','04','05','0501','06','0601','0602','0603','09','1001'); //此类变更不带入审核数据
			if(in_array($value['a43'],$cg_arr))
			{
				$value['a27'] = $value['a28'] = $value['a29'] = $value['a30'] = $value['a31'] = $value['a32'] = $value['a33'] = $value['a34'] = '';
			}

			DBUtil::Insert_tb($this->db, $this->dbtable['sys_report50'], $value);
		}
	}

	//监督通过上报
	public function Certificate_audit_reported ($SQL,$params = array()){
		$begindate = $params['begindate'];
		$enddate = $params['enddate'];
		$query = $this->db->query($SQL);
		while ($rows = $this->db->fetch_array($query))
		{
			$value = array();
			$value['a1'] = $this->audorgid;
			//$value['a2'] = str_replace('|', '；', $rows['mark']); //一证多标适用
			$value['a2'] = $rows['mark'];
			$value['a3'] = $rows['eiregistername'];
			$value['a4'] = $rows['eiregistername_e'];

			$value['a18'] = $rows['firstDate'];
			$value['a19'] = $rows['certNo'];
			$value['a38'] = $rows['certStart'];
			$value['a39'] = $rows['certEnd'];

			$value['a20'] = '否';
			$value['a21'] = '否';

			$value['a22'] = $rows['audit_ver'];
			$value['a26'] = $rows['coverFields'];
			switch($rows['audit_type'])
			{
				case '1008' : $value['a27']='01';break;
				case '1005' : $value['a27']='02';break;
				case '1002' : $value['a27']='05';break;
				case '1003' : $value['a27']='05';break;
				case '1004' : $value['a27']='05';break;
				default : $value['a27']='';
			}

			$query1 = $this->db->query("SELECT renzhengleixing FROM `{$this->dbtable['xm_rzlx']}` WHERE xmid='{$rows['xmid']}'");
			while ($rzlx = $this->db->fetch_array($query1))
			{
				if ($value['a27']== ''){
					$value['a27'] = $rzlx['renzhengleixing'];
				}else{
					$value['a27'] = $value['a27'].'；'.$rzlx['renzhengleixing'];
				}
			}

			switch($rows['audit_type'])
			{
				case '1002' : $value['a29']='1';break;
				case '1003' : $value['a29']='2';break;
				case '1004' : $value['a29']='3';break;
				default : $value['a29']='';
			}

			$com = $this->db->get_one("SELECT * FROM `{$this->dbtable['mk_company']}` WHERE id='{$rows['zuzhi_id']}'");
			/*if ($rows['changeitem'] == '01'){ //更名变更才取组织原名
				$value['a5'] = $rows['eiregistername_y'];
			}*/
			$value['a6'] = $com['eidaima'];
			$value['a8'] = $com['eisc_address'];
			$value['a9'] = $com['eiscpostalcode'];
			$value['a10'] = $com['eiphone'];
			$value['a11'] = $com['eifax'];
			$value['a12'] = $com['eifaren'];
			$value['a13'] = $com['eikind'];
			$value['a14'] = $com['eizhuceziben'];
			$value['a16'] = $com['eiman_amount'];

			$htxm = $this->db->get_one("SELECT risk,iso_people_num,re_views FROM `{$this->dbtable['ht_contract_item']}` WHERE id='{$rows['htxm_id']}'");
			$value['a17'] = $htxm['iso_people_num'];

			if(strstr($value['a27'],'02') or strstr($value['a27'],'03') or strstr($value['a27'],'04') or strstr($value['a27'],'05'))
			{
				$value['a40'] = $rows['renewaldate']; //换证日期
				$value['a41'] = $htxm['certNo_y']; //上次发证证书编号
			}
			if(strstr($value['a27'],'04'))
			{
				$value['a41'] = $htxm['zjg_no'];
				$value['a42'] = $htxm['zjg_name'];
			}

			//$xm = $this->db->get_one("SELECT audit_code FROM `{$this->dbtable['xm_item']}` WHERE id='{$rows['xmid']}' LIMIT 1");
			//$value['a25']= $xm['audit_code'];

			/*$value['a43'] = $rows['changeitem']; //变更类型

			if ($rows['changeitem'] == '03'){
				$value['a44'] = $rows['cg_reason']; //暂停原因
			}else if ($rows['changeitem'] == '05'){
				$value['a45'] = $rows['cg_reason']; //撤销原因
			}
			$value['a46'] = $rows['change_date']; //变更日期
			*/
			$msg = explode("-",$begindate);
			$oscym = date("Y-m",mktime(0,0,0,$msg[1]+1,01,$msg[0]));
			$value['a47'] = $oscym;
			$value['a48'] = $begindate;
			$value['a49'] = $enddate;

			//处理变更的审核记录
			/*$cg_arr = array('03','04','05','0501','06','0601','0602','0603','09','1001'); //此类变更不带入审核数据
			if(in_array($value['a43'],$cg_arr))
			{
				$value['a27'] = $value['a28'] = $value['a29'] = $value['a30'] = $value['a31'] = $value['a32'] = $value['a33'] = $value['a34'] = '';
			}*/

			DBUtil::Insert_tb($this->db, $this->dbtable['sys_report50'], $value);
		}
	}

	/*
	 * 证书月报
	 */
	public function monthly($where,$params = array()){
		$this->db->query("TRUNCATE TABLE  `{$this->dbtable['sys_report50']}`");
		$sql = "SELECT * FROM `{$this->dbtable['zs_cert']}` WHERE 1 {$where[0]}";
		$this->Certificate_reported($sql,$params);
		$sql = "SELECT a.changeitem,a.changereason as cg_reason,a.zs_change_date as change_date, b.* FROM `{$this->dbtable['zs_change']}` a LEFT JOIN `{$this->dbtable['zs_cert']}` b ON a.zsid=b.id WHERE 1 {$where[1]}";
		$this->Certificate_change_reported($sql,$params);

		$sql = "SELECT a.*, b.* FROM `{$this->dbtable['zs_change']}` a LEFT JOIN `{$this->dbtable['zs_cert']}` b ON a.zsid=b.id WHERE 1 {$where[2]}";
		$this->Certificate_audit_reported($sql,$params);

		$rt = $this->db->get_one("SELECT COUNT(*) AS count FROM `{$this->dbtable['sys_report50']}`");
		$this->count = $rt['count'];
	}
}

/**
 * 自愿性认证活动查询
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listAuthentication extends Page {

	protected function resultFilter($result) {
		$taskEndDate = $result['taskEndDate'];
		$com = $this->db->get_one("SELECT * FROM `{$this->dbtable['mk_company']}` WHERE id='{$result['zuzhi_id']}'");
		$result['eidaima'] = $com['eidaima'];
		$result['eilinkman'] = $com['eilinkman'];
		$result['eiphone'] = $com['eiphone'];
		$result['eiaddress_code'] = $com['eiaddress_code'];
		if($com['eipro_address']!='')
		{
			$result['eisc_address'] = $com['eipro_address'];
		}
		elseif($com['eireg_address']!='')
		{
			$result['eisc_address'] = $com['eireg_address'];
		}
		elseif($com['eisc_address']!='')
		{
			$result['eisc_address'] = $com['eisc_address'];
		}
		switch($result['taskBeginHalfDate'])
		{
			case $result['taskBeginHalfDate']<='12:00:00' : $result['taskBeginHalfDate']='A';break;
			case $result['taskBeginHalfDate']>'12:00:00' : $result['taskBeginHalfDate']='P';break;
		}
		switch($result['taskEndHalfDate'])
		{
			case $result['taskEndHalfDate']<='12:00:00' : $result['taskEndHalfDate']='A';break;
			case $result['taskEndHalfDate']>'12:00:00' : $result['taskEndHalfDate']='P';break;
		}
		$result['taskBeginDate'] = $result['taskBeginDate']." ".$result['taskBeginHalfDate'];
		$result['taskEndDate'] = $result['taskEndDate']." ".$result['taskEndHalfDate'];
		switch($result['audit_type'])
		{
			case '二阶段' : $result['audit_type']='01';break;
			case '再认证' : $result['audit_type']='02';break;
			case '监一' : $result['audit_type']='03';break;
			case '监二' : $result['audit_type']='03';break;
			case '监三' : $result['audit_type']='03';break;
			default : $result['audit_type']='';
		}

		$Auditor = new Auditor();
		$Hr_information = new Hr_information();
		$experts = $people = array();
//		if($taskEndDate > '2011-12-01' && $taskEndDate <= '2012-01-31'){	
//			if($result['iso'] == 'QY'){
//				$ts = $this->db->get_one("SELECT id FROM `{$this->dbtable['xm_auditor_plan']}` WHERE taskId='{$result['taskId']}' and iso='QY'");
//				if($ts['id'] == ''){
//					$result['iso'] = 'Q';
//				}
//			}
//		}
		$arr = $Auditor->toArray("taskId='{$result['taskId']}'");
		foreach ($arr as $v){
			$iso = explode(',', $v['iso']);
			$role = explode(',', $v['role']);
			$qualification = explode(',', $v['qualification']);
			$isLeader = explode(',', $v['isLeader']);
			foreach ($iso as $k => $vl){
				if($role[$k] != '1000'){
					if ($result['iso'] == $vl && $role[$k] == '1004'){
						$rows = $Hr_information->query($v['empId'],array('shenfenkind','cardid'));
						switch($rows['shenfenkind'])
						{
							case '00' : $rows['shenfenkind']='1';break;
							case '01' : $rows['shenfenkind']='2';break;
							case '02' : $rows['shenfenkind']='3';break;
							case '03' : $rows['shenfenkind']='4';break;
							case '04' : $rows['shenfenkind']='5';break;
							case '05' : $rows['shenfenkind']='6';break;
							default : $rows['shenfenkind']='';
						}
						$experts []=$v['empName']."，".$rows['shenfenkind']."，".$rows['cardid'];
					}else if ($result['iso'] == $vl && $role[$k] != '1004' && $isLeader[$k] != '1'){
						$people []= $v['empName'];
					}else if ($result['iso'] == $vl && $role[$k] != '1004' && $isLeader[$k] == '1') {
						$empName = $v['empName'];
					}
				}
			}
		}

		$people ['empName']= $empName;
		sort($people);
		$result['experts'] = implode("；",$experts);
		$result['people'] = implode("；",$people);
		$result['iso'] = Cache::cache_authentication_activity($result['iso']);

		return $result;
	}
}
?>