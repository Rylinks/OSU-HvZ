<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	
	$text = mysql_real_escape_string($_POST[story_z]);
	$con="UPDATE $config[content_table] SET story_z ='$text';";

	if (!mysql_query($con,$sql))
	{
		die('Error: ' . mysql_error());
	}
	
	
	echo "<body bgcolor='#000000'><center><font color='FFFFFF'>Zombie Story Saved</font></center></body>";

	mysql_close($sql)
?>

