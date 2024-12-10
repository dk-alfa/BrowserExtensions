<?php
//progect name: BARBERSHOP1
/**
 * Контроллер функций пользователя admin
 * таблица users
 */
// подключаем модели
include_once '../models/MainModel.php';
include_once '../models/ObjectsModel.php';
include_once '../models/UsersModel.php';
include_once '../models/AvModel.php';
include_once '../models/OperationsModel.php';
include_once '../models/SheduleModel.php';
/* 
include_once '../models/OperationsVvModel.php';
include_once '../models/OperationsModel.php';
include_once '../models/ObjectsModel.php';
include_once '../models/SheduleModel.php';
*/
function savetomainAction(){
 //Объявляем массив  resData,который будет возвращаться в форму,
//чтобы потом не было ошибок
 $resData['success'] = 1;
 $resData['message'] = 'savetomainAction';
 

 $id_operations = isset($_REQUEST['id_operations']) ?  $_REQUEST['id_operations'] : null;
 $id_employee = isset($_REQUEST['id_employee']) ?  $_REQUEST['id_employee'] : null;
 $counts = isset($_REQUEST['counts']) ?  $_REQUEST['counts'] : null;
 
 //d($counts);
 
 $_SESSION['user']['emp_id']=$id_employee; 
 if (!count($id_operations)){
  $resData['success'] = 0;
  $resData['message'] = 'Список операций пуст';  
 }
 else{
  $obj_id=$_SESSION['user']['obj_id'];   
  $creator_id=$_SESSION['user']['usr_id'];   
  $cur_date=getMyDateTime();
  $client_no=getClienNo($id_employee);
  $percent1=getEmpPercent1ById($id_employee,'salary');
  $percent2=getEmpPercent1ById($id_employee,'salary_1');
 
  $op_id=array();
  //Выделяем из названия id операции
  foreach ($id_operations as $value){ 
   $op_id[]=explode("_",$value)[1];
  }
  
  $i=0;
  foreach ($op_id as $value) { 
   $price=getPriceByID($value);   
   $price=$price*$counts[$i];
  //d($price);
   appendOperation($obj_id,$id_employee,$value,$client_no,$price,$counts[$i],
           $cur_date,$creator_id,$percent1,$percent2);
   $i++;
  }   
 }   
 $resData['success'] = 1;
 $resData['message'] = 'Все Ок'; 
 $resData['id_employee'] =$id_employee;
 //d($resData);
 echo json_encode($resData);
}
function getemployeeidAction() {
 $resData['success'] = 1;
 $resData['message'] = 'savetomainAction';
 $resData['user_id'] = 0;
 
 $empid = isset($_SESSION['user']['emp_id']) ?  $_SESSION['user']['emp_id'] : null;
 $resData['user_id'] = $empid;
 
 echo json_encode($resData);
 
}
function reportAction($smarty){
if(! isset($_SESSION['user']['privilegy'])){
  redirect('/'); 
 }     
 if($_SESSION['user']['privilegy'] ==="admin"){
   //$admFIO= getFIOById($_SESSION['user']['usr_id'],$_SESSION['user']['obj_id']);
   $rsAdmFIO=getFIOById($_SESSION['user']['usr_id'],$_SESSION['user']['obj_id']);
   $percent1=getEmpPercent1ById($_SESSION['user']['usr_id'],'salary');
   $percent2=getEmpPercent1ById($_SESSION['user']['usr_id'],'salary_1');
   $rsAv= getAv();
   $adm_base_salary = getbaseAdmSalary($rsAv,$percent1,$percent2);
   
   //d($adm_base_salary);
   
   $adminBaseSal=array(
       "employee_id"=>$_SESSION['user']['usr_id'],
       "operation_id"=>0,
       "client_no"=>'0',
       "create_date"=> getMyDateTime(),
       "first_name"=>$rsAdmFIO[0]['first_name'],
       "privilegy"=>'admin',
       "last_name"=>$rsAdmFIO[0]['last_name'],
       "partonymic"=>$rsAdmFIO[0]['partonymic'],
       "type_code"=>0,
       "name_code"=>0,
       "hall_code"=>0,
       "hall_name"=>'',
       "type_name"=>'',
       "name_value"=>'Основная зарплата администратору',
       "count"=>0,
       "price"=>$adm_base_salary,
       //"price"=>500,
       "@summa"=>0,
       "@salary"=>''
      );
   
   $obj_name_rus=getObjNameRusById($_SESSION['user']['obj_id']);
   $curdate= getMyDate();
   //$rsAdmFIO=getFIOById($_SESSION['user']['usr_id'],$_SESSION['user']['obj_id']);
   //$rsAllWorkToDay=array();
   
   $rsAllWorkToDay=getAllWorkToDay();
   
   if($rsAllWorkToDay){
   array_unshift($rsAllWorkToDay,$adminBaseSal);
   }
   //d($rsAllWorkToDay);
   
   $client_no=0;
   $emp_id=0;
   $sum=0;
   $rsSummaryByDay = array ();
   $rsSalaryByDay = array ();
   
   //В $rsAllWorkToDay находятся данные для отчета
   //Приводим их в необходимый формат
   if($rsAllWorkToDay){
     insrtAdmBaseSalary($rsAllWorkToDay);    
    foreach ($rsAllWorkToDay as &$value){   
      //Считаем текущую сумму
      if ( $value['employee_id']===$emp_id){
       if(!($value['type_code']==20 || $value['type_code']==0)){  
          $sum=$sum+$value['price']; 
       }else{
        $pr=getPriceByID($value['operation_id']);
        if($pr){
         $value['price']= round($pr);         
        }
       }   
      }
      else {
        $emp_id=$value['employee_id'];
        $client_no=0;
        $sum=0;
        if(!($value['type_code']==20 || $value['type_code']==0)){
          $sum=$value['price'];
        }else{
         $pr=getPriceByID($value['operation_id']);
         if($pr){
          $value['price']= round($pr);   
          //$value['price']= round(getPriceByID($value['operation_id']));
         }
        }
        $rsSummaryByDay[$value['employee_id']]=getSumAllEmpByID($value['employee_id'], getMyDate());
        $rsSalaryByDay[$value['employee_id']]=getSalaryEmpByID($value['employee_id'], getMyDate());
        
      }  
     //Удаляем одинаковые client_no   
     if ( $value['client_no']===$client_no){$value['client_no']=' ';}
     else {$client_no=$value['client_no'];}
     //Парсим из дата_время время
      $pars_date=date_parse($value['create_date']);
      $value['create_date']=$pars_date['hour'].":".$pars_date['minute'];
     //добавляем 0 перед кодами    
     if (strlen($value['type_code'])===1){
      $value['type_code'] = '0'.$value['type_code'];
     }
     if (strlen($value['name_code'])===1){
      $value['name_code'] = '0'.$value['name_code'];
      }

       if ($value['count']>1){
        $value['name_value']= $value['name_value']." [".$value['count']."]шт.";
       }
      $value['@summa']=$sum;
      //Вычисляем сумму по каждой операции
      $value['@salary']=getSalaryByEmpIdOpId($value['employee_id'],$value['operation_id'],$value['count']);
      }
   }
   $all_summary= getAllSummary(getMyDate());
   if(!$all_summary){$all_summary=0;}
   $bs_summary=getAllBsSummary(getMyDate());
   if(!$bs_summary){$bs_summary=0;}
   $photo_summary=getAllPhotoSummary(getMyDate());
   if(!$photo_summary){$photo_summary=0;}
   $xerox_summary=getAllXeroxSummary(getMyDate());
   if(!$xerox_summary){$xerox_summary=0;}
   $additional_summary= getAllAdditionalSummary(getMyDate());
   if(!$additional_summary){$additional_summary=0;}
   $sale_summary= getAllSaleSummary(getMyDate());
   if(!$sale_summary){$sale_summary=0;}
   
   //d($rsAllWorkToDay);
   
   $all_bs_salary=0;
   $admin_salary=0;
   $sale_to_boss=0;

   
   $rsSal=getSal($rsSalaryByDay);
   
   $all_bs_salary=$rsSal['all_bs_salary'];
   $admin_salary=getSalaryEmpByID($_SESSION['user']['usr_id'], getMyDate());
   
   $sale_to_boss=$all_summary-$all_bs_salary-$admin_salary;
   
   $smarty->assign('obj_name_rus',$obj_name_rus); 
   $smarty->assign('curdate',$curdate);
   $smarty->assign('adm_first_name',$rsAdmFIO[0]['first_name']); 
   $smarty->assign('adm_last_name',$rsAdmFIO[0]['last_name']); 
   $smarty->assign('adm_partonymic',$rsAdmFIO[0]['partonymic']); 
   $smarty->assign('rsAllWorkToDay',$rsAllWorkToDay);
   $smarty->assign('rsSummaryByDay',$rsSummaryByDay);
   $smarty->assign('rsSalaryByDay',$rsSalaryByDay);
   $smarty->assign('all_summary',$all_summary);
   $smarty->assign('bs_summary',$bs_summary);
   $smarty->assign('photo_summary',$photo_summary);
   $smarty->assign('xerox_summary',$xerox_summary);
   $smarty->assign('additional_summary',$additional_summary);
   $smarty->assign('sale_summary',$sale_summary);
   $smarty->assign('all_bs_salary',$all_bs_salary);
   $smarty->assign('admin_salary',$admin_salary);
   $smarty->assign('sale_to_boss',$sale_to_boss);
   $smarty->assign('adm_id',$_SESSION['user']['usr_id']);
   $smarty->assign('adm_base_salary',$adm_base_salary);

   loadTemlate($smarty,'header');   
   loadTemlate($smarty,'admin/headerline');
   loadTemlate($smarty,'admin/leftmenu');  
   if($rsAllWorkToDay){
   loadTemlate($smarty,'admin/report');
   }
   loadTemlate($smarty,'footer');    
 }   
}
function getSal($rsSalaryByDay){
   $rsRet = [
    "admin_salary" => 0,
    "all_bs_salary" => 0,
    ]; 
  $all_bs_salary=0;
  foreach ($rsSalaryByDay as $key=>$value){
    //d($key);    
    if($key==$_SESSION['user']['usr_id']){$rsRet['admin_salary']=$value;}
     else{$all_bs_salary=$all_bs_salary+$value;}
   }  
  $rsRet['all_bs_salary']=$all_bs_salary;
  return  $rsRet; 
}
function insrtAdmBaseSalary($array_ins){
       $adm=getAdm($_SESSION['user']['usr_id']);
       //d($adm);
       array_unshift($array_ins, array (
       'employee_id'=> $_SESSION['user']['usr_id'],
       'operation_id'=> 1,
       'client_no'=> 0,
       'create_date'=> getMyDateTime(),
       'first_name'=>$adm['0']['first_name'],
       'last_name'=>$adm['0']['last_name'],
       'partonymic'=>$adm['0']['partonymic'],
       'type_code'=>20,
       'name_code'=>3,
       'hall_code'=>6,
       'hall_name'=>'Работа',
       'type_name'=>'',
       'name_value'=>'lkjlkjlk',
       'count'=>1,
       'price'=>300,
       '@summa'=>0,
       '@salary'=>0
         ));  
}