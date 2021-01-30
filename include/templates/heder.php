<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet"  href="<?php echo $css; ?>font-awesome.min.css" />
    <link rel="stylesheet"  href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet"  href="<?php echo $css; ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet"  href="<?php echo $css; ?>frontend.css">
    <title><?php getTitle() ?></title>
</head>
<body>
<div class="upper-bar">
 <div class="container">
 <?php
   if(isset($_SESSION['user'])) { ?>
   <img class="my-img img-thumbnail img-circle" src="img-2.png" alt="" />
    <div class="btn-group my-info">
      <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <?php echo $sessionUser ?>
        <span class="caret"></span>
      </span>
      <ul class="dropdown-menu">
        <li><a href="profile.php">My Profile</a></li>
        <li><a href="newad.php">New Add</a></li>
        <li><a href="profile.php#my-ads">New Items</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
   <?php
   } else {
 ?>
  <a href="login.php">
    <span class="pull-right">Login/Signup</span>
  </a>
   <?php }?>
 </div>
</div>  
<nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button
            type="button"
            class="navbar-toggle collapsed"
            data-toggle="collapse"
            data-target="#app-nav"
            aria-expanded="false"
          >
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span> <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">HomePage</a>
        </div>
        <div class="collapse navbar-collapse" id="app-nav">
          <ul class="nav navbar-nav navbar-right">
            <?php
            $allCat = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
                foreach ($allCat as $cat) {
                  echo '<li>
                          <a href="categories.php?pageid=' . $cat['ID'] . '">
                          ' . $cat['Name'] .
                          '</a>
                        </li>';
                }
            
            ?>
        </ul>   
        </div>
      </div>
    </nav>
