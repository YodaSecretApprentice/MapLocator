<?php
	
	/*
		This php file creates an XML which holds every marker
		fetched from the DB along with their information
	*/
	include_once 'includes/functions.php';
	include_once 'includes/db_connect.php';
	
	$xml = new SimpleXMLElement('<markers/>');
	$rows = array();

	// set content type to XML
	header("Content-type: text/xml");

	// Select maximun 20 rows of the markers table
	$query = "SELECT title, latitude, longitude, description, state FROM tickets ORDER BY timestamp DESC LIMIT 20";
	if ($result = $mysqli->query($query)) {
		// Iterate through the rows, adding XML nodes for each
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			// ADD TO XML DOCUMENT NODE

			$child = $xml->addChild('marker', $xml);

			$child->addAttribute('title', $row['title']);
			$child->addAttribute("latitude", $row['latitude']);
			$child->addAttribute("longitude", $row['longitude']);
			$child->addAttribute("description", $row['description']);
			$child->addAttribute("state", $row['state']);

			$i += 1;
			}
		}

		echo $xml->asXML();
?>