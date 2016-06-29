<?php
include_once 'includes/db_connect.php';

// case 1 -> edit users information with ajax
if (isset($_GET['type']) && $_GET['type'] == 'ajax_edit') {

	if (isset($_POST['id'], $_POST['value'])) {

		$id = $_POST['id'];
	    $value = $_POST['value']; // The hashed password.

	    // split the $id to 2 strings: 
	    // the type is the tables column
	    // and the user_id is that users ID
		$arr = explode('_', $id);
		$type = $arr[0];
		$user_id = $arr[1];

		if ($stmt = $mysqli->prepare("UPDATE users SET " . $type . " = ? WHERE id = ?")) {
			// No need for sanitization since mysqli prepared statement handles this
			$stmt->bind_param('si', $value, $user_id);
		    $stmt->execute();
		}

		echo $value;
	}
}
// delete a user from the Database with ajax
else if (isset($_GET['type']) && $_GET['type'] == 'ajax_del') {

	if (isset($_GET['delete'])) {
		$user_id = explode("_", $_GET['delete']);
		$user_id = $user_id[1];

		if ($stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?")) {
			// No need for sanitization since mysqli prepared statement handles this
			$stmt->bind_param('i', $user_id);
		    $stmt->execute();
		}
	}
}


?>