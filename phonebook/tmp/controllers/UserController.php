<?php
/**
 * Контроллер функций пользователя
 * таблица users
 */
// подключаем модели
include_once '../models/UsersModel.php';
include_once '../models/ObjectsModel.php';
include_once '../models/SheduleModel.php';
include_once '../models/OperationsModel.php';
include_once '../models/MainModel.php';
include_once '../models/AvModel.php';

/**
 *Функция входа пользователя
 * вызывается по нажатию кнопки "Войти"  формы StartPage;
 */
function loginAction(){
  //Объявляем массив $resData
  $resData['success']=false; 
  $resData['message']="?";
  $resData['privilegy']="None";
 
  //Принимаем login, pwd
  $login = isset($_REQUEST['login']) ?  $_REQUEST['login'] : null;
  $login = trim ($login);
  
  $pwd = isset($_REQUEST['pwd']) ?  $_REQUEST['pwd'] : null;
  $pwd = trim ($pwd);  
  
  //Проверяем не пустые ли login, pwd
  if(!($login && $pwd)){
   //Пустые   
   $resData['success']=false;
   $resData['message']="Поля 'Логин' и 'Пароль' не могут быть пустыми";
  }else{  
   //Не пустые 
   $_SESSION['user']['login']= $login;
   $login = "bs_".$login;
   $pwd= sha1($pwd.$login);
   //Проверяем зарегестрирован ли пользователь с введенными login, pwd
   if ($privilegy=getPrivilegy($login,$pwd)){
     //Зарегестрирован  
     $obj_id= getObjId($login,$pwd);
     $usr_id=getUsrId($login,$pwd);
     
     $resData['success']=true;
     $resData['privilegy']=$privilegy;
     $resData['message']=$privilegy;
     $_SESSION['user']['privilegy']= $privilegy;
     $_SESSION['user']['obj_id']= $obj_id;
     $_SESSION['user']['usr_id']= $usr_id;
   }else{
    //Не зарегестроирован   
    $resData['success']=false;
    $resData['message']="Пользователь не зарегестрирован";
   }
  }
 echo json_encode($resData);
}
/**
 *  
 * @param type $smarty
 */
function superrootAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="superroot"){
  
  $rsObjects=getAllObjects();   
  $smarty->assign('rsObjects',$rsObjects); 
  
  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'superroot/headerline');
  loadTemlate($smarty,'superroot/leftmenu');
  loadTemlate($smarty,'superroot/objects');
  loadTemlate($smarty,'footer');      
 }else{
  redirect('/');
 } 
}
/**
 * Если вошли с привидегией root
 * @param type $smarty
 */
