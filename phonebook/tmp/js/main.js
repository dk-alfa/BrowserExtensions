//progect name: BARBERSHOP1

/**
 * Функция обработки нажатия клавиши "Войти" на StartPage
 * вызываем контроллер  /user/login/
 */
function login(){
  var login = $('#login').val();
  var pwd = $('#pwd').val();
  var postData = "login=" + login +"&pwd=" + pwd; 
    $.ajax({
        type: 'POST',
        async: false,
        url: '/user/login/',
        data: postData,
        dataType: 'json',
        success: function(data){
         if (data['success']){          
          if (data['privilegy'] === 'superroot') window.location.href = '/user/superroot/';
          if (data['privilegy'] === 'root') window.location.href = '/user/root/';
          if (data['privilegy'] === 'admin') window.location.href = '/user/admin/';
         // if (data['privelegy'] === 'employee') window.location.href = '/index/employee/';
         }else{
         //  $('#login').val('') ;
         //  $('#pwd').val('');
          alert(data['message']);     
         }
         
        },
        error: function(){
          alert("'Ошибка в '/user/login/'"); 
          //alert("Не верные 'Логин' , 'Пароль'");
        }
    });  
}
/*
function show_submenu(){  
$('#privelegy_sub_menu').toggle();
$('#main_menu').toggle();
}
/**
 * 
 * @returns {undefined}
 * 
 */
function buttoncreate_click(){
   
   var caption = $('#create_button').val();
   var saveMode= $('#objects_menu').val();
    
    if (caption==='Создать'){
     //alert(saveMode);   
     createObject(); 
    }
    if (caption==='Cохранить'){ 
      //alert('Cохранить'); 
      saveObject(saveMode);
    }
    if (caption==='Изменить'){
      //alert(saveMode);  
      editObject();   
    }
}
/**
 * 
 * @returns {undefined}
 */
function buttoncancel_click(){
  if ($('#objects_menu').val()==='NewObject'){
      $('#create_button').val('Создать');
  } else {
      $('#create_button').val('Изменить');
  }  
    $('#cancel_button').hide();
    $('#object_fields').hide();
}
/**
 * 
 * @param {type} sel
 * @returns {undefined}
 */
function objects_menu_change(sel){
    if (sel.value==='NewObject'){
      $('#create_button').val('Создать');  
    }else{
      $('#create_button').val('Изменить'); 
    }
}
/**
 * Функция создать объект
 * @returns {undefined}
 * 
 */
function createObject(){
    $('#create_button').val('Cохранить');
    $('#cancel_button').show();
    $('#object_fields').show();
    $('.ne_caption_in_table_l').hide();
    $('.ne_caption_in_table_r').hide(); 
    //очищаем поля ввода
    $('#name_rus').val("");
    $('#db_name').val("");
    $('#db_login').val("");
    $('#pwd1').val("");
    $('#pwd2').val("");
    $('#location').val("");
    $('#image_location').val("");
    $('#ta_comments').val(""); 
}
/**
 * Функция сохранения объекта
 * @returns {undefined}
 * 
 */
function saveObject(saveMode){   
 var buttonCaption='';
 var comment = encodeURIComponent($('#ta_comments').val());
 var postData="name_rus="+$('#name_rus').val()+
              "&name="+$('#db_name').val()+
              "&adress="+$('#location').val()+
              "&image="+$('#image_location').val()+
              "&comment="+comment;
              //"&comment="+$('#ta_comments').val();
       
 if (saveMode==='NewObject'){ 
     postData=postData+"&type=append&id=0";
     buttonCaption='Создать';
 } else {
     postData=postData+"&type=replace&id="+$('#obj_id').html();
     buttonCaption='Изменить';
  }     
  $.ajax({
      type: 'POST',
      async: false,
      url: '/objects/addobject/',
      data: postData,
      dataType: 'json',
      success: function(data){
       if (data['success']){  
        alert(data['message']); 
        $('#create_button').val(buttonCaption);   
        $('#cancel_button').hide();
        $('#object_fields').hide();
        location.reload();
       }else{
        alert(data['message']);  
       }
      },
      error: function(){
       alert('error [/objects/addobject/]');
       }
  });  
}
/**
 * Функция редактирования объекта
 * @returns {undefined}
 */
function editObject(){
 var postData="obj_id="+$('#objects_menu').val(); 

   $.ajax({
     type: 'POST',
     async: false,
     url: '/objects/editobject/',
     data: postData,
     dataType: 'json',
      success: function(data){
       if (data['success']){   
         //alert(data['message']);
             
          $('#create_button').val('Cохранить');
          $('#cancel_button').show();
          $('#object_fields').show();
          $('#obj_id').html(data.ID);
          $('#create_date').html(data.create_date);
          $('#creator').html(data.creator);
          $('#last_mod_date').html(data.last_mod_date);
          $('#last_modifer').html(data.last_modifer);
          $('#name_rus').val(data.name_rus);
          $('#db_name').val(data.name);
          $('#location').val(data.adress);
          $('#image_location').val(data.image);
          $('#ta_comments').val(data.comment);  
          $('.ne_caption_in_table_l').show();
          $('.ne_caption_in_table_r').show();
         
         }else{
             alert(data['message']);  
          }    
          },
         error: function(){
          alert('error [/objects/editobject/]');
          }
     });
    
 /*   
    $('#create_button').val('Cохранить');
    $('#cancel_button').show();
    $('#object_fields').show();
    $('.ne_caption_in_table_l').show();
    $('.ne_caption_in_table_r').show(); 
 */ 
}
/*
function users_menu_change(sel){
    if (sel.value==='NewUser'){
      $('#user_create_button').val('Создать');  
    }else{
      $('#user_create_button').val('Изменить'); 
    }
}
*/
function usercreate_click(){
    
 var caption = $('#user_create_button').val();
 var saveMode= $('#users_menu').val();
     
 if (caption==='Создать'){  
     createUser(); 
 }
 if (caption==='Cохранить'){ 
   saveUser(saveMode);
 }
 if (caption==='Изменить'){
   editUser();  
  // alert(saveMode);
 }    
}
function usercancel_click(){
  if ($('#users_menu').val()==='NewUser'){
    $('#user_create_button').val('Создать');
    $('#main_user_fields').hide();
    $('#detail_user_fields').hide();
    $('#reference_fs').hide();
    //Очистить все поля
    
  } else {
      $('#user_create_button').val('Изменить');
  }  
  $('#user_cancel_button').hide();
  $('#main_user_fields').hide();
  $('#detail_user_fields').hide();
  $('#reference_fs').hide();
}
/**
 * 
 * @param {type} sel
 * @returns {undefined}
 */
