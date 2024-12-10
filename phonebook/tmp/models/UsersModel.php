<?php
//progect name: BARBERSHOP1

 function getPrivilegy($login,$pwd){   
  global $link;
  $priv=null; 
  
  //$login = htmlspecialchars(mysql_real_escape_string($login)); 
  //$pwd = htmlspecialchars(mysql_real_escape_string($pwd));
  $login = htmlspecialchars(mysqli_real_escape_string($link,$login)); 
  $pwd = htmlspecialchars(mysqli_real_escape_string($link,$pwd));

  $sql="SELECT privilegy FROM users WHERE login='".$login.
       "' AND password='".$pwd."' AND is_work LIMIT 1";
  
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $rs=createSmartyRsArray($rs);   
   $priv=$rs[0]['privilegy'];   
  }   
 
  return $priv;
 }
 /*
  * 
  */
 function getObjId($login,$pwd){   
  global $link;
  $obj_id=null; 
  
  $login = htmlspecialchars(mysqli_real_escape_string($link,$login)); 
  $pwd = htmlspecialchars(mysqli_real_escape_string($link,$pwd));
  
  $sql="SELECT obj_id FROM users WHERE login='".$login.
       "' AND password='".$pwd."' LIMIT 1";
  
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $rs=createSmartyRsArray($rs);   
   $obj_id=$rs[0]['obj_id'];   
  }   
 
  return $obj_id;
 }
/**
 * 
 * @global type $link
 * @param type $login
 * @param type $pwd
 * @return type
 */
function getUsrId($login,$pwd){   
  global $link;
  $usr_id=null; 
  
  $login = htmlspecialchars(mysqli_real_escape_string($link,$login)); 
  $pwd = htmlspecialchars(mysqli_real_escape_string($link,$pwd));
  
  $sql="SELECT id FROM users WHERE login='".$login.
       "' AND password='".$pwd."' LIMIT 1";
  
  $rs=mysqli_query($link, $sql);
  if(mysqli_num_rows($rs))
  {
   $rs=createSmartyRsArray($rs);   
   $usr_id=$rs[0]['id'];   
  }   
  return $usr_id;
 }
function getAllUsers(){
  global $link;

 $sql="SELECT RIGHT(login,LENGTH(login)-3) , id FROM users ORDER BY login";  
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs);
 return $rs;
}
/**
 * 
 * @global type $link
 * @param type $type
 * @param type $id
 * @param type $obj_id
 * @param type $login
 * @param type $password
 * @param type $first_name
 * @param type $last_name
 * @param type $partonymic
 * @param type $salary
 * @param type $salary_1
 * @param type $is_work
 * @param type $email
 * @param type $phone
 * @param type $privelegy
 * @param type $image
 * @param type $comment
 * @param type $create_date
 * @param type $creator
 * @param type $last_mod_date
 * @param type $last_modifer
 * @return int
 */
function validateUser($type,$id,$obj_id,$login,$password,$short_name,$first_name,
                    $last_name,$partonymic,$salary,$salary_1,$is_work,$email,
                    $phone,$privelegy,$image,$comment,$create_date,$creator,
                    $last_mod_date,$last_modifer){
  global $link;
  $ret=1;
   
  //Проверяем, пустое id объекта или нет
  if($obj_id==='None'&&!($privelegy==="superroot")){$ret=6;}
  //Проверяем, пустые ПРИВИЛЕГИИ или нет
  if($privelegy==='None'){$ret=5;}
  //Проверяем, пустой ПАРОЛЬ или нет(если добавляем нового пользователя)
  if($type==='append' && !$password){$ret=4;} 
  //Проверяем, пустой ЛОГИН или нет
  if(!$login){$ret=3;}
  //Проверяем, есть ли такой пользователь в базе (если добавляем нового пользователя)
  //if($type==='append'){
   $login='bs_'.$login;
   $password= sha1($password.$login);
  
   $sql="SELECT login FROM users WHERE login='".$login."' AND password='"
       .$password."' AND id NOT LIKE ".$id." LIMIT 1";

   $rs=mysqli_query($link, $sql);
   if (mysqli_num_rows($rs)){$ret=2;}
   //}
  //Если пользователь не superroot или root, то проверяем поля имя, фамилия ... 
  //d($privelegy);
  //if ($privelegy==="admin"){ //employee
   if(!$first_name){$ret=7;}
   if(!$last_name){$ret=8;}
   
   if ($privelegy==="admin" || $privelegy==="employee"){
    if(!$salary){$ret=9;}
    if(!$salary_1){$ret=10;}
   }  
   //Если привелегии пользователя employee [парикмахер] 
   if ($privelegy==="employee"){
   //Проверяем, пустое short_name объекта или нет    
    if(!$short_name){$ret=11;}     
   }
   
  return $ret;
}
/**
 * Функция сохранения пользователя
 * @global type $link
 * @param type $type
 * @param type $id
 * @param type $obj_id
 * @param type $login
 * @param type $password
 * @param type $first_name
 * @param type $last_name
 * @param type $partonymic
 * @param type $salary
 * @param type $salary_1
 * @param type $is_work
 * @param type $email
 * @param type $phone
 * @param type $privelegy
 * @param type $image
 * @param type $comment
 * @param type $create_date
 * @param type $creator
 * @param type $last_mod_date
 * @param type $last_modifer
 */
