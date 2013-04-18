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

	// Set default time zone
	$ret = mysql_query("SELECT zone FROM $table_t");
	$row = mysql_fetch_array($ret);	
	date_default_timezone_set($row['zone']);
	
	
?>
