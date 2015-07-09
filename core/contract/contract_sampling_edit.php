<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';
include_once S_DIR.'include/module/Sampling.php';
include_once S_DIR.'/include/module/Information.php';

GrepUtil::InitGP(array('id','htxm_id','ht_id','zuzhi_id'));

Power::CkPower('B3E');

$Company = new Company();
$rows = $Company->GetCompany($zuzhi_id);
$htfrom = $rows['htfrom'];

$Sampling = new Sampling();
$htcy = $Sampling->query($id);
//检验是否合格
if($htcy['samplingtrue'] != ""){
	$htcy['samplingtrue'] == '1' ? $sel1 = "selected" : $sel0 = "selected" ;
}
//检测项目
if($htcy['samplingxm'] != ""){
	$htcy['samplingxm'] == '常规性检验' ? $selxm1 = "selected" : $selxm2 = "selected" ;
}
//样品确认方式
if($htcy['samplingmobe'] == "CMD确认"){ $selmobe1 = "selected" ;}
if($htcy['samplingmobe'] == "企业提供"){ $selmobe2 = "selected" ;}
if($htcy['samplingmobe'] == "认可样品"){ $selmobe3 = "selected" ;}
//检测类别
if($htcy['samplingclass'] == "准产"){ $selclass1 = "selected" ;}
if($htcy['samplingclass'] == "委托"){ $selclass2 = "selected" ;}
if($htcy['samplingclass'] == "抽查"){ $selclass3 = "selected" ;}
if($htcy['samplingclass'] == "复检"){ $selclass4 = "selected" ;}
if($htcy['samplingclass'] == "其他"){ $selclass5 = "selected" ;}

if($htcy['samplestrue'] == '0'){$sel1 = 'selected="selected"';}elseif($htcy['samplestrue'] == '1'){$sel2 = 'selected="selected"';}

$width = '600px';
$Information = new Information(array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id)),$width,'',$params = array('company'=>array(),'contract' => array()));

include TEMP.'header.htm';
include TEMP.'contract/contract_sampling_edit.htm';
include TEMP.'footer.htm';
?>
