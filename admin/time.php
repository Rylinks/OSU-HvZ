<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php'); 
	
	$config = load_config('../settings/config.dat');
	$table_t = $config['time_table'];
	$sql = my_quick_con($config) or die("MySQL problem"); 
?>

<html>
<head>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>
<?php
	if($_POST['timezone'] != null) 
	{
		//$com = "REPLACE INTO $table_t (zone) VALUES ('$_POST[timezone]')";
		$com = "UPDATE $table_t SET zone = '$_POST[timezone]'";
		if(!mysql_query($com,$sql))
			die('Error: ' . mysql_error());
		print "Time zone set";
	}
?>

Set the time zone of your game:
<form name="timezone" method=POST action=<?php print $PHP_SELF; ?>>
<?php
	$timeZones = DateTimeZone::listIdentifiers();
	print '<select name="timezone">';
	
	foreach ( $timeZones as $timeZone ) 
	{
           printf('<option value="%s">%s</option>', $timeZone, $timeZone);
	}
	print '</select>';
?>
<input type="submit"/>
</form>

<br />
<br />
Your timezone is currently set to:
<?php
	$ret = mysql_query("SELECT zone FROM $table_t");
	$row = mysql_fetch_array($ret);
	print $row['zone'];
?><br />     

</body>
</html>
