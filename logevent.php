<?php
	include_once 'includes/config.php';
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';

	sec_session_start(); // start the session
?>

<!DOCTYPE HTML SYSTEM>
<html>
<head>

  	<?php
  		include_once 'includes/header.php';
  	?>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <script src="http://malsup.github.com/jquery.form.js"></script> 
	<script src="js/statistics.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBeN2rLMo747VIPEQ1WYa6PmSBkwp5juAw"></script>

    <script> 
    // wait for the DOM to be loaded 
		$(document).ready(function() { 
		// bind 'myForm' and provide a simple callback function 
			$('#image_upload_form').ajaxForm({
				success : function (response) {
					document.getElementById('upload_status').innerHTML = 
					document.getElementById('upload_status').innerHTML + '<br>' + response;
				}
			});
		}); 

    </script>

	<script type="text/javascript">
		/* 
			This function updates the current cursor position
			on the HTML
		*/
		function updateMarkerPosition(latLng) {
			document.getElementById('latitude').value = latLng.lat();
			document.getElementById('longitude').value = latLng.lng();
		}
		// map initialize function
		function initialize() {

			var myLatlng = new google.maps.LatLng(43.604363, 1.442951);
			console.log(myLatlng['A']);
			var mapOptions = {
				zoom: 15,
				center: myLatlng
			};
			// change the way that the map looks like
			var styles = [ 
				{
					stylers: [
						{ hue: "#2F3238" },
						{ saturation: 0 }
					]
				}, {
					featureType: "road",
					elementType: "geometry",
					stylers: [
						{ lightness: 60 },
				 		{ visibility: "simplified" }
				 	]
				}, {
					featureType: "road",
					elementType: "labels",
					stylers: [
				 		{ visibility: "on" }
				 	]
				}
			];

	      	var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});

			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var contentString = '<div id="content">' + '</div>';

		  	updateMarkerPosition(myLatlng);

			var infowindow = new google.maps.InfoWindow({
			  content: contentString
			});

			var marker = new google.maps.Marker({
			  position: myLatlng,
			  // adds marker to the map
			  map: map,
			  title: 'New Marker',
			  // marker should be draggable
			  draggable: true
			});
			// if the geolocation button is clicked
			// then aquire the current users position and update the
			// markers coordinates
			$('#geoloc_but').on('click', function(){ 
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(pos) {
						$('#latitude').val(pos.coords.latitude);
						$('#longitude').val(pos.coords.longitude);
						
						new_lat = parseFloat( $('#latitude').val() );
						new_lng = parseFloat( $('#longitude').val() );
						marker.setPosition(new google.maps.LatLng(new_lat, new_lng));
					});
				}
				else {
					x.innerHTML="Geolocation could not be retrieved.";
				}
			});

			// update markers position from the input textfield
			$('#latitude, #longitude').on('input', function() {
				new_lat = parseFloat( $('#latitude').val() );
				new_lng = parseFloat( $('#longitude').val() );
				marker.setPosition(new google.maps.LatLng(new_lat, new_lng));
			});

		  	google.maps.event.addListener(marker, 'click', function() {
		    	infowindow.open(map, marker);
		    });
			
			google.maps.event.addListener(marker, 'drag', function() {
		    	updateMarkerPosition(marker.getPosition());
		    });

			map.mapTypes.set('map_style', styledMap);
        	map.setMapTypeId('map_style');
		}

		google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</head>
<body>

<div class="notif_container"></div>

