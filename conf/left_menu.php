<?php
$left_menu = array(
/*	'integrated'=>array(
		array(
		'title' => '组织信息查询',
		'itemurl' => 'index.php?m=company&do=qiye_list',
		'power' => 'A0S'
		),
		array(
		'title' => '合同信息查询',
		'itemurl' => 'index.php?m=contract&do=contract_list&',
		'power' => 'B0S'
		),
		array(
		'title' => '项目派人查询',
		'itemurl' => 'index.php?m=audit&do=item_people_list',
		'power' => 'C6S'
		),
		array(
		'title' => '审核项目查询',
		'itemurl' => 'index.php?m=audit&do=item_list',
		'power' => 'C4S'
		),
		array(
		'title' => '认证评定查询',
		'itemurl' => 'index.php?m=assess&do=pd_xm_list',
		'power' => 'D0S'
		),
		array(
		'title' => '财务收费明细',
		'itemurl' => 'index.php?m=finance&do=details_finance_list',
		'power' => 'G2S'
		),
		array(
		'title' => '证书查询',
		'itemurl' => 'index.php?m=certificate&do=cert_list',
		'power' => 'E0S'
		),
		array(
		'title' => '证书变更查询',
		'itemurl' => 'index.php?m=change&do=zs_change_list',
		'power' => 'F0S'
		),
		array(
		'title' => '合同费划拨',
		'itemurl' => 'index.php?m=contract&do=contract_transfer',
		'power' => 'B1B'
		),
		array(
		'title' => '合同费划拨修改明细',
		'itemurl' => 'index.php?m=contract&do=contract_basket_list',
		'power' => 'B2C'
		)
	),*/
	'company'=>array(
		array(
		'title' => '组织信息登记',
		'itemurl' => 'index.php?m=company&do=qiyedengji_edit',
		'power' => 'A0E'
		),
		array(
		'title' => '组织文档上传',
		'itemurl' => 'index.php?m=company&do=qiyedengji_upload',
		'power' => 'A1E'
		),
		array(
		'title' => '组织信息查询',
		'itemurl' => 'index.php?m=company&do=qiye_list',
		'power' => 'A0S'
		),
		array(
		'title' => '组织文档查询',
		'itemurl' => 'index.php?m=company&do=qiyedengji_upload_list',
		'power' => 'A1S'
		),
		/*array(
		'title' => '组织费用登记',
		'itemurl' => 'index.php?m=company&do=qiyedengji_finance',
		'power' => 'A1C'
		),
		array(
		'title' => '组织费用查询',
		'itemurl' => 'index.php?m=company&do=qiyedengji_finance_list',
		'power' => 'A2C'
		),
		array(
		'title' => '客户投诉登记',
		'itemurl' => 'index.php?m=company&do=qiyedengji_complaint',
		'power' => 'A2E'
		),
		array(
		'title' => '客户投诉查询',
		'itemurl' => 'index.php?m=company&do=qiyedengji_complaint_list',
		'power' => 'A2S'
		),
		array(
		'title' => '组织礼品发放记录',
		'itemurl' => 'index.php?m=gift&do=qiye_gift_list',
		'power' => 'A3S'
		),*/
	),

	'contract'=>array(
		array(
		'title' => '合同登记',
		'itemurl' => 'index.php?m=contract&do=contract_registration',
		'power' => 'B0E'
		),
		array(
		'title' => '合同费用登记',
		'itemurl' => 'index.php?m=finance&do=finance_list',
		'power' => 'G0E'
		),
		array(
		'title' => '未登记完合同',
		'itemurl' => 'index.php?m=contract&do=contract_list&online=0',
		'power' => 'B0E'
		),
		array(
		'title' => '已登记合同',
		'itemurl' => 'index.php?m=contract&do=contract_list&online=1',
		'power' => 'B0S'
		),
		array(
		'title' => '已评审合同',
		'itemurl' => 'index.php?m=contract&do=contract_list&online=2',
		'power' => 'B0S'
		),
//		array(
//		'title' => '已签约合同',
//		'itemurl' => 'index.php?m=contract&do=contract_list&online=4',
//		'power' => 'B0S'
//		),
		array(
		'title' => '已审批合同',
		'itemurl' => 'index.php?m=contract&do=contract_list&online=3',
		'power' => 'B0S'
		),
		array(
		'title' => '合同项目查询',
		'itemurl' => 'index.php?m=contract&do=contractitem_list',
		'power' => 'B1S'
		)
//		array(
//		'title' => '产品抽样查询',
//		'itemurl' => 'index.php?m=contract&do=contract_sampling_list',
//		'power' => 'B3S'
//		)
	),

	'sv'=>array(
		array(
		'title' => '监督维护',
		'itemurl' => 'index.php?m=audit&do=xm_maintain_list',
		'power' => 'C8S'
		),
		array(
		'title' => '再认证维护',
		'itemurl' => 'index.php?m=audit&do=complex_list',
		'power' => 'C9S'
		)
	),
	'audit'=>array(
		array(
		'title' => '审核员行程',
		'itemurl' => './index.php?m=report&do=auditor_plan',
		'power' => 'C6S'
		),
		array(
		'title' => '审核员请假',
		'itemurl' => './index.php?m=audit&do=ask_for_leave_list',
		'power' => 'Z4S'
		),
		array(
		'title' => '审核员值班',
		'itemurl' => './index.php?m=audit&do=on_duty_list',
		'power' => 'Z8S'
		),
		array(
		'title' => '审核员培训',
		'itemurl' => './index.php?m=audit&do=auditor_train_list',
		'power' => 'Z7S'
		),
		array(
		'title' => '未安排项目',
		'itemurl' => 'index.php?m=audit&do=xm_no_list',
		'power' => 'C0S'
		),
		array(
		'title' => '已安排项目',
		'itemurl' => 'index.php?m=audit&do=approval_list&xmonline=1',
		'power' => 'C1S'
		),
		array(
		'title' => '已派人项目',
		'itemurl' => 'index.php?m=audit&do=approval_list&xmonline=2',
		'power' => 'C1S'
		),
		array(
		'title' => '已审批项目',
		'itemurl' => 'index.php?m=audit&do=approval_list&xmonline=3',
		'power' => 'C1S'
		),

		array(
		'title' => '项目派人查询',
		'itemurl' => 'index.php?m=audit&do=item_people_list',
		'power' => 'C6S'
		),
		array(
		'title' => '审核项目查询',
		'itemurl' => 'index.php?m=audit&do=item_list',
		'power' => 'C4S'
		),
//		array(
//		'title' => '劳务费发放',
//		'itemurl' => 'index.php?m=audit&do=labor_costs_list&online=0',
//		'power' => 'C5S'
//		),
		array(
		'title' => '劳务费查询',
		'itemurl' => 'index.php?m=audit&do=labor_costs_show',
		'power' => 'C5S'
		),
		array(
		'title' => '资料回收',
		'itemurl' => 'index.php?m=audit&do=item_redata_list',
		'power' => 'C7S'
		),
		array(
		'title' => '增加特殊审核',
		'itemurl' => 'index.php?m=audit&do=item_add',
		'power' => 'C0T'
		),
//		array(
//		'title' => '审核任务回访记录',
//		'itemurl' => 'index.php?m=audit&do=return_record',
//		'power' => 'M0S'
//		)
	),

	'pd'=>array(
		array(
		'title' => '认证评定',
		'itemurl' => 'index.php?m=assess&do=pd_list',
		'power' => 'D0E'
		),
		array(
		'title' => '认证评定查询',
		'itemurl' => 'index.php?m=assess&do=pd_xm_list',
		'power' => 'D0S'
		),
		array(
		'title' => '评定人员查询',
		'itemurl' => 'index.php?m=assess&do=pd_hr_list',
		'power' => 'D1S'
		)
//		array(
//		'title' => '领导审批',
//		'itemurl' => 'index.php?m=assess&do=pd_show_list',
//		'power' => 'D4S'
//		)
//		array(
//		'title' => '材料归档',
//		'itemurl' => 'index.php?m=assess&do=item_archive_list'
//		'power' => 'D3S'
//		),
//
//		array(
//		'title' => '材料归档查询',
//		'itemurl' => 'index.php?m=assess&do=item_archive_list&zlgd=1'
//		'power' => 'D3S'
//		)
	),

	'cert'=>array(
		array(
		'title' => '证书登记',
		'itemurl' => 'index.php?m=certificate&do=cert_xm_list',
		'power' => 'E0S'
		),
		array(
		'title' => '证书邮寄',
		'itemurl' => 'index.php?m=certificate&do=cert_post',
		'power' => 'E5E'
		),
		/*array(
		'title' => '证书证明',
		'itemurl' => 'index.php?m=certificate&do=cert_zhengming',
		'power' => 'E0Z'
		),*/
		array(
		'title' => '应暂停项目',
		'itemurl' => 'index.php?m=certificate&do=should_suspend_list',
		'power' => 'E1S'
		),
		array(
		'title' => '应撤销证书',
		'itemurl' => 'index.php?m=certificate&do=should_withdraw_list',
		'power' => 'E3S'
		),
		array(
		'title' => '证书查询',
		'itemurl' => 'index.php?m=certificate&do=cert_list',
		'power' => 'E0S'
		),
		array(
		'title' => '证书到期操作',
		'itemurl' => 'index.php?m=certificate&do=cert_operate',
		'power' => 'E0S'
		)
	),

	'change'=>array(
		array(
		'title' => '证书变更',
		'itemurl' => 'index.php?m=change&do=change_list',
		'power' => 'F0S'
		),
		array(
		'title' => '证书变更查询',
		'itemurl' => 'index.php?m=change&do=zs_change_list',
		'power' => 'F0S'
		)
		/*array(
		'title' => '认证类型变更',
		'itemurl' => 'index.php?m=type&do=type_list',
		'power' => 'F1S'
		),
		array(
		'title' => '认证类型查询',
		'itemurl' => 'index.php?m=type&do=xm_type_list',
		'power' => 'F1S'
		)*/
	),

	'finance'=>array(

		array(
		'title' => '财务收费登记',
		'itemurl' => 'index.php?m=finance&do=fees_finance_list',
		'power' => 'G1S'
		),
		array(
		'title' => '已收费项目',
		'itemurl' => 'index.php?m=finance&do=xm_finance_list',
		'power' => 'G1S'
		),
		array(
		'title' => '财务收费明细',
		'itemurl' => 'index.php?m=finance&do=details_finance_list',
		'power' => 'G2S'
		),
		/*array(
		'title' => '其他费用登记',
		'itemurl' => 'index.php?m=finance&do=other_costs',
		'power' => 'G3E'
		),
		array(
		'title' => '其他费用查询',
		'itemurl' => 'index.php?m=finance&do=other_costs_list',
		'power' => 'G3S'
		)*/
	),

	'hr'=>array(
		array(
		'title' => '人员信息登记',
		'itemurl' => 'index.php?m=hr&do=hr_information_edit',
		'power' => 'H0S'
		),
		array(
		'title' => '人员信息查询',
		'itemurl' => 'index.php?m=hr&do=hr_information',
		'power' => 'H0S'
		),
		array(
		'title' => '注册资格登记',
		'itemurl' => 'index.php?m=hr&do=hr_public_list_01',
		'power' => 'H2E'
		),
		array(
		'title' => '注册资格查询',
		'itemurl' => 'index.php?m=hr&do=hr_reg_qualification_list',
		'power' => 'H1S'
		),
		array(
		'title' => '专业能力登记',
		'itemurl' => 'index.php?m=hr&do=hr_public_list_02',
		'power' => 'H2E'
		),
		array(
		'title' => '专业能力查询',
		'itemurl' => 'index.php?m=hr&do=hr_audit_code_list',
		'power' => 'H2S'
		),
		array(
		'title' => '人员培训发布',
		'itemurl' => 'index.php?m=hr&do=hr_training_edit',
		'power' => 'H4E'
		),
		/*array(
		'title' => '申请人员查询',
		'itemurl' => 'index.php?m=hr&do=hr_apply_code_list'
		),*/
		array(
		'title' => '所有培训信息查询',
		'itemurl' => 'index.php?m=hr&do=hr_training_list',
		'power' => 'H4S'
		),
		array(
		'title' => '评审人员',
		'itemurl' => 'index.php?m=hr&do=hr_review_list',
		'power' => 'H8S'
		),
		array(
		'title' => '审核员教育/培训',
		'itemurl' => 'index.php?m=hr&do=hr_education_train_list',
		'power' => 'H1P'
		),
/*		array(
		'title' => '培养计划',
		'itemurl' => 'index.php?m=hr&do=hr_auditor_plan_list',
		'power' => 'H6S'
		)
		array(
		'title' => '现场评价',
		'itemurl' => 'index.php?m=hr&do=hr_evaluation_list',
		'power' => 'H0J'
		),
		array(
		'title' => '人员工资登记',
		'itemurl' => 'index.php?m=hr&do=hr_wage',
		'power' => 'H0G'
		),
		array(
		'title' => '人员工资查询',
		'itemurl' => 'index.php?m=hr&do=hr_wage_list',
		'power' => 'H1G'
		),*/
	),

	'statistics'=>array(
		/*array(
		'title' => '合同数量统计',
		'itemurl' => './index.php?m=report&do=statistics',
		'power' => 'I0S'
		),
		array(
		'title' => '合同项目数量统计',
		'itemurl' => './index.php?m=report&do=statistics&source=htxm',
		'power' => 'I0S'
		),*/
		array(
		'title' => '月报',
		'itemurl' => './index.php?m=report&do=monthly_list',
		'power' => 'I2S'
		),
		array(
		'title' => '新月报',
		'itemurl' => './index.php?m=report&do=new_monthly_find',
		'power' => 'I2S'
		),
		array(
		'title' => '审核计划表上报',
		'itemurl' => './index.php?m=report&do=authentication_activity_list',
		'power' => 'I3S'
		),
		array(
		'title' => '年报',
		'itemurl' => './index.php?m=report&do=year_list',
		'power' => 'I5S'
		),
		array(
		'title' => '财务季报表',
		'itemurl' => './index.php?m=report&do=ccaa',
		'power' => 'I4S'
		),
		array(
		'title' => '审核员年报',
		'itemurl' => './index.php?m=report&do=year_audit_list',
		'power' => 'I6S'),
		array(
		'title' => '审核项目报表',
		'itemurl' => './index.php?m=report&do=audit_item_report',
		'power' => 'I7S')
	),
/*	'composite'=>array(
		array(
		'title' => '文件上传',
		'itemurl' => './index.php?m=composite&do=composite_upload_edit',
		'power' => 'L0S'
		),
		array(
		'title' => '文件查询',
		'itemurl' => './index.php?m=composite&do=composite_upload_list',
		'power' => 'L0E'
		),
	),*/

	'auditer'=>array(
		/*array(
		'title' => '专业申报',
		'itemurl' => 'index.php?m=auditor&do=au_audit_code_edit&hr_id='.$_SESSION['userid']
		'power' => 'K0S'
		),*/
		array(
		'title' => '我的基本信息',
		'itemurl' => 'index.php?m=auditor&do=au_information_info',
		'power' => 'K0S'
		),
		array(
		'title' => '我的注册资格',
		'itemurl' => 'index.php?m=auditor&do=au_reg_qualification_list',
		'power' => 'K0S'
		),
		array(
		'title' => '我的专业能力',
		'itemurl' => 'index.php?m=auditor&do=au_audit_code',
		'power' => 'K0S'
		),
		array(
		'title' => '个人培训信息查询',
		'itemurl' => 'index.php?m=hr&do=hr_training_list2',
		'power' => 'H5S'
		),
		array(
		'title' => '审核员审核项目',
		'itemurl' => 'index.php?m=auditor&do=au_audit_item_list',
		'power' => 'K0F'
		),
		array(
		'title' => '审核员文档上传查询',
		'itemurl' => 'index.php?m=auditor&do=auditor_upload_list',
		'power' => 'K0B'
		),
		array(
		'title' => '审核员评价',
		'itemurl' => 'index.php?m=auditor&do=auditor_evaluation_list',
		'power' => 'K0J'
		),
		array(
		'title' => '劳务费查询',
		'itemurl' => 'index.php?m=auditor&do=au_labor_costs_show',
		'power' => 'K0S'
		)
	),

	'sys'=>array(
		array(
		'title' => '系统配置',
		'itemurl' => 'index.php?m=setup&do=index',
		'power' => 'J0E'
		),
		array(
		'title' => '用户权限管理',
		'itemurl' => 'index.php?m=hr&do=sys_user_list',
		'power' => 'J1E'
		),
		array(
		'title' => '操作日记查询',
		'itemurl' => 'index.php?m=hr&do=sys_log',
		'power' => 'J2S'
		),
		array(
		'title' => '发布公告',
		'itemurl' => 'index.php?m=hr&do=sys_notice_edit',
		'power' => 'J3E'
		),
		array(
		'title' => '公告管理',
		'itemurl' => 'index.php?m=hr&do=sys_notice_list',
		'power' => 'J3E'
		),
		/*array(
		'title' => '站内短信',
		'itemurl' => 'index.php?m=hr&do=sys_sms_edit'
		'power' => 'J4E'
		),
		array(
		'title' => '查看我的短信',
		'itemurl' => 'index.php?m=hr&do=sys_sms_list'
		'power' => 'J4E'
		),*/
	),
	/*
		array(
		'title' => '认证类型变更',
		'itemurl' => 'index.php?m=type&do=type_list',
		'power' => 'F1S'
		),
		array(
		'title' => '认证类型查询',
		'itemurl' => 'index.php?m=type&do=xm_type_list',
		'power' => 'F1S'
		)*/
);

?>