<?php

	include_once 'includes/functions.php';
	include_once 'includes/db_connect.php';

	sec_session_start(); // start the session

	// count how many tickets exist for each category
	$query = "SELECT count(*), categories.category 
			  FROM tickets 
			  JOIN categories ON tickets.category = categories.id 
			  WHERE user_id = ? GROUP BY category ";

	if ($result = $mysqli->prepare($query)) {
		
		$result->bind_param('i', $_SESSION['user_id']);
	    $result->execute();
	    $result->store_result();
	    $result->bind_result($count, $category);

		$prefix = '';
		echo "[\n";
		// craft json with that information
		while ($row = $result->fetch()) {
			echo $prefix . " {\n";
			echo '  "category": "' . $category . '",' . "\n";
			echo '  "value": ' . $count . ',' . "\n";
			echo " }";
			$prefix = ",\n";

		}
		echo "\n]";

	}