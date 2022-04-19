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
    <div align="center" id="doc">
    <h3>Abstract</h3>
    <p>The title of the project is “a web-based 3D customized routing engine design and development using open source technologies”. It is a complete and functional routing application. The name of this product is 3D Routing. The basic purpose of this application is to provide routing to the user on both 2D and 3D views of the earth surface and to shift from the existing proprietary routing software's. Proprietary routing software's are expensive, difficult to customize and compromise data security application. This application is merely designed for Pakistan Army (C4I). We were given five scenarios from the Army and on the basis of those scenarios this application is designed. It is completely open-source to ensure that the application is highly secure. 3D Routing Application is offline in case of 2D visualization of the earth surface. This application offers the user to acquire the route according to the criteria set by him/she. User marks the start and the target point.  It’s upon the user that what type of route he/she wants. There are currently two criteria of routes in our application shortest and fastest routes with options to add the barriers in different cases. It also enables the user to get information about the choke points and road classifications. This application along with shortest routes and fastest route give the information of driving directions and other route information. Other functionality is that user can mark the points of interest he wants to and he can also take the snapshots of the route that is displayed over the open street map. Other functionality is automatic snapping. User has the option of viewing same route over 3D globe to have the idea at which point of the route which level of terrain will be faced. User can toggle the globe and can see the orientation by different angles and view by using the controls present. A user-friendly interface is designed in this project. User can Sign Up and sign in for this application. This application is not designed for general public.</p>
    </div>
    </td>
  </tr>
  <tr>
    <td height="67" colspan="6" class="footer"><p>&copy; All Rights Reserved<br>Institute of Geographical Information System </p></td>
  </tr>
</table>
</body>
</html>
