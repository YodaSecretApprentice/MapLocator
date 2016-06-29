<?php
	include_once 'config.php';   // As functions.php is not included
	// mysql object
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

	// connect to DB
	if ($mysqli->connect_errno) {
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	// change character set to utf8 
	if (!$mysqli->set_charset("utf8")) {
	    printf("Error loading character set utf8: %s\n", $mysqli->error);
	}

?>
