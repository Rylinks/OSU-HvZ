<?php
	ob_start();
	session_start();
	require_once('../functions/load_config.php');
	require_once('security.php');
	require_once('../functions/quick_con.php'); 
	$config = load_config('../settings/config.dat');
	$table_u = $config['admin_table'];
	$table_t = $config['time_table'];
	$sql = my_quick_con($config) or die("MySQL problem");

	
	$query = "SELECT * FROM `users` WHERE orientation = '0' AND isnpc = '0'";
	$ret = mysql_query($query);
	
	while($row = mysql_fetch_assoc($ret))
	{
		print $row['email'].",<br>";
	}
	
	
?>