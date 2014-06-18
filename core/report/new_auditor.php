<?php
$zg_arr_db = array('1002'=>'01','1003'=>'02','1004'=>'03','1005'=>'04','1001'=>'01');
$shenfen_kind_arr_db = array('00'=>'01','01'=>'02','02'=>'03','04'=>'04','03'=>'06','05'=>'99');
$role_arr_db = array();
if ($begindate!='' and $enddate!=''){
	mysql_query("TRUNCATE TABLE sys_report_auditor"); //清空表
	$up = $updb = array();
	$xm_sql="select id,zuzhi_id,htxm_id,iso,taskId,actualtaskBeginDate,actualtaskEndDate,taskBeginDate,taskEndDate,approvaldate from xm_item
	where (approvaldate >='".$begindate."' and approvaldate <='".$enddate."') and (audit_type!='1001' and audit_type!='1007' and audit_type!='1008' and audit_type!='1005' and ifchangecert='0') and zs_if='1' and zsid>'0' and taskId!='0'";
	$xm_query = $db->query($xm_sql);
	while($xm  = $db->fetch_array($xm_query)){
		$qy = $db->get_one("SELECT eiregistername FROM mk_company WHERE id='$xm[zuzhi_id]'");
		$zs_q = $db->query("SELECT certNo FROM zs_cert WHERE htxm_id='$xm[htxm_id]'");
		while($zs = $db->fetch_array($zs_q)){
			$pr_q = $db->query("SELECT id,empId,empName FROM xm_auditor WHERE taskId='$xm[taskId]' and taskId>0");
			while($pr = $db->fetch_array($pr_q)){
				$xm['iso'] == 'QY' && $xm['iso'] = 'Q';
				$au_zg = $db->get_one("SELECT isLeader,role,qualification,witness,audit_code FROM xm_auditor_plan WHERE auditorId='$pr[id]' and iso='$xm[iso]'");
				$hr = $db->get_one("SELECT shenfenkind,cardid,worktype FROM hr_information WHERE id='$pr[empId]'");
				$hr_zg = $db->get_one("SELECT qualification_no FROM hr_reg_qualification WHERE hr_id='$pr[empId]' and iso='$xm[iso]' and qualification='$au_zg[qualification]'");
				$up=array();
				$up['xm_date'] = $xm['approvaldate'];
				$up['zs_date'] = '';
				$up['eiregistername'] = $qy['eiregistername'];
				$up['iso'] = $xm['iso'];
				$up['a1'] = $conf['audorgid'];			//1.机构批准号
				$up['a2'] = $zs['certNo'];				//2.证书编号
				$up['a3'] = $xm['taskBeginDate'];		//3.审核开始时间
				$up['a4'] = '99';						//4.是否一二阶段
				$up['a5'] = $xm['taskEndDate'];		//5.审核结束时间
				$up['a6'] = $pr['empName'];	//6.审核员姓名
				$up['a7'] = $shenfen_kind_arr_db[$hr['shenfenkind']];			//7.审核员证件类型
				$up['a8'] = $hr['cardid'];				//8.审核员身份证号码
				$up['a9'] = $zg_arr_db[$au_zg['qualification']];	//9.资格类型代码
				$up['a10'] = $hr_zg['qualification_no'];			//10.资格证书编号
				$au_zg['isLeader'] == '1' ? $up['a11'] = '01' : $up['a11'] = '02'; //11.审核员身份角色 组织，组员，专家
				($au_zg['role'] == '1004' && $up['a11']=='02') && $up['a11'] = '03';
				$au_zg['audit_code'] != '' ? $up['a12'] = '1' : $up['a12'] = '0'; //12.是否专业审核员
				$up['a13'] = '';
				$hr['worktype'] == '01' && $up['a13'] = '0'; //13.专职兼职
				$hr['worktype'] == '02' && $up['a13'] = '1';
				$up['a14'] = '';
				$au_zg['witness'] == '1' && $up['a14'] = '01'; //14.见证人或被见证人
				$au_zg['witness'] == '2' && $up['a14'] = '02';
				ksort($up);
				$updb []= $up;
			}
		}
	}
	//发证
	$zs_q = $db->query("SELECT certNo,zuzhi_id,htxm_id,xmid,zsprintdate FROM zs_cert WHERE zsprintdate>='$begindate' AND zsprintdate<='$enddate'");
	while($zs = $db->fetch_array($zs_q)){
		$qy = $db->get_one("SELECT eiregistername FROM mk_company WHERE id='$xm[zuzhi_id]'");
		$xm_sql="select id,zuzhi_id,htxm_id,iso,taskId,actualtaskBeginDate,actualtaskEndDate,taskBeginDate,taskEndDate,approvaldate,audit_type from xm_item
		where taskId!='0' and
		(
			(htxm_id='$zs[htxm_id]' and (audit_type='1001' or audit_type='1007' or audit_type='1008' or audit_type='1005'))
				OR
			(ifchangecert='1' and id='$zs[xmid]')
		)
		";
		$xm_query = $db->query($xm_sql);
		while($xm  = $db->fetch_array($xm_query)){
			$pr_q = $db->query("SELECT id,empId,empName FROM xm_auditor WHERE taskId='$xm[taskId]'");
			while($pr = $db->fetch_array($pr_q)){
				$xm['iso'] == 'QY' && $xm['iso'] = 'Q';
				$au_zg = $db->get_one("SELECT isLeader,role,qualification,witness,audit_code FROM xm_auditor_plan WHERE auditorId='$pr[id]' and iso='$xm[iso]'");
				$hr = $db->get_one("SELECT shenfenkind,cardid,worktype FROM hr_information WHERE id='$pr[empId]'");
				$hr_zg = $db->get_one("SELECT qualification_no FROM hr_reg_qualification WHERE hr_id='$pr[empId]' and iso='$xm[iso]' and qualification='$au_zg[qualification]'");
				$up=array();
				$up['xm_date'] = '';
				$up['zs_date'] = $zs['zsprintdate'];
				$up['eiregistername'] = $qy['eiregistername'];
				$up['iso'] = $xm['iso'];
				$up['a1'] = $conf['audorgid'];			//1.机构批准号
				$up['a2'] = $zs['certNo'];				//2.证书编号
				$up['a3'] = $xm['taskBeginDate'];		//3.审核开始时间
				if($xm['audit_type'] == '1007'){
					$up['a4'] = '01';					//4.是否一二阶段
				}else if($xm['audit_type'] == '1008'){
					$up['a4'] = '02';
				}else{
					$up['a4'] = '99';
				}
				$up['a5'] = $xm['taskEndDate'];		//5.审核结束时间
				$up['a6'] = $pr['empName'];	//6.审核员姓名
				$up['a7'] = $shenfen_kind_arr_db[$hr['shenfenkind']];			//7.审核员证件类型
				$up['a8'] = $hr['cardid'];				//8.审核员身份证号码
				$up['a9'] = $zg_arr_db[$au_zg['qualification']];	//9.资格类型代码
				$up['a10'] = $hr_zg['qualification_no'];			//10.资格证书编号
				$au_zg['isLeader']=='1' ? $up['a11'] = '01' : $up['a11'] = '02'; //11.审核员身份角色 组织，组员，专家
				($pr['role'] == '1004' && $up['a11']=='02') && $up['a11'] = '03';
				$pr['audit_code'] != '' ? $up['a12'] = '1' : $up['a12'] = '0'; //12.是否专业审核员
				$up['a13'] = '';
				$hr['worktype'] == '01' && $up['a13'] = '0'; //13.专职兼职
				$hr['worktype'] == '02' && $up['a13'] = '1';
				$up['a14'] = '';
				$au_zg['witness'] == '1' && $up['a14'] = '01'; //14.见证人或被见证人
				$au_zg['witness'] == '2' && $up['a14'] = '02';
				ksort($up);
				$updb []= $up;
			}
		}
	}

	if(!empty($updb)){$updb = unique_arr($updb);}
	$numrows = count($updb);
	foreach($updb as $value){
		DBUtil::insert_tb($db,'sys_report_auditor',$value);
	}
}
//二维数组去重复项
function unique_arr($array2D){
	$k_arr = array_keys($array2D[0]);
	foreach ((array)$array2D as $v){
		 $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		 $temp[] = $v;
	 }
	 $temp = array_unique($temp);    //去掉重复的字符串,也就是重复的一维数组
	foreach ($temp as $k => $v){
		$temp[$k] = explode(",",$v);   //再将拆开的数组重新组装
		$temp[$k] = array_combine($k_arr,$temp[$k]);
	}

	return $temp;
}

?>
