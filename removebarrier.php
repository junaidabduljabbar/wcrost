<?php

#establishing a connection with postgresSQL database

$connection=pg_connect("host=localhost port=5432 dbname=routing user=postgres password=postgres123") or print("cant connect"); 
	#fetching records
		
	$result = pg_query("UPDATE edges_noded SET
  barrier =
  CASE 
  WHEN oneway='1' THEN '1'
  ELSE '0'
  END");
	
	
	if (!$result)  
	{
		echo "Error in query";
	}
	
?>
