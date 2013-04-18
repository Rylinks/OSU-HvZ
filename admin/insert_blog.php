<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
		require_once("include/postmessage.php");
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	
	$result = mysql_query("SELECT postid FROM $config[blog_table] ORDER BY postid DESC;");
	$content = mysql_real_escape_string($_POST['blag']);
	$row = mysql_fetch_assoc($result);
	$postid = intval($row['postid']);
	$postid++;
	$postedbyun = $_SESSION['AdminUser'];
	$query2 = "SELECT fname FROM $config[admin_table] WHERE username = '$postedbyun';";
	$ret2 = mysql_query($query2);
	$postedby = mysql_fetch_assoc($ret2);
	$postedby = $postedby['fname'];
	$time = GetCurrDateTime();
	$title = $_POST['title'];
	
	$con="INSERT INTO ".$config['blog_table']." (postid , postedby, time, title, content, postedbyun) VALUES ( '".$postid."', '".$postedby."', '".$time."', '".$title."','".$content."', '".$postedbyun."' );";
	
	if (!mysql_query($con,$sql))
	{
		die('Error: ' . mysql_error());
	}
	
	
	echo "<body bgcolor='#000000'><center><font color='FFFFFF'>".$con."<br>Great Success</font></center></body>";

	mysql_close($sql)
?>

