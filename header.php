<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    body {font-family: "Lato", sans-serif}
    .mySlides {display: none}
    </style>
  </head>
  <body>
    <div class="navbar">
      <ul class="navlist">
        <li><a href="index.php"><img src="img/logo2.png" style="height:60px;margin:10px 0.7em 10px 0;padding-left:30px" alt="">OMDB</a></li>
        <li><a href="aboutus.php">About Us</a></li>
        <li><a href="contactus.php">Contact Us</a></li>
      </ul>
      <?php
        if (isset($_SESSION['userid'])) {
          if (gettype($_SESSION['userid']) != "NULL" && isset($_SESSION['username'])) {
            if(gettype($_SESSION['username'])) {
              echo '<ul class="navlist-account">
                    <li><a id="nav-right" href="#"><i class="fa fa-user" id="user-icon"></i>'.$_SESSION['username'].'</a></li>
                    <li><a id="nav-right" href="includes/logout.inc.php">Logout</a></li>
                    </ul>';
            }
          }
        }
        else {
          echo '<ul class="navlist-account">
                <li><a id="nav-right" href="login.php">Login</a></li>
                <li><a id="nav-right" href="signup.php">Signup</a></li>
                </ul>';
        }
      ?>
      <hr class="navbar-hr">
    </div>
