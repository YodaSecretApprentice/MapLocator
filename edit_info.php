<?php
    include_once 'includes/config.php';
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
    
    sec_session_start(); // start the session
?>

<!DOCTYPE HTML SYSTEM>
<html>
<head>
<head>
<head>

    <?php
    include_once 'includes/header.php';
    ?>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div class="notif_container"></div>

<?php
$error_msg = "";

if(login_check($mysqli) == true) {
        // Add your protected page content here!

$prep_stmt = "SELECT username, email, phone FROM users WHERE id = ?";
$stmt = $mysqli->prepare($prep_stmt);

if ($stmt) {
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();

    $stmt->store_result();
    $stmt->bind_result($usernameToFill, $emailToFill, $phoneToFill);
    $stmt->fetch();

} else {
    $error_msg .= '<p class="error">Database error</p>';
}

?>

<div class="container">
    <div class="grid-wrap">    
        <div class="grid-col bp1-col-full bp2-col-full bp3-col-one-half">
                <div class="island">
            Change your settings below. Remember that:
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
            </ul>
            
            <form action="" method="post">
                <table class="display_info_table">
                <tr>
                    <td><label>Full name :</label></td>
                    <td><input type="text" name="username" value="<?php echo $usernameToFill ?>" /><br /></td>
                </tr>
                <tr>
                    <td><label>Email :</label></td>
                    <td><input type="text" name="email" value="<?php echo $emailToFill ?>" /><br /></td>
                </tr>
                <tr>
                    <td><label>Phone number:</label></td>
                    <td><input type="text" name="phone"  value="<?php echo $phoneToFill ?>"/><br/></td>
                </tr>
                <tr>
                    <td><label>Current Password:</label></td>
                    <td><input type="password" name="oldpasswd"/><br/></td>
                </table>
                <hr>
                <input type="checkbox" name="change_pw" value="selected">Change password<br>

                <table class="display_info_table">
                <tr>
                    <td><label>New Password:</label></td>
                    <td><input type="password" name="passwd"/><br/></td>
                </tr>
                <tr>
                    <td><label>Confirm Password:</label></td>
                    <td><input type="password" name="confirmpasswd"/><br/></td>
                </tr>
                </table>

                <input type="submit" value="Update Account"/><br />
            </form>
        </div>
    </div>
</div>
</div>

<?php
// check if user filled the fields
if (isset($_POST['email'], $_POST['passwd'], $_POST['oldpasswd'] )) {

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

    $oldpassword = filter_input(INPUT_POST, 'oldpasswd', FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p hidden class="error">The email address you entered is not valid</p>';
    }

    // Username validity and password validity has to be checked server side no matter what!
    // check if this email already is being used by someone else
    $prep_stmt = "SELECT id FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_id);
        $stmt->fetch();
        if (isset($db_id) && ($db_id != $_SESSION['user_id'])) {
            // A user with this email address already exists
            $error_msg .= '<p hidden class="error">A user with this email address already exists.</p>';

        }
    } 
    else {
        $error_msg .= '<p hidden class="error">Database error</p>';
    }
    // if theres no error at all
    if (empty($error_msg)) {

        if ($stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ? LIMIT 1")) {
            // No need for sanitization since mysqli prepared statement handles this
            $stmt->bind_param('i', $_SESSION['user_id']);  // Bind "$_SESSION['user_id']" to parameter.
            $stmt->execute();    // Execute the prepared query.
            $stmt->store_result();
            
            // get variables from result.
            $stmt->bind_result($db_password);
            $stmt->fetch();
        }

        // Create hashed password 
        $oldpassword = hash('sha512', $oldpassword);
        if ($db_password == $oldpassword) {

            if (isset($_POST['change_pw']) && ($_POST['change_pw'] == 'selected')) {
                if ($_POST['confirmpasswd'] == $_POST['passwd']) {
                    // maybe not filter this or what? its password. Dont allow special chars?
                    $password = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_STRING);
                    $password = hash('sha512', $password);
                 
                    // Insert the new user into the database 
                    if ($insert_stmt = $mysqli->prepare("UPDATE users SET username = ?, email = ?, password = ?, phone = ? WHERE id = ?")) {
                        $insert_stmt->bind_param('ssssi', $username, $email, $password, $phone, $_SESSION['user_id']);
                        
                        $_SESSION['username'] = $username;
                        // Execute the prepared query.
                        if (! $insert_stmt->execute()) {
                            header('Location: ../error.php?err=Registration failure: UPDATE');
                        }
                        // echo messages for noty notifications plugin
                        echo '<p hidden class="success">You have successfully updated your account.
                        <br>You will be now redirected to the main page..</p>';
                        echo "<meta http-equiv='refresh' content='2; url=index.php'>";
                    }
                }
                else {
                    $error_msg .= '<p hidden class="error">Passwords do not match!</p>';
                }   
            }
            else if (!isset($_POST['change_pw'])) {
                $password = $oldpassword;
                // Insert the new user into the database 
                if ($insert_stmt = $mysqli->prepare("UPDATE users SET username = ?, email = ?, password = ?, phone = ? WHERE id = ?")) {
                    $insert_stmt->bind_param('ssssi', $username, $email, $password, $phone, $_SESSION['user_id']);
                    
                    $_SESSION['username'] = $username;
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        header('Location: ../error.php?err=Registration failure: UPDATE');
                    }
                    echo '<p hidden class="success">You have successfully updated your account.
                    <br>You will be now redirected to the main page..</p>';
                    echo "<meta http-equiv='refresh' content='2; url=index.php'>";
                }   
            }  
        }
        else {
            $error_msg .= '<p hidden class="error">Wrong password. Please try again.</p>';
        }

        echo $error_msg;
    }
    else {
    	echo $error_msg;
    }

}


}
else {
    echo '<p hidden class="error">You are not authorized to view the content of this page. Please login firstly.</p>';
}

?>
<br>
<div class="island" style="width:30px">
    <a href="index.php"><div class="go_home"></div></a>
</div>
<?php 
  // display footer
  include_once 'includes/footer.php' 
?>

</body>
</html>