<?php
  if (isset($_POST['submit-signup'])){

    require 'dbh.inc.php';

    $username = $_POST['uname'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
      header("Location: ../signup.php?error=emptyfields&uname=".$username."&email=".$email);
      exit();
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
      header("Location: ../signup.php?error=invalidemailanduname");
      exit();
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      header("Location: ../signup.php?error=invalidemail&uname=".$username);
      exit();
    }
    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
      header("Location: ../signup.php?error=invaliduname&email=".$email);
      exit();
    }
    else if($password != $passwordRepeat) {
      header("Location: ../signup.php?error=passwordcheck&uname=".$username."&email=".$email);
      exit();
    }
    else {
      $sql = "SELECT userid, username FROM users WHERE username=?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=sqlerror1");
        exit();
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (gettype($rows) != "NULL"){
          header("Location: ../signup.php?error=unametaken");
          exit();
        }
        else {
          $sql = "INSERT INTO users (username, emailUsers, pwdUsers) VALUES (?, ?, ?);";
          $stmt = mysqli_stmt_init($conn);
          if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=sqlerror2");
            exit();
          }
          else {
            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
            mysqli_stmt_execute($stmt);
            header("Location: ../signup.php?signup=success");
            // exit();
          }
        }
      }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
  }
  else {
    header("Location: ../signup.php");
    exit();
  }
?>
