<?php
	include_once 'includes/functions.php';
	include_once 'includes/db_connect.php';
	/* returns a human readable date formatted string */
	function timestampp2date($seconds)
	{
		$days = floor($seconds / 86400);
		if ($days > 1) // 2 days+, we need days to be in plural
		{
		return $days . ' days ' . gmdate('H:i:s', $seconds);
		}
		else if ($days > 0) // 1 day+, day in singular
		{
		return $days . ':' . gmdate('H:i:s', $seconds);
		}

		return gmdate ('H:i:s', $seconds);
	}


	$result = array();
	/*
		The queries below are fetching the required information 
		(open tickets, closed tickets) so that statistics can be 
		displayed to the user
	*/
	if ($stmt = $mysqli->query("SELECT * FROM tickets")) {

		// No need for sanitization since mysqli prepared statement handles this
		$result['total_tickets'] = $stmt->num_rows;
	    // get variables from result.
	    $stmt->close();
	}


	if ($stmt = $mysqli->query("SELECT * FROM tickets WHERE state = 'open'")) {

		// No need for sanitization since mysqli prepared statement handles this
		$result['open_tickets'] = $stmt->num_rows;
	    // get variables from result.
	    $stmt->close();
	}

	if ($stmt = $mysqli->query("SELECT * FROM tickets WHERE state = 'closed'")) {

		// No need for sanitization since mysqli prepared statement handles this
		$result['closed_tickets'] = $stmt->num_rows;

			if ($result['closed_tickets'] > 0) {
			while ($row = $stmt->fetch_array()) {
				$closed_reports[] = $row;
			}
			// iterate through each reports timestamp
			// and calculate their difference
			$total_time = 0;
			$n = 0;

			foreach ($closed_reports as &$rep) {
				$time1 = strtotime($rep['timestamp']);
				$time2 = strtotime($rep['solved_at']);
				$total_time += ($time2 - $time1);
				$n += 1;
			}

			$result['avg_ticket_time'] = timestampp2date($total_time / $n);
		}
	    // get variables from result.
	    $stmt->close();
	} 

    echo json_encode($result);
?>