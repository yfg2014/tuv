<?php
if ($begindate!='' and $enddate!='')
{
	mysql_query("TRUNCATE TABLE sys_report50_new"); //清空表
	//需要参数列表 begindate,enddate,sqs_id,zuzhi_id,htxm_id,ht_id,num,iso,xmid,taskId,zsid,pdid

	$msg1	=explode("-",$begindate);
	$msgxx	=$msg1[0];
	$msgyy	=$msg1[1];
	$oscym	=date("Y-m",mktime(0,0,0,$msgyy+1,01,$msgxx));		//47.机构上报年月
	$a57=$begindate; //57.取数开始日期
	$a58=$enddate; //58.取数截止日期

	//监督评定通过后上报，转机构新发证监督除外
	$updb = array();
	$xm_sql="select id,zuzhi_id,htxm_id,ht_id,iso,taskId,xmid from `pd_xm`
	where (approvaldate >='".$begindate."' and approvaldate <='".$enddate."') and (audit_type='1002' or audit_type='1003' or audit_type='1004') and zs_if='1' and ifchangecert='0' and zsid>'0'";
	$xm_query = $db->query($xm_sql);
	while($xm  = $db->fetch_array($xm_query))
	{
		$up['zuzhi_id'] = $xm['zuzhi_id'];
		$up['htxm_id'] = $xm['htxm_id'];
		$up['ht_id'] = $xm['ht_id'];
		$up['iso'] = $xm['iso'];
		$up['xmid'] = $xm['xmid'];
		$up['taskId'] = $xm['taskId'];
		$up['pdid'] = $xm['id'];
		$up['cgid'] = '';

		if($xm['htxm_id']> '0')
		{
			$zs_sql = "SELECT id FROM zs_cert WHERE htxm_id = '$xm[htxm_id]' AND (online='03' OR online='01' OR online='04')";
			$zs_query = $db->query($zs_sql);
			while($zs = $db->fetch_array($zs_query))
			{
				$up['zsid'] = $zs['id'];
				$updb []= $up;
				$zsid_td []= $zs['id'];
			}
		}
	}
	ksort($updb);
	$numrows += up_data($updb,'1');
	$updb = array();
	//新发证书上报
	$zs_sql="select id,xmid,zuzhi_id,htxm_id,ht_id,iso,pdid from `zs_cert` where zsprintdate >='".$begindate."' and zsprintdate <='".$enddate."'";
	$zs_query = $db->query($zs_sql);
	while($zs  = $db->fetch_array($zs_query))
	{
		if(!in_array($zs['id'],$zsid_td))
		{
			$pd_sql="select zuzhi_id,htxm_id,ht_id,iso,id,taskId,xmid from `pd_xm` where id = '$zs[pdid]'";
			$pd = $db->get_one($pd_sql);

			$up['zuzhi_id'] = $zs['zuzhi_id'];
			$up['htxm_id'] = $zs['htxm_id'];
			$up['ht_id'] = $zs['ht_id'];
			$up['iso'] = $zs['iso'];
			$up['xmid'] = $pd['xmid'];
			$up['taskId'] = $pd['taskId'];
			$up['zsid'] = $zs['id'];
			$up['pdid'] = $zs['pdid'];
			$up['cgid'] = '';
			$updb []= $up;
		}
	}
	ksort($updb);
	$numrows += up_data($updb,'2');
	$updb = array();
	//变更上报
	$cg_sql="select id,xmid,htxm_id,zsid,zuzhi_id,pdid from `zs_change` where sp_date >='".$begindate."' and sp_date <='".$enddate."' and (
	 changeitem='03' or changeitem='04' or changeitem='05' or changeitem='0501' or changeitem='06' or changeitem='0601' or changeitem='0602' or changeitem='0603' or changeitem='09' or changeitem='1001') and sp_online='1'";
	$cg_query = mysql_query($cg_sql);
	while($cg = mysql_fetch_array($cg_query))
	{
		$pd_sql="select id,taskId,xmid from `pd_xm` where id = '$cg[pdid]'";
		$pd = $db->get_one($pd_sql);
		$zst =  $db->get_one("select ht_id,iso from zs_cert where id = '$cg[zsid]'");
		$up['zsid'] = $cg['zsid'];
		$up['zuzhi_id'] = $cg['zuzhi_id'];
		$up['htxm_id'] = $cg['htxm_id'];
		$up['ht_id'] = $zst['ht_id'];
		$up['iso'] = $zst['iso'];
		$up['xmid'] = $pd['xmid'];
		$up['taskId'] = $pd['taskId'];
		$up['pdid'] = $cg['pdid'];
		$up['cgid'] = $cg['id'];
		$updb []= $up;

	}
	ksort($updb);
	$numrows += up_data($updb,'3');
}
//获取50项数据
function up_data($updb,$upkind)
{
	//移除相同数组单元
	if(!empty($updb)){
		$updb = unique_arr($updb);
	}

	global $db,$a57,$a58,$conf;
	$numrows = count($updb);
	foreach($updb as $v)
	{
		$fix_arr = array('a1'=>$conf['audorgid'],'a57'=>$a57,'a58'=>$a58);
		if($upkind == '1')
		{
			$value = $fix_arr + get_zuzhi($v[zuzhi_id]) + get_htxm($v[htxm_id]) + get_xm($v[pdid],$v[htxm_id]) + get_pd($v[pdid]) + get_money($v[xmid],$v[ht_id]) + get_zs($v[zsid]);
		}
		else if($upkind == '2')
		{
			$value = $fix_arr + get_zuzhi($v[zuzhi_id]) + get_htxm($v[htxm_id]) + get_xm($v[pdid],$v[htxm_id]) + get_pd($v[pdid]) + get_money($v[xmid],$v[ht_id]) + get_zs($v[zsid]);
		}
		else if($upkind == '3')
		{
			$value = $fix_arr + get_zuzhi($v[zuzhi_id]) + get_htxm($v[htxm_id]) + get_xm($v[pdid],$v[htxm_id]) + get_pd($v[pdid]) + get_money($v[xmid],$v[ht_id]) + get_zs($v[zsid]);
		}

		DBUtil::insert_tb($db,'sys_report50_new',$value);
	}
	return $numrows;
}

