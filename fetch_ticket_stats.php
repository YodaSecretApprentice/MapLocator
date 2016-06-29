<?php

	include_once 'includes/functions.php';
	include_once 'includes/db_connect.php';	
	// count how many tickets exist for each category
	$query = "SELECT count(*), categories.category 
			  FROM tickets 
			  JOIN categories ON tickets.category = categories.id 
			  GROUP BY category";

	if ($result = $mysqli->query($query)) {

		// craft a json version of that information
		$prefix = '';
		echo "[\n";
		
		while ($row = $result->fetch_assoc()) {

			echo $prefix . " {\n";
			echo '  "category": "' . $row['category'] . '",' . "\n";
			echo '  "value": ' . $row['count(*)'] . ',' . "\n";
			echo " }";
			$prefix = ",\n";

		}
		echo "\n]";

}