<?php
  require 'dbh.inc.php';
  session_start();
  if (isset($_SESSION['userid'])){
    if (isset($_GET['movieid'])) {
      if (isset($_POST['user-rating'])) {
        $user_id = $_SESSION['userid'];
        $user_rating = $_POST['user-rating'];
        $movie_id = $_GET['movieid'];
        // echo '<p>'.$movie_id.'</p>';
        if ($user_rating < 1 && $user_rating > 5) {
          header('Location: ../moviedetails.php?movieid='.$movie_id);
          exit();
        }
        else {
        $sql = "SELECT * FROM ratings WHERE user_id=? AND movie_id=?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          header('Location: ../moviedetails.php?movieid='.$movie_id);
          exit();
        }
        else {
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $movie_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if (gettype($row) != "NULL"){
              $sql = "UPDATE ratings SET rating=? WHERE user_id=? AND movie_id=?";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                header('Location: ../moviedetails.php?movieid='.$movie_id);
                exit();
              }
              else {
                mysqli_stmt_bind_param($stmt, "iii", $user_rating, $user_id, $movie_id);
                mysqli_stmt_execute($stmt);
                header('Location: ../moviedetails.php?movieid='.$movie_id);
                exit();
              }
            }
            else {
              $sql = "INSERT INTO ratings (movie_id, user_id, rating ) VALUES(?, ?, ?);";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                header('Location: ../moviedetails.php?movieid='.$movie_id);
                exit();
              }
              else {
                mysqli_stmt_bind_param($stmt, "iii", $movie_id, $user_id, $user_rating);
                mysqli_stmt_execute($stmt);
                header('Location: ../moviedetails.php?movieid='.$movie_id);
                exit();
              }
            }
          }
        }
      }
      else {
        echo '<p>invalid_user_rating</p>';
        exit();
      }

    }
    else {
      echo '<p>invalid_movie</p>';
      exit();
    }
  }
  else {
    // echo '<p>no_user</p>';
    header("Location: ../index.php");
    exit();
  }
  // if (!isset($_POST['star'])){
  //  echo '<p>not set</p>';
  // }
  // else {
  //   echo $_POST['star'];
  // }
  // echo "</main>";
?>
