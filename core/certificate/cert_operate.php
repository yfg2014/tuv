<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';

Power::CkPower('E0S');

GrepUtil::InitGP(array('date'));

if ($date != ''){
	if ($date < date('Y-m-d'))
	{
		$Certificate = new Certificate();
		$num = $Certificate->maturity($date);
	}else{
		echo '<script type="text/javascript">alert("请选择小于当天日期时间,进行操作");history.back(-1);</script>';
	}
		
}else{
	$date = date("Y-m-d",strtotime("-1 days"));
}

$width = '400px';
include TEMP.'header.htm';
include TEMP.'certificate/cert_operate.htm';
include TEMP.'footer.htm';
?>
