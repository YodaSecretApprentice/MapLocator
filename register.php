<?php

include_once 'includes/config.php';
include_once 'includes/db_connect.php';
?>

<!DOCTYPE HTML SYSTEM>
<html>
<head>
    <title>Event log service - Register</title>
    <?php
    include_once 'includes/header.php';
    ?>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <script type="text/JavaScript" src="js/register.js"></script>
</head>
<body>
<div class="notif_container"></div>

<?php
$error_msg = "";
// if user clicked the create account button
if (isset($_POST['create_account'])) {
    // and if those fields are not empty
    if (!empty($_POST['email']) && (!empty($_POST['passwd'])) && (!empty($_POST['confirmpasswd']))) {

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        
        $password = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_STRING);
        $confirmpasswd = filter_input(INPUT_POST, 'confirmpasswd', FILTER_SANITIZE_STRING);

        // maybe not filter this or what? its password. Dont allow special chars?
        if (isset($_POST['phone'])) {
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        }

        if (isset($_POST['username'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Not a valid email
            $error_msg .= '<p class="error" hidden> The email address you entered is not valid</p>';
        }

        // Username validity and password validity has to be checked server side no matter what!
        $prep_stmt = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = $mysqli->prepare($prep_stmt);
     
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
     
            if ($stmt->num_rows == 1) {
                // A user with this email address already exists
                $error_msg .= '<p class="error" hidden> A user with this email address already exists.</p>';
            }
        } else {
            $error_msg .= '<p class="error" hidden> Database error</p>';
        }

        if ($password !== $confirmpasswd) {
            $error_msg .= '<p class="error" hidden> Passwords do not match!</p>';
        }

        if (empty($error_msg)) {
            // Create password 
            $password = hash('sha512', $password);

            // Insert the new user into the database 
            if ($insert_stmt = $mysqli->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)")) {
                $insert_stmt->bind_param('ssss', $username, $email, $password, $phone);
                // Execute the prepared query.
                if (! $insert_stmt->execute()) {
                    header('Location: ../error.php?err=Registration failure: INSERT');
                }
            }
            echo "<p hidden class='success'> You have successfully created your account. You will be redirected to the main page in 3 seconds.. </p>";
            echo "<meta http-equiv='refresh' content='3;url=index.php'>";
        }
        else
        	echo $error_msg;
    }
    else {
        echo '<p class="error" hidden>  Please enter all required information.</p>';
    }
}

?>

<div class="container">
    <div class="grid-wrap">    
        <div class="grid-col bp1-col-full bp2-col-full bp3-col-one-half">
            <div class="island">
                <ul>
                    <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
                    <li>Emails must have a valid email format</li>
                    <li>Passwords must be at least 6 characters long</li>
                    <li>Passwords must contain
                        <ul>
                            <li>At least one upper case letter (A..Z)</li>
                            <li>At least one lower case letter (a..z)</li>
                            <li>At least one number (0..9)</li>
                        </ul>
                    </li>
                    <li>Your password and confirmation must match exactly</li>
                <br><br>
        		<form action="" method="post">
                    <table class="display_info_table">
                        <tr>
                            <td><label>Full name:</label></td>
                            <td><input type="text" name="username"/></td>
                        </tr>
                        <tr>
                    		<td>(*) Email:</td>
                    		<td><input type="text" name="email"/></td>
                    	</tr>
                        <tr>
                    		<td>(*) Password:</td>
                    		<td><input type="password" name="passwd"/></td>
                        </tr>
                        <tr>
                    		<td>(*) Confirm Password:</td>
                    		<td><input type="password" name="confirmpasswd"/></td>

                            <!--
                    		<span class="captcha"></span>
                    		Enter captcha:
                    		<input type="text" name="captcha"/><br/>
                    		-->
                        </tr>
                        <tr>
                            <td>Phone number:</td>
                            <td><input type="text" name="phone"/></td>
                        </tr>
                    </table>
                    <br><br>
                    <input type="submit" name="create_account" value="Create Account" onclick="return regformhash(this.form,
                                                                                               this.form.email,
                                                                                               this.form.passwd;"/>
        		</form>
                </ul>
                
            </div>
        </div>
    </div>
<br>
<div class="island" style="width:30px">
    <a href="index.php"><div class="go_home"></div></a>
</div>
</div>

<?php 
  // display footer
  include_once 'includes/footer.php' 
?>

</body>
</html>