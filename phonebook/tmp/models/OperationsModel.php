<?php
//progect name: BARBERSHOP1
function getAllOperations(){
 // d( $_SESSION['user']['obj_id']);
 
 global $link;  
 $ret=null;
/*
  $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code) AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code) AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code) AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " ORDER BY type_code,name_code";
 */
 //d($sql);
  $sql="SELECT *,
   (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
    AND operations_vv.variable=operations.category_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
    AS category_name,
   (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
    AND operations_vv.variable=operations.hall_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
    AS hall_name,
   (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
    AND operations_vv.variable=operations.type_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
    AS type_name
    FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
    " ORDER BY type_code,name_code";
  
  //d($sql);
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }   
  return $ret;
}
function getActiveOperations(){
 global $link;  
 $ret=null;
 /*
   $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code) AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code) AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code) AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " AND operations.active=1 ORDER BY type_code,name_code"; 
  */
  $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " AND operations.active=1 ORDER BY type_code,name_code"; 
 //AND operations_vv.obj_id=".$_SESSION['user']['obj_id']." 
 //d($sql);
  
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }   
  return $ret;
}
function getAdminOperations(){
 global $link;  
 $ret=null;
 /*
   $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code) AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code) AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code) AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " AND operations.active=1 AND (type_code=20 OR type_code=80 OR type_code=90) 
      ORDER BY type_code,name_code"; 
  */
  $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " AND operations.active=1 AND (type_code=20 OR type_code=80 OR type_code=90) 
      ORDER BY type_code,name_code"; 
//d($sql);
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs)
 //if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }  
  //d($ret);
  return $ret; 
}
function getRootOperations(){
 global $link;  
 $ret=null;
 /*
  $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code) AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code) AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code) AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " AND operations.active=1 AND (type_code=80 OR type_code=90) 
      ORDER BY type_code,name_code"; 
  */
  $sql="SELECT *,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 1 
      AND operations_vv.variable=operations.category_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].")
      AS category_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 2 
      AND operations_vv.variable=operations.hall_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].")
      AS hall_name,
      (SELECT value FROM operations_vv WHERE operations_vv.section = 3 
      AND operations_vv.variable=operations.type_code AND operations_vv.obj_id=".$_SESSION['user']['obj_id'].") 
      AS type_name
      FROM operations WHERE operations.obj_id=".$_SESSION['user']['obj_id'].
      " AND operations.active=1 AND (type_code=80 OR type_code=90) 
      ORDER BY type_code,name_code";  
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs)
 //if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }  
  return $ret; 
}
function getOperationDetilesByID($Id){  
  global $link;
  $ret=null;
  
  $sql="SELECT * FROM operations  WHERE obj_id=".$_SESSION['user']['obj_id'].
       " AND ID=".$Id;
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $ret=createSmartyRsArray($rs);   
  }    
  return $ret;
 }
 function getOperationActionById($Id){  
  global $link;
  $ret=null;
  
  $sql="SELECT action  FROM operations  WHERE obj_id=".$_SESSION['user']['obj_id'].
       " AND ID=".$Id;
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $rs=createSmartyRsArray($rs);
   $ret=$rs[0]['action'];   
  }    
  return $ret;
 }
/*
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
 */
