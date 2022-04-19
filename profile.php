<?php 
require_once("functions.php");
require_once("db-const.php");
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Routing Application</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/register.css" />
</head>
<body bgcolor="#000000">
<table width="100%" border="0" id="topmenu">
  <tr>
    <td width="497" height="57" class="header"><img src="images/logo1.png" id="logo"></td>
    <td width="70" class="topmenu"><a href="user_home.php">Home</a></td>
    <td width="70" class="topmenu"><a href="index.php">Get Started</a></td>
    <td width="70" class="topmenu"><a href="user_documentation.php">Documentation</a></td>
    <td width="70" class="topmenu"><a href="user_support.php">Support</a></td>
    <td width="70" class="topmenu"><a href="login.php">Login</a></td>
  </tr>
  <tr>
    <td colspan="6" class="my_body2">
    <div>
    <section class="profile_container">
<?php
if (logged_in() == false) {
	redirect_to("login.php");
} else {
	if (isset($_GET['id']) && $_GET['id'] != "") {
		$id = $_GET['id'];
	} else {
		$id = $_SESSION['user_id'];
	}
	
	## connect mysql server
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		# check connection
		if ($mysqli->connect_errno) {
			echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
			exit();
		}
	## query database
		# fetch data from mysql database
		$sql = "SELECT * FROM users WHERE id = {$id} LIMIT 1";

		if ($result = $mysqli->query($sql)) {
			$user = $result->fetch_array();
		} else {
			echo "<p>MySQL error no {$mysqli->errno} : {$mysqli->error}</p>";
			exit();
		}
		
		if ($result->num_rows == 1) {
			# calculating online status
			if (time() - $user['status'] <= (30)) { // 300 seconds = 5 minutes timeout
				$status = "Online";
			} else {
				$status = "Offline";
			}
			
			# echo the user profile data
			echo "<p>User ID: {$user['id']}</p>";
			echo "<p>Username: {$user['username']}</p>";
			//echo "<p>Status: {$status}</p>";						
		} else { // 0 = invalid user id
			echo "<p><b>Error:</b> Invalid user ID.</p>";
		}
}

// showing the login & register or logout link
if (logged_in() == true) {
	echo '<a id="signinhere" href="logout.php">Log Out</a>';
} else {
	echo '<a href="login.php">Login</a> | <a href="register.php">Register</a>';
}
?>

</section>
    </div>
    </td>
  </tr>
  <tr>
    <td height="67" colspan="6" class="footer"><p>&copy; All Rights Reserved<br>Institute of Geographical Information System </p></td>
  </tr>
</table>
</body>
</html>