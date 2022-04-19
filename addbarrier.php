<?php
$store=$_POST['x'];
#establishing a connection with postgresSQL database

$connection=pg_connect("host=localhost port=5432 dbname=routing user=postgres password=postgres123") or print("cant connect"); 
	#fetching records
		
	$result = pg_query("UPDATE edges_noded set barrier='yes' where source=$store or target=$store");
	
	
	if (!$result)  
	{
		echo "Error in query";
	}
	
?>
