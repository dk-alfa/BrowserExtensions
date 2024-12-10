<?php
//progect name: BARBERSHOP1
/**
 * Контроллер функций пользователя root
 * таблица users
 */
// подключаем модели
include_once '../models/OperationsVvModel.php';
include_once '../models/OperationsModel.php';
include_once '../models/MainModel.php';
include_once '../models/ObjectsModel.php';
include_once '../models/UsersModel.php';
include_once '../models/SheduleModel.php';
//include_once '../config/config.php';
/* 
include_once '../models/ObjectsModel.php';
include_once '../models/SheduleModel.php';
*/
function employessAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
  $employeeCaption='';
  $adminCaption='';
  $admin_image='';
  $selectedAdm=0;
  //В $rsAdmins список работающих администраторов
  $rsAdmins=getWorkAdmins($_SESSION['user']['obj_id']);
  $admShedule=getAdmShedule($_SESSION['user']['obj_id']);
  //d($admShedule);
  //Если нет записи в таблице shedulers
   if (!$admShedule){
    $employeeCaption='Не выброно ни одного парикмахера';
    $adminCaption='Администратор не выбран';
    $admin_image=$rsAdmins[0]['image'];
   }else{
    $employeeCaption='Сегодня работают';
    $adminCaption='Администратор';  
    $rsAdm=getAdm($admShedule);
    $selectedAdm=$rsAdm[0]['id'];
    $admin_image=$rsAdm[0]['image'];
   } 
   
     
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $rsEmployesList=getEmployesList();
  $rsEmployee=getWorkEmployess($_SESSION['user']['obj_id']);

  //d($rsAdmins);
  if (getShedule($_SESSION['user']['obj_id'])){      
   $rsWorkEmp=getEmployeesFromSheduler();
    foreach ($rsEmployee as &$emp) {
      foreach ($rsWorkEmp as &$w_emp){
       if($emp['id']===$w_emp){$emp['@checked']='checked';}   
      }  
    }
  }
  $smarty->assign('selectedAdm',$selectedAdm);  
  $smarty->assign('admin_image',$admin_image);  
  $smarty->assign('obj_name',$obj_name);  
  $smarty->assign('employeeCaption',$employeeCaption);  
  $smarty->assign('adminCaption',$adminCaption);  
  //d($rsEmployesList);
  $smarty->assign('rsEmployesList',$rsEmployesList);  
  $smarty->assign('rsAdmins',$rsAdmins);  
  $smarty->assign('rsEmployee',$rsEmployee);       
  
  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'root/headerline');
  loadTemlate($smarty,'root/leftmenu');
  loadTemlate($smarty,'root/employess');
  loadTemlate($smarty,'footer');  
 } 
}
function operationsAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
   
  $rsOperations=getAllOperations(); 
  //добавляем 0 перед кодами в $rsOperations
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $smarty->assign('obj_name',$obj_name);
  if ($rsOperations){  
   foreach ($rsOperations as &$value) {    
    if (strlen($value['type_code'])===1){
     $value['type_code'] = '0'.$value['type_code'];
    }
    if (strlen($value['name_code'])===1){
     $value['name_code'] = '0'.$value['name_code'];
    }
    }
   }
  $rsCategory=getAllCategory();
  $rsHalls=getAllHalls();
  $rsTypes=getAllTypes();
  //d($rsOperations);
  
  $smarty->assign('rsOperations',$rsOperations); 
  $smarty->assign('rsCategory',$rsCategory);
  $smarty->assign('rsHalls',$rsHalls);
  $smarty->assign('rsTypes',$rsTypes);
  
  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'root/headerline');
  loadTemlate($smarty,'root/leftmenu');
  loadTemlate($smarty,'root/operations');
  loadTemlate($smarty,'footer');  
 } 
}
function operationsvvAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
 
  $rsVv=getAllVv();
  $smarty->assign('rsVv',$rsVv);    
 
  $ZeroLavel=getZeroLavel();
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $smarty->assign('obj_name',$obj_name);
  $smarty->assign('ZeroLavel',$ZeroLavel);

  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'root/headerline');
  loadTemlate($smarty,'root/leftmenu');
  loadTemlate($smarty,'root/operations_vv');
  loadTemlate($smarty,'footer');
 }
}
function settingsAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $smarty->assign('obj_name',$obj_name);    
  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'root/headerline');
  loadTemlate($smarty,'root/leftmenu');
  loadTemlate($smarty,'root/settings');
  loadTemlate($smarty,'footer');  
 } 
}
function getdetilesAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';

 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
 $id = trim ($id);
 
 $detailes=getVvByID($id);
 if ($detailes){
     
  $resData['message'] = $detailes[0]['value'];   
 }
 echo json_encode($resData); 
}
function getopdetilesbyidAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';

 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
 $id = trim ($id);
 
 $detailes=getOperationDetilesByID($id);
 
 $resData['message'] = "success";
 $resData['category_code'] = $detailes[0]['category_code'];
 $resData['hall_code'] = $detailes[0]['hall_code'];
 $resData['type_code'] = $detailes[0]['type_code'];
 $resData['name_code'] = $detailes[0]['name_code'];
 $resData['name_value'] = $detailes[0]['name_value'];
 $resData['price'] = $detailes[0]['price'];
 $resData['action'] = $detailes[0]['action'];
 $resData['printable'] = $detailes[0]['printable'];
 $resData['comment'] = $detailes[0]['comment'];
 $resData['ID'] = $detailes[0]['ID'];
 $resData['obj_id'] = $detailes[0]['obj_id'];
 $resData['create_date'] = $detailes[0]['create_date'];
 $resData['creator'] = $detailes[0]['creator'];
 $resData['last_mod_date'] = $detailes[0]['last_mod_date'];
 $resData['last_modifer'] = $detailes[0]['last_modifer'];
 $resData['active'] = $detailes[0]['active'];
 
 //if ($detailes){
     
 // $resData['message'] = $detailes[0]['value'];   
 //}
 echo json_encode($resData); 
}
function replaceoperationAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = 'replaceoperationAction';

 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
 $id = trim ($id);
  
 $obj_id = isset($_REQUEST['obj_id']) ?  $_REQUEST['obj_id'] : null;
 $obj_id = trim ($obj_id);
 
 $category_code = isset($_REQUEST['category_code']) ?  $_REQUEST['category_code'] : null;
 $category_code = trim ($category_code);
 
 $hall_code = isset($_REQUEST['hall_code']) ?  $_REQUEST['hall_code'] : null;
 $hall_code = trim ($hall_code);
 
 $type_code = isset($_REQUEST['type_code']) ?  $_REQUEST['type_code'] : null;
 $type_code = trim ($type_code);
 
 $name_code = isset($_REQUEST['name_code']) ?  $_REQUEST['name_code'] : null;
 $name_code = trim ($name_code);
 
 $name_value = isset($_REQUEST['name_value']) ?  $_REQUEST['name_value'] : null;
 $name_value = trim ($name_value);
 
 $price = isset($_REQUEST['price']) ?  $_REQUEST['price'] : null;
 $price = trim ($price);
 
 $action = isset($_REQUEST['action']) ?  $_REQUEST['action'] : null;
 $action = trim ($action);
 
 $printable = isset($_REQUEST['printable']) ?  $_REQUEST['printable'] : null;
 $printable = trim ($printable);
 
 $comment = isset($_REQUEST['comment']) ?  $_REQUEST['comment'] : null;
 $comment = trim ($comment);
 
 $create_date = isset($_REQUEST['create_date']) ?  $_REQUEST['create_date'] : null;
 $create_date = trim ($create_date);
 
 $creator = isset($_REQUEST['creator']) ?  $_REQUEST['creator'] : null;
 $creator = trim ($creator);

 $last_mod_date=getMyDateTime();
 
 $last_modifer = isset($_SESSION['user']['login']) ? $_SESSION['user']['login'] : null;
 
 $active = isset($_REQUEST['active']) ?  $_REQUEST['active'] : null;
 
 //Проверяем правильность введенных данных
 $type='replace';
 $validateMessage=validateOperation($type,$id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer);  
 $resData['message'] = $validateMessage;
   switch ($validateMessage)
   {    
    case 1: //Данные введены правильно
    $rd=replaceOperaton($id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer,$active);
    $resData['message'] = $rd;
     if ($rd){         
       $resData['success'] = true;
       $resData['message'] = 'Данные сохранены';      
      }     
   }
 
 echo json_encode($resData); 
}
function appendoperationAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = 'appendoperation';

 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
 $id = trim ($id);
  
 $obj_id = isset($_REQUEST['obj_id']) ?  $_REQUEST['obj_id'] : null;
 $obj_id = trim ($obj_id);
 
 $category_code = isset($_REQUEST['category_code']) ?  $_REQUEST['category_code'] : null;
 $category_code = trim ($category_code);
 
 $hall_code = isset($_REQUEST['hall_code']) ?  $_REQUEST['hall_code'] : null;
 $hall_code = trim ($hall_code);
 
 $type_code = isset($_REQUEST['type_code']) ?  $_REQUEST['type_code'] : null;
 $type_code = trim ($type_code);
 
 $name_code = isset($_REQUEST['name_code']) ?  $_REQUEST['name_code'] : null;
 $name_code = trim ($name_code);
 
 $name_value = isset($_REQUEST['name_value']) ?  $_REQUEST['name_value'] : null;
 $name_value = trim ($name_value);
 
 $price = isset($_REQUEST['price']) ?  $_REQUEST['price'] : null;
 $price = trim ($price);
 
 $action = isset($_REQUEST['action']) ?  $_REQUEST['action'] : null;
 $action = trim ($action);
 
 $printable = isset($_REQUEST['printable']) ?  $_REQUEST['printable'] : null;
 $printable = trim ($printable);
 
 $active = isset($_REQUEST['active']) ?  $_REQUEST['active'] : null;
 
 $comment = isset($_REQUEST['comment']) ?  $_REQUEST['comment'] : null;
 $comment = trim ($comment);
 
 $create_date = isset($_REQUEST['create_date']) ?  $_REQUEST['create_date'] : null;
 $create_date = trim ($create_date);
 
 $creator = isset($_REQUEST['creator']) ?  $_REQUEST['creator'] : null;
 $creator = trim ($creator);

 $last_mod_date=getMyDateTime();
 
 $last_modifer = isset($_SESSION['user']['login']) ? $_SESSION['user']['login'] : null;
 $type='append';
 //Проверяем правильность введенных данных
 $validateMessage=validateOperation($type,$id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer);  
 
   switch ($validateMessage)
   {    
    case 1: //Данные введены правильно
     $name_code=getNameCode($obj_id,$category_code,$hall_code,$type_code);   
     $rd=appendOperaton($id,$obj_id,$category_code,$hall_code,
                    $type_code,$name_code,$name_value,$price,$action,$printable,
                    $comment,$create_date,$creator,$last_mod_date,$last_modifer,$active);
     //$resData['message'] = $rd;   
     if ($rd){         
       $resData['success'] = true;
       $resData['message'] = 'Запись добавлена';      
      }     
   }
 echo json_encode($resData); 
}
function deleteoperationAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = 'deleteoperation';

 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
 $id = trim ($id);
 
 //$type='delete';
 //Проверяем правильность введенных данных
 //$validateMessage=validateOperation($type,$id,$obj_id,$category_code,$hall_code,
 //$type_code,$name_code,$name_value,$price,$action,$printable,                   
 //$comment,$create_date,$creator,$last_mod_date,$last_modifer);  
