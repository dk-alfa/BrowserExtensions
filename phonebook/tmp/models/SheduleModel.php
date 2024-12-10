<?php
//progect name: BARBERSHOP1
/**
 * 
 * @global type $link
 * @return boolean
 */
function getShedule($obj_id){
 global $link;
 $ret=false;
 
 $sql="SELECT emp_ids FROM shedule WHERE date='".getMyDate()."' AND obj_id='".$obj_id." ' LIMIT 1";   
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs);
 
 if(isset($rs[0]['emp_ids'])){$ret=true;}
 //d($rs[0]['emp_ids']);
 return $ret; 
}
function getAdmShedule($obj_id){
 global $link;
 $ret=NULL;
 
 $sql="SELECT adm_id FROM shedule WHERE date='".getMyDate()."' AND obj_id='".$obj_id." ' LIMIT 1";   
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs);
 
 if(isset($rs[0]['adm_id'])){$ret=$rs[0]['adm_id'];}
 //d($rs[0]['emp_ids']);
 return $ret; 
}
/**
 * Функция записи данных в таблицу shedule
 * @global type $link
 * @param type $obj_id
 * @param type $date
 * @param type $adm_id
 * @param type $emp_ids
 */
function saveToShedule($obj_id,$date,$adm_id,$emp_ids){   
 global $link;
 $sql="None";
 //Если запись за текущую дату в текущем объекте уже есть,
 //то заменяем  в таблице поле emp_ids
 if(getEmployeesFromSheduler()){
  $sql="UPDATE `bs_barbershop`.`shedule` SET  emp_ids='".$emp_ids."' 
        WHERE date='".$date."' AND obj_id='".$obj_id."' LIMIT 1";
 //Если запись за текущую дату в текущем объекте отсутствует,
 //то добавляем запись в таблицу
 }else{
  $sql="INSERT INTO `bs_barbershop`.`shedule` (
  `obj_id`,
  `date`,
  `adm_id`, 
  `emp_ids`)
  VALUES ('".$obj_id."','"
  .$date."','".$adm_id."','".$emp_ids."'    
  )";   
 }
  mysqli_set_charset($link, "utf8");
  mysqli_query($link, $sql);
}
/**
 * 
 * @global type $link
 * @return type
 */
function getEmployeesFromSheduler(){
  global $link;
  $ret=null;
  
  $sql="SELECT emp_ids FROM shedule WHERE date='".getMyDate()."' AND obj_id=".$_SESSION['user']['obj_id'];
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  $rs=createSmartyRsArray($rs); 
  //if(isset($rs[0]['emp_ids'])){$ret=$rs[0]['emp_ids'];}
  if(isset($rs[0]['emp_ids'])){
    $ret=explode("/", substr($rs[0]['emp_ids'],0, strlen($rs[0]['emp_ids'])-1));
  }
  return $ret;  
}
function getAdminIdFromSheduler(){
  global $link;
  $ret=null;
  
  $sql="SELECT adm_id FROM shedule WHERE date='".getMyDate()."' AND obj_id=".$_SESSION['user']['obj_id'];
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  $rs=createSmartyRsArray($rs); 
  if($rs){
   $ret=$rs[0]['adm_id'];
  } 
 // if(isset($rs[0]['emp_ids'])){
 //   $ret=explode("/", substr($rs[0]['emp_ids'],0, strlen($rs[0]['emp_ids'])-1));
 // }
  return $ret;  
}
function appendAdmToShedule($obj_id,$adm_id,$date){
 global $link;
   $sql="INSERT INTO `bs_barbershop`.`shedule` (
  `obj_id`,
  `date`,
  `adm_id`)
  VALUES ('".$obj_id."','"
  .$date."','".$adm_id."'    
  )";
 //d($sql);  
 mysqli_set_charset($link, "utf8");
 mysqli_query($link, $sql);
}
function replaceAdmToShedule($obj_id,$adm_id,$date){
 global $link;
 $sql="UPDATE `bs_barbershop`.`shedule` SET  adm_id='".$adm_id."' 
        WHERE date='".$date."' AND obj_id='".$obj_id."' LIMIT 1";
 mysqli_set_charset($link, "utf8");
 mysqli_query($link, $sql); 
}
