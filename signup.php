<?php
  require "header.php";
?>
<main>
  <div class="page-container">
    <h1 class="page-title">Sign Up</h1>
    <!-- <div class="account-input"> -->
    <form class="form" action="includes/signup.inc.php" method="post">
    <?php
    echo '<div class="response-text">';
    // style="padding: 0.8em; margin: 1em 10px; color:red; display:block; background-color:darkgrey; border-radius: 4px; text-align: center"
        if (isset($_GET['error'])) {
          if ($_GET['error'] == "emptyfields") {
            echo '<p>Fill in all fields</p>';
          }
          else if ($_GET['error'] == "invalidemailanduname") {
            echo '<p>Enter a valid email and username</p>';
          }
          else if ($_GET['error'] == "invaliduname") {
            echo '<p>Enter a valid username</p>';
          }
          else if ($_GET['error'] == "invalidemail") {
            echo '<p>Enter a valid email</p>';
          }
          else if ($_GET['error'] == "unametaken") {
            echo '<p>Username already taken</p>';
          }
          else if ($_GET['error'] == "passwordcheck") {
            echo '<p>Passwords did not match</p>';
          }
        }
        else if(isset($_GET['signup']) && $_GET['signup'] == "success") {
          echo '<p>Signup Successful ! </p>';
        }
        else {
          echo '<p style="border-color: #45A29E; color: #45A29E">Enter Details</p>';
        }
    echo '</div>';
    ?>
        <input type="text" name="uname" placeholder="Username">
        <input type="text" name="email" placeholder="E-mail">
        <input type="password" name="pwd" placeholder="Password">
        <input type="password" name="pwd-repeat" placeholder="Repeat Password">
        <input id="submit" type="submit" name="submit-signup" value="Submit">
      </form>
    <!-- </div> -->
  </div>
</main>
<?php
  require "footer.php"
?>