//默认当月开始和结束时间
if ($begindate=='' or $enddate=='')
{
	if($ydate==""){$ydate=date("Y");}
	if($mdate==""){$mdate=date("m");}
	$tianshu=date("t",mktime(0,0,0,$mdate,01,$ydate));			//获得当月天数

	$begindate	=$ydate."-".$mdate."-01";						//48.取数开始日期
	$enddate	=$ydate."-".$mdate."-".$tianshu;				//49.取数截止日期
}

/************************  function  函数部分  ****************************/

//二维数组去重复项
function unique_arr($array2D){
	$k_arr = array_keys($array2D[0]);
	foreach ($array2D as $v){
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

//==============  取企业信息 ==============//
function get_zuzhi($zuzhi_id)
{
	global $db,$setup_new_com;
	$stat[a8] = $stat[a9] = '';
	$forum_zz = $db->get_one("select
	eiregistername,eiregistername_e,eiregistername_y,eidaima,eiaddress_code,eisc_address,eireg_address,eipro_address,eiscpostalcode,eipropostalcode,eiregpostalcode,eiphone,eilinkman_mob
	,eifax,eifaren,eikind,eizhuceziben,eiman_amount,country_area,industry,htfrom,money_unit
	from mk_company where id='$zuzhi_id'");
	$stat[a3]=$forum_zz['eiregistername']; //3.组织中文名称
	$stat[a2]=Cache::cache_htfrom($forum_zz['htfrom'],'grantNo'); //2.分支机构批准号
	$stat[a4]=$forum_zz['eiregistername_e']; //4.组织英文名称
	$stat[a5]=$forum_zz['eiregistername_y']; //5.组织原名称
	$stat[a6]=$forum_zz['eidaima']; //6.组织机构代码
	$stat[a8]=$forum_zz['industry']; //8.所属行业
	$stat[a9]=$forum_zz['country_area']; //9.所国家区域代码
	$stat[a10]=$forum_zz['eiaddress_code']; //10.组织所在地域代码
	if($forum_zz['eisc_address']!='')
	{
		$stat[a11]=$forum_zz['eisc_address']; //11.组织通讯地址
	}
	elseif($forum_zz['eireg_address']!='')
	{
		$stat[a11]=$forum_zz['eireg_address']; //11.组织注册地址
	}
	elseif($forum_zz['eipro_address']!='')
	{
		$stat[a11]=$forum_zz['eipro_address']; //11.组织生产地址
	}

	if($forum_zz['eiscpostalcode']!='')
	{
		$stat[a12]=$forum_zz['eiscpostalcode']; //12.组织通讯地址邮编
	}
	elseif($forum_zz['eipropostalcode']!='')
	{
		$stat[a12]=$forum_zz['eipropostalcode']; //12.组织生产地址邮编
	}
	elseif($forum_zz['eiregpostalcode']!='')
	{
		$stat[a12]=$forum_zz['eiregpostalcode']; //12.组织注册地址邮编
	}

	$forum_zz['eiphone'] == '' && $forum_zz['eiphone'] = $forum_zz['eilinkman_mob'];
	$stat[a13]=$forum_zz['eiphone']; //13.组织联系电话（取联系人电话）
	$stat[a14]=$forum_zz['eifax']; //14.组织联系传真
	$stat[a15]=$forum_zz['eifaren']; //15.组织法人
	$stat[a16]=$setup_new_com[$forum_zz['eikind']]; //16.组织性质
	$stat[a17]=$forum_zz['eizhuceziben']; //17.组织注册资本
	$stat[a18]=$forum_zz['money_unit']; //18.注册资本币种
	$stat[a19]=$forum_zz['eiman_amount']; //19.组织人数
	return $stat;
}

//查合同项目表
function get_htxm($htxm_id)
{
	global $db;
	$forum_htxm = $db->get_one("select iso_people_num,mark,risk,audit_ver,re_views,audit_code from ht_contract_item  where id='$htxm_id'");
	$stat[a20]=$forum_htxm['iso_people_num']; //17.体系人数
	$stat[a47] = $forum_htxm['risk']; //36.风险等级
	$stat[a28] = str_replace('；；','；',str_replace('|','；',$forum_htxm['audit_code'])); //25.专业代码
	$stat[a28] = ereg_replace("[a-z]","", $stat[a28]);
	$temp_a28 = '';
	$temp_a28 = explode('；',$stat[a28]);
	foreach($temp_a28 as $k=>$v)
	{
		if($v==''){unset($temp_a28[$k]);}
	}
	$stat[a28] = implode('；',$temp_a28);
	$stat[a38]=$forum_htxm['re_views']; //28.复评次数
	return $stat;
}

//查审核项目表
function get_xm($pdid,$htxm_id)
{
	global $db,$begindate,$enddate;
	$stat[a30] = $stat[a39] = $stat[a40] = $stat[a41] = $stat[a42] = '';
	$onesta[a40] = $onesta[a42]= $onesta['actualtaskBeginDate'] = $onesta['actualtaskEndDate'] = $onesta['taskBeginDate'] = $onesta['taskEndDate'] = '';
	$forum_class = $db->get_one("select id,xmid,audit_code,audit_type,ifchangecert,approvaldate from pd_xm  where id='$pdid'");


	$a30 = $forum_class['audit_type']; //27.认证类型，29.监督次数
	switch($a30)
	{
		case '1001' : $stat[a30]='01';break;
		case '1008' :
			$stat[a30]='01';
			$onesta = $db->get_one("select actualtaskBeginDate,actualtaskEndDate,taskBeginDate,taskEndDate,xcd from xm_item where htxm_id='$htxm_id' and audit_type='1007'");
			$stat[a42] = $onesta['xcd'];
			break;
		case '1005' : $stat[a30]='02';break;
		case '1002' : $stat[a30]='03';$stat[a39]='1';break;
		case '1003' : $stat[a30]='03';$stat[a39]='2';break;
		case '1004' : $stat[a30]='03';$stat[a39]='3';break;
		default : $stat[a30] = '99';
	}
	$stat[a33] = '0';
	if($a30 !='1001' && $a30 !='1005'){
		$forum_class[ifchangecert] == '1' && $stat[a33] = '1'; //是否换证
	}

	//查其它认证类型变更，查认证类型变更表
	global $setup_changeitem_iso_db;
	$changeitem31 = array();
	$sql_rz="select changerange,renzhengleixing,sp_date from xm_rzlx where pdid='$pdid' and pdid>'0' and renzhengleixing!='' and sp_online='1'";
	$quer_rz = mysql_query($sql_rz);
	while($forum_rz  = mysql_fetch_array($quer_rz))
	{
		$forum_rz['renzhengleixing'] = $forum_rz['renzhengleixing'].$forum_rz['changerange'];
		$setup_changeitem_iso_db['rzlx'][$forum_rz['renzhengleixing']] !='' && $changeitem31 []= $setup_changeitem_iso_db['rzlx'][$forum_rz['renzhengleixing']];
		$stat[a56] = $forum_rz['sp_date']; //56.变更时间
	}

	$ck_cg_q = $db->query("SELECT changeitem,sp_date FROM zs_change WHERE ((pdid='$pdid' and pdid>'0') or (htxm_id='{$htxm_id}' and htxm_id>'0' and sp_date >='".$begindate."' and sp_date <='".$enddate."')) and changeitem!='99' and sp_online='1'");
	while($ck_cg = $db->fetch_array($ck_cg_q)){
		$setup_changeitem_iso_db['cg'][$ck_cg['changeitem']] !='' && $changeitem31 []= $setup_changeitem_iso_db['cg'][$ck_cg['changeitem']];
		$stat[a56] = $ck_cg['sp_date']; //56.变更时间
	}
	$stat[cg_date] = $stat[a56];
	($stat[a30]=='03' and $stat[a56] == '') && $stat[a56] = $forum_class['approvaldate'];
	$stat[a31] = implode('；',array_unique($changeitem31));
	if($a30 !='1001' && $a30!='1008' && $a30!='1002' && $a30!='1003' && $a30!='1004'){
		if($stat[a31] !='' && $stat[a30] == '99'){
			$stat[a30] = '04';
			$last_xm = $db->get_one("SELECT audit_type FROM xm_item WHERE id!='$forum_class[id]' AND htxm_id='$htxm_id' AND htxm_id>'0' AND online='3' AND (audit_type='1008' OR audit_type='1001' OR audit_type='1005' OR audit_type='1002' OR audit_type='1003' OR audit_type='1004') ORDER BY id DESC LIMIT 1");
			if($last_xm['audit_type']=='1008' || $last_xm['audit_type']=='1001'){
				$stat[a32] = '01';
			}else if($last_xm['audit_type']=='1005'){
				$stat[a32] = '02';
			}else {
				$stat[a32] = '03';
			}
		}
	}

	$forum_xm = $db->get_one("select actualtaskBeginDate,actualtaskEndDate,taskBeginDate,taskEndDate,xcd from xm_item  where id='$forum_class[xmid]'");

	//end 查认证类型表
	$stat[a40] = $forum_xm['taskBeginDate']; //a41审核开始日期
	$stat[a41] = $forum_xm['taskEndDate'];//a42审核结束日期
	if($onesta['taskBeginDate'] != ''){
		$stat[a40] = $onesta['taskBeginDate'];
	}
	if($forum_xm['xcd'] > '0')
	{
		$stat[a42] += $forum_xm['xcd']; //42.现场审核人数
	}

	//判断43项 是否结合审核
	if($stat[a42] != '')
	{
		$num = 0;
		if($forum_class['taskId']>0){
			$q_iso = $db->query("SELECT id FROM xm_item WHERE taskId='{$forum_class[taskId]}' AND taskId>'0' GROUP BY iso");
			while($iso_row = $db->fetch_array($q_iso)){
				$num++;
			}
		}
		$num > 1 ? $stat[a59] = '1' : $stat[a59] = '0';
	}

	$stat[xm_date]=$forum_class['approvaldate'];
	return $stat;
}

//纪委会评定表-查评定人员
function get_pd($pdid)
{
	$a43 = '';
	$sql_jwh="select username from pd_evaluation_hr where pdid='$pdid' AND pdid>'0'";
	$quer_jwh = mysql_query($sql_jwh);
	while($forum_jwh = mysql_fetch_array($quer_jwh))
	{
		$a43 == '' ? $a43 = $forum_jwh['username'] :  $a43 = $a43.'；'.$forum_jwh['username']; //43.评定人员
	}
	$stat[a43] = $a43;
	return $stat;
}

//查收费表
function get_money($xmid,$ht_id)
{
	global $db;
	$stat[a44] = $stat[a45] =  $stat[a46] = $invoice_temp = '';
	$sql_jwh="select invoicemoney,invoice,money_unit from cw_finance_list_ex where xmid='$xmid' AND xmid>'0'";
	$quer_jwh = mysql_query($sql_jwh);
	while($forum_jwh  = mysql_fetch_array($quer_jwh))
	{
		if($invoice_temp !== $forum_jwh['invoice'])
		{
			$stat[a44] += $forum_jwh['invoicemoney'];  //44.实收认证费用
			$stat[a45] = $forum_jwh['money_unit'];
			$stat[a46] == '' ? $stat[a46] = $forum_jwh['invoice'] : $stat[a46] =  $stat[a46].'；'.$forum_jwh['invoice'];  //45.实收费用币种

			$invoice_temp = $forum_jwh['invoice'];
		}
	}
	$stat[a45] == '' && $stat[a45] = '01';//45.实收费用币种
	if($stat[a44] == ''){
		$q_f = $db->query("SELECT contract_money FROM `cw_finance_item` WHERE ht_id = '{$ht_id}' AND ht_id>'0'");
		while($row_f = $db->fetch_array($q_f)){
			$stat[a44] += $row_f['contract_money'];
		}
	}
	if($stat[a46] == ''){
		$stat[a46] = Cache::cache_htcode($ht_id);
	}

	return $stat;
}

//查证书表
function get_zs($zsid)
{
	global $db,$setup_new_certificate_online,$setup_new_zs_stop,$setup_new_zs_revocation,$setup_new_iso;
	$forum_zs = $db->get_one("select * from zs_cert where id='$zsid'");
	$stat[a7]=$forum_zs['mark']; //7.认可标志
	$stat[a7] =='' && $stat[a7] = '00'; //7.认可标志为空，则赋值为 00
	$stat[a21]=$forum_zs['firstDate'];	//21.初评获证日期
	$stat[a22]=$forum_zs['certNo'];			//22.证书号
	$stat[a26] = $setup_new_iso[$forum_zs['iso']];//26.认证领域版本

	if(strpos($forum_zs['certNo'],'-') !== false)
	{
		$stat[a23]= '1'; //23.是否子证书
		$stat[a24]= $forum_zs['main_certNo']; //24.主证书编号
		$stat[a25]= '0'; //25.是否多现场
	}
	else
	{
		$stat[a23]= '0'; //23.是否子证书
		$stat[a25]= '0'; //25.是否多现场
	}

	$stat[a34] = $forum_zs['renewal_reason']; //34.换证原因
	$stat[a29] = $forum_zs['coverFields']; //29.认证范围
	$stat[a48] = $forum_zs['certStart']; //48.发证日期
	$stat[a49] = $forum_zs['certEnd']; //49.证书截止日期
	 //当第33项为“是”，在本栏填写上一个证书的注册号

		$stat[a37]=$forum_zs['renewaldate']; //37.换证日期
		$stat[a35]=$forum_zs['certNo_y']; //35.原证书注册号
		$stat[a36]=$forum_zs['zjg_name']; //36.原证书办法机构


	$stat[a50] = $setup_new_certificate_online[$forum_zs['online']];//50.证书状态
	if($stat[a50] == '02'){
		$stat[a51] = $setup_new_zs_stop[$forum_zs['changereason']];//51.暂停原因
		$stat[a52] = $forum_zs['zs_change_date'];//52.暂停时间
		$stat[a53] = $forum_zs['zs_zanting_edate'];//53.暂停到期时间
	}else if($stat[a50] == '03'){
		$stat[a54] = $setup_new_zs_revocation[$forum_zs['changereason']];//54.撤销原因
		$stat[a54] == '' && $stat[a54] = $forum_zs['changereason'];
		$stat[a55] = $forum_zs['zs_change_date'];//55.暂停时间
	}
	$stat[zs_date]=$forum_zs['zsprintdate'];

	return $stat;
}
?>
