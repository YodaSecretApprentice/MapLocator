<?php
  include_once 'includes/db_connect.php';
  include_once 'includes/functions.php';
 
  sec_session_start(); // Our custom secure way of starting a PHP session.
?>

<!DOCTYPE HTML SYSTEM>
<html>
<head>
  <title>Event log service</title>
  <?php
  include_once 'includes/header.php';
  ?>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

</head>
<body>

<div class="notif_container"></div>

<?php

if ((!empty($_POST['email'])) || (!empty($_POST['passwd']))) {
    $email = $_POST['email'];
    $password = $_POST['passwd']; // The hashed password.
 
    if (login($email, $password, $mysqli) == true) {
      // Login success: redirect the user to the main page
      echo "<p hidden class='information'> Login successful.. </p>";
      echo "<meta http-equiv='refresh' content='1.5;url=index.php'>";

    } 
    else {
      // Login failed: display the proper notification with noty plugin
      $error_msg = '<p hidden class="error">Wrong credentials. Please try again.</p>';
      echo $error_msg . "<meta http-equiv='refresh' content='1.5;url=login.php'>";
    }
} else {
    // The correct POST variables were not sent to this page. 
    if (login_check($mysqli) == true) {
        // Add your protected page content here!
      echo "<p hidden class='success'> You are already logged in. You will be redirected to the main page in 3 seconds.. </p>";
      echo "<meta http-equiv='refresh' content='3;url=index.php'>";
    }
    else {
      ?>



<div class="container">

    <div class="grid-wrap"> 
      <div class="grid-col bp1-col-full bp2-col-full bp3-col-one-third"> 
      <p></p>
      </div>

      <div class="grid-col bp1-col-full bp2-col-full bp3-col-one-third">
        <br><br>  
        <div class="island">

            <center>
            <div class="box_container">
              <h2>Log in</h2>
              <div id="login_form">
                <form action="login.php" method="post">
                  <table class="login_table">
                    <tr>
                      <td>Email:</td>
                      <td><input type="text" name="email"/><br/></td> 
                    </tr>
                    <tr>
                      <td>Password:</td>
                      <td><input type="password" name="passwd"/><br/></td> 
                    </tr>
                    <tr>
                      <td><p></p></td>
                      <td><input type="submit" value="Log in"/> or <a href="register.php">Sign up now</a></td>
                    </tr>
                  </table>
                </form>
              </div>

              <br>
              <a href="index.php"><div class="go_home"></div></a>
              <br>
            </div>
            </center>
        </div>
    </div> 
          

  </div>  

</div>
<?php 
  // display footer
  include_once 'includes/footer.php' 
?>
<?php


    }
}

?>





</body>
</html>
