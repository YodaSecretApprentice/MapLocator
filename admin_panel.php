<?php 
include_once 'includes/functions.php';
include_once 'includes/db_connect.php';
	
sec_session_start(); // start the session

?>
<!DOCTYPE HTML SYSTEM>
<html>
<head>

	<?php
	include_once 'includes/header.php';
	?>	
	<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
 	<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js" type="text/javascript"></script>	
	<script src="js/jquery.jeditable.js" type="text/javascript"></script>	
	<script src="http://malsup.github.com/jquery.form.js"></script> 
	
	<script src="amcharts/amcharts.js" type="text/javascript"></script>
	<script src="amcharts/serial.js" type="text/javascript"></script>
	<script src="amcharts/pie.js" type="text/javascript"></script>
	
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/dataTables.css">
	<script src="js/admin_panel.js" type="text/javascript"></script>
</head>
<body>
<div class="notif_container"></div>

					<?php

					$error_msg = "";

					if ((login_check($mysqli)) && (check_admin($mysqli))) {
?>

<div class="container">
	<div class="grid-wrap">    
		<div class="grid-col bp1-col-full bp2-col-full bp3-col-full">
			<div class="island">
				<?php 
					// fetch the users username to print a welcome message
					if (isset($_SESSION['username'])) {
						$query = "SELECT username FROM users WHERE id = " . $_SESSION['user_id'];
						if ($result = $mysqli->query($query)) {
							$row = $result->fetch_assoc();
							echo "Welcome admin, <strong>" . $row['username'] . "</strong>";
						}
					}
				?>
				<br><br>
				<p class="admin_info_message">In this page you may view or edit the currently existent users, open tickets and ticket categories. 
				Only field columns that carry the edit button can be edited. Simply click on any of those fields to edit it.</p> 
				<br>	
			</div>
		</div>
	</div>
	<br>
	<div class="divide-bottom">
		<div class="grid-wrap">    
			<div class="grid-col bp1-col-full bp2-col-full bp3-col-two-thirds">
				<div class="island">

				<?php
						// registered users
						$query = "SELECT * FROM users";
						if ($result = $mysqli->query($query)) {
							echo "<h3>Registed users:</h3>";
							echo "<table id='users_table'> ";
							echo "<thead><tr> 
						    <td>ID</td>
						    <td>Username <div class='edit_icon'></div> </td>
						    <td>Email <div class='edit_icon'></div> </td>
						    <td>Phone <div class='edit_icon'></div> </td>  
						    <td>Role <div class='edit_icon'></div> </td>
						    <td>Delete</td> </tr> </thead> <tbody>";
						    // craft a table with user information
							while ($row = $result->fetch_assoc()) {
								echo '<tr>
									  <td id="id_' . $row['id'] . '"> ' . $row['id'] . '</td>
		  							  <td class="edit_users_table" id="username_' . $row['id'] . '">' .  $row['username'] . '</td>
									  <td class="edit_users_table" id="email_' . $row['id'] . '">' . $row['email'] . '</td>
									  <td class="edit_users_table" id="phone_' . $row['id'] . '">' . $row['phone'] . '</td>
									  <td class="edit_role" id="role_' . $row['id'] . '">' . $row['role'] . '</td> 
									  <td><button type="button" id="button_' . $row['id'] . '" class="deletebut"></button> </td>
									  </tr>';
							}
						    echo "</tbody></table>";
						}
					?>

				</div>
				<br>
				<div class="island">

					<?php
						// open tickets
						$query = "SELECT t.id, t.title, categories.category, t.state, t.description, t.comment 
								  FROM tickets AS t
								  LEFT OUTER JOIN categories ON t.category = categories.id
								  WHERE state = 'open' ORDER BY timestamp DESC";

						if ($result = $mysqli->query($query)) {
						
						    echo '<h3>Open tickets: <br></h3>';

						    echo '<table id="open_tickets_table">';
						    echo '<thead> <tr> <td>Title </td> <td>Category</td> 
						    			  <td>State <div class="edit_icon"></div> </td>
						    			  <td>Description</td>  
						    			  <td>Admin\'s Comment <div class="edit_icon"></div> </td> 
						    			  <td>Images</td> </tr> </thead>
						    			  <tbody>';
						    // craft a table with open tickets information
						    while ($row = $result->fetch_assoc()) {
						    	echo '<tr>
						    		  <td>' . $row['title'] . '</td>
						    	      <td>' .  $row['category'] . '</td>
						    		  <td class="edit_state" id="state_' . $row['id'] . '"> ' . $row['state'] . '</td>
						    		  <td>' . $row['description'] . '</td>
						    		  <td class="edit_open_tickets" id="comment_' . $row['id'] . '">' . $row['comment'] . '</td>';

								$query2 = "SELECT filename FROM images WHERE ticket_id = " . $row['id'];
								if ($result2 = $mysqli->query($query2)) {
						    		echo "<td>";
									while ($row2 = $result2->fetch_assoc()) {
						    			echo "<a class='fancybox' rel='group_" . $row['id'] . "' href='uploaded_images/" . $row2['filename'] . "'>
						    				  <img width='20px' height='20px' src='uploaded_images/" . $row2['filename'] . "'/>
						    				  </a>";
									}
									echo "</td>";
								}
								echo  "</tr>";
						    }
						    echo '</tbody></table>';
						} else {
						    $error_msg .= '<p hidden class="error">Database error</p>';
						}
					?>

				</div>
				<br>
				<div class="island">
					<?php
					// closed tickets
					$query = "SELECT users.username, t.id, t.title, categories.category, t.state, t.solved_by, t.description, t.comment 
							  FROM tickets AS t 
							  LEFT OUTER JOIN categories ON t.category = categories.id							  
							  LEFT OUTER JOIN users ON users.id = t.solved_by 
							  WHERE t.state = 'closed' ORDER BY timestamp DESC";

					if ($result = $mysqli->query($query)) {
					
					    echo '<h3>Closed tickets: <br></h3>';

					    echo '<table id="closed_tickets_table">';
					    echo '<thead> 
					    	  <tr> 
					    	  <td>Title</td> 
					    	  <td>Category</td>
					    	  <td>State</td>  
					    	  <td>Description</td>  
					    	  <td>Admin\'s Comment</td> 
					    	  <td>Images</td>
					    	  <td>Resolved by</td>
					    	  </tr>
					    	  </thead>';
					    echo '<tbody>';
					    // craft a table with closed tickets information
					    while ($row = $result->fetch_assoc()) {
					    	echo '<tr>
					    		  <td>' . $row['title'] . '</td>
					    	      <td>' . $row['category'] . '</td>
					    		  <td>' . $row['state'] . '</td>
					    		  <td>' . $row['description'] . '</td>
					    		  <td>' . $row['comment'] . '</td>';
							
							$query2 = "SELECT filename FROM images WHERE ticket_id = " . $row['id'];
							if ($result2 = $mysqli->query($query2)) {
					    		echo "<td>";
					    		// display an clickable image preview
								while ($row2 = $result2->fetch_assoc()) {
					    			echo "<a class='fancybox' rel='group_" . $row['id'] . "' href='uploaded_images/" . $row2['filename'] . "'>
					    				  <img width='20px' height='20px' src='uploaded_images/" . $row2['filename'] . "'/>
					    				  </a>";
								}
								echo "</td>";
							}

					    	echo '<td>' . $row['username'] . '</td>
					    		  </tr>';
					    }
					    echo '</tbody></table>';
					} else {
					    $error_msg .= '<p hidden class="error">Database error</p>';
					}

					?>
				</div>
				<br>
			</div>
			<div class="grid-col bp1-col-full bp2-col-full bp3-col-one-third">
				<div class="island">
					<h4>Graph of how events are allocated between the different categories</h4>
    				<div id="chartdiv" style="width: 100%; height: 350px;"></div>
				</div>
				<br>
				<div class="island">
					<h3>Ticket categories: <br></h3>
					<?php
						// open tickets
						$query = "SELECT * FROM categories WHERE 1";

						if ($result = $mysqli->query($query)) {
						    echo '<h4>Edit/Delete ticket categories <br></h4>';
						    echo '<table id="ticket_categories_table" border=1>';
						    echo '<thead> <tr> <td>Category <div class="edit_icon"></div></td> <td>Delete</td> 
						    </tr> </thead><tbody>';	
						    // craft a table with ticket categories information					
						    while ($row = $result->fetch_assoc()) {
						    	echo '<tr>
						    		<td class="edit_cat" id="editcat_' . $row['id'] . '">' . $row['category'] . '</td>
 									<td> <button type="button" id="button_' . $row['id'] . '" class="deletecat"></button> </td>
						    		</tr>';
						    }
						    echo '</tbody></table>';
						}
					?>	

					<h4>Add a category:</h4>
					<form id="add_new_category" method="POST" action="edit_category.php">
						<input type="text" name="add_category_field">
						<button type="submit" name="add_category_but">Add</button>
						<div id="category_added"></div>
					</form>

				</div>
			<br>

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