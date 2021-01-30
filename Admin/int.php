<?php

include 'connect.php';

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

//Include Navbar On All Pages Expect The One With $Navbar Variable

if(!isset($noNavbar)) { include $tpl . 'navbar.php'; }


?>