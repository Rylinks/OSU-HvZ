<?php
	ob_start();
	session_start();
	require_once('../functions/load_config.php');
	require_once('security.php');
	require_once('../functions/quick_con.php'); 
	$config = load_config('../settings/config.dat');
	$table_u = $config['user_table'];
	$table_v = $config['var_table'];
	$table_t = $config['time_table'];
	$table_a = $config['admin_table'];
	$sql = my_quick_con($config) or die("MySQL problem");
	
	
	
	
	
	
	
	
?>
<html>
<head>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>



<body>
<h3>Admin List</h3>
<center>
<table width="90%" border>
	<tr>
	
		<th>Name</th>
		<th>Username</th>
		<th>Email</th>
		<th>Phone Number</th>
		<th>Last Online</th>
	</tr>
	
<?php
	$ret = mysql_query("SELECT username, password, email, phone, fname, lname, lonline FROM $table_a;");
	
	
	if($ret && ($rows = mysql_num_rows($ret)) > 0)
	{
	
		for($i = 0; $i < $rows; $i++) 
		{
			$row = mysql_fetch_assoc($ret);
			
			$row_id = ($i % 2) + 1;
			print '<tr class="row'.$row_id.'">';
			
			print '<td align="center">';
			print $row['fname'].' '.$row['lname'];
			print '</td>';
			
			print '<td align="center">';
			print $row['username'];
			print '</td>';
			
			print '<td align="center">';
			print $row['email'];
			print '</td>';
			
			print '<td align="center">';
			print $row['phone'];
			print '</td>';		

			print '<td align="center">';
			print $row['lonline'];
			print '</td>';		
			
		}
		
	}
	
	
?>
	
</table>
<form method=POST action=<?php echo $PHP_SELF; ?>>
<input type='submit' name='submit' value='Generate Mailing List'><br>
<?php

if($_POST['submit'] == 'Generate Mailing List') 
	{
		print "<textarea cols=60 rows=10>";

		
		$ret = mysql_query("SELECT email FROM $atable;");
		
		for($i = 0; $i < mysql_num_rows($ret); $i++) 
		{
			
			$row = mysql_fetch_assoc($ret);
			
			print $row['email'];
			if($i < mysql_num_rows($ret) - 1) print ", ";
		
		
		}
		print "</textarea>";
	}
?>
</form>
</center>

</body>

</html>

<?php
	mysql_free_result($ret);
	mysql_close($sql);
	ob_end_flush();
?>

	