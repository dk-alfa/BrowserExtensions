<?php
//progect name: BARBERSHOP1
//appendOperation($obj_id,$id_employee,$value,$client_no,$price,$cur_date,$creator_id);
function appendOperation($obj_id,$employee_id,$operation_id,$client_no,
        $price,$count,$cur_date,$creator_id,$percent1,$percent2) {
 global $link;
 //d($price);
 //Если тип операции = 20 (работа, то поле Price=0) [работа не приносит прибыль]
 $sql="SELECT type_code FROM operations WHERE ID=".$operation_id;
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $rs= createSmartyRsArray($rs);
  if($rs[0]['type_code']==20){$price=0;}
 }   
 //d($rs);
 $sql="INSERT INTO `main`
   (ID,
    obj_id,
    employee_id,
    operation_id,
    client_no,
    price,
    count,
    create_date,
    creator_id,
    percent_1,
    percent_2) 
  VALUES(
   NULL,
   ".$obj_id.",".$employee_id.",".$operation_id.",".$client_no.",".$price.",".$count.",'".$cur_date."',".$creator_id.",".$percent1.",".$percent2.")";
 
 //d($sql);
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 return $rs;
}

function getClienNo($id_employee){
  global $link;
  $date=getMyDate(); 
  $ret=1;
  
  $sql="SELECT client_no FROM main WHERE obj_id=".$_SESSION['user']['obj_id']." AND employee_id=".$id_employee.
       " AND create_date LIKE '".$date."%'  ORDER BY client_no DESC LIMIT 1"; 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs);
 
 if ($rs){
   $ret=$rs[0]['client_no']+1;
 }
 return $ret;
}
function getAllCurOperationByID($id_employee){
  global $link;
  $date=getMyDate(); 
  $ret=null;   
 /* 
  $sql="SELECT ID,create_date,operation_id,client_no,price,count,(@sum) AS sum,
        (SELECT type_code FROM operations WHERE operations.id=main.operation_id) AS type_code,
        (SELECT name_code FROM operations WHERE operations.id=main.operation_id) AS name_code,
        
        
        (SELECT hall_code FROM operations WHERE operations.id=main.operation_id) AS hall_code,
        (SELECT value FROM operations_vv WHERE section=2 AND variable=hall_code) AS hall_name,
        
        (SELECT type_code FROM operations WHERE operations.id=main.operation_id) AS type_code,
        (SELECT value FROM operations_vv WHERE section = 3 AND variable=type_code) AS type_name,     
 
        (SELECT name_value FROM operations WHERE operations.id=main.operation_id) AS name_value
         
       FROM main WHERE obj_id=".$_SESSION['user']['obj_id']." AND employee_id=".$id_employee.
       " AND create_date LIKE '".$date."%'  ORDER BY ID";   
 */
   $sql="SELECT ID,create_date,operation_id,client_no,price,count,(@sum) AS sum,
    (SELECT type_code FROM operations WHERE operations.id=main.operation_id) 
    AS type_code,
    (SELECT name_code FROM operations WHERE operations.id=main.operation_id) 
    AS name_code,
    (SELECT hall_code FROM operations WHERE operations.id=main.operation_id) 
    AS hall_code,
    (SELECT value FROM operations_vv WHERE section=2 AND variable=hall_code AND obj_id=".$_SESSION['user']['obj_id'].") 
    AS hall_name,        
    (SELECT type_code FROM operations WHERE operations.id=main.operation_id AND obj_id=".$_SESSION['user']['obj_id'].") 
    AS type_code,
    (SELECT value FROM operations_vv WHERE section = 3 AND variable=type_code AND obj_id=".$_SESSION['user']['obj_id'].") 
    AS type_name,     
    (SELECT name_value FROM operations WHERE operations.id=main.operation_id) 
    AS name_value     
    FROM main WHERE obj_id=".$_SESSION['user']['obj_id']." AND employee_id=".$id_employee.
       " AND create_date LIKE '".$date."%'  ORDER BY ID";  

 //d($sql);  
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs); 
 //d($rs);
 if ($rs){
  $ret=$rs;
 }
 
 return $ret;
}
function getAllSummary($date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '".$date."%'";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;
}
function getAllBsSummary($date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '".$date.
      "%' AND (SELECT category_code FROM operations WHERE id=main.operation_id)=1";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;
}
function getAllPhotoSummary($date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '".$date.
      "%' AND (SELECT category_code FROM operations WHERE id=main.operation_id)=2";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;
}
function getAllXeroxSummary($date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '".$date.
      "%' AND (SELECT category_code FROM operations WHERE id=main.operation_id)=3";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;
}
function getAllAdditionalSummary($date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '".$date.
      "%' AND (SELECT category_code FROM operations WHERE id=main.operation_id)=4";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;
}
function getAllSaleSummary($date){
 global $link;
 $ret=0;
 /*
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '".$date.
      "%' AND (SELECT category_code FROM operations WHERE id=main.operation_id)=4";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
  */
 //d($ret); 
 return $ret;
}
/**
 * Функция вычисления суммы за парикмахерские услуги
 * @global type $link
 * @param type $id_employee
 * @param type $date
 * @return type
 */
function getSumBsEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=1";
 
 //d($sql);
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;   
}
function getSumPhotoEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=2";
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;   
}
function getSumXeroxEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=3";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 return $ret;   
}
function getSumWorkEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 /*
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=6";
 */
 $sql=" SELECT SUM((SELECT price FROM operations WHERE ID=operation_id)) AS sum_operation 
      FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=6";
 //d($sql);
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $rs=createSmartyRsArray($rs);
  //$ret=$rs[0]['SUM(price)']; sum_operation
  $ret=$rs[0]['sum_operation'];
 }
 return round($ret);   
}
function getSumSaleEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 /*
 $sql=" SELECT SUM((SELECT price FROM operations WHERE ID=operation_id)) AS sum_operation 
      FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=6";
 //d($sql);
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $rs=createSmartyRsArray($rs);
  //$ret=$rs[0]['SUM(price)']; sum_operation
  $ret=$rs[0]['sum_operation'];
 }
  */
 return $ret;   
}
function getSumAdditionalEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=4";
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 return $ret;   
}
function getSumAllEmpByID($id_employee,$date){
 global $link;
 $ret=0;
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee;
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 return $ret;     
}
/**
 * Функция вычисления общей зарплаты
 * @global type $link
 * @param type $id_employee
 * @param type $date
 * @return type
 */
function getSalaryEmpByID($id_employee, $date){
  //global $link;
  $allSalary=0;//Суммарная зп.
  $bsSalary=0;//Зп за парикмахерские услуги
  $baseAdmSalary=0;//Базовая зп администратора
  $photoSalary=0;//Зп за фото
  $xeroxSalary=0;//Зп за ксерокс
  $additionalSalary=0;//Зп за доп услуги
  $workSalary=0;//Зп за работу
  $saleSalary=0;//Зп за продажи
  $adm_id=getAdminIdFromSheduler();//id администратора

  //Получаем процент и повышенный процент
  $percent1=getEmpPercent1ById($id_employee,'salary');
  $percent2=getEmpPercent1ById($id_employee,'salary_1');
  //Получаем настройки
  $rsAv=getAv();
  //Если id= id администратора:
  //if($id_employee===$_SESSION['user']['usr_id']){
  if($id_employee===$adm_id){
    $baseAdmSalary=getbaseAdmSalary($rsAv,$percent1,$percent2); 
   }

   $bsSalary=getBsSalary($percent1,$percent2,$rsAv,$id_employee);
   $photoSalary=getPhotoSalary($rsAv,$id_employee);
   $xeroxSalary=getXeroxSalary($rsAv,$id_employee);
   $workSalary=getWorkSalary($id_employee);
   $saleSalary=getSaleSalary($rsAv,$id_employee);
   $additionalSalary=getAdditionalSalary($rsAv,$id_employee);
   
   $allSalary=$baseAdmSalary+$bsSalary+$photoSalary+$xeroxSalary+$workSalary+
              $additionalSalary+$saleSalary;
   //Получаем привилегии пользователя
   $emp_privilegy= getPrivilegyById($id_employee);
   //Если пользователь root, то зарплату не начисляем
   if($emp_privilegy==='root'){$allSalary=0;}
  return round($allSalary);
}
function getEmployeeCount(){
 global $link;
 $ret=0;

 $sql="SELECT COUNT(DISTINCT employee_id) AS emp_count FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND  create_date LIKE '". getMyDate()."%' AND NOT(employee_id=".$_SESSION['user']['usr_id'].")";

 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);

 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['emp_count'];
 }
 return $ret; 
}
/**
 * Функция вычисления зарплаты за парикмахерские услуги
 * @param type $percent1
 * @param type $percent2
 * @param type $rsAv
 * @param type $id_employee
 * @return type
 */