function user_priv_menu_change(sel){
 if (sel.value==='superroot' || sel.value==='root'){
      $('#salary').hide();
      $('#salary_1').hide();
      $('#c_salary').hide();
      $('#c_salary_1').hide();
      $('#short_name').hide();
      $('#c_short_name').hide();
    }else{
      $('#salary').show();
      $('#salary_1').show();
      $('#c_salary').show();
      $('#c_salary_1').show();
      $('#short_name').show();
      $('#c_short_name').show();
 }   
 if (sel.value==='superroot'){
      $('#user_obj_caption').hide();
      $('#user_obj_menu').hide();     
 }else{
      $('#user_obj_caption').show();
      $('#user_obj_menu').show();     
 }
 if (sel.value==='admin'){
      $('#short_name').hide();
      $('#c_short_name').hide();     
 }  
}
/**
 * 
 * @param {type} sel
 * @returns {undefined}
 */
function users_menu_change(sel){
 if (sel.value==='NewUser'){
  $('#user_create_button').val('Создать');  
 }else{
  $('#user_create_button').val('Изменить'); 
 }  
}
/**
 * 
 * @returns {undefined}
 */
function createUser(){
  $('#user_create_button').val('Cохранить');
  $('#user_cancel_button').show();
  $('#main_user_fields').show();
  $('#detail_user_fields').show();
 
  //очищаем поля ввода
  $('#user_login').val("");
  $('#user_pass').val("");
  $('#user_obj_menu').val("None");
  $('#user_priv_menu').val("None");
  $('#us_comments').val("");
  $('#user_login').val("");
  $('#user_pass').val("");
  $('#first_name').val("");
  $('#last_name').val("");
  $('#partonymic').val("");
  $('#salary').val("");
  $('#salary_1').val("");
  $('#is_work').prop("checked");
  $('#user_email').val("");
  $('#phone').val("");
  $('#reference_fs').hide();  
}
/**
 * 
 * 
 */
function saveUser(saveMode){
   var buttonCaption='';
   //var res = encodeURIComponent(uri);
   var phone=encodeURIComponent($('#phone').val());
   var comment = encodeURIComponent($('#us_comments').val());
   var short_name=$('#short_name').val();
   if ($('#user_priv_menu').val()==='admin'){
      short_name="Администратор"; 
   }
   var postData =
                "obj_id="+$('#user_obj_menu').val()+
                "&login="+$('#user_login').val()+
                "&password="+$('#user_pass').val()+
                "&short_name="+short_name+
                "&first_name="+$('#first_name').val()+
                "&last_name="+$('#last_name').val()+
                "&partonymic="+$('#partonymic').val()+
                "&salary="+$('#salary').val()+
                "&salary_1="+$('#salary_1').val()+
                "&is_work="+$('#is_work').prop("checked") +
                "&email="+$('#user_email').val()+
                //"&phone="+$('#phone').val()+
                "&phone="+phone+
                "&image=/image"+
                //"&comment="+$('#us_comments').val()+
                "&comment="+comment+
                "&privelegy="+$('#user_priv_menu').val();
            
    if (saveMode==='NewUser'){ 
        postData=postData+"&type=append&id=0";
        buttonCaption='Создать';
    } else {
       postData=postData+"&type=replace&id="+$('#usr_id').html();
       buttonCaption='Изменить';
    } 
    //alert("postData="+ postData);
      $.ajax({
      type: 'POST',
      async: false,
      url: '/user/adduser/',
      data: postData,
      dataType: 'json',
      success: function(data){ 
       if (data['success']){  
           alert(data['message']);               
          $('#user_create_button').val(buttonCaption);  
          $('#user_cancel_button').hide();
          $('#main_user_fields').hide();
          $('#detail_user_fields').hide();
          location.reload();
         //if (!data['message']==='Данные не изменялись'){
         //location.reload();
         //}
       }else{
        alert(data['message']);  
       }
      },
      error: function(){
       alert('error [/user/adduser/]');
       }
    });
}
function editUser(){
 var postData="usr_id="+$('#users_menu').val();  

   $.ajax({
     type: 'POST',
     async: false,
     url: '/user/edituser/',
     data: postData,
     dataType: 'json',
    success: function(data){    
       if (data['success']){   
          //alert(data['message']);
             
          $('#user_create_button').val('Cохранить');
          $('#user_cancel_button').show();
          $('#main_user_fields').show();
          $('#detail_user_fields').show();
          $('#reference_fs').show();
          $('#user_obj_menu').show();
          $('#user_obj_caption').show();
          $('#salary').show();
          $('#salary_1').show();
          $('#c_salary').show();
          $('#c_salary_1').show();
          
          if (data.privilegy==='superroot'){
            $('#user_obj_menu').hide();
            $('#user_obj_caption').hide();
            $('#salary').hide();
            $('#salary_1').hide();
            $('#c_salary').hide();
            $('#c_salary_1').hide();
            $('#short_name').hide();
            $('#c_short_name').hide();
          }          
          if (data.privilegy==='root'){
            $('#salary').hide();
            $('#salary_1').hide();
            $('#c_salary').hide();
            $('#c_salary_1').hide();
            $('#short_name').hide();
            $('#c_short_name').hide();
          }
           if (data.privilegy==='admin'){
            $('#short_name').hide();
            $('#c_short_name').hide();               
           }
          
          $('#usr_id').html(data.ID);
          $('#user_obj_menu').val(data.obj_id);
          $('#user_login').val(data.login);
          $('#user_pass').val(data.password);
          $('#short_name').val(data.short_name); 
          $('#first_name').val(data.first_name);
          $('#last_name').val(data.last_name);
          $('#partonymic').val(data.partonymic);
          $('#salary').val(data.salary);
          $('#salary_1').val(data.salary_1);
          $('#is_work').prop("checked",Number(data.is_work));
          $('#user_email').val(data.email);
          $('#phone').val(data.phone);
          $('#user_priv_menu').val(data.privilegy);
          $('#us_comments').val(data.comment); 
          $('#usr_crd').html(data.create_date);
          $('#usr_cr').html(data.creator);
          $('#usr_lmd').html(data.last_mod_date);
          $('#usr_lm').html(data.last_modifer);
          
         
         }else{
             alert(data['message']);  
          }    
          },
         error: function(){
          alert('error [/objects/editobject/]');
          }
     });
    
 /*   
    $('#create_button').val('Cохранить');
    $('#cancel_button').show();
    $('#object_fields').show();
    $('.ne_caption_in_table_l').show();
    $('.ne_caption_in_table_r').show(); 
 */ 
   
}
function save_shedule(){
 var work_emp_str="";
 var postData="";
 
 $('input:checkbox:checked').each(function(){  
 work_emp_str=work_emp_str+$(this).val()+"/";
 });   
 postData="work_emp_str="+work_emp_str;
 //alert(postData);
  $.ajax({
   type: 'POST',
   async: false,
   url: '/user/shedule/',
   data: postData,
   dataType: 'json',
    success: function(data){
    if (data['success'])
    {   
     //alert(data['message']);   
      window.location.href = '/user/admin/';
    }else{
     alert(data['message']);     
     }     
    },
     error: function(){
      alert("'Ошибка в '/user/shedule/'");
     }
    }); 
}
/*
function tab_data_set_detiles_op_set(){
 //var title = $(this).attr('id'); 
 var title = $this.id;
  alert(title);  
}
*/
/**
 * Обработка onclick  в таблице tab_data
 * @returns {undefined}
 */
