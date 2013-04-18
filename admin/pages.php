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
	$sql = my_quick_con($config) or die("MySQL problem");
	
	
	$query = "SELECT pagename FROM pages";
	
?>
	
<html>
<head>
<title>Edit Pages</title>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>
<body>

	<table width="90%" border>
		<tr>
			<th> Page Name </th>
			<th> Edit </th>
		</tr>

<?php
	$ret = mysql_query($query);
	$rows = mysql_num_rows($ret);
	
	
	
	for($i = 0; $i < $rows; $i++)
	{
		$row = mysql_fetch_assoc($ret);
		
		$row_id = ($i % 2) + 1;
		print '<tr class="row'.$row_id.'">';
	
	
		print '<td align="center">';
		print $row['pagename'];
		print '</td>';
		
	
		print '<td align="center">';
		print "<a href='editpage.php?page=".$row['pagename']."'>Edit </a>";
		print '</td>';
		
	
	}
	
	
	
?>
	</table>
	<center><a href="addpage.php">Add a new page</a>

</body>