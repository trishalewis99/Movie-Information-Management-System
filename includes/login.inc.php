<?php
  if (isset($_POST['submit-login'])) {
    require 'dbh.inc.php';

    $email_uname = $_POST['email_uname'];
    $password = $_POST['pwd'];

    if (empty($email_uname) || empty($password)) {
      header("Location: ../login.php?error=emptyfields");
      exit();
    }
    else {
      $sql = "SELECT * FROM users WHERE username=? OR emailUsers=?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../login.php?error=sqlerror");
        exit();
      }
      else {
        mysqli_stmt_bind_param($stmt, "ss", $email_uname, $email_uname);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if (gettype($row) != "NULL") {
          $pwdCheck = password_verify($password, $row['pwdUsers']);
          if ($pwdCheck == false) {
            header("Location: ../login.php?error=invalidpwd");
            exit();
          }
          else if($pwdCheck == true) {
            session_start();
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = $row['userid'];
            header("Location: ../index.php?login=success");
            exit();
          }
          else {
            header("Location: ../login.php?error=invalidpwd");
            exit();
          }
        }
        else {
          header("Location: ../login.php?error=invaliduname");
          exit();
        }
      }
    }
  }
  else {
    header("Location: ../login.php");
    exit();
  }
?>