function saveUser($type,$id,$obj_id,$login,$password,$short_name,$first_name,
                    $last_name,$partonymic,$salary,$salary_1,$is_work,$email,
                    $phone,$privelegy,$image,$comment,$create_date,$creator,
                    $last_mod_date,$last_modifer){
global $link;
 $sql="";
 $id = htmlspecialchars(mysqli_real_escape_string($link,$id));
 $login = htmlspecialchars(mysqli_real_escape_string($link,$login));
 $password = htmlspecialchars(mysqli_real_escape_string($link,$password));
 $short_name = htmlspecialchars(mysqli_real_escape_string($link,$short_name));
 $first_name = htmlspecialchars(mysqli_real_escape_string($link,$first_name));
 $last_name = htmlspecialchars(mysqli_real_escape_string($link,$last_name));
 $partonymic = htmlspecialchars(mysqli_real_escape_string($link,$partonymic));
 $salary = htmlspecialchars(mysqli_real_escape_string($link,$salary));
 $salary_1 = htmlspecialchars(mysqli_real_escape_string($link,$salary_1));
 $is_work = htmlspecialchars(mysqli_real_escape_string($link,$is_work));
 $email = htmlspecialchars(mysqli_real_escape_string($link,$email));
 $phone = htmlspecialchars(mysqli_real_escape_string($link,$phone));
 $privelegy = htmlspecialchars(mysqli_real_escape_string($link,$privelegy));
 $image = htmlspecialchars(mysqli_real_escape_string($link,$image));
 $comment = htmlspecialchars(mysqli_real_escape_string($link,$comment));
 //Если type==='append' добавляем нового пользователя.  
 if ($type==='append'){
   $sql="INSERT INTO `bs_barbershop`.`users` (
 `ID`, 
 `obj_id`,
 `login`,
 `password`, 
 `short_name`,
 `first_name`, 
 `last_name`, 
 `partonymic`, 
 `salary`, 
 `salary_1`, 
 `is_work`,  
 `email`, 
 `phone`,
 `privilegy`,
 `image`,
 `comment`,
 `create_date`,
 `creator`,
 `last_mod_date`,
 `last_modifer`
 ) 
 VALUES (
  NULL,'" 
  .$obj_id."','"
  .$login."','"
  .$password."','" 
  .$short_name."','"
  .$first_name."','"         
  .$last_name."','" 
  .$partonymic."','" 
  .$salary."','" 
  .$salary_1."'," 
  .$is_work.",'"  
  .$email."','" 
  .$phone."','"
  .$privelegy."','"
  .$image."','"
  .$comment."','"
  .$create_date."','"
  .$creator."','"
  .$last_mod_date."','"
  .$last_modifer."')";
   
  //d($sql);
  // $rs=mysqli_query($link, $sql);
 }
 //Если type==='append' изменяем данные пользователя.  
 if ($type==='replace'){
     
  $sql="UPDATE users SET
   obj_id='".$obj_id."',
   login='".$login."',
   short_name='".$short_name."',
   first_name='".$first_name."', 
   last_name='".$last_name."', 
   partonymic='".$partonymic."', 
   salary='".$salary."', 
   salary_1='".$salary_1."', 
   is_work=".$is_work.",  
   email='".$email."', 
   phone='".$phone."',
   privilegy='".$privelegy."',
   image='".$image."',
   comment='".$comment."',
   last_mod_date='".$last_mod_date."',";
  //d($password);
   if($password){
    $sql=$sql." password='".$password."',";   
   }
   
   $sql=$sql."last_modifer='".$last_modifer."' 
   WHERE ID=".$id." LIMIT 1"; 
  //d($sql); 
 }
 mysqli_set_charset($link, "utf8");
 $rs=mysqli_query($link, $sql);
}
/**
 * Функция поиска пользователя по id
 * @param type $usr_id
 */