<?php
// $doc = domxml_new_doc("1.0");
// if user is logged in ALLOW them to log an event | open ticket
if(login_check($mysqli) == true) {

	//if button submit:
	if (isset($_POST['log_event'])) {
		if (isset($_POST['category']) && (!empty($_POST['title']))) {
			$category = preg_replace('/[^-a-zA-Z0-9_ ]/', '', $_POST['category']);
			$arr = explode("_", $category);
			$category_id = $arr[1];

			// EDO AUTA THELOUN ALLO SANITIZE GT PREPEI NA KANOUME ALLO TO COMMA
			// i apla kaneis allow tin teleia sto regex auto?
			$lat = preg_replace('/[^-a-zA-Z0-9._]/', '', $_POST['latitude']);
			$lng = preg_replace('/[^-a-zA-Z0-9._]/', '', $_POST['longitude']);

			// SANITIZE THIS MAYBE?
			$desc = preg_replace("/[^\p{Greek}a-zA-Z0-9\s]+/u", '', $_POST['desc']);
			$title = preg_replace("/[^\p{Greek}a-zA-Z0-9\s]+/u", '', $_POST['title']);

			/*
				The queries below insert a new ticket in the tickets table
				and set the images' ids (if images exist) to the proper one
				after it is made sure that the user successfully submitted the ticket
			*/
		    // Insert the new event into the database 
		    if ($insert_stmt = $mysqli->prepare("INSERT INTO tickets (user_id, title, category, state, latitude, longitude, description) 
		    									 VALUES (?, ?, ?, 1, ?, ?, ?)")) {
		        $insert_stmt->bind_param('isidds', $_SESSION['user_id'], $title, $category_id, $lat, $lng, $desc);
		        // Execute the prepared query.
		        if (! $insert_stmt->execute()) {
	   				echo "<p hidden class='error'>INSERT: There was an error uploading the file, please try again!</p>";
		        }

		    }
			if ($stmt = $mysqli->prepare("SELECT id FROM tickets WHERE user_id = ? ORDER BY timestamp DESC LIMIT 1")) {
				// No need for sanitization since mysqli prepared statement handles this
				$stmt->bind_param('i', $_SESSION['user_id']);
			    $stmt->execute();
			    $stmt->store_result();
		    	$stmt->bind_result($ticket_id);

		    	$stmt->fetch();
			}

			if ($stmt = $mysqli->prepare("UPDATE images SET ticket_id = ? WHERE ticket_id = ?")) {
				$temp_ = (-1)*$_SESSION['user_id'];
				// No need for sanitization since mysqli prepared statement handles this
				$stmt->bind_param('ii', $ticket_id, $temp_);
			    $stmt->execute();
			}
		    echo "<p hidden class='success'>Your ticket has been submitted successfully</p>";
		}
		else {
			echo "<p hidden class='error'>Please fill all the information.</p>";	
		}

	}
	else if (isset($_POST['cancel_ticket'])) {
		if ($stmt = $mysqli->prepare("DELETE FROM images WHERE ticket_id = ?")) {
			$temp_ = (-1)*$_SESSION['user_id'];
			// No need for sanitization since mysqli prepared statement handles this
			$stmt->bind_param('i', $temp_);
		    $stmt->execute();
		}
		echo "<p hidden class='error'>Your ticket submission has been canceled.</p>";
	}
?>


<div class="container">
		<div class="grid-wrap">    
			<div class="grid-col bp1-col-full bp2-col-full bp3-col-one-third">
				<div class="island">
					<form action="" method="POST" id="eventform">
						Title: 
						<input type="text" style="width: 70%" name="title"></input>
						
						<br>Category:<br>
						<select name="category">
						    <option selected disabled>Choose one</option>

							<?php
								$query = "SELECT * FROM categories WHERE 1";

								if ($result = $mysqli->query($query)) {
								    while ($row = $result->fetch_assoc()) {
								    	echo '<option value="category_'. $row['id'] . '">' . $row['category'] . '</option>';
								    }
								}
							?>
						</select>
						
						<br>Coordinates:<br>
						<input id="latitude" style="width:30%" type="text" name="latitude" size="18">
						<input id="longitude" style="width:30%" type="text" name="longitude" size="18">
						
						<button type="button" id="geoloc_but">Get my geolocation</button>

						<br>Description:<br>
						<textarea class="info_textarea" name="desc" rows="8"></textarea>
						<br>
						
					</form>

					<form id="image_upload_form" enctype="multipart/form-data" action="upload_image.php" method="POST">
						<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
						Choose a file to upload: (Max file size 2MB)
						<input name="uploadedfile" type="file"><br>
						<input type="submit" id="Upload File" value="Upload Image">
					</form>
					<span id="upload_status"></span>
					<br>

					<input type="submit" form="eventform" name="log_event" value="Log event">
					<input type="submit" form="eventform" name="cancel_ticket" value="Cancel">
				</div>

				<br>

			</div>

			<div class="grid-col bp1-col-full bp2-col-full bp3-col-two-thirds">
				<div class="island" >
					<div id="map-canvas"></div> 
				</div>
			</div>

	</div>
</div>

<?php 
	}
	else { 
        echo '<p hidden class="error">You are not authorized to access this page, please login.<p>';
}

?>
<div class="island" style="width:30px">
	<a href="index.php"><div class="go_home"></div></a>
</div>
<?php 
  // display footer
  include_once 'includes/footer.php' 
?>

</body>
</html>
