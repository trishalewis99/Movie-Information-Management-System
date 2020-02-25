<?php
  require 'header.php';
  require 'includes/dbh.inc.php';
?>

<main>
  <div class="page-container">
    <div class="movgrid">
      <div style="padding-top: 0.5em;">
        <h1 class="page-title" style="margin-left: 5%;letter-spacing: 3px;display: inline-block;">Movies</h1>
        <form class="search-bar" action="index.php" method="get" style="height:2em;">
          <input type="image" id="searchbutton" src="img/icons/search-icon.svg" alt="Search">
          <input type="text" id="textbox" name="searchText" placeholder="Search Movie">
        </form>
      </div>
      <table>
        <tbody>
          <?php
            if (!isset($_GET['pageno'])) {
              $pageNo = 1;
            }
            else {
              $pageNo = $_GET['pageno'];
              if ($pageNo > 3 || $pageNo < 1) {
                header("Location: index.php");
                exit;
              }
            }
            if (isset($_GET['searchText'])) {
              $searchText = $_GET['searchText'];
              $searchText = mysqli_real_escape_string($conn, $searchText);
              $qSearchText = '%'.$searchText.'%';
              $sql = "SELECT movie_id, title FROM movies WHERE title LIKE ?;";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: index.php?error=sqlerror1");
                exit();
              }
              else {
                mysqli_stmt_bind_param($stmt, "s", $qSearchText);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
              }
            }
            else {
              $sql = "SELECT * FROM movies_and_avg_ratings;";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: index.php?error=sqlerror2");
                exit();
              }
              else {
                // mysqli_stmt_bind_param($stmt);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
              }
            }
              $complete = false;
              $rowCount = 4;
              $colCount = 3;
              $baseCount = ($pageNo-1)*$rowCount*$colCount;
              while ($baseCount > 0){
                $row = mysqli_fetch_assoc($result);
                if (gettype($row) == "NULL") {
                  header("Location: index.php");
                  exit();
                }
                $baseCount = $baseCount - 1;
              }
              $countR = 0;
              while (!$complete && $countR < $rowCount) {
                $countC = 0;
                echo '<tr>';
                while ($countC < $colCount) {
                  $row = mysqli_fetch_assoc($result);
                  if (gettype($row) == "NULL") {
                    $complete = true;
                    break;
                  }
                  else {
                    // echo '<p class="section">'.$row['movie_id'].'</p>';
                    if (isset($row['avg_rating'])) {
                      $avg_rating = $row['avg_rating'];
                      if (gettype($avg_rating) == "NULL") {
                        $avg_rating = '0.0';
                      }
                      $avg_rating = number_format((float)$avg_rating, 1, '.', '');
                    }
                    else {
                      $avg_rating = '0.0';
                    }


                    echo '<td><a href="moviedetails.php?movieid='.$row['movie_id'].'">
                          <section class="grid-avg-rating">
                            <span id="avg-rating-small" >'.$avg_rating.' / 5</span>
                            <img id="small-popcorn" src="img/popcorn1.png" class="star-icon">
                          </section>
                          <img src="img/movies/'.$row['movie_id'].'.jpg" alt="'.$row['movie_id'].'">
                          <p>'.$row['title'].'</p>
                          </a></td>';
                    // IDEA: http://jsfiddle.net/Vqmaw/
                    // IDEA: Create View for movie and avg(rating) use avg rating to display
                  }
                  $countC = $countC + 1;
                }
                echo '</tr>';
                $countR = $countR + 1;
              }
          ?>
        </tbody>
      </table>
      <div class="pagination-container">
        <div class="pagination">
          <ul>
            <?php
              if (isset($searchText)) {
                $url = 'index.php?searchText='.$searchText.'&';
              }
              else {
                $url = 'index.php?';
              }
              switch ($pageNo) {
                case 1:
                  echo '<li><a href="'.$url.'pageno=1" class="active">1</a></li>
                        <li><a href="'.$url.'pageno=2">2</a></li>
                        <li><a href="'.$url.'pageno=3">3</a></li>
                        <!--<li><a href="'.$url.'pageno=4">4</a></li>-->';
                  break;
                case 2:
                  echo '<li><a href="'.$url.'pageno=1">1</a></li>
                        <li><a href="'.$url.'pageno=2" class="active">2</a></li>
                        <li><a href="'.$url.'pageno=3">3</a></li>
                        <!--<li><a href="'.$url.'pageno=4">4</a></li>-->';
                  break;
                case 3:
                  echo '<li><a href="'.$url.'pageno=1">1</a></li>
                        <li><a href="'.$url.'pageno=2">2</a></li>
                        <li><a href="'.$url.'pageno=3" class="active">3</a></li>
                        <!--<li><a href="'.$url.'pageno=4">4</a></li>-->';
                  break;
                case 4:
                  echo '<li><a href="'.$url.'pageno=1" class="active">1</a></li>
                        <li><a href="'.$url.'pageno=2">2</a></li>
                        <li><a href="'.$url.'pageno=3">3</a></li>
                        <!--<li><a href="'.$url.'pageno=4" class="active">4</a></li>-->';
                default:
                  header("Location: index.php");
                  exit();
                  break;
              }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</main>

<?php
  require "footer.php"
?>