function getBsSalary($percent1,$percent2,$rsAv,$id_employee){
  $sum=getSumBsEmpByID($id_employee,getMyDate()); 
  //$bsSalary=0;
  //зп = процент от выручки
  $bsSalary=($sum*$percent1)/100;  
   //если включен повышенный процент  
  if($rsAv){
   if ($rsAv[0]['IsHiPercent']){  
   //Если выручка больше HiPercentSimpleCache, то считаем по повышенному проценту
    if ($sum > $rsAv[0]['HiPercentSimpleCache']){
     $bsSalary=($sum*$percent2)/100;
    }
    else{ 
      $empCount=getEmployeeCount(); 
     //Если выручка больше HiPercentMinCache и количество парикмахеров >= HiPercentMinCountEmployee
     //то считаем по повышенному проценту 
     if (($sum > $rsAv[0]['HiPercentMinCache']) && 
         ($rsAv[0]['HiPercentMinCountEmployee'] <= $empCount)){
      $bsSalary=($sum*$percent2)/100;
      //d($sum);
     }   
    }          
   }  
  } 
   return round($bsSalary); 
}
function getPhotoSalary($rsAv,$id_employee){
  //Вычисляем сумму за фото услуги
  $photoSalary=0;
  
  $sum=getSumPhotoEmpByID($id_employee, getMyDate());
  if($sum){$photoSalary=($sum*$rsAv[0]['PhotoPercent'])/100;}
  return round($photoSalary);
 
}
function getXeroxSalary($rsAv,$id_employee){
  //Вычисляем сумму за ксерокс услуги
  $xeroxSalary=0;  
  $sum=getSumXeroxEmpByID($id_employee, getMyDate());
  if($sum){$xeroxSalary=($sum*$rsAv[0]['XeroxPercent'])/100;}
  return round($xeroxSalary);
}
function getbaseAdmSalary($rsAv,$percent1,$percent2) {
  //Изначально зарплата = минимальной зп. администратора
  $baseSalary=$rsAv[0]['AdmMinSalary'];   
   //зп = процент от выручки
  $CurBaseSalary=(getAllBsSummary(getMyDate())*$percent1)/100;
  //Если зп больше минимальной, то зп=процент от выручки 
  if($CurBaseSalary){
   if ($CurBaseSalary>$rsAv[0]['AdmMinSalary']){
     $baseSalary=$CurBaseSalary;  
   }   
  }
  return round($baseSalary);
}
function getWorkSalary($id_employee){
   $workSalary=0;
   $sum=getSumWorkEmpByID($id_employee,getMyDate());
   if($sum){$workSalary=$sum;}
   return $workSalary;
}
function getAdditionalSalary($rsAv,$id_employee){
  //Вычисляем сумму за ксерокс услуги
  $addSalary=0;  
  $sum=getSumAdditionalEmpByID($id_employee, getMyDate());
  if($sum){$addSalary=($sum*$rsAv[0]['AdditionalPercent'])/100;}
  return $addSalary;
}
function getSaleSalary($rsAv,$id_employee){
  //Вычисляем сумму за продажи
  $saleSalary=0;  
  //$sum=getSumXeroxEmpByID($id_employee, getMyDate());
  //if($sum){$xeroxSalary=($sum*$rsAv[0]['XeroxPercent'])/100;}
  return $saleSalary;
}
function getAllWorkToDay(){
 global $link;
 $date=getMyDate(); 
 $ret=null;   
 /*
   $sql="SELECT  employee_id,operation_id,client_no,create_date,
       (SELECT first_name FROM users WHERE ID=employee_id) AS first_name,
       (SELECT privilegy FROM users WHERE ID=employee_id) AS privilegy,
       (SELECT last_name FROM users WHERE ID=employee_id) AS last_name,
       (SELECT partonymic  FROM users WHERE ID=employee_id) AS partonymic,
       (SELECT type_code FROM operations WHERE ID=operation_id) AS type_code,
       (SELECT name_code FROM operations WHERE ID=operation_id) AS name_code,
       (SELECT hall_code FROM operations WHERE ID=operation_id) AS hall_code,
       (SELECT value FROM operations_vv WHERE variable=hall_code AND section=2) AS hall_name,
       (SELECT value FROM operations_vv WHERE variable=type_code AND section=3) AS type_name,
       (SELECT name_value FROM operations WHERE ID=operation_id) AS name_value,
       count,price,@summa,@salary
       FROM main WHERE obj_id=".$_SESSION['user']['obj_id']." AND create_date LIKE '".$date."%' 
        ORDER BY FIELD(privilegy,'admin','employee','root'),employee_id, client_no, ID";
  */
  $sql="SELECT  employee_id,operation_id,client_no,create_date,
       (SELECT first_name FROM users WHERE ID=employee_id) AS first_name,
       (SELECT privilegy FROM users WHERE ID=employee_id) AS privilegy,
       (SELECT last_name FROM users WHERE ID=employee_id) AS last_name,
       (SELECT partonymic  FROM users WHERE ID=employee_id) AS partonymic,
       (SELECT type_code FROM operations WHERE ID=operation_id) AS type_code,
       (SELECT name_code FROM operations WHERE ID=operation_id) AS name_code,
       (SELECT hall_code FROM operations WHERE ID=operation_id) AS hall_code,
       (SELECT value FROM operations_vv WHERE variable=hall_code AND section=2 
       AND obj_id=".$_SESSION['user']['obj_id'].")
       AS hall_name,
       (SELECT value FROM operations_vv WHERE variable=type_code AND section=3 
       AND obj_id=".$_SESSION['user']['obj_id'].") 
       AS type_name,
       (SELECT name_value FROM operations WHERE ID=operation_id) AS name_value,
       count,price,@summa,@salary
       FROM main WHERE obj_id=".$_SESSION['user']['obj_id']." AND create_date LIKE '".$date."%' 
        ORDER BY FIELD(privilegy,'admin','employee','root'),employee_id, client_no, ID";
 //d($sql);  
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 $rs=createSmartyRsArray($rs); 
 
 if ($rs){
  $ret=$rs;
 }
 //d($ret);
 return $ret;   
}
function getSalaryByEmpIdOpId($emp_id,$op_id,$count){ 
   // d();
 //global $link;
 //$date=getMyDate(); 
 $ret=null; 
 $rsAv= getAv();
 //$action=getOperationActionById($op_id);
 $category_code=getOperationCategoryCode($op_id);
 $ret=$category_code;
 $price=getOperationPriceById($op_id);
 //Если операция "стрижка"
 if($category_code==1){
  $ret=getOperationBsSalary($emp_id,$price,$rsAv);  
 }
  if($category_code==2){
  $ret=getOperationPhotoSalary($price,$rsAv,$count);  
 }
 if($category_code==3){
  $ret=getOperationXeroxSalary($price,$rsAv,$count);  
 }
 if($category_code==4){
  $ret=getOperationAdditionalSalary($price,$rsAv,$count);  
 } 
  if($category_code==6){
  $ret=getOperationWorkSalary($price,$rsAv,$count);  
 } 
 //$ret=$price;
 return $ret;
}
function getOperationBsSalary($emp_id,$price,$rsAv){
  $percent=getEmpPercent1ById($emp_id,'salary'); 
  $percent1=getEmpPercent1ById($emp_id,'salary_1');
  $sum=getSumBsEmpByID($emp_id,getMyDate());
  //d($sum);
  //зп = процент от выручки
  $bsSalary=($price*$percent)/100;  
  //Если есть настройки
  if($rsAv){
   //если включен повышенный процент     
   if ($rsAv[0]['IsHiPercent']){  
   //Если выручка больше HiPercentSimpleCache, то считаем по повышенному проценту
    if ($sum > $rsAv[0]['HiPercentSimpleCache']){
     $bsSalary=($price*$percent1)/100;
    }
    else{ 
      $empCount=getEmployeeCount(); 
     //Если выручка больше HiPercentMinCache и количество парикмахеров >= HiPercentMinCountEmployee
     //то считаем по повышенному проценту 
     if (($sum > $rsAv[0]['HiPercentMinCache']) && 
         ($rsAv[0]['HiPercentMinCountEmployee'] <= $empCount)){
      $bsSalary=($price*$percent1)/100;
      //d($sum);
     }   
    }          
   }  
  } 
   return round($bsSalary);  
}
function getOperationPhotoSalary($price,$rsAv,$count){
  $Salary=($price*$rsAv[0]['PhotoPercent']*$count)/100;
   return round($Salary);  
}
function getOperationXeroxSalary($price,$rsAv,$count){
  $Salary=($price*$rsAv[0]['XeroxPercent']*$count)/100;
  return round($Salary);  
}
function getOperationAdditionalSalary($price,$rsAv,$count){
 $Salary=($price*$rsAv[0]['AdditionalPercent']*$count)/100;
 return round($Salary);   
}
function getOperationWorkSalary($price,$rsAv,$count){
 $Salary=$price*$count;
 return round($Salary);
}
function getEmpWorckingToDay($date){
 global $link;
 $ret=NULL;
/*
 $sql=" SELECT employee_id, 
     (SELECT short_name FROM users WHERE ID=main.employee_id) AS short_name,
     (SELECT first_name FROM users WHERE ID=main.employee_id) AS first_name,
     (SELECT last_name FROM users WHERE ID=main.employee_id) AS last_name
      FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' GROUP BY employee_id";
 */
  $sql=" SELECT employee_id, 
     (SELECT privilegy FROM users WHERE ID=main.employee_id) AS privilegy, 
     (SELECT short_name FROM users WHERE ID=main.employee_id) AS short_name,
     (SELECT first_name FROM users WHERE ID=main.employee_id) AS first_name,
     (SELECT last_name FROM users WHERE ID=main.employee_id) AS last_name,
     (SELECT partonymic FROM users WHERE ID=main.employee_id) AS partonymic,
     (SELECT image FROM users WHERE ID=main.employee_id) AS image
      FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' GROUP BY employee_id
      ORDER BY FIELD(privilegy,'admin','employee','root' ), employee_id ";

//d($sql);
 
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 if($rs){
  $ret=createSmartyRsArray($rs);
  //$ret=$rs[0]['sum_operation'];
 }
 //d($ret);
 return $ret;    
}
function getPhotoInHome(){
 global $link;
 $ret=0;
 $date= getMyDate();
 $sql="SELECT SUM(price) FROM main WHERE obj_id=".$_SESSION['user']['obj_id'].
      " AND create_date LIKE '".$date."%' AND employee_id=".$id_employee.
      " AND (SELECT category_code FROM operations WHERE id=main.operation_id)=2";
 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 
 if($rs){
  $rs=createSmartyRsArray($rs);
  $ret=$rs[0]['SUM(price)'];
 }
 //d($ret); 
 return $ret;   
}
function delRecordById($id){
 global $link;  
  $sql="DELETE FROM main WHERE ID=".$id; 
 mysqli_set_charset($link, "utf8"); 
 $rs=mysqli_query($link, $sql);
 return $rs;
}