function rootAction($smarty){ 
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="root"){
   $date=getMyDate();  
   $emp_sum_salary=0;
   $adm_salary=0;
   $photo_in_office=0;
   $photo_in_home=0; 
   $rsEmps=NULL;
   $rsAdm=NULL;
   $base_adm_salary=0;
   $rsEmpWorckingToDay=getEmpWorckingToDay($date);
   if(!$rsEmpWorckingToDay){
   /*
    $rsEmpWorckingToDay[0]['employee_id']=5;
    $rsEmpWorckingToDay[0]['privilegy']='admin'; 
    $rsEmpWorckingToDay[0]['short_name']='Администратор'; 
    $rsEmpWorckingToDay[0]['first_name']='Панькова'; 
    $rsEmpWorckingToDay[0]['last_name']='Валентина'; 
    $rsEmpWorckingToDay[0]['partonymic']= 'Петровна'; 
    $rsEmpWorckingToDay[0]['image']='/images/shagane/emp_5_bs.png'; 
    */
   }
   //d($rsEmpWorckingToDay);
   
   $allSummary=getAllSummary($date);
   if(!$allSummary){$allSummary=0;}
   $allBsSummary=getAllBsSummary($date);
   if(!$allBsSummary){$allBsSummary=0;}
   $allPhotoSummary=getAllPhotoSummary($date);
   if(!$allPhotoSummary){$allPhotoSummary=0;}
   $allXeroxSummary=getAllXeroxSummary($date);
   if(!$allXeroxSummary){$allXeroxSummary=0;}
   $allAdditionalSummary=getAllAdditionalSummary($date);
   if(!$allAdditionalSummary){$allAdditionalSummary=0;}
   $allSaleSummary=getAllSaleSummary($date);
   if(!$allSaleSummary){$allSaleSummary=0;}

   //Если в таблице shedule есть записи на текущий день 
   if (getShedule($_SESSION['user']['obj_id'])){
    //1.Получаем массив id парикмахеров   
    $rsIds=getEmployeesFromSheduler();
    //2.Вычисляем зп. парикмахеров в $emp_sum_salary
    foreach ($rsIds as $value){
     $emp_sum_salary=$emp_sum_salary+getSalaryEmpByID($value, $date); 
     $photo_in_office=$photo_in_office+getSumPhotoEmpByID($value,$date);
    }
    //3.Получаем id администратора
    $adm_id=getAdminIdFromSheduler();
    //4.Считаем фото сделанные администратором
    $photo_in_office=$photo_in_office+getSumPhotoEmpByID($adm_id,$date);
    //5.Вычисляем з.п. администратора
    $adm_salary=getSalaryEmpByID($adm_id, $date);
    //6.Получаем массив данных [id,first_name, last_name,partonymic, image] 
    //о парикмахерах из таблицы users в массив $rsEms  
    $rsEmps=getSeveralEmployees($rsIds);
    $rsAdmId=array(0=>$adm_id);
    $rsAdm=getSeveralEmployees($rsAdmId);
   }
    //d($rsAdm);
   $photo_in_home=$allPhotoSummary-$photo_in_office;
   
   $cur_date= getMyDateTime();
   $sale_to_boss=$allSummary-$emp_sum_salary-$adm_salary;
   //Готовим массив $rsEmpWorckingToDay
    $rsAv= getAv();
    
   foreach ($rsEmpWorckingToDay as &$value){
    $bs_summary=getSumBsEmpByID($value['employee_id'],$date);
    if(!$bs_summary){$bs_summary=0;}
    $photo_summary=getSumPhotoEmpByID($value['employee_id'],$date);
    if(!$photo_summary){$photo_summary=0;}
    $xerox_summary=getSumXeroxEmpByID($value['employee_id'],$date);
    if(!$xerox_summary){$xerox_summary=0;}
    $additional_summary=getSumAdditionalEmpByID($value['employee_id'],$date);
    if(!$additional_summary){$additional_summary=0;}
    $sale_summary=getSumSaleEmpByID($value['employee_id'],$date);
    if(!$sale_summary){$sale_summary=0;}
    $work_summary=getSumWorkEmpByID($value['employee_id'],$date);
    if(!$work_summary){$work_summary=0;}
    $all_summary=getSumAllEmpByID($value['employee_id'],$date);
    if(!$all_summary){$all_summary=0;}
    $percent1=getEmpPercent1ById($value['employee_id'],'salary');
    $percent2=getEmpPercent1ById($value['employee_id'],'salary_1');
    $bs_salary=getBsSalary($percent1,$percent2,$rsAv,$value['employee_id']);
    if(!$bs_salary){$bs_salary=0;}
    $photo_salary=getPhotoSalary($rsAv,$value['employee_id']);
    if(!$photo_salary){$photo_salary=0;}
    $xerox_salary=getXeroxSalary($rsAv,$value['employee_id']);
    if(!$xerox_salary){$xerox_salary=0;} //getAdditionalSalary   
    $additional_salary=getAdditionalSalary($rsAv,$value['employee_id']);
    if(!$additional_salary){$additional_salary=0;}   
    $sale_salary=getSaleSalary($rsAv,$value['employee_id']);
    if(!$sale_salary){$sale_salary=0;} 
    $work_salary=getWorkSalary($value['employee_id']);
    if(!$work_salary){$work_salary=0;} 
    if(isset($adm_id)){
     if($value['employee_id']===$adm_id)
     {$base_adm_salary=getbaseAdmSalary($rsAv,$percent1,$percent2);}
     else{$base_adm_salary=0;}
    }
    $all_salary=$base_adm_salary+$bs_salary+$photo_salary+$xerox_salary+$additional_salary+
            $sale_salary+$work_salary;
    if(!$all_salary){$all_salary=0;}
    
    $rsAllEmpOperation=getAllCurOperationByID($value['employee_id']);
    $rsAllEmpOperation=formatOperationsToTable($rsAllEmpOperation,$value['employee_id'],'root');
    
    if($value['privilegy']==='root'){
     $bs_salary=0;
     $photo_salary=0;
     $xerox_salary=0;
     $additional_salary=0;
     $sale_salary=0;
     $work_salary=0;
     $all_salary=0;
     //d($rsAllEmpOperation);
     foreach( $rsAllEmpOperation as &$val){
       //$rsAllEmpOperation  salary_by_op
       $val['salary_by_op']=0;  
     }
    } 
    $value['bs_summary']=$bs_summary;
    $value['photo_summary']=$photo_summary;
    $value['xerox_summary']=$xerox_summary;
    $value['additional_summary']=$additional_summary;
    $value['sale_summary']=$sale_summary;
    $value['work_summary']=$work_summary;
    $value['all_summary']=$all_summary;
    $value['bs_salary']=$bs_salary;
    $value['photo_salary']=$photo_salary;
    $value['xerox_salary']=$xerox_salary;
    $value['additional_salary']=$additional_salary;
    $value['sale_salary']=$sale_salary;
    $value['work_salary']=$work_salary;
    $value['all_salary']=$all_salary;
    $value['operation_array']=$rsAllEmpOperation;
   }
   //парсим дату
   $date_time=date_parse(getMyDateTime());
   $create_date=$date_time['hour'].":".$date_time['minute'];
   //Находим базоаую з.п. администратора
   $adm_id= getAdminIdFromSheduler();
   $percent1=getEmpPercent1ById($adm_id,'salary');
   $percent2=getEmpPercent1ById($adm_id,'salary_1');
   $adm_base_salary = getbaseAdmSalary($rsAv,$percent1,$percent2);
   
   $adminBaseSal=array(
    "ID"=>0,   
    "create_date" => $create_date,
    "operation_id" => 0,
    "client_no" => 0,
    "price" => 0,
    "count" => 1,
    "sum" => '',
    "type_code" => '00',
    "name_code" => '00',
    "hall_code" => 0,
    "hall_name" => 'Работа',
    "type_name" => '',
    "name_value" => 'Базовая з.пл. администратору',
    "salary_by_op" => $adm_base_salary
   );
  
   if($rsEmpWorckingToDay){
    //$rsEmpWorckingToDay[0]['operation_array']=$adminBaseSal;   
    //d($rsEmpWorckingToDay);  
    array_unshift($rsEmpWorckingToDay[0]['operation_array'],$adminBaseSal);
   }
   
   //d($rsEmpWorckingToDay);
   $smarty->assign('rsEmpWorckingToDay',$rsEmpWorckingToDay);
   if($rsEmps){$smarty->assign('rsEmps',$rsEmps);}
   if($rsAdm){$smarty->assign('rsAdm',$rsAdm);}
   
   $obj_name= mb_strtoupper(getObjNameById($_SESSION['user']['obj_id']));
   $smarty->assign('obj_name',$obj_name);
   $smarty->assign('allSummary',$allSummary);
   $smarty->assign('allPhotoSummary',$allPhotoSummary);
   $smarty->assign('allBsSummary',$allBsSummary);
   $smarty->assign('allXeroxSummary',$allXeroxSummary);
   $smarty->assign('allAdditionalSummary',$allAdditionalSummary);
   $smarty->assign('allSaleSummary',$allSaleSummary);
   $smarty->assign('cur_date',$cur_date);
   $smarty->assign('emp_sum_salary',$emp_sum_salary);
   $smarty->assign('adm_salary',$adm_salary);
   $smarty->assign('sale_to_boss',$sale_to_boss);
   $smarty->assign('photo_in_office',$photo_in_office);
   $smarty->assign('photo_in_home',$photo_in_home);
   
   loadTemlate($smarty,'header');   
   loadTemlate($smarty,'root/headerline');
   loadTemlate($smarty,'root/leftmenu');   
   loadTemlate($smarty,'root/summary');
   loadTemlate($smarty,'footer');  
 }
}
/**
 * Функция вызова страницы администратора
 * @param type $smarty
 */
