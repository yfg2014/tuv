<?php
include S_DIR.'/include/module/CompanyQuestion.php';

Power::CkPower('A2D');

GrepUtil::InitGP(array('id'));

$CompanyQuestion = new CompanyQuestion();
$CompanyQuestion->delete($id);
Url::goto_url('index.php?m=company&do=qiyedengji_question_list', '删除文件成功');
?>