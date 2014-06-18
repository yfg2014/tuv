<?php
GrepUtil::InitGP(array('type','eidaima','eiregistername','zizhi_id'));

switch ($type) {
	case 'zuzhi_daima':
		$result	= $db->get_one("SELECT id FROM {$dbtable['mk_company']} WHERE eidaima = '$eidaima' LIMIT 1");
		if ($result['id'] != '') {
			echo('组织机构代码已存在');
		}
		break;
	case 'zuzhi_name':
		$result	= $db->get_one("SELECT id FROM {$dbtable['mk_company']} WHERE eiregistername = '$eiregistername' LIMIT 1");
		if ($result['id'] != '') {
			echo('组织名称已存在');
		}
		break;
	case 'q_del':
		$db->get_one("DELETE FROM mk_company_qualification where id='$zizhi_id'");
		echo('操作成功');
		break;
}
?>