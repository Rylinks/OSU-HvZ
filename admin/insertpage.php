<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	
?>

<html>
<head><title>Add a Blog Page</title>
	<link rel="stylesheet" type="text/css" href="style/main.css" />
</head>
<body>

<?php
	
	if($_POST['submit']=="add")
	{
		$page = mysql_real_escape_string($_POST['content']);
		$text = mysql_real_escape_string($_POST['name']);
	
		$con="INSERT INTO pages (pagename, content) VALUES ('$text', '$page');";
		
		
		if (!mysql_query($con,$sql))
		{
			die('Error: ' . mysql_error());
		}
		echo "<center>$text page Added</center>";
	}
	else if($_POST['submit']=="save")
	{
		$text = mysql_real_escape_string($_POST['content']);
		$page = mysql_real_escape_string($_GET['pagename']);
	
		$con="UPDATE pages SET content ='$text' WHERE pagename='$page';";

		if (!mysql_query($con,$sql))
		{
			die('Error: ' . mysql_error());
		}
		echo "<center>$page page saved</center>";
	}
	
?>
</body>
<?php
	mysql_close($sql)
?>