function findUserByID($usr_id){
  global $link; 
  
  $sql="SELECT * FROM users WHERE 
         `id` = '".$usr_id. "' LIMIT 1"; 
  
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  return createSmartyRsArray($rs);     
}
function getWorkEmployess($obj_id){
  global $link; 
  
  $sql="SELECT id,short_name,first_name,last_name, @checked FROM users WHERE 
         `obj_id` = '".$obj_id. "' AND privilegy='employee' AND is_work  ORDER BY last_name"; 
  //d($sql);
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  return createSmartyRsArray($rs);    
}
function getSeveralEmployees($rsIds){
 global $link;   
   
  $sql="SELECT id, short_name,first_name, last_name,partonymic, image FROM users WHERE ";
  foreach ($rsIds as $emp_ids) {
   $sql=$sql." id='".$emp_ids."' OR ";  
  }
  $sql= substr($sql,0, strlen($sql)-3) ." ORDER BY id";
 
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  return  createSmartyRsArray($rs);
}
function getAdm($adm_id){
 global $link;
 $sql="SELECT id,short_name,first_name, last_name,partonymic, image FROM users WHERE id='".$adm_id."'";
 mysqli_set_charset($link, "utf8"); 
 $rs = mysqli_query($link, $sql); 
 
 return  createSmartyRsArray($rs);
}
function getEmpPercent1ById($emp_id,$percent){
  global $link;
  $ret = 0;
  //$rs=NULL;
  $sql="SELECT ".$percent." FROM users WHERE id='".$emp_id."'";
  
  mysqli_set_charset($link, "utf8"); 
  $rs=mysqli_query($link, $sql);
  $rs=createSmartyRsArray($rs);
 if($rs){
  
 
  $ret=$rs[0][$percent];
 }  
 return $ret;   
}
function getFIOById($user_id,$obj_id){
  global $link; 
  
  $sql="SELECT first_name,last_name,partonymic  FROM users WHERE 
         `obj_id` = '".$obj_id. "' AND ID = ".$user_id; 
  
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  return createSmartyRsArray($rs);    
}
function getEmpPercentsRs1ById($emp_id){
  global $link;
  $ret = NULL;
  $sql="SELECT salary,salary_1 FROM users WHERE id='".$emp_id."'";
  //d($sql);
  mysqli_set_charset($link, "utf8"); 
  $rs=mysqli_query($link, $sql);
 
 if($rs){$ret=createSmartyRsArray($rs);}  
 return $ret;   
}
function getPrivilegyById($id){
 global $link;
 $ret='';
 $sql="SELECT privilegy FROM users WHERE id='".$id."'";
 
 mysqli_set_charset($link, "utf8"); 
 $rs = mysqli_query($link, $sql); 
 if($rs){
  $ret=createSmartyRsArray($rs);
  $ret=$ret[0]['privilegy'];
 }
 return  $ret;  
}
function getEmployesList(){
 global $link;
  $sql="SELECT * ,(SELECT name_rus FROM objects WHERE ID = users.obj_id) AS name_rus  FROM users WHERE `obj_id` = ".$_SESSION['user']['obj_id']. 
     " ORDER BY privilegy , first_name,last_name,partonymic"; 
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  //d($rs);
  return createSmartyRsArray($rs);        
}
function getEmployesRecordById($id){
 global $link;
 $sql="SELECT * ,(SELECT name_rus FROM objects WHERE ID = users.obj_id) AS name_rus  FROM users WHERE ID = ".$id;
 mysqli_set_charset($link, "utf8"); 
 $rs = mysqli_query($link, $sql); 
 return createSmartyRsArray($rs);  
}
function getWorkAdmins($obj_id){
  global $link; 
  
  $sql="SELECT ID,image,partonymic,first_name,last_name FROM users WHERE 
         `obj_id` = '".$obj_id. "' AND privilegy='admin' AND is_work  ORDER BY last_name"; 
  //d($sql);
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  return createSmartyRsArray($rs);    
}







