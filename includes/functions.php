<?php

include_once 'includes/config.php';
include_once 'includes/db_connect.php';

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}


function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 

    if ($stmt = $mysqli->prepare("SELECT id, username, password 
        FROM users WHERE email = ? LIMIT 1")) {

    	// No need for sanitization since mysqli prepared statement handles this
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 		
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password);
        $stmt->fetch();

        // hash the password
 		$password = hash('sha512', $password);

        if ($stmt->num_rows == 1) {

            // If the user exists we check if the account is locked
            // from too many login attempts 

 			// gia na min mpei pote edo mexri na to kano implement
            if (checkbrute($user_id, $mysqli) == true) {
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
 				// return true;

                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\s\-]+/", "", $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);

                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
}


function register_user($email, $password, $username, $mysqli ) {
	

}

/* Checks if user is logged in */
function login_check($mysqli) {

    // Check if all session variables are set
    if (isset($_SESSION['user_id'], 
              $_SESSION['username'], 
              $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM users 
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

/* Checks if user has role == admin */
function check_admin($mysqli) {
    if ($stmt = $mysqli->prepare("SELECT role
                                    FROM users 
                                   WHERE id = ?")) {

        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();   // Execute the prepared query.
        $stmt->store_result();

        $stmt->bind_result($role);
        $stmt->fetch();

        if ($role == "admin") {
            return true;
        }
        else {
            return false;
        }
    }
}

// TO DO: Implement it from http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
function checkbrute($user_id, $mysqli) {

	return false;
}

?>