<?php

	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start(); // start the session

	if (isset($_FILES['uploadedfile']['error'])) {
		switch ($_FILES['uploadedfile']['error']) {
			// if all went good, upload the image
			case 0:
				$target_path = "uploaded_images/";

				if (isset($_FILES['uploadedfile']['name'])) {
				    $prep_stmt = "SELECT * 
				    		 	  FROM images
				    		 	  WHERE ticket_id = ?";
					$stmt = $mysqli->prepare($prep_stmt);
					// if query was executed successfully..
					if ($stmt) {
						$temp_ = (-1)*$_SESSION['user_id'];
					    $stmt->bind_param('i', $temp_);
					    $stmt->execute();
					    $stmt->store_result();

					    $stmt -> fetch();
				  		$numberofrows = $stmt->num_rows;
					    $stmt -> close();
					    // checking if there are already 4 images -> required limit
						if ($numberofrows < 4) {
							$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

							$filename = $_FILES['uploadedfile']['name'];
							// move the image file to a the final destinarion and update the Database
							if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {	    
							    if ($insert_stmt = $mysqli->prepare("INSERT INTO images (ticket_id, filename) VALUES (?, ?)")) {
							    	$insert_stmt->bind_param('is', $temp_, $filename);
							    	// Execute the prepared query.
							    	if (! $insert_stmt->execute()) {
					   					echo "INSERT: There was an error uploading the file, please try again!";
							    	}
								}
							    echo "The file " .  basename( $_FILES['uploadedfile']['name']) . " has been uploaded";
							}
						} else {
					   		echo "You have already reached the maximum number of images";
						}	
					} else {
					    echo "There was an error uploading the file, please try again!";
					}
				}

				break;

			case 1:
				echo "File size exceeds the maximum filesize allowed.";
				break;
			
			case 2:
				echo "File size exceeds the maximum filesize allowed.";
				break;
			default:
				echo "Something went wrong unfortunately.";
				break;
		}
	}


?>