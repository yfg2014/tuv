<?php
include '../include/globals.php';
include SET_DIR.'setup_key_part.php';
include SET_DIR.'setup_product.php';

GrepUtil::InitGP(array('product'));

include 'template/header.htm';
include 'template/in_keypieces_list.htm';
include 'template/footer.htm';
?>