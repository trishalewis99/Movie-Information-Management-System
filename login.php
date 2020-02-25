<?php
  require "header.php";
?>
<main>
  <div class="page-container">
    <h1 class="page-title">Login</h1>
    <!-- <div class="account-input"> -->
      <form class="form" action="includes/login.inc.php" method="post">
        <?php
        echo '<div class="response-text">';
        // style="padding: 0.8em; margin: 1em 10px; color:red; display:block; background-color:darkgrey; border-radius: 4px; text-align: center"
            if (isset($_GET['error'])) {
              if ($_GET['error'] == "emptyfields") {
                echo '<p>Fill in all fields</p>';
              }
              else if ($_GET['error'] == "invaliduname") {
                echo '<p>Wrong Username</p>';
              }
              else if ($_GET['error'] == "invalidpwd") {
                echo '<p>Wrong Password</p>';
              }
            }
            else {
              echo '>';
              echo '<p style="border-color: #45A29E;color: #45A29E">Enter Details</p>';
            }
        echo '</div>';
        ?>
        <input type="text" name="email_uname" placeholder="Username / E-Mail">
        <input type="password" name="pwd" placeholder="Password">
        <a id=forgotpwd href="forgotpwd.php">Forgot Password?</a>
        <input id="submit" type="submit" name="submit-login" value="Submit">
      </form>
    <!-- </div> -->
  </div>
</main>
<?php
  require "footer.php"
?>
