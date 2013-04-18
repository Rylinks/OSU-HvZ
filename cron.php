<?php
	ob_start();
	session_start();
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php'); 
	$config = load_config('settings/config.dat');
	$table_u = $config['user_table'];
	$table_v = $config['var_table'];
	$table_t = $config['time_table'];
	$sql = my_quick_con($config) or die("MySQL problem"); 
	
	// Set default time zone
	$ret = mysql_query("SELECT zone FROM $table_t");
	$row = mysql_fetch_array($ret);
	date_default_timezone_set($row['zone']);
	
  	
	// update the starvation times and oz reveal (every 5 minutes, not on every page refresh)
	
	$ret = mysql_query("UPDATE $table_u SET state = -4 WHERE now() > feed + INTERVAL 2 day;");
	$ret = mysql_query("UPDATE $table_u SET starved = feed + INTERVAL 2 day WHERE state = -4;");
	$ret = mysql_query("UPDATE $table_u SET state = 0 WHERE state = -4;");
	$ret = mysql_query("SELECT value FROM $table_v WHERE keyword='oz-revealed';");
		 
	$ret        = mysql_query("SELECT COUNT(*) FROM $table_u WHERE state > 0 AND isnpc = 0");
	$humancount = mysql_fetch_array($ret); 
	$humancount = $humancount[0];
	
	$ret        = mysql_query("SELECT COUNT(*) FROM $table_u WHERE state < 0 AND isnpc = 0");
	$zedcount   = mysql_fetch_array($ret);
	$zedcount   = $zedcount[0];
	
	$ret        = mysql_query("SELECT COUNT(*) FROM $table_u WHERE state = 0 AND isnpc = 0");
	$deadcount  = mysql_fetch_array($ret);
	$deadcount  = $deadcount[0];

	$log  = date(DATE_ISO8601).",";
	$log .= $humancount.",";
	$log .= $zedcount.",";
	$log .= $deadcount."\n";

	file_put_contents ('settings/playerlog.txt', $log, FILE_APPEND);
?>

