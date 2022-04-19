<?php
$store=$_POST['x'];
$store2=$_POST['y'];

#establishing a connection with postgresSQL database

$connection=pg_connect("host=localhost port=5432 dbname=routing user=postgres password=postgres123") or print("cant connect"); 
	#fetching records
		
	$result = pg_query("DROP view dist cascade;");
	$result2 = pg_query("DROP view time_val cascade;");
	
	$crete=pg_query("CREATE VIEW dist AS
SELECT
  min(r.seq) AS seq,
  e.old_id AS id,
  e.name,
  e.type,
  e.oneway,
  sum(e.time) AS time,
  sum(e.distance) AS distance,
  ST_Collect(e.the_geom) AS geom
FROM
  pgr_dijkstra(
   'SELECT
    id::integer,
    source::INT4,
    target::INT4,
    distance AS cost,
    CASE oneway
      WHEN ''1'' THEN -1
      ELSE distance
    END AS reverse_cost
  FROM edges_noded', $store, $store2, true, true) AS r,
  edges_noded AS e
WHERE
  r.id2 = e.id
GROUP BY
  e.old_id, e.name, e.type, e.oneway;");

	$crete2=pg_query("CREATE VIEW time_val AS
SELECT
  min(r.seq) AS seq,
  e.old_id AS id,
  e.name,
  e.type,
  e.oneway,
  sum(e.time) AS time,
  sum(e.distance) AS distance,
  ST_Collect(e.the_geom) AS geom
FROM
  pgr_dijkstra(
   'SELECT
    id::integer,
    source::INT4,
    target::INT4,
    time AS cost,
    CASE oneway
      WHEN ''1'' THEN -1
      ELSE time
    END AS reverse_cost
  FROM edges_noded', $store, $store2, true, true) AS r,
  edges_noded AS e
WHERE
  r.id2 = e.id
GROUP BY
  e.old_id, e.name, e.type, e.oneway;");
  
	$result3 = pg_query("DELETE from countinput;");
	
	$result4 = pg_query(" INSERT into countinput (st_astext,name)
SELECT      
    ST_AsText(ST_Intersection(a.geom, b.geom)),
	a.name

FROM
    dist as a,
    time_val as b
WHERE
    ST_Touches(a.geom, b.geom)
GROUP BY
    ST_Intersection(a.geom, b.geom),a.name");
	
	
	$result3 = pg_query("update countinput
						set geometrical= st_astext");
	

	
	
	
	
	if (!$result)  
	{
		echo "Error in query";
	}
	
?>
