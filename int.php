<?php

//Error

ini_set('display-errors', 'On');
error_reporting(E_ALL);


include 'admin/connect.php';

$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}

//Routes
$tpl   = 'include/templates/';  //Templates Directory
$lang  = 'include/lang/';      // Language Directory
$func  = 'include/function/'; //Function Directory 
$css   = 'layout/css/';      //Css Directory
$js    = 'layout/js/';      //Js Directory


//Include The Important Files 
include $func . 'function.php';
include $lang . 'en.php';
include $tpl  . 'heder.php';

?>