<?php
	include_once '../includes/db_connect.php';

	header("Content-type: text/xml");
    // create an xml RSS page
    $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
    $rssfeed .= '<rss version="2.0">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>Latest tickets opened RSS feed</title>';
    $rssfeed .= '<description>This is a RSS feed for the latest tickets that were opened in our service</description>';
    $rssfeed .= '<language>en-us</language>';

    // fetch the latest 20 tickets from the DB
	$query = "SELECT * FROM tickets WHERE 1 ORDER BY timestamp DESC LIMIT 20";

	if ($result = $mysqli->query($query)) {
        // for each result keep adding xml nodes
		while ($row = $result->fetch_assoc()) {
        	$rssfeed .= '<item>';
        	$rssfeed .= '<title>' . $row['title'] . '</title>';
        	$rssfeed .= '<description>' . $row['description'] . '</description>';
        	$rssfeed .= '<pubDate>' . $row['timestamp'] . '</pubDate>';

        	$rssfeed .= '</item>';
		}
	}

    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';

    echo $rssfeed;
?>