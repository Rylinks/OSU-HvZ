<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table']; 
	$table_u = $config['user_table'];
	$err = 0; 
?>

<html>
<head>
<title>Pick Original Zombie</title>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>

<body>
<?php

	if($_POST['submit'] == 'Select Original Zombie') 
	{

		$oz = $_POST['oz_pick']; 
		print "<table height=100% width=100%>";
		$i = 0;
		foreach($oz as $val)
		{
			$i++;
			// users table 
		
		$ret = mysql_query("UPDATE $table_u SET state = -2 WHERE id='$val';");
		//$ret = mysql_query("INSERT INTO $table_u (id, fname, lname, state, kills) VALUES ('$i','Original','Zombie', -3, 0);");


		// variables table
		$ret = mysql_query("UPDATE $table_v SET value = 1 WHERE zkey='oz-selected';");


		$ret = mysql_query("SELECT fname, lname FROM $table_u WHERE id='$val';");
		$row = mysql_fetch_assoc($ret);
		
		print "<tr><td align=center valign=center>";
		print $row['fname']." ".$row['lname']." has been selected as the original zombie.<br>"; 
		print "</td></tr>";
		
		}
			print "<a href='flow.php'>Back to game flow</a>";
		print "</table>";
		
		
	} 
	else 
	{
		$ret = mysql_query("SELECT value FROM $table_v WHERE zkey='reg-closed';");
		$reg_closed = mysql_fetch_assoc($ret);
		$reg_closed = $reg_closed['value'];
		if($reg_closed == 0) 
		{
			$err = 1; 
			print "<table height=100% width=100%><tr><td align=center valign=center>User registration has not been completed.</td></tr></table>"; 
		}

		if($err == 0) 
		{
			$ret = mysql_query("SELECT fname, lname, id FROM $table_u WHERE oz_opt=1;");
?>

<form method=POST action=<?php echo $PHP_SELF; ?>>
<center>
<table>

<?php
			$array = array();
			while($row = mysql_fetch_assoc($ret))
			{
				$array[] = $row;
			}
			
			shuffle($array);
			
			foreach($array as $val)
			{
?>
			<tr>
				<td><input type='checkbox' name=<?php print 'oz_pick[]'; ?> value='<?php print $val['id']; ?>'></td>
				<td><?php print $val['fname'] . " " . $val['lname']; ?></td>
				</tr>
	
<?php	
			
			}
			
?>
</table>
<input type='submit' name='submit' value='Select Original Zombie'>
</center>
</form>

<?php
		}
	}
?>
</body>

<?php
	//mysql_free_result($ret);
	mysql_close($sql); 
	ob_end_flush();
?>
