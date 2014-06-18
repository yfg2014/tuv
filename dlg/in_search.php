<?php
include '../include/globals.php';

$result = Company::search($q);

foreach ($result as $v) {
	echo $v."\n";
}
?>