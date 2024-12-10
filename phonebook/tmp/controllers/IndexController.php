<?php
//progect name: BARBERSHOP1

/*
 * Функция загрузки стартовой страницы
 * 
 */
function indexAction($smarty){
  loadTemlate($smarty,'header');
  loadTemlate($smarty,'startpage');
  loadTemlate($smarty,'footer');   
}
/**
 * 
 * @param type $smatry
 */
function logoutAction(){
    unset($_SESSION['user']['privilegy']);
    redirect();
}

