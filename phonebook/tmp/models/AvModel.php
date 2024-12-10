<?php
//progect name: BARBERSHOP1
function getAv(){
 global $link;
 $rsRet=NULL;
 
 $sql="SELECT 
       IsHiPercent,HiPercentMinCache,HiPercentMinCountEmployee,HiPercentSimpleCache,
       AdmMinSalary,PhotoPercent,XeroxPercent,AdditionalPercent   
       FROM av WHERE obj_id=".$_SESSION['user']['obj_id'].
      " ORDER BY last_mod_date DESC LIMIT 1 ";
 //d($sql);
  mysqli_set_charset($link, "utf8"); 
  $rs=mysqli_query($link, $sql);
 
 if($rs){
  $ret=createSmartyRsArray($rs);
 }
 //d($ret); 
 return $ret;   
}
function getDefaultImage(){
 global $link;
 $rsRet=NULL;
 $ret=NULL;
 
 $sql="SELECT 
       default_image   
       FROM av WHERE obj_id=".$_SESSION['user']['obj_id'].
      " ORDER BY last_mod_date DESC LIMIT 1 ";
 //d($sql);
  mysqli_set_charset($link, "utf8"); 
  $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rsRet=createSmartyRsArray($rs);
  $ret=$rsRet[0]['default_image'];
 }
 
 //d($ret);
 return $ret;       
}