$(function () { 
 $("table#tab_data tbody tr").click(function(){    
  var id = $(this).attr("id"); 
  var postData="";
  postData="id="+id;
  
  $.ajax({
   type: 'POST',
   async: false,
   url: '/root/getdetiles/',
   data: postData,
   dataType: 'json',
  success: function(data){   
   if (data['success']){   
     alert(data['message']);   
      //window.location.href = '/user/admin/';
    }else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/getDetiles/'");
   }
  });
 });  
});
/**
 * Обработка onclick  в таблице operation_details
 * @returns {undefined}
 */
$(function () { 
 $("table#operations_table tbody tr").click(function(){ 

  var id = $(this).attr("id"); 
  var postData="";
  postData="id="+id;
 
  $.ajax({
   type: 'POST',
   async: false,
   url: '/root/getopdetilesbyid/',
   data: postData,
   dataType: 'json',
  success: function(data){   
   if (data['success'])
   {   
     //alert(data.active);  
     var ch=Number(data.active);
          
     $('#category_menu').val(data.category_code);
     $('#hall_menu').val(data.hall_code);
     $('#type_menu').val(data.type_code);
     $('#op_name').val(data.name_value);
     $('#op_price').val(data.price);
     $('#op_action').val(data.action);
     //$('#printable_menu').val(data.printable);
     $('#printable').prop('checked',Number(data.printable));
     $('#operations_comments').val(data.comment);
     $('.operation_details_caption1').show();
     $('#id_value').show();
     $('#id_value').html(data.ID);
     $('#obj_id_value').show();
     $('#obj_id_value').html(data.obj_id);
     $('#create_date_value').show();
     $('#create_date_value').html(data.create_date);
     $('#creator_value').show();
     $('#creator_value').html(data.creator);
     $('#last_mod_date_value').show();
     $('#last_mod_date_value').html(data.last_mod_date);
     $('#last_modifer_value').show();
     $('#last_modifer_value').html(data.last_modifer);
     $('#name_code_value').show();
     $('#name_code_value').html(data.name_code);
     $('#cb_active').prop('checked',ch);
     
     
     //$('#type_menu').hide();
    }else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/getDetiles/'");
   }
  });
 });  
});
function new_op_click(){
 $('.operation_details_caption1').hide();   
 $('.caption1_value').hide();
 $('#op_name').val(" ");
 $('#op_price').val("");
 $('#operations_comments').val("");
}
function save_op_click(){
 var isSave = confirm("Сохранить данные?");   
 if (isSave){
 //проверяем старая запись или нет
 var isOld= $('.operation_details_caption1').is(':visible');
  if (isOld){replace_record();}
  else{append_record();}     
 }   
}
function del_op_click(){
 var id=$('#id_value').html();
 var postData="";
 postData="id="+id;   
 var isDel = confirm("Удалить запись c ID="+id+" ?");   
 if (isDel){
     $.ajax({
   type: 'POST',
   async: false,
   url: '/root/deleteoperation/',
   data: postData,
   dataType: 'json',
  success: function(data){   
   if (data['success']){    
     alert(data['message']);
     location.reload();
    }else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/deleteoperation/'");
   }
  });   
  //alert ("Запись удалена");    
 }    
}
function replace_record(){
   var postData="";
   postData="id="+$('#id_value').html()+
            "&obj_id="+$('#obj_id_value').html()+
            "&category_code="+$('#category_menu').val()+
            "&hall_code="+$('#hall_menu').val()+
            "&type_code="+$('#type_menu').val()+
            "&name_code="+$('#name_code_value').html()+
            "&name_value="+$('#op_name').val()+ 
            "&price="+$('#op_price').val()+
            "&action="+$('#op_action').val()+
            //"&printable="+$('#printable_menu').val()+
            "&printable="+$('#printable').prop('checked')+
            "&comment="+$('#operations_comments').val()+ 
            "&create_date="+$('#create_date_value').html()+
            "&creator="+$('#creator_value').html()+
            "&active="+$('#cb_active').prop('checked');
    //alert(postData);        
   $.ajax({
   type: 'POST',
   async: false,
   url: '/root/replaceoperation/',
   data: postData,
   dataType: 'json',
  success: function(data){   
   if (data['success']){    
     alert(data['message']);
     location.reload();
    }else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/replaceoperation/'");
   }
  });
}
function append_record(){
 //alert("Новая запись"); 
 var postData="";
 postData="id="+$('#id_value').html()+
            "&obj_id="+$('#obj_id_value').html()+
            "&category_code="+$('#category_menu').val()+
            "&hall_code="+$('#hall_menu').val()+
            "&type_code="+$('#type_menu').val()+
            "&name_code="+$('#name_code_value').html()+
            "&name_value="+$('#op_name').val()+ 
            "&price="+$('#op_price').val()+
            "&action="+$('#op_action').val()+
            //"&printable="+$('#printable_menu').val()+
            "&printable="+$('#printable').prop('checked')+
            "&comment="+$('#operations_comments').val()+ 
            "&create_date="+$('#create_date_value').html()+
            "&creator="+$('#creator_value').html()+
            "&active="+$('#cb_active').prop('checked');
   $.ajax({
   type: 'POST',
   async: false,
   url: '/root/appendoperation/',
   data: postData,
   dataType: 'json',
  success: function(data){   
   if (data['success']){    
     alert(data['message']);
     location.reload();
    }else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/appendoperation/'");
   }
  });
}
function add_operation_onclick(data,event){
 //alert("nct_"+data+"_ct");
     
  var sel_id=$('#os_'+data+'_ct').val();  
 //var sel_id=$('.opreation_sel').val();
 //var sel_string =$('.opreation_sel option:selected').text();
 var sel_string =$('#os_'+data+'_ct option:selected').text();
 var sel_value =$('#os_'+data+'_ct option:selected').val();
 var action=sel_value.split('_')[2];
 var count=1;
 if (action==='1'){
  $('#emp_id_modal').text(data);
  do_action(action,event);
 /* 
  count=$('#sel_count option:selected').text();
  $('#nct_'+data+'_ct > tbody')
   .append('<tr id="table'+data+'row'+tr+'ct">\n\
   <td id="id_'+data+'_'+tr+'_ct" class="new_client_table_td_hide">'+sel_id+'</td>\n\
   <td id="id_'+data+'_'+tr+'_count" class="new_client_table_td_hide">'+count+'</td>\n\
   <td class="new_client_table_td"> '+sel_string+'</td>\n\
   <td class="new_client_table_td">\n\
    <input class="btn_del_operation" id="'+data+'r'+tr+'r" type="button" value="  X  " onclick="new_client_table_del(this); ">\n\
   </td></tr>');
  $('.opreation_sel').val(0);
 */       
 }
 else{
 var tr=$('#nct_'+data+'_ct tr').length;
 //var num_op;
 
 if(!sel_string){alert('Не выбрано ни одной операции');}else{  
  $('#nct_'+data+'_ct > tbody')
   .append('<tr id="table'+data+'row'+tr+'ct">\n\
   <td id="id_'+data+'_'+tr+'_ct" class="new_client_table_td_hide">'+sel_id+'</td>\n\
   <td id="id_'+data+'_'+tr+'_count" class="new_client_table_td_hide">'+count+'</td>\n\
   <td class="new_client_table_td"> '+sel_string+'</td>\n\
   <td class="new_client_table_td">\n\
    <input class="btn_del_operation" id="'+data+'r'+tr+'r" type="button" value="  X  " onclick="new_client_table_del(this); ">\n\
   </td></tr>');
  $('.opreation_sel').val(0);
 }
 }
}
function root_add_operation_onclick(data,event){
 //alert("root_add_operation_onclick");
    
 var sel_id=$('#root_operaton').val();
 var sel_string =$('#root_operaton option:selected').text();
 var sel_value =$('#root_operaton option:selected').val();
 var action=sel_value.split('_')[2];
 var count=1;   
 if (action==='1'){
  $('#emp_id_modal').text(data);
  do_action(action,event);       
 }
 else{    
 var tr=$('#root_new_client_table tr').length;
 if(!sel_string){alert('Не выбрано ни одной операции');}else{  
  $('#root_new_client_table > tbody')
   .append('<tr id="table'+data+'row'+tr+'ct">\n\
   <td id="id_'+data+'_'+tr+'_ct" class="new_client_table_td_hide">'+sel_id+'</td>\n\
   <td id="id_'+data+'_'+tr+'_count" class="new_client_table_td_hide">'+count+'</td>\n\
   <td class="new_client_table_td"> '+sel_string+'</td>\n\
   <td class="new_client_table_td">\n\
    <input class="btn_del_operation" id="'+data+'r'+tr+'r" type="button" value="  X  " onclick="new_client_table_del(this); ">\n\
   </td></tr>');
  $('.opreation_sel').val(0);
 }       
 } 
}
function new_client_table_del(obj){
  var str=obj.id;  
  var parce_arr = [];
  parce_arr = str.split('r');
  $('#table'+parce_arr[0]+'row'+parce_arr[1]+'ct').remove(); 
}
function save_operations_onclick(data,mode){
 //alert('save_operation_onclick');   
 var postData; 
 //Массив соответствующий id операций
 var arrayOfID = [];
 var arrayOfCount = [];
 //Объект таблицы в DOM
 
 var table = $('#nct_'+data+'_ct');
 if (mode=== 'root'){
  table = $('#root_new_client_table');   
 }    
 //Объекты всех строк таблицы
 var rows = table.children().children();
 //Перебор строк (DOM-элементы)
 for (var rowI = 0; rowI < rows.length; rowI++) {    
   //Объект, соответсвующий содержимому одной строки
   var row = {};       
   //Ячейки текущей строки (DOM-элемент)
   var tr = $(rows[rowI]).children();
   row = $(tr[0]).html();
   row1=$(tr[1]).html();
 //Добавление элемента в результат
  arrayOfID.push(row);
  arrayOfCount.push(row1);
 }
  postData = {id_operations: arrayOfID,
              counts: arrayOfCount,
              id_employee:  data }; 
  $.ajax({
   type: 'POST',
   async: false,
   url: '/admin/savetomain/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
   if (data['success']){    
     //alert(data['message']);
     //alert('tab_'+data['id_employee']+'_ct');
     location.reload();
     //$('#tab_'+data['id_employee']+'_ct').click();
     //$('#tab_11_ct').click();
    }
    else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/admin/savetomain/'");
   }
  });
   
 if (mode=== 'root'){
  table = $('#root_new_client_table > tbody >tr').remove();   
 }
 if(mode==='admin'){
 $('#nct_'+data+'_ct > tbody >tr').remove();
 }
}
$(document).ready(function(){    
 if (document.location.pathname==='/user/admin/'){
  $.ajax({
  type: 'POST',
  async: false,
  url: '/admin/getemployeeid/',  
  dataType: 'json',
  success: function(data){   
   if (data['success']){    
     //alert(data['user_id']);
     $('#tab_'+data['user_id']+'_ct').click();
    }
    else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/admin/savetomain/'");
   }
  });  
 }   
}); 
//Модальное окно
/*
$(document).ready(function() { // вся мaгия пoсле зaгрузки стрaницы
	$('#go').click( function(event){ // лoвим клик пo ссылки с id="go"
		event.preventDefault(); // выключaем стaндaртную рoль элементa
		$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		 	function(){ // пoсле выпoлнения предъидущей aнимaции
				$('#modal_form') 
					.css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
					.animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
		});
	});
	/* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
/*	$('#modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
		$('#modal_form')
			.animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
				function(){ // пoсле aнимaции
					$(this).css('display', 'none'); // делaем ему display: none;
					$('#overlay').fadeOut(400); // скрывaем пoдлoжку
				}
			);
	});
});
*/
function open_mf(event){
  alert('open_mf');
  event.preventDefault();  
		$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		 	function(){ // пoсле выпoлнения предъидущей aнимaции
				$('#modal_form') 
					.css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
					.animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
		}); 
  	/* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
	$('#modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
		$('#modal_form')
			.animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
				function(){ // пoсле aнимaции
					$(this).css('display', 'none'); // делaем ему display: none;
					$('#overlay').fadeOut(400); // скрывaем пoдлoжку
				}
			);
	}); 
}
function do_action(action,event){
 if(action==='1')
 {
 event.preventDefault(); // выключaем стaндaртную рoль элементa 
 $('#overlay').fadeIn(200, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
    function(){ // пoсле выпoлнения предъидущей aнимaции
    $('#modal_form').css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
    .animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
    });
 $('#overlay').fadeTo(0,0.4);   
 $('#modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
   $('#modal_form').animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
   function(){ // пoсле aнимaции
   $(this).css('display', 'none'); // делaем ему display: none;
   $('#overlay').fadeOut(400); // скрывaем пoдлoжку
    }
   );
  });
 } 
}
function save_count(){
  var emp_id = $('#emp_id_modal').text();
  var count=$('#sel_count option:selected').text();
  var sel_id=$('#os_'+emp_id+'_ct').val(); 
  var tr=$('#nct_'+emp_id+'_ct tr').length;
  //var selected_text=$('#os_'+$('#emp_id_modal').text()+'_ct option:selected').text();
  var selected_text=$('#os_'+emp_id+'_ct option:selected').text();
  
  selected_text=selected_text+' x ['+$('#sel_count option:selected').text()+']';
  //$('#os_'+$('#emp_id_modal').text()+'_ct option:selected').text(selected_text);
  $('#os_'+emp_id+'_ct option:selected').text(selected_text); 
 
  $('#nct_'+emp_id+'_ct > tbody')
   .append('<tr id="table'+emp_id+'row'+tr+'ct">\n\
   <td id="id_'+emp_id+'_'+tr+'_ct" class="new_client_table_td_hide">'+sel_id+'</td>\n\
   <td id="id_'+emp_id+'_'+tr+'_count" class="new_client_table_td_hide">'+count+'</td>\n\
   <td class="new_client_table_td"> '+selected_text+'</td>\n\
   <td class="new_client_table_td">\n\
    <input class="btn_del_operation" id="'+emp_id+'r'+tr+'r" type="button" value="  X  " onclick="new_client_table_del(this); ">\n\
   </td></tr>');
  $('.opreation_sel').val(0);  
  $('#sel_count').val(1);
 
}
function root_save_count(){ 
  var emp_id = $('#emp_id_modal').text();
  var count=$('#sel_count option:selected').text();
  var sel_id=$('#root_operaton').val(); 
  var tr=$('#root_new_client_table').length;
  var selected_text=$('#root_operaton option:selected').text();
 
  selected_text=selected_text+' x ['+$('#sel_count option:selected').text()+']';
  $('#root_operaton option:selected').text(selected_text); 
 
  $('#root_new_client_table > tbody')
   .append('<tr id="table'+emp_id+'row'+tr+'ct">\n\
   <td id="id_'+emp_id+'_'+tr+'_ct" class="new_client_table_td_hide">'+sel_id+'</td>\n\
   <td id="id_'+emp_id+'_'+tr+'_count" class="new_client_table_td_hide">'+count+'</td>\n\
   <td class="new_client_table_td"> '+selected_text+'</td>\n\
   <td class="new_client_table_td">\n\
    <input class="btn_del_operation" id="'+emp_id+'r'+tr+'r" type="button" value="  X  " onclick="new_client_table_del(this); ">\n\
   </td></tr>');
  $('.opreation_sel').val(0);  
  $('#sel_count').val(1);
 
}
function print_button_click(){ 
 //alert('print_button_click');   
 var printing_css='<style media="print">tr:nth-child(even) td{background: #f0f0f0;}</style>'; 
 var html_to_print=printing_css+$('div#to_print').html();
 //var html_to_print=printing_css+$('.print_arrea').html();
 var iframe=$('<iframe id="print_frame" style="display: none;">'); // создаем iframe в переменную

 $('body').append(iframe); //добавляем эту переменную с iframe в наш body (в самый конец)
 var doc = $('#print_frame')[0].contentDocument || $('#print_frame')[0].contentWindow.document;
 var win = $('#print_frame')[0].contentWindow || $('#print_frame')[0];
 doc.getElementsByTagName('body')[0].innerHTML=html_to_print;
 //win.document.close();
 //win.focus();
 win.print();
 location.reload();
 $('iframe').remove();
}
function PrintElem(elem)
 { 
  Popup($(elem).html()); 
 } 
function Popup(data) 
 { 
  var mywindow = window.open('', 'my div', 'height=400,width=700,left=10,top=10,menubar=no,\n\
     toolbar=no,location=no'); 
  mywindow.document.write('<html><head>\n\
    <style>\n\
     @media all {\n\
      body{\n\
       width: 210mm;\n\
       font-family: "Arial";\n\
       font-size: 12pt;\n\
       text-align: center;\n\
       overflow: auto;\n\
      } \n\
      .print_table{\n\
        width: 182mm;\n\
        border-collapse: collapse;\n\
      } \n\
       .print_table tr td {\n\
         border: 1px solid;\n\
       }\n\
     .print_table_td_fio{\n\
      -webkit-print-color-adjust:exact;\n\
       background-color:  #ddd !important;\n\
     }\n\
      .italic_caption{\n\
       font-weight:  bold;  \n\
       font-style:  oblique;\n\
       }\n\
     .italic_underline_caption{\n\
       font-weight:  bold;  \n\
       font-style:  oblique;\n\
       padding-left: 0;\n\
      }\n\
      .pre{\n\
        font-family: "Arial";\n\
       }\n\
       .print_div{\n\
        overflow: auto;\n\
        }\n\
    </style>\n\
   </head>\n\
   <body>\n\
   <div class="prin_div">');

  mywindow.document.write(data); 
  mywindow.document.write('</div></body></html>'); 
  mywindow.document.close(); // necessary for IE >= 10 
  mywindow.focus(); // necessary for IE >= 10 
  mywindow.print(); 
  mywindow.close();    
  return true; 
}  
   /*
 function save_operations_onclick(data){
 //alert('save_operation_onclick');   
 var postData; 
 //Массив соответствующий id операций
 var arrayOfID = [];
 var arrayOfCount = [];
 //Объект таблицы в DOM
 var table = $('#nct_'+data+'_ct');
 //Объекты всех строк таблицы
 var rows = table.children().children();
 //Перебор строк (DOM-элементы)
 for (var rowI = 0; rowI < rows.length; rowI++) {    
   //Объект, соответсвующий содержимому одной строки
   var row = {};       
   //Ячейки текущей строки (DOM-элемент)
   var tr = $(rows[rowI]).children();
   row = $(tr[0]).html();
   row1=$(tr[1]).html();
 //Добавление элемента в результат
  arrayOfID.push(row);
  arrayOfCount.push(row1);
 }
  postData = {id_operations: arrayOfID,
              counts: arrayOfCount,
              id_employee:  data };
  
  $.ajax({
   type: 'POST',
   async: false,
   url: '/admin/savetomain/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
   if (data['success']){    
     //alert(data['message']);
     //alert('tab_'+data['id_employee']+'_ct');
     location.reload();
     //$('#tab_'+data['id_employee']+'_ct').click();
     //$('#tab_11_ct').click();
    }
    else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/admin/savetomain/'");
   }
  });

/*
  //Объект таблицы в DOM
 var table = $('#nct_'+data+'_ct');
 //Объекты всех строк таблицы
 var rows = table.children().children();
 //Массив соответствующий строкам таблицы
 var arrayOfTrValues = [];
 //Перебор строк (DOM-элементы)
 for (var rowI = 0; rowI < rows.length; rowI++) {    
 //Объект, соответсвующий содержимому одной строки
 var row = {};       
 //Ячейки текущей строки (DOM-элемент)
 var tr = $(rows[rowI]).children();
 //Перебор ячеек (DOM-элементы)
  for (var trI = 0; trI < tr.length; trI++) {    
  //Название класса текущей строки (DOM-элемент)
  var tdClass = $(tr[trI]).attr('class');
  //Запись значения
  if(tdClass=== "new_client_table_td_hide"){
  row[tdClass] = $(tr[trI]).html();
  }
  }      
 //Добавление элемента в результат
  arrayOfTrValues.push(row);      
 }
 */      
