<?php
  require "header.php";
  require "includes/dbh.inc.php";
?>

  <main>
    <div class="page-container">
      <div class="thumbnail-full">
        <?php
          if (!isset($_GET['movieid'])) {
            header("Location: index.php");
            exit();
          }
          else {
            $movie_id = $_GET['movieid'];
            if ($movie_id == "") {
              header("Location: index.php");
              exit();
            }
            else {
              // session_start();
              if(isset($_SESSION['userid'])) {
                $user_id = $_SESSION['userid'];
                $sql = "SELECT * FROM ratings WHERE user_id=? AND movie_id=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header("Location: index.php");
                  exit();
                }
                else {
                  mysqli_stmt_bind_param($stmt, "ii", $user_id, $movie_id);
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
                  $row = mysqli_fetch_assoc($result);
                  $user_movie_rating = $row['rating'];
                  // echo '<p style="color: white;">user_rating:'.$movie_rating.'</p>';
                }
              }
              $sql = "SELECT * FROM movies WHERE movie_id=?;";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: index.php");
                exit();
              }
              else {
                mysqli_stmt_bind_param($stmt, "i", $movie_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if ($row == "NULL") {
                  header("Location: index.php?error=invalidmovieid");
                  exit();
                }
                else{
                  echo '<img src="img/movies/'.$movie_id.'.jpg" alt="'.$movie_id.'">';
                  // IDEA: http://jsfiddle.net/Vqmaw/
                }
              }
        ?>
      </div>
      <div class="movie-details">
        <br>
        <?php
          echo '<h1>'.$row['title'].'</h1>';
        ?>
        <br>
        <div class="movie-rate">
        <?php
          $avg_rating = NULL;
          $sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE movie_id=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: index.php?error=sqlerror1");
            exit();
          }
          else {
            mysqli_stmt_bind_param($stmt, "i", $movie_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row_ = mysqli_fetch_assoc($result);
            if ($row_ == "NULL") {
              $avg_rating = 0;
            }
            else {
              $avg_rating = $row_['avg_rating'];
              $avg_rating = number_format((float)$avg_rating, 1, '.', '');
            }
            echo '<p id="rating-label" style="float:left;">Avg. Rating:</p>
                  <span id="avg-rating">'.$avg_rating.' / 5</span>';
          }
        ?>
        <div class="rate">
          <img src="img/popcorn1.png" class="star-icon">
        </div>
        <div class="stars">
          <?php
            echo '<form class="rating-form" action="includes/rating.inc.php?movieid='.$movie_id.'" method="post" onchange="submit()">';
           ?>
           <!-- <input class="submit-rating" type="submit" name="submit-rating" value="Submit"> -->
           <section>
           <?php
              $count = 5;
              while ($count > 0) {

                echo '<input class="star star-'.$count.'" id="star-'.$count.'" type="radio" name="user-rating" value='.$count;
                if (isset($user_movie_rating)) {
                  if (gettype($user_movie_rating) != "NULL" && $user_movie_rating == $count) {
                    echo ' checked="checked"';
                  }
                }
                echo '/>
                      <label class="star star-'.$count.'" for="star-'.$count.'"></label>';
                $count--;
              }
           ?>
          <p id="rating-label" style="float: right;">User Rating:</p>
          </section>
          </form>
        </div>
        <span class="sub-title" style="float: left;padding-bottom: 0">Description</span>
        <?php
            $description = (gettype($row['description']) != "NULL" ? $row['description'] : '-');
            echo '<p style="clear:left;" class="section">'.$description.'</p>';
        ?>
        <hr class="navbar-hr" style="margin: 15px 0;width: 100%;align: center">
        <span class="sub-title">Details</span>
          <ul>
            <li>
              <span class="detail">Director(s):</span> <?php echo (gettype($row['director']) != "NULL" ? $row['director'] : '-'); ?>
            </li>
            <li>
              <span class="detail">Producer(s):</span> <?php echo(gettype($row['producer']) != "NULL" ? $row['producer'] : '-'); ?>
            </li>
            <li>
              <span class="detail" id="cast">Cast:</span>
                <!-- <ul id="cast"> -->
                  <?php
                  $sql = "SELECT * FROM mov_cast WHERE movie_id=?;";
                  $stmt = mysqli_stmt_init($conn);
                  if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: index.php");
                    exit();
                  }
                  else {
                    mysqli_stmt_bind_param($stmt, "i", $movie_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row_ = mysqli_fetch_assoc($result);
                    // $count = count($row_);
                    while (gettype($row_) != "NULL") {
                      $actor = $row_['actor'];
                      // echo '<li>'.$actor.'</li>';
                      $row_ = mysqli_fetch_assoc($result);
                      if (gettype($row_) == "NULL") {
                        echo $actor;
                      }
                      else{
                        echo $actor.', ';
                      }
                    }
                  }
                  ?>
                    </li>
                    <li>
                      <span class="detail">Language:</span>
                      <?php
                      $lang_id = $row['lang_id'];
                      $sql = "SELECT lang_name FROM languages WHERE lang_id=?;";
                      $stmt = mysqli_stmt_init($conn);
                      if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: index.php?error=sqlerror2");
                        exit();
                      }
                      else {
                          mysqli_stmt_bind_param($stmt, "i", $lang_id);
                          mysqli_stmt_execute($stmt);
                          $result = mysqli_stmt_get_result($stmt);
                          $row_ = mysqli_fetch_assoc($result);
                          echo $row_['lang_name'];
                      }
                      ?>
                    </li>
                    <li>
                      <span class="detail">Genres:</span>
                      <?php
                      // $lang_id = $row['lang_id'];
                      $sql = "SELECT movie_id, genre_name FROM mov_genres, genres WHERE mov_genres.genre_id = genres.genre_id AND movie_id=?";
                      $stmt = mysqli_stmt_init($conn);
                      if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: index.php?error=sqlerror2");
                        exit();
                      }
                      else {
                          mysqli_stmt_bind_param($stmt, "i", $movie_id);
                          mysqli_stmt_execute($stmt);
                          $result = mysqli_stmt_get_result($stmt);
                          $row_ = mysqli_fetch_assoc($result);
                          while (gettype($row_) != "NULL") {
                            $genre_name = $row_['genre_name'];
                            $row_ = mysqli_fetch_assoc($result);
                            if (gettype($row_) == "NULL") {
                              echo $genre_name;
                            }
                            else{
                              echo $genre_name.', ';
                            }
                          }
                      }
                    ?>
                    </li>
                    <li>
                      <span class="detail">Release Date:</span>
                      <?php
                      $release_date = (gettype($row['release_date']) != "NULL" ? $row['release_date'] : '-');
                      $release_date = date("d-m-Y", strtotime($release_date));
                      $release_date = str_replace('-', ' / ', $release_date );
                      echo $release_date;
                }
              }
                ?>
                <!-- </ul> -->
                </li>
          </ul>
          <!-- <hr class="navbar-hr" style="margin: 15px 0;width: 100%;align: center">
          <span class="sub-title">User Review</span>
          <div class="review-tile">
              <textarea name="review" rows="8" cols="80"></textarea>
          </div> -->

      </div>
    </div>
    <div class="" style="clear: both; margin: 1em 0;">
      <hr class="navbar-hr" style="margin: 0em 3%;width: 94%;align: center">
      <p class="section" style="text-align: center; font-size: 2em">Trailers & more</p>
      <?php
        $xml_file = file_get_contents("xml/embed_links.xml");
        $xml = simplexml_load_string($xml_file) or die("Error: Cannot create object");
        $json  = json_encode($xml);
        $xmlArr = json_decode($json, true);
        $movie = $xmlArr['id_'.$movie_id];
        $link = $movie['link'];
        ?>
      <div class="trailer-container">
        <iframe class="trailer" width="720" height="400" src="<?php echo $link ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
    </div>
  </main>

<?php
  require "footer.php";
?>
