<?php
	include_once 'includes/functions.php';
	include_once 'includes/db_connect.php';

	// Include database connection and functions here.  See 3.1. 
	sec_session_start(); 
?>
<!DOCTYPE HTML SYSTEM>
<html>
<head>
	

	<?php
	include_once 'includes/header.php';
	?>

   	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<link rel="alternate" href="/feed/" title="My RSS feed" type="application/rss+xml" />
	<script src="js/statistics.js" type="text/javascript"></script>
	<script src="http://malsup.github.com/jquery.form.js"></script> 
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeN2rLMo747VIPEQ1WYa6PmSBkwp5juAw">
    </script>
<script type="text/javascript">
    	$(document).ready(function() {
    		$("#show_login_form").load("login.php #login_form");

    		function delayer(){
			    window.location = "index.php";
			}

    		$('#my_login_form').ajaxForm({
				success : function (response) {
					$('.notif_container').html(response);
					setTimeout(delayer, 1500);
				}
			});

    	});
    </script>
</head>

<body>
<div class="notif_container"></div>
<div class="container">
	<div class="grid-wrap">  
		<div id="share-buttons">
		 
			<!-- Facebook -->
			<a href="http://www.facebook.com/sharer.php?u=http://www.simplesharebuttons.com" target="_blank"><img src="http://www.simplesharebuttons.com/images/somacro/facebook.png" alt="Facebook" /></a>
			<!-- Twitter -->
			<a href="http://twitter.com/share?url=http://www.simplesharebuttons.com&text=Simple Share Buttons" target="_blank"><img src="http://www.simplesharebuttons.com/images/somacro/twitter.png" alt="Twitter" /></a>
			<!-- RSS feed -->
			<a href="/feed/" target="_blank"><img src="images/feed-icon-28x28.png" alt="RSS feed" /></a>			
			<!-- Email -->
			<a href="mailto:209467@supinfo.com"><img src="http://www.simplesharebuttons.com/images/somacro/email.png" alt="Email" /></a>
		</div>
	</div>
	<div class="divide-bottom">
		<div class="grid-wrap">    
			<div class="grid-col bp1-col-full bp2-col-one-quarter">
				<div class="island">
					<?php 
						// display a welcome message to the user
						if (isset($_SESSION['username'])) {
							$query = "SELECT username FROM users WHERE id = " . $_SESSION['user_id'] . " LIMIT 1";
							if ($result = $mysqli->query($query)) {
								$row = $result->fetch_assoc();
								if ($result->num_rows == 1) {
									echo "Welcome, <strong>" . $row['username'] . "</strong>";
								} else {
									echo "Welcome, <strong> visitor </strong>";
								}
							}
						}
					?>
					<?php
					// if user is logged in display a menu to them
						if (login_check($mysqli) == true) {
						?>
						<ul class="user_options">
							<li><a href="logevent.php">Log an event</a></li>
							<li><a href="user_tickets.php">My tickets</a></li>	
							<li><a href="edit_info.php">Edit profile</a></li>	
					<?php
							// if user is also an admin, display the link to admin panel
							if (check_admin($mysqli) == true) { 
							echo '<li><a href="admin_panel.php">Go to admin panel</a></li>';
						}
					?>
								<form action="logout.php" method="post">
									<input type="submit" value="Log out"/>
								</form>
					<?php
						// sto logout na valoume redirect sto index
						} else { 
					?>

					     <form id="my_login_form" action="login.php" method="post">
		                  <table class="login_table">
		                    <tr>
		                      <td>Email:</td>
		                      <td><input type="text" name="email"/><br/></td> 
		                    </tr>
		                    <tr>
		                      <td>Password:</td>
		                      <td><input type="password" name="passwd"/><br/></td> 
		                    </tr>
		                    <tr>
		                      <td><p></p></td>
		                      <td><input type="submit" value="Log in"/> or <a href="register.php">Sign up now</a></td>
		                    </tr>
		                  </table>
		                </form>

					<?php
						}

					?>
						</ul>
				</div>
				<br>

					<?php
					// Display user statistics
					if (login_check($mysqli) == true) {
						echo '<div class="island">';

						echo "<ul class='user_stats'>";
					    if ($stmt = $mysqli->prepare("SELECT * FROM tickets WHERE user_id = ?")) {
					        $stmt->bind_param('i', $_SESSION['user_id']);
					        // Execute the prepared query.
					        $stmt->execute();
					        $stmt->store_result();
					        $stmt->fetch();

					        echo "<li>You have submitted " . $stmt->num_rows . " tickets in total</li>";
					    }

					    if ($stmt = $mysqli->prepare("SELECT * FROM tickets WHERE user_id = ? and state = 'closed'")) {
					        $stmt->bind_param('i', $_SESSION['user_id']);
					        // Execute the prepared query.
					        $stmt->execute();
					        $stmt->store_result();
					        $stmt->fetch();

					        if ($stmt->num_rows == 1) {
					        	$plural = "has";
					        }
					        else {
					        	$plural = "have";
					        }


					        echo "<li>" . $stmt->num_rows . " of them " . $plural . " been resolved</li>";
					        echo "</ul>";
					    }
						echo '</div>';
					}

					?>

				<br>
			</div>
			<div class="grid-col bp1-col-full bp2-col-three-quarters">
				<div class="island">
					<h2>Reports located on the map</h2>
					    <iframe width="100%" height="800px" src="markers.html" name="google_map" allowTransparency="true" scrolling="no" frameborder="0" >
					    </iframe>
				</div>
			</div>
		</div>
	</div>
	<br>
	<span id="statistics_title"><center>Statistics</center></span>
	<br>
	<div class="grid-wrap">   
		<div class="grid-col bp2-col-one-quarter">
			<p></p>
		</div>
		<div class="grid-col bp1-col-one-half bp2-col-one-quarter">
			<div class="grid-wrap half-gutter">    
				<div class="grid-col bp2-col-one-half">
					<center>
						<div id="total_tickets_image"></div> 
						<div id="total_tickets"></div>
						<br>
						<p class="statistics_title">Total tickets</p>
					</center>
				</div>
				<div class="grid-col bp2-col-one-half">
					<center>
						<div id="open_tickets_image"></div> 
						<div id="open_tickets"></div>
						<br>
						<p class="statistics_title">Open tickets</p>
					</center>	
				</div>
			</div>
		</div>
		<div class="grid-col bp1-col-one-half bp2-col-one-quarter">
			<div class="grid-wrap half-gutter">    
				<div class="grid-col bp2-col-one-half">
					<center>
						<div id="resolved_tickets_image"></div> 
						<div id="closed_tickets"></div>
						<br>
						<p class="statistics_title">Resolved tickets</p>
					</center>
				</div>
				<div class="grid-col bp2-col-one-half">
					<center>
						<div id="average_time_image"></div> 
						<div id="avg_ticket_time"></div>
						<br>
						<p class="statistics_title">Average time of resolving an issue</p>
					</center>
				</div>
			</div>
		</div>
		<div class="grid-col bp2-col-one-quarter">
			<p></p>
		</div>
	</div>
</div>
    
<?php 
  // display footer
  include_once 'includes/footer.php' 
?>


</body>
</html>
