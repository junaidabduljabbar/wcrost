<?php 
	require_once("functions.php");
	require_once("db-const.php");
	session_start();
	if (logged_in() == true) {
		goto come;
			}
	else {
		redirect_to("login.php");
	}
	come:
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Routing Application</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />

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
    <td colspan="6" class="my_body" background="images/black-black-circles-free-icons-1600x1200px-hd-background.jpg">
    <div id="slider">
<!--	<figure>
	<img src="images/3-ness-c2.jpg" alt="">
	<img src="images/3-photo1B.jpg" alt="">
	<img src="images/C4iTE.jpg" alt="">
	<img src="images/Displays.jpg" alt="">
	<img src="images/SCSfrontpic.gif" alt="">
	</figure>-->
	</div>
    <div id="intro"><h3>Introduction</h3>
    With the advancement and development in the field of  <abbr title="Geographical Information System">GIS</abbr>, it is now the go-to technology for effectively dealing with real time and dynamically changing spatial data. Among many other applications, GIS is widely used for optimization of daily fleet movements.  With the world wide increase in travelling expense, security issues and traffic congestion route selection has become a critical step in the initialization of any project and has significant impact in terms of cost, man power and service quality. Optimal route determination involves consideration of several factors such as environmental, sociological, economical and safety. <br />
    <h3>Aims & Objectives</h3>
The aims and objectives of this project are as follows:<br />
•	To increase use of open source technologies inside Army<br />
•	To make open source optimal routing system<br />
•	To customize the system according to army requirements<br />
•	To visualize the route in 3D<br />
</div>
    </td>
  </tr>
  <tr>
    <td height="67" colspan="6" class="footer"><p>&copy; All Rights Reserved<br>Institute of Geographical Information System </p></td>
  </tr>
</table>
</body>
</html>
