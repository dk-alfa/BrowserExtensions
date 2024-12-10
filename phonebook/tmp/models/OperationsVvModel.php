<?php
//progect name: BARBERSHOP1
function getAllVv(){
 // d( $_SESSION['user']['obj_id']);
 
 global $link;  
 $ret=null;
 $sql="SELECT * FROM operations_vv WHERE obj_id=".$_SESSION['user']['obj_id'].
      "  ORDER BY `lavel`,`section`,`variable`";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }   
  return $ret;
}
function getVvByID($Id){  
  global $link;
  $ret=null;
  
  $sql="SELECT * FROM operations_vv  WHERE obj_id=".$_SESSION['user']['obj_id'].
       " AND ID=".$Id;
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }    
  return $ret;
 }
function getZeroLavel(){  
  global $link;
  $ret=null;
  
  $sql="SELECT variable,value FROM operations_vv  WHERE obj_id=".$_SESSION['user']['obj_id'].
       " AND lavel=0";
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }    
  return $ret;
 }
function getAllCategory(){
 global $link;  
 $ret=null;
 $sql="SELECT variable , value FROM operations_vv WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND section = 1 ORDER BY `lavel`,`section`,`variable`";
 
 //d($sql);
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }   
  return $ret;
}
function getAllHalls(){
 global $link;  
 $ret=null;
 $sql="SELECT variable , value FROM operations_vv WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND section = 2 ORDER BY `lavel`,`section`,`variable`";
 
 //d($sql);
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }   
  return $ret;
}
function getAllTypes(){
 // d( $_SESSION['user']['obj_id']);
 
 global $link;  
 $ret=null;
 $sql="SELECT variable , value FROM operations_vv WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND section = 3 ORDER BY `lavel`,`section`,`variable`";
 
 //d($sql);
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }   
  return $ret;
}
 
 
 
 
 
 
 
 