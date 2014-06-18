<?php

try {
switch ($type) {
	case 'getcode':
		$of = new OfficesDao();
		$res = $of->getCode($lev,$fid);
		echo $res;
		break;
	default:
}

} catch (Exception $e) {
	
}
?>