function validateOperation($type,$id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer){
 if ($type==='replace'){/*код проверки если замена*/}   
 if ($type==='append'){/*код проверки если добавление*/}
 if ($type==='delete'){/*код проверки если удаление*/}
 
 $ret=1;
 return $ret;  
}
function replaceOperaton($id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer,$active){
 global $link;  
 $rs=null;

$id = htmlspecialchars(mysqli_real_escape_string($link,$id));
$obj_id = htmlspecialchars(mysqli_real_escape_string($link,$obj_id));
$category_code = htmlspecialchars(mysqli_real_escape_string($link,$category_code));
$hall_code = htmlspecialchars(mysqli_real_escape_string($link,$hall_code));
$type_code = htmlspecialchars(mysqli_real_escape_string($link,$type_code));
$name_code = htmlspecialchars(mysqli_real_escape_string($link,$name_code));
$name_value = htmlspecialchars(mysqli_real_escape_string($link,$name_value));
$price = htmlspecialchars(mysqli_real_escape_string($link,$price));
$action = htmlspecialchars(mysqli_real_escape_string($link,$action));
$printable = htmlspecialchars(mysqli_real_escape_string($link,$printable));
$comment = htmlspecialchars(mysqli_real_escape_string($link,$comment));
$create_date = htmlspecialchars(mysqli_real_escape_string($link,$create_date));
$creator = htmlspecialchars(mysqli_real_escape_string($link,$creator));
$last_mod_date = htmlspecialchars(mysqli_real_escape_string($link,$last_mod_date));
$last_modifer = htmlspecialchars(mysqli_real_escape_string($link,$last_modifer));
$active = htmlspecialchars(mysqli_real_escape_string($link,$active));
 
$sql="UPDATE `operations` SET 
      category_code='".$category_code."',
      hall_code='".$hall_code."',
      type_code='".$type_code."',
      name_value='".$name_value."',
      price='".$price."',
      action='".$action."',
      printable=".$printable.",
      comment='".$comment."',
      last_mod_date='".$last_mod_date."',
      last_modifer='".$last_modifer."', 
      active=".$active."     
     WHERE ID=".$id." LIMIT 1";

 //d($sql);
    mysqli_set_charset($link, "utf8");  
    $rs=mysqli_query($link, $sql); 
  return $rs;
}
function appendOperaton($id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer,$active){
 global $link;  
 $rs=null;

$id = htmlspecialchars(mysqli_real_escape_string($link,$id));
$obj_id = htmlspecialchars(mysqli_real_escape_string($link,$obj_id));
$category_code = htmlspecialchars(mysqli_real_escape_string($link,$category_code));
$hall_code = htmlspecialchars(mysqli_real_escape_string($link,$hall_code));
$type_code = htmlspecialchars(mysqli_real_escape_string($link,$type_code));
$name_code = htmlspecialchars(mysqli_real_escape_string($link,$name_code));
$name_value = htmlspecialchars(mysqli_real_escape_string($link,$name_value));
$price = htmlspecialchars(mysqli_real_escape_string($link,$price));
$action = htmlspecialchars(mysqli_real_escape_string($link,$action));
$printable = htmlspecialchars(mysqli_real_escape_string($link,$printable));
$comment = htmlspecialchars(mysqli_real_escape_string($link,$comment));
$create_date = htmlspecialchars(mysqli_real_escape_string($link,$create_date));
$creator = htmlspecialchars(mysqli_real_escape_string($link,$creator));
$last_mod_date = htmlspecialchars(mysqli_real_escape_string($link,$last_mod_date));
$last_modifer = htmlspecialchars(mysqli_real_escape_string($link,$last_modifer));
$active = htmlspecialchars(mysqli_real_escape_string($link,$active));

$sql="INSERT INTO `bs_barbershop`.`operations` (
 `ID`,
 `obj_id`,
 `category_code`,
 `hall_code`,
 `type_code`,
 `name_code`,
 `name_value`,
 `price`,
 `action`,
 `printable`,
 `comment`,
 `create_date`,
 `creator`,
 `last_mod_date`,
 `last_modifer`,
 `active`
 ) 
 VALUES (
  NULL," 
  .$obj_id.","
  .$category_code.","
  .$hall_code.","
  .$type_code.","
  .$name_code.",'"
  .$name_value."',"
  .$price.","
  .$action.","   
  .$printable.",'"  
  .$comment."','"
  .$create_date."','"
  .$creator."','"      
  .$last_mod_date."','"
  .$last_modifer."',"
  .$active.")";

 mysqli_set_charset($link, "utf8");  
 $rs=mysqli_query($link, $sql); 
 //d($sql);
  //d($rs);
  return $rs;
}
function getNameCode($obj_id,$category_code,$hall_code,$type_code){
 global $link; 
 $ret=0;
 $sql="SELECT name_code FROM operations 
        WHERE 
        obj_id=".$obj_id." AND category_code=".$category_code." AND hall_code="
        .$hall_code." AND type_code=".$type_code." ORDER by name_code DESC LIMIT 1";
 mysqli_set_charset($link, "utf8");  
 $rs=mysqli_query($link, $sql); 
 if(mysqli_num_rows($rs))
  {
   $rs=createSmartyRsArray($rs);
   $ret= $rs[0]['name_code']+1;
  } 
 //d($ret);
 return $ret; 
}
function deleteOperaton($id){
 global $link; 
 $ret=0;
 $sql="DELETE FROM `operations` WHERE `operations`.`ID`=".$id." LIMIT 1";
 //d($sql);
 mysqli_set_charset($link, "utf8");  
 $rs=mysqli_query($link, $sql); 
 //if(mysqli_num_rows($rs))
 // {
   $ret= 1;
 // } 
 //d($ret);
 return $ret; 
} 
function getOperationPriceById($Id){  
  global $link;
  $ret=null;
  
  $sql="SELECT price FROM operations  WHERE obj_id=".$_SESSION['user']['obj_id'].
       " AND ID=".$Id;
  mysqli_set_charset($link, "utf8");
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $rs=createSmartyRsArray($rs);
   $ret=$rs[0]['price'];   
  }    
  return $ret;
 }
function getOperationCategoryCode($op_id){
 global $link;
 $ret=0;
 $sql="SELECT category_code FROM operations WHERE id=".$op_id;
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 //if($rs){
 if($op_id){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['category_code'];
 }
 //d($ret); 
 return $ret;
}
function getPriceByID($id){
 global $link;
 $ret=NULL;
 
 $sql="SELECT price FROM operations WHERE ID=".$id." AND obj_id=".$_SESSION['user']['obj_id'];
 //d($sql);
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($id){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['price'];
 }
 return $ret;
}

 
 
 
 