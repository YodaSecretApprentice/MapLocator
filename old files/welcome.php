<?php
include_once 'includes/functions.php';
include_once 'includes/db_connect.php';

// Include database connection and functions here.  See 3.1. 
sec_session_start(); 
if (login_check($mysqli) == true) {
        // Add your protected page content here!
?>

<aside>
	<center>
	<img src="http://saam.mech.upatras.gr/zefyros/images/logos/upatras.jpeg" alt="upatras university" width="180" height="180">
	<form action="logevent.php" method="post">
	<br>
	<input type="submit" value="Log an event"/><br />
	</form>	
	<br>
	<form action="logout.php" method="post">
	<br>
	<input type="submit" value="Log out"/><br />
	</form>
	</center>
</aside>

<div class="statistics">
	<h3><i>Statistics:</i></h3>
	Total reports on the system: 
	<span id="total_reports"></span> <br>
	Total number of open tickets:
	<span id="open_reports"></span> <br>
	Total number of resolved tickets:
	<span id="closed_reports"></span> <br>
	Average time of resolving an issue:
	<span id="avg_report_time"></span> <br>

</div>



<a href="statistics.php">Stats!</a>
<br>
<a href="edit_info.php">Edit profile!</a>
<br>
<a href="user_tickets.php">My tickets</a>
<br>

<?php 
	if (check_admin($mysqli) == true) { 
		echo '<a href="admin_panel.php">Go to admin panel</a>';
	}

} else { 
        echo '<a href="login.php"><button type="button">Log in</button></a>';
}

?>

<head>
   <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

	<script src="js/statistics.js"></script>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
    </script>

</head>

<body>

<div class="container">

<section>
	<h1>Welcome 
	<?php 
		if (isset($_SESSION['username'])) 
			echo $_SESSION['username'];
		else
			echo "visitor";
	?> 
	</h1>

	<h6>Your cookie is: <?php if (isset($_SESSION['login_string'])) echo $_SESSION['login_string'];?></h6>
</section>

<br><br>Current location: 
<div id="map-canvas"/> 
    <iframe width="100%" height="100%" src="markers.html" name="google_map" allowTransparency="true" scrolling="no" frameborder="0" >
    </iframe>
</div> 
</div>


</body>