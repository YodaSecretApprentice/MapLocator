<?php

	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start(); // start the session

?>
<!DOCTYPE HTML SYSTEM>
<html>
<head>
<head>
	<title>Event log service</title>

	<?php
		include_once 'includes/header.php';
	?>

	<link rel="stylesheet" type="text/css" href="css/dataTables.css">
	<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script src="amcharts/amcharts.js" type="text/javascript"></script>
	<script src="amcharts/serial.js" type="text/javascript"></script>
	<script src="amcharts/pie.js" type="text/javascript"></script>
	<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js" type="text/javascript"></script>	
 	<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript">
		// create a dataTable on the already existend HTML table with id: show_users_tickets
		$(document).ready(function() {
			$('#show_users_tickets').dataTable( {
				"lengthMenu": [[20, 50, -1], [20, 50, "All"]],
				"ordering":  true,
				"columnDefs": [ { "width": "18%", "targets": 4 }]
			});

			$(".fancybox").fancybox();

			AmCharts.loadJSON = function(url) {
				// create the request
				if (window.XMLHttpRequest) {
					// IE7+, Firefox, Chrome, Opera, Safari
					var request = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					var request = new ActiveXObject('Microsoft.XMLHTTP');
				}
				// load it
				// the last "false" parameter ensures that our code will wait before the
				// data is loaded
				request.open('GET', url, false);
				request.send();

				// parse and return the output
				return eval(request.responseText);
			};

			var chart;
			// create chart
			AmCharts.ready(function() {

			  // load the data
			  var chartData = AmCharts.loadJSON('fetch_user_stats.php');
			  // SERIAL CHART    
			  chart = new AmCharts.AmPieChart();
			  chart.dataProvider = chartData;
			  chart.valueField = "value";
			  chart.titleField = "category";
			  chart.innerRadius = "50%";
			  chart.radius = "30%";
			  chart.colors = ["#FF0F00", "#FF6600", "#FF9E01", "#FCD202", "#F8FF01", "#B0DE09"];
			  // WRITE result to the proper DIV
			  chart.write("chartdiv");
			});

		});
	</script>
</head>
<body>
<div class="notif_container"></div>

			<?php

				$error_msg = "";
				if(login_check($mysqli) == true) {
			?>


<div class="container">
	<div class="grid-wrap">    
		<div class="grid-col bp1-col-full bp2-col-full bp3-col-two-thirds">
			<div class="island">
				<h3>Your tickets: </h3><br>

<?php
					// fetch every ticket and its information that this user has opened
					$prep_stmt = "SELECT t.id, t.title, categories.category, t.state, t.description, t.comment, t.timestamp 
								  FROM tickets AS t
								  LEFT OUTER JOIN categories ON t.category = categories.id
								  WHERE user_id = ? 
								  ORDER BY timestamp DESC";
					$stmt = $mysqli->prepare($prep_stmt);

					if ($stmt) {
					    $stmt->bind_param('i', $_SESSION['user_id']);
					    $stmt->execute();

					    $stmt->store_result();
					    $stmt->bind_result($id, $title, $category, $state, $description, $comment, $timestamp);

					    echo "<table id='show_users_tickets'>";
					    echo "<thead>
					    	  <tr> 
					    	  <td>Title</td>
					    	  <td>Category</td>
					    	  <td>State</td>
					    	  <td>Description</td>
					    	  <td>Date</td>
					    	  <td>Admin's Comment</td> 
					    	  <td>Images</td>
					          </tr>
					          </thead>";
					    echo "<tbody>";
					    // craft the user tickets table
					    while($stmt->fetch()) {
					    	echo '<tr>
					    		  <td>' . $title . '</td>
					    		  <td>' . $category . '</td>
					    		  <td>' . $state . '</td>
					    		  <td>' . $description . '</td>
					    		  <td>' . $timestamp . '</td>
					    		  <td>' . $comment . '</td>';

					    	$query2 = "SELECT filename FROM images WHERE ticket_id = " . $id;
							if ($result2 = $mysqli->query($query2)) {
					    		echo "<td>";
								while ($row2 = $result2->fetch_assoc()) {
					    			echo "<a class='fancybox' rel='group_" . $id . "' href='uploaded_images/" . $row2['filename'] . "'>
					    				  <img width='20px' height='20px' src='uploaded_images/" . $row2['filename'] . "'/>
					    				  </a>";
								}
								echo "</td>";
							}
				    		echo '</tr>';
					    }
					    echo "</tbody> </table>";
					} else {
					    $error_msg .= '<p hidden class="error">Database error</p>';
					}
					?>

			</div>
			<br>
		</div>

		<div class="grid-col bp1-col-full bp2-col-full bp3-col-one-third">
			<div class="island">
				<h4>Graph of how your reports are allocated between the different categories</h4>
				<div id="chartdiv" style="width: 100%; height: 350px;"></div>
			</div>
		</div>

	</div>

					<?php

				}
				else {
				    $error_msg .= '<p hidden class="error">You are not authorized to view the content of this page. Please login firstly.</p>';
				    echo $error_msg;
				}

				?>

<br>

<div class="island" style="width:30px">
    <a href="index.php"><div class="go_home"></div></a>
</div>


</div>




<?php 
  // display footer
  include_once 'includes/footer.php' 
?>

</body>
</html>