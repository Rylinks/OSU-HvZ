<?php
	ob_start();
	session_start();
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php');
	$config = load_config('settings/config.dat');
 

	//NOT SAVING TO SESSION.
	$sql = my_quick_con($config) or die("MySQL problem");
	$result = mysql_query("SELECT rules FROM $config[content_table]");
	$row = mysql_fetch_assoc($result);
	

?>
 
<?php include('template_top.php'); ?>
 
<p><strong><h1>Humans Vs Zombies Rules:</h1></strong>
<p>
<?php print $row['rules']; ?>
</p>
 
<?php include('template_bottom.php'); ?>
 
