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
<link rel="stylesheet" type="text/css" href="css/login.css" />
</head>

<body bgcolor="#000000">
<table width="100%" border="0" id="topmenu">
  <tr>
    <td width="497" height="57" class="header"><img src="images/logo1.png" id="logo"></td>
    <td width="70" class="topmenu"><a href="home.html">Home</a></td>
    <td width="70" class="topmenu"><a href="documentation.html">Documentation</a></td>
    <td width="70" class="topmenu"><a href="support.html">Support</a></td>
    <td width="70" class="topmenu"><a href="login.php">Login</a></td>
  </tr>
  <tr>
    <td colspan="6" class="my_body2">
    <section class="login">
    	<div class="title">LOG IN</div>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <input type="text" name="username" required title="Username required" placeholder="Username">
        <input type="password" name="password" required title="Password required" placeholder="Password"><br /><br />
        <input type="submit" name="submit" value="LOGIN">
        </form>
        <p align="center">Not a member yet?</p>
        <a href="register.php">SIGN UP</a>
    </section>
    
<!-- The HTML login form 	<form action="" method="post">
		Username: <input type="text" name="username" /><br />
		Password: <input type="password" name="password" /><br />
		Remember me: <input type="checkbox" name="remember" /><br />

		<input type="submit" name="submit" value="Login" />
		<a href="forgot.php">Forgot Password?</a>
		<a href="register.php">Register</a>
	</form>-->

<?php
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	//hashing password
	$salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
	$hash = crypt($password,$salt) ; 
	// processing remember me option and setting cookie with long expiry date
	if (isset($_POST['remember'])) {	
		session_set_cookie_params('604800'); //one week (value in seconds)
		session_regenerate_id(true);
	} 

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	
	$sql = "SELECT * from users WHERE username LIKE '{$username}' AND password LIKE '{$hash}' LIMIT 1";
	$result = $mysqli->query($sql);
	
	if ($result->num_rows != 1) {
		$error = "Invalid username/password combination";
		echo "<script type='text/javascript'>alert('$error');</script>";
	} else {
		// Authenticated, set session variables
		$user = $result->fetch_array();
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['username'] = $user['username'];
		
		// update status to online
		$timestamp = time();
		$sql = "UPDATE users SET status={$timestamp} WHERE id={$_SESSION['user_id']}";
		$result = $mysqli->query($sql);
		
		redirect_to("profile.php?id={$_SESSION['user_id']}");
		// do stuffs
	}
}

if(isset($_GET['msg'])) {
	echo "<p style='color:red;'>".$_GET['msg']."</p>";
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