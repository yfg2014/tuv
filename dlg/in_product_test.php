<?php
include '../include/globals.php';
include SET_DIR.'setup_product_test.php';

GrepUtil::InitGP(array('product'));

include 'template/header.htm';
include 'template/in_product_test.htm';
include 'template/footer.htm';
?>