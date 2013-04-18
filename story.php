<?php
ob_start();
session_start();
require_once('functions/load_config.php');
require_once('functions/quick_con.php');
$config = load_config('settings/config.dat');

$sql = my_quick_con($config) or die("MySQL problem");
$story_content = "Error.";

if(!isset($_SESSION['id'])) {
	$result = mysql_query("SELECT content FROM pages WHERE pagename='defaultstory'");
	if($result == false)
	{
		die( "mysql error" . mysql_error());
	}
	$row = mysql_fetch_array($result);
	$story_content = $row[0];
	mysql_free_result($result);
} else {
	$id = $_SESSION['id'];
	$result = mysql_query("SELECT state from $config[user_table] WHERE id='$id'");
	if ($result == false)
	{
		die("mysql error ". mysql_error());
	}
	$state = mysql_fetch_array($result);
	$state = $state[0];
	mysql_free_result($result);
	
	if ($state == 1) {
		$result = mysql_query("SELECT content FROM pages WHERE pagename='humanstory'");
		if($result == false)
		{
			die( "mysql error" . mysql_error());
		}
		$row = mysql_fetch_array($result);
		$story_content = $row[0];
		mysql_free_result($result);
	} else if ($state == -1 || $state == -2) {
		$result = mysql_query("SELECT content FROM pages WHERE pagename='zombiestory'");
		if($result == false)
		{
			die( "mysql error" . mysql_error());
		}
		$row = mysql_fetch_array($result);
		$story_content = $row[0];
		mysql_free_result($result);

	}
}
?>
 
<?php include('template_top.php'); ?>
 
<p>
<?php 
print($story_content);
?>
</p>
 

 
<?php include('template_bottom.php'); ?>
 
