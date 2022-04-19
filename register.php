<?php 
	require_once("functions.php");
	require_once("db-const.php");
	session_start();
	if (logged_in() == true) {
		redirect_to("profile.php");
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Routing Application</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/register.css" />
<script type="text/javascript" src="js/register.js"></script>
</head>

<body bgcolor="#000000">
<table width="100%" border="0" id="topmenu">
  <tr>
    <td width="497" height="57" class="header"><img src="images/logo1.png" id="logo"></td>
    <td width="70" class="topmenu"><a href="home.html">Home</a></td>
    <td width="70" class="topmenu"><a href="index.html">Get Started</a></td>
    <td width="70" class="topmenu"><a href="documentation.php">Documentation</a></td>
    <td width="70" class="topmenu"><a href="support.html">Support</a></td>
    <td width="70" class="topmenu"><a href="login.php">Login</a></td>
  </tr>
  <tr>
    <td colspan="6" class="my_body2">
    <section class="login">
    	<div class="title">SIGN UP</div>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" onsubmit="return myFunction()" >
        <input type="text" name="username" required title="Username required" placeholder="Username">
        <input id="pass1" type="password" name="password" required title="Password required" placeholder="Password">
        <input id="pass2" type="password" name="password" required title="Password required" placeholder="Verify Password">
        <input type="text" name="first_name" required title="Username required" placeholder="First Name">
        <input type="text" name="last_name" required title="Username required" placeholder="Last Name">
        <input type="email" name="email" required title="Email required" placeholder="Email"><br /><br />
        <input type="submit" name="submit" value="SIGN UP">
        </form>
        <p align="center">Already a member?</p>      
        <a href="login.php" type="button">LOGIN</a>
    </section>
<!-- The HTML registration form <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
	Username: <input type="text" name="username" /><br />
	Password: <input type="password" name="password" /><br />
	First name: <input type="text" name="first_name" /><br />
	Last name: <input type="text" name="last_name" /><br />
	Email: <input type="type" name="email" /><br />

	<input type="submit" name="submit" value="Register" />
	<a href="login.php">I already have an account...</a>
</form>-->
<?php
if (isset($_POST['submit'])) {
## connect mysql server
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
## query database
	# prepare data for insertion
	$username	= $_POST['username'];
	$password	= $_POST['password'];
	$first_name	= $_POST['first_name'];
	$last_name	= $_POST['last_name'];
	$email		= $_POST['email'];
	//password hashing
	$salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
	$hash = crypt($password,$salt) ; 

	# check if username and email exist else insert
	// u = username, e = emai, ue = both username and email already exists
	$exists = "";
	$result = $mysqli->query("SELECT username from users WHERE username = '{$username}' LIMIT 1");
	if ($result->num_rows == 1) {
		$exists .= "u";
	}	
	$result = $mysqli->query("SELECT email from users WHERE email = '{$email}' LIMIT 1");
	if ($result->num_rows == 1) {
		$exists .= "e";
	}

	if ($exists == "u") echo "<p><b>Error:</b> Username already exists!</p>";
	else if ($exists == "e") echo "<p><b>Error:</b> Email already exists!</p>";
	else if ($exists == "ue") echo "<p><b>Error:</b> Username and Email already exists!</p>";
	else {
		# insert data into mysql database
		$sql = "INSERT  INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`) 
				VALUES (NULL, '{$username}', '{$hash}', '{$first_name}', '{$last_name}', '{$email}')";

		if ($mysqli->query($sql)) {
			redirect_to("login.php?msg=Registred successfully");
		} else {
			echo "<p>MySQL error no {$mysqli->errno} : {$mysqli->error}</p>";
			exit();
		}
	}
}
?>	
</td>
  </tr>
  <tr>
    <td height="67" colspan="6" class="footer"><p>&copy; All Rights Reserved<br>Institute of Geographical Information System </p></td>
  </tr>
</table>
</body>
</html>