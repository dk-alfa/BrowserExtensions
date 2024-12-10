<?php
//progect name: BARBERSHOP1
/**
 * 
 * @global type $dbname
 * @global type $g_table_objects
 * @return type
 */
function getAllObjects() {
 global $link;

 $sql="SELECT name_rus , id FROM objects ORDER BY name_rus";  
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs);
 return $rs;
}
/**
 * 
 * @param type $type
 * @param type $db_name
 * @param type $obj_name
 * @param type $location
 * @param type $image
 * @param type $ta_comments
 * @param type $creator
 * @param type $last_modifer
 * @param type $last_mod_date
 * @param type $create_date
 * @return int
 */
function validateObject($type,$name_rus,$name,$type_obj,
                    $adress,$image,$comment,$creator,$last_modifer,$last_mod_date,
                    $create_date){
global $link;
$ret=1;
  //Проверяем на совпадение имени
  if($type==='append'){
   if($name){
    $sql="SELECT id FROM objects WHERE name='".$name."'";
    $rs=mysqli_query($link, $sql);
    if (mysqli_num_rows($rs)){$ret=3;}
   }
  }
  //Проверяем на предмет заполненности полей
  if(!$name_rus || !$name || !$adress ){$ret=2;}
  
  return $ret;  
}
/**
 * 
 * @global type $link
 * @param type $type
 * @param type $id
 * @param type $type_obj
 * @param type $name
 * @param type $name_rus
 * @param type $adress
 * @param type $image
 * @param type $comment
 * @param type $create_date
 * @param type $creator
 * @param type $last_mod_date
 * @param type $last_modifer
 * @return type
 */
function saveObject($type,$id,$type_obj,$name,$name_rus,$adress,$image,
               $comment,$create_date,$creator,$last_mod_date,$last_modifer){
global $link;  

$rs=null;

$name = htmlspecialchars(mysqli_real_escape_string($link,$name));
$name_rus = htmlspecialchars(mysqli_real_escape_string($link,$name_rus));
$adress = htmlspecialchars(mysqli_real_escape_string($link,$adress));
$image = htmlspecialchars(mysqli_real_escape_string($link,$image));
$comment = htmlspecialchars(mysqli_real_escape_string($link,$comment));
//d($type);

if ($type==='append'){ 
    $sql="INSERT INTO `objects`
      (id,
       type,
       name,
       name_rus,
       adress,
       image,
       comment,
       create_date,
       creator,
       last_mod_date,
       last_modifer) 
       VALUES(
       NULL,
       '".$type_obj."','"
       .$name."','"
       .$name_rus."','"
       .$adress."','"
       .$image."','"
       .$comment."','"
       .$create_date."','"
       .$creator."','"
       .$last_mod_date."','"
       .$last_modifer."')";
    
   mysqli_set_charset($link, "utf8");  
   $rs=mysqli_query($link, $sql);
   //d($sql);
   }
  if ($type==='replace'){ 
   
   //Если изменено name изменяем директорию с картинками   
   $sql="SELECT name FROM `objects` WHERE ID=".$id;
   mysqli_set_charset($link, "utf8");  
   $rs=mysqli_query($link, $sql); 
   $rs= createSmartyRsArray($rs);
   $old_name=$rs[0]['name'];
   if(!($old_name===$name)){
    global $imagedir;
    rename($imagedir.$old_name,$imagedir.$name);     
   }  
   $sql="UPDATE `objects` SET 
            name='".$name."',
            name_rus='".$name_rus."',
            adress='".$adress."',
            image='".$image."',
            comment='".$comment."',
            last_mod_date='".$last_mod_date."',
            last_modifer='".$last_modifer."'  
            WHERE ID=".$id." LIMIT 1";  
    mysqli_set_charset($link, "utf8");  
    $rs=mysqli_query($link, $sql); 
  }
  return $rs;
}
/**
 * 
 * @global type $dbname
 * @global type $g_table_objects
 * @param type $obj_id
 * @return type
 */
function findObjectByID($obj_id)
{
  global $link; 
  
  $sql="SELECT * FROM objects WHERE 
         `id` = '".$obj_id. "' LIMIT 1"; 
  
  mysqli_set_charset($link, "utf8"); 
  $rs = mysqli_query($link, $sql); 
  return createSmartyRsArray($rs);
}
function getObjNameById($obj_id){
 global $link;
 $ret='';
 $sql="SELECT name FROM objects WHERE ID=".$obj_id;
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
 $rs=createSmartyRsArray($rs);
 $ret=$rs[0]['name'];
 }
 return $ret;  
}

/**
 * 
 * @global type $g_table_objects
 * @global type $dbname
 * @param type $id
 * @param type $type
 * @param type $db_name
 * @param type $obj_name
 * @param type $location
 * @param type $image
 * @param type $ta_comments
 * @param type $creator
 * @param type $last_modifer
 * @param type $last_mod_date
 * @param type $create_date
 * @return type
 */
