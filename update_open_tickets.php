<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';

	sec_session_start(); // start the session

	if (isset($_POST['id'], $_POST['value'])) {

		$id = $_POST['id']; // element id
	    $value = $_POST['value']; 

		$arr = explode('_', $id);
		$type = $arr[0];
		$ticket_id = $arr[1];
		date_default_timezone_set('Europe/Athens');
		$curr_time = date("Y-m-d H:i:s O");
		// query to update an open ticket -> close it
		if ($stmt = $mysqli->prepare("UPDATE tickets SET " . $type . " = ?, solved_by = ?, solved_at = ? WHERE id = ?")) {
			// No need for sanitization since mysqli prepared statement handles this
			$stmt->bind_param('sisi', $value, $_SESSION['user_id'], $curr_time, $ticket_id);
		    $stmt->execute();
		}

		echo $value;
	}

?>