<?php
require_once('functions/load_config.php');
require_once('functions/quick_con.php'); 
require_once('functions/util.php');
	
$config = load_config('settings/config.dat'); 
	
$sql = my_quick_con($config) or die("MySQL problem"); 
$table_u = $config['user_table'];
$ret = mysql_query("UPDATE $table_u SET feed = feed + INTERVAL 12 hour WHERE state < 0;");
$ret = mysql_query("UPDATE $table_u SET feed = now() WHERE state < 0 AND feed > now();");

print("Done.");
?>