//function addObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
//                   $creator,$last_modifer,$last_mod_date,$create_date){ 
  /*  
  global $g_table_objects,$dbname; 
  $sql="?";
  $rs="?";
  
  $sql="SELECT USER()";
  $rs = mysql_query($sql);
   
  $id = htmlspecialchars(mysql_real_escape_string($id));  
  $db_name = htmlspecialchars(mysql_real_escape_string($db_name)); 
  //$login_name  = htmlspecialchars(mysql_real_escape_string($login_name)); 
  //$pwd = htmlspecialchars(mysql_real_escape_string($pwd)); 
  $obj_name = htmlspecialchars(mysql_real_escape_string($obj_name)); 
  $location= htmlspecialchars(mysql_real_escape_string($location));
  $image=htmlspecialchars(mysql_real_escape_string($image));
  $ta_comments= htmlspecialchars(mysql_real_escape_string($ta_comments));
  $creator= htmlspecialchars(mysql_real_escape_string($creator));
  $last_modifer= htmlspecialchars(mysql_real_escape_string($last_modifer));
  $last_mod_date= htmlspecialchars(mysql_real_escape_string($last_mod_date));
  $create_date= htmlspecialchars(mysql_real_escape_string($create_date));
 
  //type - тип действмя 'append' добавление новой строки, 
  //                   'replace' замена старой
   
  if($type==='append'){  
  $sql = "INSERT INTO `".$db_name."`.'".$g_table_objects."'
        (`db_name`,`name_rus`,`location`,`image`,
         `comment`,`create_date`,`creator`,`last_mod_date`,`last_modifer`)
  VALUES ('{$db_name}','{$obj_name}','{$location}',
          '{$image}','{$ta_comments}','{$create_date}',
          '{$creator}','{$last_mod_date}','{$last_modifer}')";
  //echo($sql);      
  $rs = mysql_query($sql);
   * 
   */
 // }
  /*
  if($type==='replace'){
   /**
    * Обработчик изменения параметров БД (/index/superroot/):
    *  Название объекта [рус.]
    *  Название базы данных [eng.]
    *  Логин
    *  Пароль
    *  Адрес
    *  Изображение
    *  Коментарии
    *                                     
    */
    /*
    //Объявляем переменные       
      $replace_obj=false;     //нужно ли чтото изменять
      $replace_db_name=false;  //нужно ли менять им базы
      
      $rsTheObject=getObjectById($id);
      $sql="UPDATE ".$dbname.".".$g_table_objects." SET ";
 
      //Проверяем изменено ли название БД    
      if(!($db_name===$rsTheObject[0]['db_name'])){
       $replace_obj=true;
       $replace_db_name=true;
       $sql=$sql."db_name='".$db_name."',";
      }
      //Проверяем изменено ли название на русском БД
      if(!($obj_name===$rsTheObject[0]['name_rus'])){
       $replace_obj=true;
       $sql=$sql."name_rus='".$obj_name."',";
      }
      //Проверяем изменен ли адрес объекта
      if(!($location===$rsTheObject[0]['location'])){
       $replace_obj=true;
       $sql=$sql."location='".$location."',";
      }  
      //Проверяем изменен ли путь к изображению
      if(!($image===$rsTheObject[0]['image'])){
       $replace_obj=true;
       $sql=$sql."image='".$image."',";
      } 
      //Проверяем изменены ли коментарии
      if(!($ta_comments===$rsTheObject[0]['comment'])){
       $replace_obj=true;
       $sql=$sql."comment='".$ta_comments."',";
      }  
      
      //если что то изменено($replace_obj=true), то меняем данные в главной
      //таблице объектов переменная $g_table_objects объявлена в /config/db.php
      if ($replace_obj){
       //Формируем итоговый sql запрос на изменение данных 
       $sql=$sql."last_mod_date='".$last_mod_date."',last_modifer='".$last_modifer.
               "' WHERE ID=".$id." LIMIT 1";   
        $rs = mysql_query($sql);   
       }else{$rs=2;}
       //если меняем имя БД
       if($replace_db_name){
         
         $new_db=$db_name;
         $old_db=$rsTheObject[0]['db_name'];

         mysql_query("CREATE DATABASE ".$new_db);
         $res = mysql_query("SHOW TABLES FROM ".$old_db);
         while ($result = mysql_fetch_assoc($res)){
           $sql="RENAME TABLE ".$old_db.".".$result['Tables_in_'.$old_db].
           " TO ".$new_db.".".$result['Tables_in_'.$old_db];
           mysql_query($sql);
         } 
          mysql_query("DROP DATABASE ".$old_db);
          renameImageDir($old_db,$new_db);
          $rs = mysql_query($sql);
       }
       //если ничего не меняли ($replace_obj=false), то возвращаем 2 
       
  }*/
  //return $rs;
//}
function getObjNameRusById($obj_id){
 global $link;
 $ret='';
 $sql="SELECT name_rus FROM objects WHERE ID=".$obj_id;
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
 $rs=createSmartyRsArray($rs);
 $ret=$rs[0]['name_rus'];
 }
 return $ret;  
}