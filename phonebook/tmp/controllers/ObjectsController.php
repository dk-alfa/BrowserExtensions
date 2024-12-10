<?php
//progect name: BARBERSHOP1
include_once '../models/ObjectsModel.php';

function addobjectAction(){   
 global $imagedir;
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = false;
 $resData['message'] = '?';

 //ПРИНИМАЕМ ДАННЫЕ
 //type - тип действмя 'append' добавление новой строки, 
 //                   'replace' замена старой
 $type = isset($_REQUEST['type']) ?  $_REQUEST['type'] : null;
 $type = trim ($type);
   
 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
   
 $name_rus = isset($_REQUEST['name_rus']) ?  $_REQUEST['name_rus'] : null;
 $name_rus = trim ($name_rus);

 $name = isset($_REQUEST['name']) ?  $_REQUEST['name'] : null;
 $name = trim ($name);
   
 $adress = isset($_REQUEST['adress']) ?  $_REQUEST['adress'] : null;
 $adress = trim ($adress);
   
 $image = isset($_REQUEST['image']) ?  $_REQUEST['image'] : null;
 $image = trim ($image);
   
 $comment = isset($_REQUEST['comment']) ?  $_REQUEST['comment'] : null;
 $comment = trim ($comment);
 
 $type_obj="bs";
 $last_mod_date=getMyDateTime();
 $create_date="";
 $creator="";
 $last_modifer = isset($_SESSION['user']['login']) ? $_SESSION['user']['login'] : null;
 
 if($type==='append'){
  $creator=$last_modifer;
  $create_date=$last_mod_date;
 }    
 //Проверяем правильность введенных данных
 $validateMessage=validateObject($type,$name_rus,$name,$type_obj,
                    $adress,$image,$comment,$creator,$last_modifer,$last_mod_date,
                    $create_date);   

  switch ($validateMessage)
   {    
    case 1: //Данные введены правильно
     $rd=saveObject($type,$id,$type_obj,$name,$name_rus,$adress,$image,
               $comment,$create_date,$creator,$last_mod_date,$last_modifer);
     if($type==='append'){mkdir($imagedir.$name);}
     if ($rd){         
       $resData['success'] = true;
       $resData['message'] = 'Данные сохранены';      
      }   
     
    break;
    case 2://Пустые поля ввода
      $resData['success'] = false;
      $resData['message'] = 'Заполнены не все поля';
    break;    
    case 3://Объект с таким имененм уже зарегестрирован
      $resData['success'] = false;
      $resData['message'] = 'Объект с таким имененм уже зарегестрирован';        
    break;    
   }
 echo json_encode($resData);
}
/**
 * 
 */
function editobjectAction(){
 
 $resData['success'] = 1;
 $resData['message'] = '?';  

 $obj_id = isset($_REQUEST['obj_id']) ?  $_REQUEST['obj_id'] : null;
 $obj_id = trim ($obj_id);  
 
 $resData['message'] = $obj_id; 
 
 $rsObject=findObjectByID($obj_id);

 //d($rsObject);
 
 $resData['success'] = 1;
 $resData['message'] = $rsObject[0]['ID'];
 
 $resData['ID']            = $rsObject[0]['ID'];
 $resData['type']          = $rsObject[0]['type'];
 $resData['name']          = $rsObject[0]['name'];
 $resData['name_rus']      = $rsObject[0]['name_rus'];
 $resData['adress']        = $rsObject[0]['adress'];
 $resData['image']         = $rsObject[0]['image'];
 $resData['comment']       = $rsObject[0]['comment']; 
 $resData['create_date']   = $rsObject[0]['create_date']; 
 $resData['creator']       = $rsObject[0]['creator']; 
 $resData['last_mod_date'] = $rsObject[0]['last_mod_date'];
 $resData['last_modifer']  = $rsObject[0]['last_modifer'];
 
 echo json_encode($resData);     
}
/**
 * 
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
/*
function  objectTable($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
                   $creator,$last_modifer,$last_mod_date,$create_date){
    
 $resData['success'] = 1;
 $resData['message'] = '?_objectTable';

 if($type==='append'){  
  $resData=appendObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
                   $creator,$last_modifer,$last_mod_date,$create_date);
 }
 /* 
 if($type==='replace'){
  $resData=replaceObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
                   $creator,$last_modifer,$last_mod_date,$create_date);
 }
  * 
  */
// return $resData;
//}
/**
 * 
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
 * @return string
 */
/*
function appendObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
                   $creator,$last_modifer,$last_mod_date,$create_date){
 $resData['success'] = 1;
 $resData['message'] = '?_appendObject';
 
 if(addObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
              $creator,$last_modifer,$last_mod_date,$create_date)){
  /* 
  if (createDB($db_name)){
    if (createTablesOnDB($db_name)&&createImageDir($db_name)){
         $resData['success'] = 1;
         $resData['message'] = 'Объект создан';  
    }else{
          $resData['success'] = 0;
          $resData['message'] = 'Ошибка создания таблиц или папки для изображений';        
    }
   } else {
     $resData['success'] = 0;
     $resData['message'] = 'Ошибка создания БД';      
   }         
 } else {
  $resData['success'] = 0;
  $resData['message'] = 'Ошибка добавления записи';    
  */ 
// }
// return $resData;
//}
/**
 * 
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
 * @return string
 */



/*
function replaceObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
                   $creator,$last_modifer,$last_mod_date,$create_date){
    
 $resData['success'] = 1;
 $resData['message'] = '?_replaceObject';   
    
 $addObj=addObject($id,$type,$db_name,$obj_name,$location,$image,$ta_comments,
                   $creator,$last_modifer,$last_mod_date,$create_date);
 
  if(!$addObj){
     $resData['success'] = 0;
     $resData['message'] = 'Ошибка изменения данных';   
  } 
  if($addObj===2){ 
     $resData['success'] = 1;
     $resData['message'] = 'Данные не изменялись';   
   }else{
     $resData['success'] = 1;
     $resData['message'] = 'Данные успешно изменены';     
    } 
 return $resData;
}
 */ 