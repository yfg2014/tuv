<?php
session_start();
include './include/globals.php';
Power::ckLogin('login.php','您还没有登录，请登录！');
include './frontEnd/template/main.htm';
?>