<?php
	if(!isset($_SESSION['AdminUser']) || !isset($_SESSION['pass_hash']))
	{
		header('location:login.php');
	}

	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	

	$sdatestr = date('l jS \of F Y h:i:s A');
	$auser = $_SESSION['AdminUser'];
	$atable = $config['admin_table'];
	mysql_query("UPDATE $atable SET lonline = TIMESTAMP'$sdatestr' WHERE username = '$auser';");

?>
