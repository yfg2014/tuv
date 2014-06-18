<?php
!defined('IN_SUPU') && exit('Forbidden');
//include_once S_DIR.'/include/module/Report_information.php';
//include_once S_DIR.'include/setup/setup_certificate_online.php';


GrepUtil::InitGP(array('begindate','enddate','kind'));
Power::CkPower('I2S');
/********************************/
// 15.重大事故的情况 未填写
// 23.为产品P1时，填写产品认证性质 TLC 默认自愿性认证，其它机构注意修改
// 30.实施审核的关键场所名称 为空
// 31.审核组及审核成员 其中审核员资格只取数组第1位，未按审核，实习，专业比较大小
// 50.检查报告 未填写

/**********************************/
if ($begindate!='' and $enddate!='')
{
	mysql_query("TRUNCATE TABLE sys_report50"); //清空表
	//需要参数列表 begindate,enddate,sqs_id,zuzhi_id,htxm_id,ht_id,num,iso,xmid,taskId,zsid,pdid

	$msg1	=explode("-",$begindate);
	$msgxx	=$msg1[0];
	$msgyy	=$msg1[1];
	$oscym	=date("Y-m",mktime(0,0,0,$msgyy+1,01,$msgxx));		//47.机构上报年月
	$a47=$oscym; //47.机构上报年月
	$a48=$begindate; //48.取数开始日期
	$a49=$enddate; //49.取数截止日期

	//监督评定通过后上报，转机构新发证监督除外
	if($kind == '2'){
		$pd_sql="select zuzhi_id,htxm_id,ht_id,iso,id,taskId,xmid from `pd_xm`
		where (approvaldate >='".$begindate."' and approvaldate <='".$enddate."') and (audit_type='1002' or audit_type='1003' or audit_type='1004') and zs_if='1' and zsid>'0'";
		$pd_query = $db->query($pd_sql);
		while($pd  = $db->fetch_array($pd_query))
		{
			$up['zuzhi_id'] = $pd['zuzhi_id'];
			$up['htxm_id'] = $pd['htxm_id'];
			$up['ht_id'] = $pd['ht_id'];
			$up['iso'] = $pd['iso'];
			$up['xmid'] = $pd['xmid'];
			$up['taskId'] = $pd['taskId'];
			$up['pdid'] = $pd['id'];
			$up['cgid'] = '';

			if($pd['htxm_id']!='0' and $pd['htxm_id']!='')
			{
				$zs_sql = "SELECT id FROM zs_cert WHERE htxm_id = '$pd[htxm_id]' AND (online='03' OR online='01' OR online='04')";
				$zs_query = $db->query($zs_sql);
				while($zs = $db->fetch_array($zs_query))
				{
					$up['zsid'] = $zs['id'];
					$updb []= $up;
					$zsid_td []= $zs['id'];
				}
			}
		}
		//echo"<pre>";
		//print_r($updb);
		//echo"</pre>";
		$numrows += up_data($updb,'1');
	}elseif($kind == '1'){
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
		//echo"<pre>";
		//print_r($updb);
		//echo"</pre>";
		$numrows += up_data($updb,'2');
	}elseif($kind == '3'){
		$updb = array();
		//变更上报
		$cg_sql="select id,xmid,htxm_id,zsid,zuzhi_id,pdid from `zs_change` where sp_date >='".$begindate."' and sp_date <='".$enddate."' and (changeitem='01'
		or changeitem='0201' or changeitem='0202' or changeitem='0203' or changeitem='0204' or changeitem='0205' or changeitem='0206' or changeitem='0207'
		or changeitem='03' or changeitem='04' or changeitem='05' or changeitem='0501' or changeitem='06' or changeitem='0601' or changeitem='0602' or changeitem='0603'
		or changeitem='09' or changeitem='1001') and sp_online='1'";

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
		$numrows += up_data($updb,'3');

		//echo"<pre>";
		//print_r($updb_t);
		//echo"</pre>";
	}

}
//获取50项数据
function up_data($updb,$upkind)
{
	//移除相同数组单元
	if(!empty($updb)){
		$updb = unique_arr($updb);
	}
	global $db,$a47,$a48,$a49,$conf;
	$numrows = count($updb);
	foreach($updb as $v)
	{
		$fix_arr = array('a1'=>$conf['audorgid'],'a47'=>$a47,'a48'=>$a48,'a49'=>$a49);
		if($upkind == '1')
		{
		$value = $fix_arr + get_zuzhi($v[zuzhi_id]) + get_htxm($v[htxm_id]) + get_xm($v[pdid]) + get_pr($v[taskId],$v[iso]) + get_pd($v[pdid]) + get_money($v[xmid]) + get_zs($v[zsid]) ;
		}
		else if($upkind == '2')
		{
		$value = $fix_arr + get_zuzhi($v[zuzhi_id]) + get_htxm($v[htxm_id]) +  get_xm($v[pdid]) + get_pr($v[taskId],$v[iso]) + get_pd($v[pdid]) + get_money($v[xmid]) + get_zs($v[zsid]) ;
		}
		else if($upkind == '3')
		{
		$value = $fix_arr + get_zuzhi($v[zuzhi_id]) + get_htxm($v[htxm_id]) + get_xm($v[pdid]) + get_pr($v[taskId],$v[iso]) + get_pd($v[pdid]) + get_money($v[xmid]) + get_zs($v[zsid]) +  get_change($v[cgid]);
			//处理变更的审核记录
			$cg_arr = array('01','02','03','04','05','0501','06','0601','0602','0603','09','1001');
			if(in_array($value['a43'],$cg_arr))
			{
				$value['a27'] = $value['a28'] = $value['a29'] = $value['a30'] = $value['a31'] = $value['a32'] = $value['a33'] = $value['a34'] = '';
			}
		}

		$value['a43']!='01' && $value['a5'] = '';
		$value['a22']=='H0' && $value['a22'] = 'H1';
		$value['a33'] == '0' && $value['a33'] = '';
		$value['a22'] == 'P1' && $value['a24'] = '自愿性认证';
		$value['a17'] > $value['a16'] && $value['a17'] = $value['a16'];
		//print_r($db);
		DBUtil::insert_tb($db,'sys_report50',$value);
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
	global $db;
	$stat[a8] = $stat[a9] = '';
	$forum_zz = $db->get_one("select
	eiregistername,eiregistername_e,eiregistername_y,eidaima,eiaddress_code,eisc_address,eireg_address,eipro_address,eiscpostalcode,eipropostalcode,eiregpostalcode,eiphone,eilinkman_tel
	,eifax,eifaren,eikind,eizhuceziben,eiman_amount
	from mk_company where id='$zuzhi_id'");
	$stat[a3]=$forum_zz['eiregistername']; //3.组织中文名称
	$stat[a4]=$forum_zz['eiregistername_e']; //4.组织英文名称
	$stat[a5]=$forum_zz['eiregistername_y']; //5.组织原名称
	$stat[a6]=$forum_zz['eidaima']; //6.组织机构代码
	$stat[a7]=$forum_zz['eiaddress_code']; //7.组织所在地域代码

	if($forum_zz['eisc_address']!='')
	{
		$stat[a8]=$forum_zz['eisc_address']; //8.组织通讯地址
	}
	elseif($forum_zz['eireg_address']!='')
	{
		$stat[a8]=$forum_zz['eireg_address']; //8.组织注册地址
	}
	elseif($forum_zz['eipro_address']!='')
	{
		$stat[a8]=$forum_zz['eipro_address']; //8.组织生产地址
	}

	if($forum_zz['eiscpostalcode']!='')
	{
		$stat[a9]=$forum_zz['eiscpostalcode']; //9.组织通讯地址邮编
	}
	elseif($forum_zz['eipropostalcode']!='')
	{
		$stat[a9]=$forum_zz['eipropostalcode']; //9.组织生产地址邮编
	}
	elseif($forum_zz['eiregpostalcode']!='')
	{
		$stat[a9]=$forum_zz['eiregpostalcode']; //9.组织注册地址邮编
	}

	$forum_zz['eiphone'] == '' && $forum_zz['eiphone'] = $forum_zz['eilinkman_tel'];
	$stat[a10]=$forum_zz['eiphone']; //10.组织联系电话（取联系人电话）
	$stat[a11]=$forum_zz['eifax']; //11.组织联系传真
	$stat[a12]=$forum_zz['eifaren']; //12.组织法人
	$stat[a13]=$forum_zz['eikind']; //13.组织性质
	$stat[a14]=$forum_zz['eizhuceziben']; //14.组织注册资本
	if($stat[a14] == '0万元' or $stat[a14] == '0万人民币' or $stat[a14] == '万元' or $stat[a14] == '万人民币'){$stat[a14]='';}
	$stat[a16]=$forum_zz['eiman_amount']; //16.组织人数
	return $stat;
}

//查认证申请项目表
/*function get_rz($sqs_id,$iso)
{
	global $db;
	$forum_rz = $db->get_one("select iso_people_num from mk_company_rz where sqs_id='$sqs_id' and iso='$iso' limit 1");
	$stat[a17]=$forum_rz['iso_people_num']; //17.体系人数
	return $stat;
}*/

//查合同项目表
function get_htxm($htxm_id)
{
	global $db;
	$forum_htxm = $db->get_one("select iso_people_num,mark,risk,audit_ver,re_views,audit_code from ht_contract_item  where id='$htxm_id'");
	$stat[a17]=$forum_htxm['iso_people_num']; //17.体系人数
	$stat[a36] = $forum_htxm['risk']; //36.风险等级
	$stat[a42] = $old_zs['zjg_name']; //42.原颁证机构
	$stat[a25] = str_replace('；；','；',str_replace('|','；',$forum_htxm['audit_code'])); //25.专业代码
	$stat[a25] = ereg_replace("[a-z]","", $stat[a25]);
	$temp_a25 = '';
	$temp_a25 = explode('；',$stat[a25]);
	foreach($temp_a25 as $k=>$v)
	{
		if($v==''){unset($temp_a25[$k]);}
	}
	$stat[a25] = implode('；',$temp_a25);
	//$stat[shoufeiheli] = $forum_htxm['shoufeiheli']; // 收费是否合理
	/*if($forum_htxm['kind']==2)
	{
		$stat[a22]='P1';								//22.认证标准 产品则为P1
	}
	else
	{
		$stat[a22]=$forum_htxm['audit_ver'];			//22.认证标准，体系
		$stat[a22] == 'Q3' && $stat[a22] = 'Q2';
		$stat[a22] == 'Q4' && $stat[a22] = 'Q2';
	}
	if($forum_htxm['kind']==2) //为产品时提取下面数据
	{
		$stat[a23] = $forum_htxm['audit_ver']; //23.为产品P1时，填写产品标准号
		$stat[a24] = '自愿性认证'; //24.为产品P1时，填写产品认证性质 TLC 默认自愿性认证
	}*/
	$stat[a28]=$forum_htxm['re_views']; //28.复评次数
	return $stat;
}

//查审核方案表-风险等级
/*function get_fa($ht_id,$iso)
{
	global $db;
	$forum_rz = $db->get_one("select risk from  mk_shenhefangan_main where ht_id='$ht_id' and iso='$iso'");
	$stat[a36] = $forum_rz['risk']; //36.风险等级
	return $stat;
}*/
//查审核项目表
function get_xm($pdid)
{
	global $db,$begindate,$enddate;
	$stat[a27] = $stat[a32] = $stat[a33] = '';
	$onesta[a32] = $onesta['actualtaskBeginDate'] = $onesta['actualtaskEndDate'] = $onesta['taskBeginDate'] = $onesta['taskEndDate'] = $onesta[a33]= '';
	$forum_class = $db->get_one("select id,htxm_id,xmid,audit_code,audit_type,ifchangecert from pd_xm  where id='$pdid'");
	$htxm_id = $forum_class['htxm_id'];

	$a27 = $forum_class['audit_type']; //27.认证类型，29.监督次数
	switch($a27)
	{
		case '1001' : $stat[a27]='01';break;
		case '1008' :
			$stat[a27]='01';
			$onesta = $db->get_one("select actualtaskBeginDate,actualtaskEndDate,taskBeginDate,taskEndDate,xcd from xm_item where htxm_id='$htxm_id' and audit_type='1007'");
			$onesta[a33] = $onesta['xcd'];
			break;
		case '1005' : $stat[a27]='02';break;
		case '1002' : $stat[a27]='05';$stat[a29]='1';break;
		case '1003' : $stat[a27]='05';$stat[a29]='2';break;
		case '1004' : $stat[a27]='05';$stat[a29]='3';break;
		default : $stat[a27] = '';
	}

	//查其它认证类型变更，查认证类型变更表
	$sql_rz="select renzhengleixing from xm_rzlx where (pdid='$pdid' or (htxm_id='{$htxm_id}' and sp_date >='".$begindate."' and sp_date <='".$enddate."')) and sp_online='1'";
	$quer_rz = mysql_query($sql_rz);
	while($forum_rz  = mysql_fetch_array($quer_rz))
	{
		if($forum_rz['renzhengleixing'] != '')
		{
			$stat[a27] == '' ? $stat[a27] = $forum_rz['renzhengleixing'] : $stat[a27] = $stat[a27].'；'.$forum_rz['renzhengleixing'];
		}
	}

	$forum_cg = $db->get_one("SELECT changeitem,zs_change_date FROM zs_change WHERE (pdid='$pdid' or (htxm_id='{$htxm_id}' and sp_date >='".$begindate."' and sp_date <='".$enddate."')) and sp_online='1' and (changeitem='01'
		or changeitem='0201' or changeitem='0202' or changeitem='0203' or changeitem='0204' or changeitem='0205' or changeitem='0206' or changeitem='0207') ORDER BY id DESC");
	if($forum_cg['changeitem'] == '0201' or $forum_cg['changeitem'] == '0202' or $forum_cg['changeitem'] == '0203' or $forum_cg['changeitem'] == '0204' or $forum_cg['changeitem'] == '0205' or $forum_cg['changeitem'] == '0206' or $forum_cg['changeitem'] == '0207')
	{
		$forum_cg['changeitem'] = '02';
	}
	$stat[a43] = $forum_cg['changeitem'];

	//end 查认证类型表
	$forum_xm = $db->get_one("select actualtaskBeginDate,actualtaskEndDate,taskBeginDate,taskEndDate,xcd from xm_item  where id='$forum_class[xmid]'");
	if($onesta['actualtaskBeginDate'] != '0000-00-00' and $onesta['actualtaskBeginDate']!='' and $onesta['actualtaskEndDate'] != '0000-00-00' and $onesta['actualtaskEndDate']!='')
	{
		$onesta[a32] = $onesta['actualtaskBeginDate'].'；'.$onesta['actualtaskEndDate'];	//32.实际审核日期，开始-结束
	}
	elseif($onesta['taskBeginDate'] != '0000-00-00' and $onesta['taskBeginDate']!='' and $onesta['taskEndDate'] != '0000-00-00' and  $onesta['taskEndDate']!='')
	{
		$onesta[a32] = $onesta['taskBeginDate'].'；'.$onesta['taskEndDate'];	//32.实际审核日期，开始-结束
	}
	if($forum_xm != ''){
		if($forum_xm['actualtaskBeginDate'] != '0000-00-00' and $forum_xm['actualtaskEndDate'] != '0000-00-00')
		{
			$stat[a32] = $forum_xm['actualtaskBeginDate'].'；'.$forum_xm['actualtaskEndDate'];	//32.实际审核日期，开始-结束
		}
		elseif($forum_class['taskBeginDate'] != '0000-00-00' and $forum_class['taskEndDate'] != '0000-00-00')
		{
			$stat[a32] = $forum_xm['taskBeginDate'].'；'.$forum_xm['taskEndDate'];	//32.实际审核日期，开始-结束
		}
	}
	if($onesta[a32]!='')
	{
		$stat[a32] = $onesta[a32].'；'.$stat[a32];
	}
	if($forum_xm['xcd'] !='0' and $forum_xm['xcd'] !='')
	{
		if($onesta[a33] == '')
		{
			$stat[a33] = $forum_xm['xcd'].'(现场)'; //33.现场审核人数
		}
		else
		{
			$onesta[a33] == '0' && $onesta[a33] = floor($forum_xm['xcd']/2);
			$stat[a33] = $onesta[a33].'(一阶段现场)；'.$forum_xm['xcd'].'(二阶段现场)'; //33.现场审核人数
		}
	}
	return $stat;
}

//审核派人表
function get_pr($taskId,$iso)
{
	global $db;
	if($taskId > 0){
		if($iso == 'QY'){
			$ts = $db->get_one("SELECT id FROM `xm_auditor_plan` WHERE taskId='{$taskId}' and iso='{$iso}'");
			if($ts['id'] == ''){
				$iso = 'Q';
			}
		}

		$zgdb = array('1001'=>'审核员','1002'=>'高级审核员','1003'=>'注册审核员','1004'=>'实习审核员','1005'=>'技术专家');
		$sql_rw="select isLeader,auditorId,qualification,audit_code from xm_auditor_plan where taskId='$taskId' and iso='{$iso}'  and role!='1000' order by isLeader desc";
		$quer_rw = mysql_query($sql_rw);
		while($forum_rw  = mysql_fetch_array($quer_rw))
		{
			//取审核员姓名
			$pr = $db->get_one("SELECT empName FROM xm_auditor WHERE id='$forum_rw[auditorId]'");
			//31.审核组及审核成员
			$forum_rw['isLeader']=='1' ? $zuzhang = '(组长)' : $zuzhang = '';
			$forum_rw['audit_code']!='' ? $zhuanshen = '(专审)' : $zhuanshen = '';
			$forum_rw['qualification']!='' ? $zige = '('.$zgdb[$forum_rw['qualification']].')' : $zige = '';
			$a31 == '' ? $a31 = $pr['empName'].$zuzhang.$zhuanshen.$zige : $a31 = $a31.'；'.$pr['empName'].$zuzhang.$zhuanshen.$zige;
		}
	}

	/*$a31 = iconv('gbk','utf-8',$a31);
	$a31 = ereg_replace("[0-9a-zA-Z]","", $a31);
	$a31 = iconv('utf-8','gbk',$a31);*/
	$stat[a31] = $a31;

	return $stat;
}

//纪委会评定表-查评定人员
function get_pd($pdid)
{
	$a34 = '';
	$sql_jwh="select username from pd_evaluation_hr where pdid='$pdid'";
	$quer_jwh = mysql_query($sql_jwh);
	while($forum_jwh = mysql_fetch_array($quer_jwh))
	{
		$a34 == '' ? $a34 = $forum_jwh['username'] :  $a34 = $a34.'；'.$forum_jwh['username']; //34.评定人员
	}
	$stat[a34] = $a34;
	return $stat;
}

//查收费表
function get_money($xmid)
{
	$stat[a35] = $stat[a37] = '';
	$sql_jwh="select invoicemoney,invoice from cw_finance_list_ex where xmid='$xmid'";
	$quer_jwh = mysql_query($sql_jwh);
	while($forum_jwh  = mysql_fetch_array($quer_jwh))
	{
		if($invoice_temp !== $forum_jwh['invoice'])
		{
			$stat[a35] == '' ? $stat[a35] = $forum_jwh['invoicemoney'] : $stat[a35] =  $stat[a35].'；'.$forum_jwh['invoicemoney'];  //35.实收认证费用
			$stat[a37] == '' ? $stat[a37] = $forum_jwh['invoice'] : $stat[a37] =  $stat[a37].'；'.$forum_jwh['invoice'];   //37.发票号

			$invoice_temp = $forum_jwh['invoice'];
		}
	}
	return $stat;
}

//查证书表
function get_zs($zsid)
{
	global $db;
	$forum_zs = $db->get_one("select eiregistername,eiregistername_e,mark,audit_ver,certNo,firstDate,zuzhi_id,fatherzuzhi_id,coverFields,certStart,certEnd,renewaldate,certNo_y,product_ver,zsprintdate from zs_cert where id='$zsid'");
	$stat[a2]=$forum_zs['mark']; //2.认可标志
	$stat[a2] =='' && $stat[a2] = '00'; //2.认可标志为空，则赋值为 00
	$stat[a18]=$forum_zs['firstDate'];	//18.初评获证日期
	$stat[a19]=$forum_zs['certNo'];			//19.证书号
	$stat[a22] = $forum_zs['audit_ver'];//22.认证领域版本
	$stat[a23] = Cache::cache_product_ver($forum_zs['product_ver']);//23.产品认证标准编号
	/*if($forum_zs['zuzhi_id']!=$forum_zs['fatherzuzhi_id'])
	{
		$stat[a20]='是'; //20.是否子证书
		$stat[a21]='是'; //21.是否多现场
	}
	else
	{
		$stat[a20]='否'; //20.是否子证书
		$stat[a21]='否'; //21.是否多现场
	}*/
	if(strpos($forum_zs['certNo'],'-')!==false)
	{
		$stat[a20]='是'; //20.是否子证书
		$stat[a21]='否'; //21.是否多现场
	}
	else
	{
		$stat[a20]='否'; //20.是否子证书
		$stat[a21]='否'; //21.是否多现场
	}
	$stat[a26] = $forum_zs['coverFields']; //26.认证范围
	$stat[a38] = $forum_zs['certStart']; //38.发证日期
	$stat[a39] = $forum_zs['certEnd']; //39.证书截止日期
	$stat[a40] = $forum_zs['renewaldate']; //40.换证日期
	$stat[a41] = $forum_zs['certNo_y']; //41.原证书注册号

	//企业名字
	//取该企业分场所
	/*if($forum_zs[fatherzuzhi_id]!='')
	{
		$qy = $db->get_one("SELECT eiregistername FROM mk_company WHERE zuzhi_id='$forum_zs[fatherzuzhi_id]' ");
	}
	else
	{
		$qy = $db->get_one("SELECT eiregistername FROM mk_company WHERE zuzhi_id='$forum_zs[zuzhi_id]' ");
	}
	$forum_zs['eiregistername'] = $qy['eiregistername'];
	if($forum_zs['addchildkind'] == '1' or $forum_zs['addchildkind'] == '2' or $forum_zs['addchildkind'] == '3')
	{
		$qy = array();
		$addchild = implode("','",explode(',',$forum_zs['addchild']));
		$sql = "SELECT eiregistername FROM mk_company WHERE id IN('$addchild') ORDER BY id ASC ";
		$sql_query = $db->query($sql);
		while($sql_arr = $db->fetch_array($sql_query))
		{

			$qy []= $sql_arr['eiregistername'];
		}
		if($forum_zs['addchildkind'] == '1')
		{
			$qy = implode('，',$qy);
			$forum_zs['eiregistername'] .= "（含{$qy}）";
		}
		else if($forum_zs['addchildkind'] == '2')
		{
			$qy = implode('/',$qy);
			$forum_zs['eiregistername'] .= "/{$qy}";
		}
		else if($forum_zs['addchildkind'] == '3')
		{
			$forum_zs['eiregistername'] .= "（属{$forum_zs[belong]}）";
		}
	}*/
	//$stat[a3]=$forum_zs['eiregistername']; //3.组织中文名称
	//$stat[a4]=$forum_zs['eiregistername_e']; //4.组织英文名称

	return $stat;
}
//查旧认证机构
/*function get_oldzs($sqs_id,$iso)
{
	global $db;
	$old_zs = $db->get_one("select zjg_name from mk_zuzhi_shenqing_zs where sqs_id='$sqs_id' and old_renzheng='$iso'");
	$stat[a42] = $old_zs['zjg_name']; //42.原颁证机构
	return $stat;
}*/
//查变更表，变更信息
/*function get_xmchange($zsid)
{
	global $db,$begindate,$enddate;;
	$forum_cg = $db->get_one("select changeitem,changereason,zs_change_date from zs_change  where zsid = '$zsid' and up_date >='".$begindate."' and zs_change_date <='".$enddate."' and changekind='1' and changeitem!='88' and changeitem!='03' and changeitem!='04' and changeitem!='05' and changeitem!='0501' and changeitem!='06' and changeitem!='0601' and changeitem!='0602' and changeitem!='0603' and changeitem!='09' and changeitem!='1001' and online='1' ORDER BY id DESC LIMIT 1");
	if($forum_cg['changeitem'] == '0201' or $forum_cg['changeitem'] == '0202' or $forum_cg['changeitem'] == '0203' or $forum_cg['changeitem'] == '0204' or $forum_cg['changeitem'] == '0205')
	{
		$forum_cg['changeitem'] = '02';
	}
	$stat[a43] = $forum_cg['changeitem'];  //43.变更类别
	$stat[a43] == '03' ? $stat[a44] = $forum_cg['changereason'] : $stat[a44] = '';  //44.03原因
	$stat[a43] == '05' ? $stat[a45] = $forum_cg['changereason'] : $stat[a45] = '';  //45.撤销原因
	$stat[a46] = $forum_cg['zs_change_date'];  //45.变更日期
	return $stat;
}*/
//查变更表，变更信息
function get_change($cgid)
{
	global $db,$begindate,$enddate;
	$forum_cg = $db->get_one("select changeitem,changereason,zs_change_date from zs_change  where id = '$cgid' and sp_date >='".$begindate."' and sp_date <='".$enddate."' and changeitem!='99' and sp_online='1'");
	if($forum_cg['changeitem'] == '0201' or $forum_cg['changeitem'] == '0202' or $forum_cg['changeitem'] == '0203' or $forum_cg['changeitem'] == '0204' or $forum_cg['changeitem'] == '0205' or $forum_cg['changeitem'] == '0206' or $forum_cg['changeitem'] == '0207')
	{
		$forum_cg['changeitem'] = '02';
	}
	$stat[a43] = $forum_cg['changeitem'];  //43.变更类别
	$stat[a43] == '03' ? $stat[a44] = $forum_cg['changereason'] : $stat[a44] = '';  //44.03原因
	$stat[a43] == '05' ? $stat[a45] = $forum_cg['changereason'] : $stat[a45] = '';  //45.撤销原因
	$stat[a46] = $forum_cg['zs_change_date'];  //45.变更日期
	return $stat;
}


include T_DIR.'header.htm';
include T_DIR.'report/monthly_list.htm';
include T_DIR.'footer.htm';
?>