<?php

	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start(); // start the session

	/*
		This php file receives 3 kind of requests.
		Each request is different by the variable
		it makes the POST/GET request with.
		So there are 3 cases
	*/

	// add a new category case
 	if (isset($_POST['add_category_field'])) {

	    if ($insert_stmt = $mysqli->prepare("INSERT INTO categories (category) VALUES (?)")) {
	        $insert_stmt->bind_param('s', $_POST['add_category_field']);
	        // Execute the prepared query.
	        if (! $insert_stmt->execute()) {
   				echo "INSERT: There was an error uploading the file, please try again!";
	        }
	    }

	    if ($stmt = $mysqli->prepare("SELECT id FROM categories WHERE category = ?")) {
	        $stmt->bind_param('s', $_POST['add_category_field']);
	        // Execute the prepared query.
	        if (! $stmt->execute()) {
   				echo "There was an error uploading the file, please try again!";
	        }

	        $stmt->store_result();
	        $stmt->bind_result($cat_id);
	        $stmt->fetch();
	    }

	    $result = $_POST['add_category_field'];
	    $result .= "[%delimiter%]" . $cat_id;

 		echo $result;

 	}
 	// delete a category with ajax
 	else if (isset($_GET['type']) && $_GET['type'] == "ajax_del") {

 		if (isset($_GET['delete'])) {
			$cat_id = explode("_", $_GET['delete']);
			$cat_id = $cat_id[1];

			if ($stmt = $mysqli->prepare("DELETE FROM categories WHERE id = ?")) {
				// No need for sanitization since mysqli prepared statement handles this
				$stmt->bind_param('i', $cat_id);
			    $stmt->execute();
			}
 		}
 	}
 	// change a category's title with ajax
 	else if (isset($_POST['id'], $_POST['value'])) {

		$id = $_POST['id']; // element id
	    $value = $_POST['value']; 

		$arr = explode('_', $id);
		$cat_id = $arr[1];

		if ($stmt = $mysqli->prepare("UPDATE categories SET category = ? WHERE id = ?")) {
			// No need for sanitization since mysqli prepared statement handles this
			$stmt->bind_param('si', $value, $cat_id);
		    $stmt->execute();
		}

		echo $value;
 	}