function adminAction($smarty){     
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }      
 if($_SESSION['user']['privilegy'] ==="admin"){
  //Если в таблице shedule нет записи на текущий день   
  if (!getShedule($_SESSION['user']['obj_id'])){ 
    employessAction($smarty);
  }else{
   //Если в таблице shedule есть запись о том кто сегодня работает то:
   //1.Получаем массив id парикмахеров   
   $rsIds=getEmployeesFromSheduler();
   //2.Получаем массив данных [id,first_name, last_name,partonymic, image] 
   //о парикмахерах из таблицы users в массив $rsEms
   $rsEmps=getSeveralEmployees($rsIds);
   $rsAdm=getAdm($_SESSION['user']['usr_id']);
   array_unshift($rsEmps,$rsAdm[0]);
   //d($rsEmps);
   //3.Передаем массив $rsEms в smarty 
   $smarty->assign('rsEmps',$rsEmps); 

   //Загружаем операции для администратора
   $rsAdminOperations=getAdminOperations();
 
   //Загружаем операции для парикмахеров
   $rsOperations=getActiveOperations();
    //добавляем 0 перед кодами в $rsAdminOperations
   foreach ($rsAdminOperations as &$value) {    
    if (strlen($value['type_code'])===1){
     $value['type_code'] = '0'.$value['type_code'];
    }
    if (strlen($value['name_code'])===1){
     $value['name_code'] = '0'.$value['name_code'];
     }
    }         
   //добавляем 0 перед кодами в $rsOperations
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
   //Получаем массив id  всех сотрудников
   array_unshift($rsIds,$_SESSION['user']['usr_id']);
   foreach ($rsIds as $cur_id) {
    $rsName="rsOperation_".$cur_id."_emp";
    //В $$rsName хранятся все операции сделанные сотрудником, из базы main
    $$rsName=getAllCurOperationByID($cur_id);
    //d($$rsName);
    //Подсчитываем общую выручку сотрудника
    $Name="rsSumm_".$cur_id."_bs";
    $$Name=getSumAllEmpByID($cur_id, getMyDate());
    //$$Name=getSumBsEmpByID($cur_id, getMyDate());
    //Подсчитываем зарплату сотрудника на текщий день
    $NameSalary="Salary_".$cur_id."_bs";
    $$NameSalary=getSalaryEmpByID($cur_id, getMyDate());
    
    $cn=0;
    $summa=0;
    //если массив операций не пуст, приводим его в порядок для вывода
   if($$rsName){
   foreach ($$rsName as &$value) {
     //считаем сумму
     //Если тип операции "работа" , то в сумме её не учитываем 
     if(!($value['type_code']==20)){  
      $summa=$summa+$value['price'];
      $value['sum']=$summa;
     } 
     //Если тип операции "работа" то в поле "price" массива вставляем стоимость работы
     else{
       $value['price']= round(getPriceByID($value['operation_id']));
     }
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
   //d($$rsName);
   //вычисляем выручку
   //общая сумма по всем сотрудникам
     $allSummary=getAllSummary(getMyDate());
     
     $smarty->assign($rsName,$$rsName);
     $smarty->assign($Name,$$Name);
     $smarty->assign($NameSalary,$$NameSalary);
   }
   //d($rsAdminOperations);
   $objName=getObjNameById($_SESSION['user']['obj_id']);
   $smarty->assign('allSummary',$allSummary);
   $smarty->assign('objName',$objName);
   $smarty->assign('admin_id',$_SESSION['user']['usr_id']);
   $smarty->assign('rsOperations',$rsOperations);
   $smarty->assign('rsAdminOperation',$rsAdminOperations);
   
   loadTemlate($smarty,'header');   
   loadTemlate($smarty,'admin/headerline');
   loadTemlate($smarty,'admin/leftmenu');   
   loadTemlate($smarty,'admin/main');
   loadTemlate($smarty,'footer');  
  }
 } 
 }
/**
 * 
 * @param type $smarty
 */
function usersAction($smarty){

 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
  }     
  if($_SESSION['user']['privilegy'] ==="superroot"){
      
/*     
//     $pr="";
    
    $login = isset($_SESSION['user']['login']) ? $_SESSION['user']['login'] : null;
      $dbname = isset($_SESSION['user']['dbname']) ? $_SESSION['user']['dbname'] : null;
    
    if($login&&$dbname){
    $pr=checkPrivelegy($login,$dbname);
    }
    
    if ($pr == 'superroot'){
 */    
     $rsUsers=getAllUsers();
     $rsObjects=getAllObjects();
     $smarty->assign('rsUsers',$rsUsers); 
     $smarty->assign('rsObjects',$rsObjects); 
     
     loadTemlate($smarty,'header');    
     loadTemlate($smarty,'superroot/headerline');
     loadTemlate($smarty,'superroot/leftmenu');
     loadTemlate($smarty,'superroot/users');
     loadTemlate($smarty,'footer');
     } 
 
}
/**
 * 
 */
