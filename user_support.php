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
<div align="center" style=" margin-left:500px; float:left; width:200px">
    <img src="images/1.jpg"  class="circular"/><br  />
    <h6>Dr. Ejaz Hussain<br />
    Supervisor<br />
    Ass. Dean IGIS, NUST<br />
	ejaz@igis.nust.edu.pk</h6><br />
    </div>
    <div align="center" style="float:left; width:200px">
    <img src="images/3.jpg"  class="circular"/><br  />
    <h6>Dr. Ali Tahir<br />
    Co-Supervisor<br />
    Ass. Professor IGIS, NUST<br />
	ali.tahir@igis.nust.edu.pk</h6><br />
    </div>
    <div align="center" style=" margin-left:600px; width:200px">
    <img src="images/12.jpg"  class="circular"/><br  />
    <h6>Junaid Abdul Jabbar<br />
    Group Leader<br />
	junaid.abdul.jabbar@gmail.com</h6><br />
    </div>
    <div align="center" style=" float:left; margin-left:130px; width:200px">
    <img src="images/2.jpg" class="circular"/>
    <h6>Ahsan Mukhtar<br />
    Co-Group Leader<br />
   	ahsanmukhtar02@gmail.com</h6>
    </div>
        <div align="center" style="margin-left:30px; float:left; width:200px">
    <img src="images/15.jpg" class="circular"/>
    <h6>Aqib Shehzad<br />
    Group Member<br />
    aqib.shehzad1993@gmail.com</h6>
    </div>
    <div align="center" style="margin-left:30px; float:left; width:200px">
    <img src="images/21.jpg" class="circular"/>
    <h6>Shahzad Bacha<br />
    Group Member<br />
    shahzadbacha_gis@yahoo.com</h6>
    </div>
      <div align="center" style="margin-left:30px; float:left; width:200px">
    <img src="images/29.jpg" class="circular"/>
    <h6>Maria Saeed<br />
    Group Member<br />
    msmalik7293@gmail.com</h6>
    </div>
       <div align="center" style="margin-left:30px; float:left; width:200px">
    <img src="images/30.jpg" class="circular"/>
    <h6>Mashal Maqsood<br />
    Group Member<br />
    mashalmaqsood@yahoo.com</h6>
    </div>
    </td>
  </tr>
  <tr>
    <td height="67" colspan="6" class="footer"><p>&copy; All Rights Reserved<br>Institute of Geographical Information System </p></td>
  </tr>
</table>
</body>
</html>
