<?php	
	session_start(); 	
	$server = 'localhost';
	$db_username = 'root';
	$db_password = '';
	$database = 'register';
	
	if(!mysql_connect($server,$db_username,$db_password))
	{
		die('Could not connect to mySQL Database');
		}
	if(!mysql_select_db($database))
	{
		die('Could not connect to Database');
		}
?>