function adduserAction() {
   //Объявляем массив  resData,который будет возвращаться в форму,
   //чтобы потом не было ошибок
   $resData['success'] = 1;
   $resData['message'] = '?';
   $id=0;
  
   //ПРИНИМАЕМ ДАННЫЕ
   //type - тип действмя 'append' добавление новой строки, 
   //                    'replace' замена старой
    
   $type = isset($_REQUEST['type']) ?  $_REQUEST['type'] : null;
   $type = trim ($type);
   
   $id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : null;
  
   $obj_id = isset($_REQUEST['obj_id']) ?  $_REQUEST['obj_id'] : null;
   
   $login = isset($_REQUEST['login']) ?  $_REQUEST['login'] : null;
   $login = trim ($login);
  
   $password = isset($_REQUEST['password']) ?  $_REQUEST['password'] : null;
   $password = trim ($password);
   
   $short_name = isset($_REQUEST['short_name']) ?  $_REQUEST['short_name'] : null;
   $short_name = trim ($short_name);
   
   $first_name = isset($_REQUEST['first_name']) ?  $_REQUEST['first_name'] : null;
   $first_name = trim ($first_name);
   
   $last_name = isset($_REQUEST['last_name']) ?  $_REQUEST['last_name'] : null;
   $last_name = trim ($last_name);
  
   $partonymic = isset($_REQUEST['partonymic']) ?  $_REQUEST['partonymic'] : null;
   $partonymic = trim ($partonymic);
    
   $salary = isset($_REQUEST['salary']) ?  $_REQUEST['salary'] : null;
  
   $salary_1 = isset($_REQUEST['salary_1']) ?  $_REQUEST['salary_1'] : null;
   
   $is_work = isset($_REQUEST['is_work']) ?  $_REQUEST['is_work'] : null;
  
   $email = isset($_REQUEST['email']) ?  $_REQUEST['email'] : null;
   $email = trim ($email);
 
   $phone = isset($_REQUEST['phone']) ?  $_REQUEST['phone'] : null;
   $phone = trim ($phone);
   
   $image = isset($_REQUEST['image']) ?  $_REQUEST['image'] : null;
   $image = trim ($image);
   
   $comment = isset($_REQUEST['comment']) ?  $_REQUEST['comment'] : null;
   $comment = trim ($comment);
   
   $privelegy = isset($_REQUEST['privelegy']) ?  $_REQUEST['privelegy'] : null;
   $privelegy = trim ($privelegy);
 
   $last_mod_date=getMyDateTime();
   $last_modifer = isset($_SESSION['user']['login']) ? $_SESSION['user']['login'] : null;
   $create_date="";
   $creator="";
   //d($phone);
   if ($type==='append'){
   $create_date=$last_mod_date;
   $creator=$last_modifer;
   }
   /*-----*/
   //$resData['message'] = "type=".$type." login=".$login." email=".
   //        $email." password=".$password." privelegy=".$privelegy." dbnam=".$db_name." id=".$id;
   
   $validateUser=validateUser($type,$id,$obj_id,$login,$password,$short_name,$first_name,
                    $last_name,$partonymic,$salary,$salary_1,$is_work,$email,
                    $phone,$privelegy,$image,$comment,$create_date,$creator,
                    $last_mod_date,$last_modifer);
   switch ($validateUser)
   {    
    case 1: //Данные введены правильно
      $login="bs_".$login;
      if($password){  
      $password= sha1($password.$login);
      }
      if($privelegy==='superroot'){
       $salary=0;
       $salary_1=0;
       $obj_id=0;
       $short_name="";
      }   
      if($privelegy==='root'){
       $salary=0;
       $salary_1=0;
       $short_name="";
      }
      saveUser($type,$id,$obj_id,$login,$password,$short_name,$first_name,
                    $last_name,$partonymic,$salary,$salary_1,$is_work,$email,
                    $phone,$privelegy,$image,$comment,$create_date,$creator,
                    $last_mod_date,$last_modifer);  
      $resData['success'] = 1;
      $resData['message'] = "Данные сохранены"; 
      break;
    case 2://Пользователь уже существует
      $resData['success'] = 0;
      $resData['message'] = "Пользователь с таким логином и паролем уже зарегестрирован.";  
      break;
    case 3:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Логин' не может быть пустым";  
      break; 
    case 4:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Пароль' не может быть пустым";  
      break; 
    case 5:
      $resData['success'] = 0;
      $resData['message'] = "Не выбраны Привилегии";  
      break; 
    case 6:
      $resData['success'] = 0;
      $resData['message'] = "Не выбран объект";  
      break; 
    case 7:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Фамилия' не может быть пустым";  
      break; 
    case 8:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Имя' не может быть пустым";  
      break; 
    case 9:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Процент' не может быть пустым";  
      break; 
    case 10:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Повышенный %' не может быть пустым";  
      break; 
    case 11:
      $resData['success'] = 0;
      $resData['message'] = "Поле 'Короткое' не может быть пустым";  
      break; 
   }
    $resData['error_code'] = $validateUser;
   echo json_encode($resData);
}
function edituserAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';
 $id=0;
 
 //ПРИНИМАЕМ ДАННЫЕ
 //usr_id - id пользователя в таблице users

 $usr_id = isset($_REQUEST['usr_id']) ?  $_REQUEST['usr_id'] : null;
 $usr_id = trim ($usr_id);
 
 //$resData['message'] = $usr_id;
 $rsUser=findUserByID($usr_id);
 
 
 $resData['success'] = 1;
 $resData['message'] = "Ok";
 $resData['ID'] = $rsUser[0]['ID'];
 $resData['obj_id'] = $rsUser[0]['obj_id'];
 $resData['privilegy'] = $rsUser[0]['privilegy'];
 $resData['login'] =substr($rsUser[0]['login'],3, (strlen($rsUser[0]['login'])-3));
 $resData['password'] = "";
 $resData['short_name'] = $rsUser[0]['short_name'];
 $resData['first_name'] = $rsUser[0]['first_name'];
 $resData['last_name'] = $rsUser[0]['last_name'];
 $resData['partonymic'] = $rsUser[0]['partonymic']; 
 $resData['salary'] = $rsUser[0]['salary'];
 $resData['salary_1'] = $rsUser[0]['salary_1'];
 $resData['is_work'] = $rsUser[0]['is_work'];
 $resData['email'] = $rsUser[0]['email'];
 $resData['phone'] = $rsUser[0]['phone'];
 $resData['image'] = $rsUser[0]['image'];
 $resData['comment'] = $rsUser[0]['comment'];
 $resData['create_date'] = $rsUser[0]['create_date'];
 $resData['creator'] = $rsUser[0]['creator'];
 $resData['last_mod_date'] = $rsUser[0]['last_mod_date'];
 $resData['last_modifer'] = $rsUser[0]['last_modifer'];
 
 echo json_encode($resData);
}
function employessAction($smarty){
 if(! isset($_SESSION['user']['privilegy'])){
       redirect('/'); 
   }     
 if($_SESSION['user']['privilegy'] ==="admin"){
  
  $rsEmployee=getWorkEmployess($_SESSION['user']['obj_id']); 
   //d($rsEmployee);
  //Если нет записи в таблице shedulers
  if (!getShedule($_SESSION['user']['obj_id'])){
   $warning="Выберите, кто сегодня работает.";  
   $smarty->assign('warning',$warning); 
  }else{      
   $rsWorkEmp=getEmployeesFromSheduler();
    foreach ($rsEmployee as &$emp) {
      foreach ($rsWorkEmp as &$w_emp){
       if($emp['id']===$w_emp){$emp['@checked']='checked';}   
      }  
    }
  }
  $smarty->assign('rsEmployee',$rsEmployee);

  loadTemlate($smarty,'header');   
  loadTemlate($smarty,'admin/headerline');
  loadTemlate($smarty,'admin/leftmenu');
  loadTemlate($smarty,'admin/employees');
  loadTemlate($smarty,'footer');  
 } 
 else{redirect('/');}
}
function sheduleAction(){
//Объявляем массив  resData,который будет возвращаться в форму,
//чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';
  
 $work_emp_str = isset($_REQUEST['work_emp_str']) ?  $_REQUEST['work_emp_str'] : null;
 $work_emp_str = trim ($work_emp_str);
 
 if (!$work_emp_str){
  $resData['success'] = 0;
  $resData['message'] = 'Не выбрано ни одного сотрудника';  
 }else{
   $cur_date= getMyDate();  
   saveToShedule($_SESSION['user']['obj_id'],$cur_date,$_SESSION['user']['usr_id'],$work_emp_str);
   $resData['success'] = 1;
   $resData['message'] = 'Данные сохранены';
   //$resData['message'] = $cur_date;
 }
 
 echo json_encode($resData);
}
function formatOperationsToTable($rs,$emp_id,$mode){
 $cn=0;
 $summa=0; 
 if($rs){
 foreach ($rs as &$value){
 //Заводим поле зарплата за операцию    
  $value['salary_by_op']=0;   
 //парсим дату
 $date_time=date_parse($value['create_date']);
 $value['create_date']=$date_time['hour'].":".$date_time['minute'];
 //считаем сумму
 //Если тип операции "работа" , то в сумме её не учитываем 
 if(!($value['type_code']==20)){  
  $summa=$summa+$value['price'];
  $value['sum']=$summa;
  $value['salary_by_op']=getSalaryByEmpIdOpId($emp_id,$value['operation_id'],$value['count']);
  }
 //Если тип операции "работа" то в поле "price" массива вставляем стоимость работы
 else{
  $value['price']= round(getPriceByID($value['operation_id']));
  $value['salary_by_op']=$value['price'];
  if($mode==='root'){
    $value['price']=0;  
  }
 }
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
return $rs;   
}
function saveuserAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
 //чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';   
 
 echo json_encode($resData);
}
function getdefaultimageAction(){
//Объявляем массив  resData,который будет возвращаться в форму,
//чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';   
 $resData['default_imge'] = getDefaultImage();
 echo json_encode($resData); 
}
function getempparamAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
//чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';   
 
 $emp_id = isset($_REQUEST['$emp_id']) ?  $_REQUEST['$emp_id'] : null;
  
 $resData['emp_param'] = getAdm($emp_id);
 //d();
 echo json_encode($resData);   
}
function newadmAction(){
//Объявляем массив  resData,который будет возвращаться в форму,
//чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = '?';   
 
$adm_id = isset($_REQUEST['adm_id']) ?  $_REQUEST['adm_id'] : null;
$date = getMyDate();
$resData['message'] = $date;
  
if(!getShedule($_SESSION['user']['obj_id'])){
appendAdmToShedule($_SESSION['user']['obj_id'],$adm_id,$date);
$resData['message'] = 'Добавлено';
}
else {
 replaceAdmToShedule($_SESSION['user']['obj_id'],$adm_id,$date);
 $resData['message'] = 'Сохранено';
}
// $resData['emp_param'] = getAdm($emp_id); 
 echo json_encode($resData);      
}
