<?php
	ob_start();
	session_start();
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php');
	$config = load_config('settings/config.dat');
 
	// get the front content and save it into the session
	$sql = my_quick_con($config) or die("MySQL problem");
	include('template_top.php');
	
	
	if(isset($_GET['p']))
	{	
		$pagename = mysql_real_escape_string($_GET["page"]);
	}
	else
	{
		$pagename = 'front';
	}
	

	$result = mysql_query("SELECT * FROM pages WHERE pagename='$pagename';");
	
	if(!$result || mysql_num_rows($result) <= 0)
	{	
		print("That Page Doesn't Exist");
	}
	else
	{
		$ret = mysql_fetch_assoc($result);
		print($ret['content']);
	}

 
include('template_bottom.php'); ?>
 
