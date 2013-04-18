<?php
	ob_start();
	session_start();
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php');
	$config = load_config('settings/config.dat');
include('template_top.php'); ?>
<h1>Registration is Closed :(</h1>
<?php 

	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table']; 
	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey = 'reg-open';"); 
	$open = mysql_fetch_assoc($ret);
	$open = $open['value'];
	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey = 'reg-closed';");
	$closed = mysql_fetch_assoc($ret);
	$closed = $closed['value'];
	if($open == 0)
	{
		$query = "SELECT content FROM pages WHERE pagename='regnotopen';";
		
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		print $row['content'];
	
	}
	if($closed == 1)
	{
		$query = "SELECT content FROM pages WHERE pagename='regclosed';";
		
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		print $row['content'];
	
	}
	if($open == 1 && $closed == 0)
	{
		print "Ok, something messed up.  Email us!";
	}
?>

<?php include('template_bottom.php'); 

ob_end_flush();
?>