/*
 $validateMessage=1;
   switch ($validateMessage)
   {    
    case 1: //Данные введены правильно   
     $rd=deleteOperaton($id);
     //$resData['message'] = $rd;   
     if ($rd){         
       $resData['success'] = true;
       $resData['message'] = 'Запись удалена';      
    }     
   }
 * 
 */
 if ($id){
  $rd=deleteOperaton($id);
   if ($rd){         
    $resData['success'] = true;
    $resData['message'] = 'Запись удалена';      
   }   
 }
 echo json_encode($resData); 
}
function workAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
  $rsRootOperations=getRootOperations();
  foreach ($rsRootOperations as &$value) {
    //добавляем 0 перед кодами    
    if (strlen($value['type_code'])===1){
     $value['type_code'] = '0'.$value['type_code'];
    }
    if (strlen($value['name_code'])===1){
     $value['name_code'] = '0'.$value['name_code'];
     }
    } 
  $usr_id=$_SESSION['user']['usr_id'];
  //В $rsCurOperations находятся все операции за день
  $rsCurOperations=getAllCurOperationByID($usr_id);
  //Если $rsCurOperations непуст приводим его в порядок
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $smarty->assign('obj_name',$obj_name);
  if($rsCurOperations){
   $cn=0;//Номер клиента
   $summa=0;//Сумма
   foreach ($rsCurOperations as &$value){
    //считаем сумму
     $summa=$summa+$value['price'];
     $value['sum']=$summa;
     //если уже есть такой client_no, то стаим пробел
     if ($value['client_no']===$cn){
       $value['client_no']='';  
     }  
     else{$cn=$value['client_no'];}
         //добавляем 0 перед кодами    
    if (strlen($value['type_code'])===1){
     $value['type_code'] = '0'.$value['type_code'];
    }
    if (strlen($value['name_code'])===1){
     $value['name_code'] = '0'.$value['name_code'];
     }
    //Если количество больше 1 то добавляем количество в название 
    if (!($value['count']==='1')){
      $value['name_value']=$value['name_value'].' ['.$value['count'].' шт.]';  
     }  
   }
  }
 
  $smarty->assign('rsRootOperations',$rsRootOperations);
  $smarty->assign('usr_id',$usr_id);
  $smarty->assign('rsCurOperations',$rsCurOperations);
  
  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'root/headerline');
  loadTemlate($smarty,'root/leftmenu');
  loadTemlate($smarty,'root/work');
  loadTemlate($smarty,'footer');  
 } 
}
function deloperationbyidAction(){
  //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?'; 
 
 $rec_id = isset($_REQUEST['rec_id']) ?  $_REQUEST['rec_id'] : null;
 if($rec_id){delRecordById($rec_id);$resData['message'] = 'Операция удалена'; }
 
 echo json_encode($resData); 
}
function analyticsAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $smarty->assign('obj_name',$obj_name);
  /*   
  $rsRootOperations=getRootOperations();
  foreach ($rsRootOperations as &$value) {
    //добавляем 0 перед кодами    
    if (strlen($value['type_code'])===1){
     $value['type_code'] = '0'.$value['type_code'];
    }
    if (strlen($value['name_code'])===1){
     $value['name_code'] = '0'.$value['name_code'];
     }
    } 
  $usr_id=$_SESSION['user']['usr_id'];
  //В $rsCurOperations находятся все операции за день
  $rsCurOperations=getAllCurOperationByID($usr_id);
  //Если $rsCurOperations непуст приводим его в порядок
  $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
  $smarty->assign('obj_name',$obj_name);
  if($rsCurOperations){
   $cn=0;//Номер клиента
   $summa=0;//Сумма
   foreach ($rsCurOperations as &$value){
    //считаем сумму
     $summa=$summa+$value['price'];
     $value['sum']=$summa;
     //если уже есть такой client_no, то стаим пробел
     if ($value['client_no']===$cn){
       $value['client_no']='';  
     }  
     else{$cn=$value['client_no'];}
         //добавляем 0 перед кодами    
    if (strlen($value['type_code'])===1){
     $value['type_code'] = '0'.$value['type_code'];
    }
    if (strlen($value['name_code'])===1){
     $value['name_code'] = '0'.$value['name_code'];
     }
    //Если количество больше 1 то добавляем количество в название 
    if (!($value['count']==='1')){
      $value['name_value']=$value['name_value'].' ['.$value['count'].' шт.]';  
     }  
   }
  }
 
  $smarty->assign('rsRootOperations',$rsRootOperations);
  $smarty->assign('usr_id',$usr_id);
  $smarty->assign('rsCurOperations',$rsCurOperations);
  */
  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'root/headerline');
  loadTemlate($smarty,'root/leftmenu');
  loadTemlate($smarty,'root/analytics');
  loadTemlate($smarty,'footer');  
 } 
}
function getempparbyidAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?'; 
 
 $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
 $id = trim ($id);
 if($id) {
 $empRecord=getEmployesRecordById($id);
 
  $resData['id']= $empRecord[0]['ID'];
  $resData['obj_id']= $empRecord[0]['obj_id'];
  $resData['login']= $empRecord[0]['login'];
  $resData['password']= $empRecord[0]['password'];
  $resData['short_name']= $empRecord[0]['short_name'];
  $resData['first_name']= $empRecord[0]['first_name'];
  $resData['last_name']= $empRecord[0]['last_name'];
  $resData['partonymic']= $empRecord[0]['partonymic'];
  $resData['salary']= $empRecord[0]['salary']; 
  $resData['salary_1']= $empRecord[0]['salary_1'];
  $resData['is_work']= $empRecord[0]['is_work'];
  $resData['email']= $empRecord[0]['email'];
  $resData['phone']= $empRecord[0]['phone'];
  $resData['privilegy']= $empRecord[0]['privilegy'];
  $resData['image']= $empRecord[0]['image'];
  $resData['comment']= $empRecord[0]['comment'];
  $resData['create_date']= $empRecord[0]['create_date'];
  $resData['creator']= $empRecord[0]['creator'];
  $resData['last_mod_date']= $empRecord[0]['last_mod_date']; 
  $resData['last_modifer']= $empRecord[0]['last_modifer'];
 } 
 else{
 $resData['success'] = 0;
 $resData['message'] = 'Не известный ID  в таблице users';   
 } 
 $resData['message'] = $id;
 echo json_encode($resData); 
}
function loadfileAction(){
  //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?'; 

 if ( 0 < $_FILES['file']['error'] ) { 
   $resData['success'] = 0;
 } 
 else {  
 $file_postfix='\images\\'.getObjNameById($_SESSION['user']['obj_id']).'\\'.$_FILES['file']['name'];    
 $file_to_load = getcwd().$file_postfix;   
 //move_uploaded_file($_FILES['file']['tmp_name'], 'C:\xampp\htdocs\MyProjects\barbershop1\www\images\shagane\\'. $_FILES['file']['name']); 
 move_uploaded_file($_FILES['file']['tmp_name'], $file_to_load); 
 $resData['message'] = 'Загружено';
 $resData['emp_image'] = $file_postfix;
 //$resData['message'] = $path_to_load;
}
  echo json_encode($resData); 
}
function getuseranddateAction(){
 global $MyTimeZone,$MyDateTimeFormat;
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?'; 
 $resData['user'] = $_SESSION['user']['login'];
 date_default_timezone_set($MyTimeZone);
 $resData['datetime'] = date($MyDateTimeFormat);

 echo json_encode($resData); 
}
function getobjidAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?'; 
 $resData['obj_id'] = $_SESSION['user']['obj_id'];
 echo json_encode($resData);     
}