//}
function opdel_click(id_op,op_num,time){
 var parce_arr = [];
 var question;
 parce_arr = id_op.split('_');
 var postData='rec_id='+parce_arr[1];
 if(!(op_num==='00-00') && confirm('Удалить операцию ['+ op_num + '] в ['+ time +'] ?' )){
  $.ajax({
  type: 'POST',
   async: false,
   url: '/root/deloperationbyid/',
   data: postData,
   dataType: 'json',
   success: function(data){   
   if (data['success']){    
     alert(data['message']);
     $('#row_'+id_op).remove();
     //location.reload();
    }else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/deloperationbyid/'");
   }
  });
 }
}
function t_e_r_onclick(object){
  //alert(object.id);  
  $('#cur_row').text(object.id); 
  show_emp(object.id);
  /*
    var postData='id='+object.id;
  $.ajax({
   type: 'POST',
   async: false,
   url: '/root/getempparbyid/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
   if (data['success']){
    $("#emp_img").attr("src",data['image']);
    $("#e_id").text(data['id']);
    //alert(data['is_work']);
    if (data['is_work'] =='1') $('#emp_is_work')[0].checked = true;
    if (data['is_work'] =='0') $('#emp_is_work')[0].checked = false;
    $("#emp_fn_span").html(data['first_name']);
    $("#emp_login_span").html(data['login'].substring(3));
    $("#emp_ln_span").html(data['last_name']);
    $("#emp_comment").html(data['comment']);
    $("#emp_partonymic_span").html(data['partonymic']);
    $("#obj_name_rus").html(data['name_rus']);
    $("#emp_short_name_span").html(data['short_name']);
    $("#emp_creator").html(data['creator']);
    if(data['privilegy']=== 'admin') $("#emp_privilegy_span").html('Администратор');
    if(data['privilegy']=== 'employee') $("#emp_privilegy_span").html('Парикмахер');
    if(data['privilegy']=== 'root') $("#emp_privilegy_span").html('Директор');
    $("#emp_create_date").html(data['create_date']);
    $("#emp_salary_span").html(data['salary']);
    $("#emp_last_modifer").html(data['last_modifer']);
    $("#emp_salary_1_span").html(data['salary_1']);
    $("#emp_last_mod_date").html(data['last_mod_date']);
    $("#emp_phone_span").html(data['phone']);
    $("#emp_email_span").html(data['email']);
    $("#emp_image").html(data['image']);
   }
    else{
     alert(data['message']);     
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/getempparbyid/'");
   }
  }); 
  */
    //alert(object.id);
}
function e_button_load_click(){
var file_data = $('#e_file').prop('files')[0];
var form_data = new FormData(); 

if (!file_data) alert('Файл не выбран\nнажмите кнопку \"Обзор\"');
else{
 var form_data = new FormData(); 
 form_data.append('file', file_data);
  $.ajax({ 
  url: '/root/loadfile/', 
  dataType: 'json', 
  cache: false, 
  contentType: false, 
  processData: false, 
  data: form_data, 
  type: 'post', 
  success: function(data){   
  if (data['success']){
    alert(data['message']); 
    $('#emp_image').html(data['emp_image']);
    $("#emp_img").attr("src",data['emp_image']);
   }   
  },
  error: function(){
    alert("Ошибка в '/root/loadfile/'");
  }
  });
}
    //alert(file_data);
 //var form_data = new FormData(); 
 //form_data.append('file', file_data); 
 //alert(form_data); 
/*    
 $.ajax({ 
  url: 'upload.php', 
  dataType: 'text', 
  cache: false, 
  contentType: false, 
  processData: false, 
  data: form_data, 
  type: 'post', 
 success: function(php_script_response){ 
  alert(php_script_response); 
   } 
  });
 */ 
}
function e_button_edit_click(obj){
 if (obj.value === " Редактировать ") e_button_edit_action(); 
 else e_button_cancel_action();
}
function e_button_edit_action(){
 $('#e_button_edit').val("Отмена");
 $('#e_button_save')[0].disabled = false;
 $('#e_button_load')[0].disabled = false;
 $('#emp_is_work')[0].disabled = false;
 $('#emp_comment').prop('readonly', false); 
 $('#emp_comment').removeClass('disable_events');
 $('#e_list_table').addClass('disabled_arrea');
 $('#button').css('color', 'black');
 $('#e_file').css('pointer-events', 'all');

 $('#emp_fn_span').addClass('cl_hiden');
 $('#emp_fn_input').removeClass('cl_hiden');
 $('#emp_fn_input').val($('#emp_fn_span').html());
 
 $('#emp_login_span').addClass('cl_hiden');
 $('#emp_login_input').removeClass('cl_hiden');
 $('#emp_login_input').val($('#emp_login_span').html());
 
 $('#emp_ln_span').addClass('cl_hiden');
 $('#emp_ln_input').removeClass('cl_hiden');
 $('#emp_ln_input').val($('#emp_ln_span').html());
 
 $('#emp_password_span').addClass('cl_hiden');
 $('#emp_password_input').removeClass('cl_hiden');
 $('#emp_password_input').val('');
 
 $('#emp_partonymic_span').addClass('cl_hiden');
 $('#emp_partonymic_input').removeClass('cl_hiden');
 $('#emp_partonymic_input').val($('#emp_partonymic_span').html());
 
 $('#emp_short_name_span').addClass('cl_hiden');
 $('#emp_short_name_input').removeClass('cl_hiden');
 $('#emp_short_name_input').val($('#emp_short_name_span').html()); 
 
 $('#emp_salary_span').addClass('cl_hiden');
 $('#emp_salary_input').removeClass('cl_hiden');
 $('#emp_salary_input').val($('#emp_salary_span').html());
 
 $('#emp_salary_1_span').addClass('cl_hiden');
 $('#emp_salary_1_input').removeClass('cl_hiden');
 $('#emp_salary_1_input').val($('#emp_salary_1_span').html());
 
 $('#emp_phone_span').addClass('cl_hiden');
 $('#emp_phone_input').removeClass('cl_hiden');
 $('#emp_phone_input').val($('#emp_phone_span').html()); 

 $('#emp_email_span').addClass('cl_hiden');
 $('#emp_email_input').removeClass('cl_hiden');
 $('#emp_email_input').val($('#emp_email_span').html()); 
 
 $('#emp_privilegy_span').addClass('cl_hiden');
 $('#emp_privilegy_select').removeClass('cl_hiden');
 
 if($('#emp_privilegy_span').html() === 'Парикмахер') 
  $('#emp_privilegy_select').val('employee');
 if($('#emp_privilegy_span').html() === 'Администратор'){
  $('#emp_privilegy_select').val('admin');
  $('#emp_short_name_input').addClass('cl_hiden');
  $('#emp_short_name_span').removeClass('cl_hiden');
 } 
 if($('#emp_privilegy_span').html() === 'Директор'){
  $('#emp_privilegy_select').val('root');
    $('#emp_short_name_input').addClass('cl_hiden');
  $('#emp_short_name_span').removeClass('cl_hiden');
 }
}
function e_button_cancel_action(){
  //alert($('#is_new').text());  
 $('#e_button_edit').val(" Редактировать ");
 $('#e_button_save')[0].disabled = true;
 $('#e_button_load')[0].disabled = true;
 $('#emp_is_work')[0].disabled = true;
 $('#e_list_table').removeClass('disabled_arrea');
 $('#emp_comment').prop('readonly', true);
 $('#emp_comment').addClass('disable_events');
 $('#e_file').prop('pointer-events', 'none');
 $('#button').css('color', '#666666');
 $('#file_form')[0].reset();
 $('#e_file').css('pointer-events', 'none');

 $('#emp_fn_span').removeClass('cl_hiden');
 $('#emp_fn_input').addClass('cl_hiden');
 
 $('#emp_login_span').removeClass('cl_hiden');
 $('#emp_login_input').addClass('cl_hiden');
 
 $('#emp_ln_span').removeClass('cl_hiden');
 $('#emp_ln_input').addClass('cl_hiden');
 
 $('#emp_password_span').removeClass('cl_hiden');
 $('#emp_password_input').addClass('cl_hiden'); 
 
 $('#emp_partonymic_span').removeClass('cl_hiden');
 $('#emp_partonymic_input').addClass('cl_hiden');  
 
 $('#emp_short_name_span').removeClass('cl_hiden');
 $('#emp_short_name_input').addClass('cl_hiden');  
 
 $('#emp_salary_span').removeClass('cl_hiden');
 $('#emp_salary_input').addClass('cl_hiden');
 
 $('#emp_salary_1_span').removeClass('cl_hiden');
 $('#emp_salary_1_input').addClass('cl_hiden');
 
 $('#emp_phone_span').removeClass('cl_hiden');
 $('#emp_phone_input').addClass('cl_hiden'); 

 $('#emp_email_span').removeClass('cl_hiden');
 $('#emp_email_input').addClass('cl_hiden');
 
 $('#emp_privilegy_span').removeClass('cl_hiden');
 $('#emp_privilegy_select').addClass('cl_hiden');
 
 if($('#is_new').text()=== '1') {  
  if($('#cur_row').text()=== '0'){
   var obj = document.getElementById('e_list_table').getElementsByTagName('tr');
   $('#cur_row').text(obj[1].id);
  }      
  show_emp($('#cur_row').text());
     
 } 
}
function emp_privilegy_select_change(s_obj){
 if ($("select#"+s_obj.id).val() === 'root'){ 
  $('#emp_short_name_span').removeClass('cl_hiden');
  $('#emp_short_name_input').addClass('cl_hiden');
  $('#emp_short_name_span').text('');
  $('#emp_salary_1_input').val('0.00');
  $('#emp_salary_input').val('0.00');
 }
 if ($("select#"+s_obj.id).val() === 'admin'){
  $('#emp_short_name_span').removeClass('cl_hiden');
  $('#emp_short_name_input').addClass('cl_hiden');
  $('#emp_short_name_span').text('Администратор');
 }
 if ($("select#"+s_obj.id).val() === 'employee'){
  $('#emp_short_name_input').removeClass('cl_hiden');
  $('#emp_short_name_span').addClass('cl_hiden');
 } 
}
function e_button_save_click(s_obj){
//alert('e_button_save_click');
 //window.err_code=0;
 //$('#emp_fn_span').text($('#emp_fn_input').val());
 //$('#emp_ln_span').text($('#emp_ln_input').val());
 $('#emp_partonymic_span').text($('#emp_partonymic_input').val());
 //$('#emp_login_span').text($('#emp_login_input').val());
 if ($("select#emp_privilegy_select").val() === 'employee')
  $('#emp_short_name_span').text($('#emp_short_name_input').val());
 $('#emp_privilegy_span').text($("#emp_privilegy_select option:selected").text());
 //$('#emp_salary_span').text($('#emp_salary_input').val());
 //$('#emp_salary_1_span').text($('#emp_salary_1_input').val());
 $('#emp_phone_span').text($('#emp_phone_input').val());
 $('#emp_email_span').text($('#emp_email_input').val());
 $('#emp_password_span_h').text('');
 if ($('#emp_password_input').val() === '') $('#emp_password_span').text('********');
 else{ 
  $('#emp_password_span').text('********');
  $('#emp_password_span_h').text($('#emp_password_input').val());
  }
 var postData = '';
 var errorCode=0;
  $.ajax({
   type: 'POST',
   async: false,
   url: '/root/getuseranddate/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
  if (data['success']){
   $('#emp_last_modifer').html(data['user']);
   $('#emp_last_mod_date').html(data['datetime']);
    }   
   },
  error: function(){
      alert("'Ошибка в '/root/getuseranddate/'");
   }
  }); 
 if($('#e_id').text() === '0') errorCode=e_save('append'); else errorCode=e_save('replace');
 if( errorCode === 1){
  $('#emp_login_span').text($('#emp_login_input').val());
  $('#emp_fn_span').text($('#emp_fn_input').val());
  $('#emp_ln_span').text($('#emp_ln_input').val());
  $('#emp_salary_span').text($('#emp_salary_input').val());
  $('#emp_salary_1_span').text($('#emp_salary_1_input').val());
  e_button_cancel_action();
 }  
 //window.err_code= errorCode;
}
function e_save(mode){
 var postData = 'type='+mode;
 var errorCode=0;
 if (mode === 'replace') {
  postData = postData+'&id='+$('#e_id').html();   
 }
 if (mode === 'append') {
  postData = postData+'&id=0';   
 }
 $.ajax({
   type: 'POST',
   async: false,
   url: '/root/getobjid/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
  if (data['success']){
   postData = postData+'&obj_id='+data['obj_id'];   
    //alert(data['obj_id']);  
    }   
   },
  error: function(){
      alert("'Ошибка в '/root/getobjid/'");
   }
  }); 
 //postData = postData+'&login='+$('#emp_login_span').html();
 postData = postData+'&login='+$('#emp_login_input').val();
 postData = postData+'&password='+$('#emp_password_span_h').html();
 postData = postData+'&short_name='+$('#emp_short_name_span').html();
 //postData = postData+'&first_name='+$('#emp_fn_span').html();
 postData = postData+'&first_name='+$('#emp_fn_input').val();
 //postData = postData+'&last_name='+$('#emp_ln_span').html();
 postData = postData+'&last_name='+$('#emp_ln_input').val();
 postData = postData+'&partonymic='+$('#emp_partonymic_span').html();
 //postData = postData+'&salary='+$('#emp_salary_span').html();
 postData = postData+'&salary='+$('#emp_salary_input').val();
 //postData = postData+'&salary_1='+$('#emp_salary_1_span').html();
 postData = postData+'&salary_1='+$('#emp_salary_1_input').val();
 if ($('#emp_is_work')[0].checked === true) postData = postData+'&is_work=1';
 else postData = postData+'&is_work=0';
 postData = postData+'&email='+$('#emp_email_span').html();
 postData = postData+'&phone='+$('#emp_phone_span').html();
 postData = postData+'&image='+$('#emp_image').html();
 postData = postData+'&comment='+$('#emp_comment').val();
 if($('#emp_privilegy_span').html()==='Администратор') postData = postData+'&privelegy=admin';
 if($('#emp_privilegy_span').html()==='Парикмахер') postData = postData+'&privelegy=employee';
 if($('#emp_privilegy_span').html()==='Директор') postData = postData+'&privelegy=root';
 $.ajax({
   type: 'POST',
   async: false,
   url: '/user/adduser/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
  //if (data['success']){
   alert(data['message']);
   errorCode =  data['error_code'];
    //}   
   },
  error: function(){
      alert("'Ошибка в '/user/adduser/'");
   }
  }); 
  return errorCode; 
}
function e_button_new_click(n_obj){
 $('#is_new').text('1');      
 $('#emp_fn_span').text('');
 $('#emp_ln_span').text('');
 $('#emp_partonymic_span').text('');
 $('#emp_login_span').text('');
 $('#emp_phone_span').text('');
 $('#emp_email_span').text('');
 $('#emp_short_name_span').text('');
 $('#emp_comment').text('');
 $('#e_id').text('0');
 var postData;
 $.ajax({
   type: 'POST',
   async: false,
   url: '/user/getdefaultimage/',
   data: postData ,   
   dataType: 'json',
  success: function(data){ 
    $('#emp_image').html(data['default_imge']); 
    $("#emp_img").attr("src",data['default_imge']);
    //alert(data['message']);   
   },
  error: function(){
      alert("'Ошибка в '/root/getdefaultimage/'");
   }
  });  
 e_button_edit_action(); 
}
function show_emp(em_id){
 var postData='id='+em_id;
  $.ajax({
   type: 'POST',
   async: false,
   url: '/root/getempparbyid/',
   data: postData ,   
   dataType: 'json',
  success: function(data){   
   if (data['success']){
    $("#emp_img").attr("src",data['image']);
    $("#e_id").text(data['id']);
    //alert(data['is_work']);
    if (data['is_work'] =='1') $('#emp_is_work')[0].checked = true;
    if (data['is_work'] =='0') $('#emp_is_work')[0].checked = false;
    $("#emp_fn_span").html(data['first_name']);
    $("#emp_login_span").html(data['login'].substring(3));
    $("#emp_ln_span").html(data['last_name']);
    $("#emp_comment").html(data['comment']);
    $("#emp_partonymic_span").html(data['partonymic']);
    $("#obj_name_rus").html(data['name_rus']);
    $("#emp_short_name_span").html(data['short_name']);
    $("#emp_creator").html(data['creator']);
    if(data['privilegy']=== 'admin') $("#emp_privilegy_span").html('Администратор');
    if(data['privilegy']=== 'employee') $("#emp_privilegy_span").html('Парикмахер');
    if(data['privilegy']=== 'root') $("#emp_privilegy_span").html('Директор');
    $("#emp_create_date").html(data['create_date']);
    $("#emp_salary_span").html(data['salary']);
    $("#emp_last_modifer").html(data['last_modifer']);
    $("#emp_salary_1_span").html(data['salary_1']);
    $("#emp_last_mod_date").html(data['last_mod_date']);
    $("#emp_phone_span").html(data['phone']);
    $("#emp_email_span").html(data['email']);
    $("#emp_image").html(data['image']);
   }
    else{
     //alert(data['message']); 
     //alert('qq'); 
     }     
    },
   error: function(){
      alert("'Ошибка в '/root/getempparbyid/'");
   }
  });     
}
function e_save_shedule(){
 var work_emp_str="";
 var postData="";
 
 $('input:checkbox:checked').each(function(){  
 work_emp_str+=$(this).val()+"/";
 });   
 work_emp_str=work_emp_str.substr(3);
 postData="work_emp_str="+work_emp_str; 
 //alert(postData);
  $.ajax({
   type: 'POST',
   async: false,
   url: '/user/shedule/',
   data: postData,
   dataType: 'json',
    success: function(data){
    if (data['success'])
    {   
     alert(data['message']);   
      //window.location.href = '/user/admin/';
    }else{
     alert(data['message']);     
     }     
    },
     error: function(){
      alert("'Ошибка в '/user/shedule/'");
     }
  });
}
function e_admchange(obj){
 postData="$emp_id="+$("#"+obj.id+"").val(); 
 $.ajax({
  type: 'POST',
  async: false,
  url: '/user/getempparam/',
  data: postData,
  dataType: 'json',
  success: function(data){ 
   $('#adm_img').attr("src",data['emp_param'][0]['image']);    
  },
  error: function(){
   alert("'Ошибка в '/user/getempparam/'");
  }
 });
}
function e_adm_btn_onclick(){
//  alert($('#e_adm_select').val()); 
 var postData="adm_id="+$('#e_adm_select').val(); 
 //alert(postData);
 $.ajax({
  type: 'POST',
  async: false,
  url: '/user/newadm/',
  data: postData,
  dataType: 'json',
  success: function(data){
   alert(data['message']);   
   //$('#adm_img').attr("src",data['emp_param'][0]['image']);    
  },
  error: function(){
   alert("'Ошибка в '/user/newadm/'");
  }
 });  
  
}