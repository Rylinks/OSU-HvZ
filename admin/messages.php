<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	require_once("include/postmessage.php");
	$config = load_config('../settings/config.dat');

	$sql = my_quick_con($config) or die("MySQL problem");
	
	$mtable = $config['message_table'];
	$atable = $config['admin_table'];
	
	
	$readto = GetTotalMessages();
	$username = $_SESSION['AdminUser'];
	mysql_query("UPDATE $atable SET readto = '$readto' WHERE username = '$username';");
	
	if($_POST['submit'] == 'Refresh') 
	{
	
		$showlog = $_POST['show_log'];
		$max = intval($_POST['nummsg']);

	
	
	}
	else if($_POST['submit'] == 'Post')
	{
		
		if(!$_POST['message'] == '')
		{
			PostMessage($_POST['message']);
		}
		header('location:messages.php');
	}
	else
	{
		$max = 10;
		$showlog = 0;	
	}	
?>


<html>
<head>
<link rel='stylesheet' type='text/css' href='style/main.css'>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>

<script type="text/javascript">
	window.onload = function()
	{
		var oFCKeditor = new FCKeditor( 'message', '100%', '200' ) ;
		oFCKeditor.BasePath = "../fckeditor/" ;
		oFCKeditor.ReplaceTextarea();
	}
</script>
</head>

<body>
<h3>Admin Message Center</h3>
<form name="AdminMessageForm" method="POST" action="<?php echo $PHP_SELF; ?>">
<center>
Display: <input type='text' name='nummsg' value=<?php print $max ?>>
<input type='checkbox' name='show_log' value='1' <?php if($showlog) print "checked"; ?>>Show Log Messages
<input type='submit' name='submit' value='Refresh'>

</center>

<br />
<table width="90%" border>
	<tr>
		<th>Post </th>
		<th>Posted By</th>
		<th>When</th>
		<th>Message</th>
		<?php if($showlog == 1) print "<th>Log Message</th>"; ?>

	</tr>

<?php
	
	if($min < 0) $min = 0;
	$query = "SELECT post, fromun, whentime, content, islog FROM $mtable";
	if($showlog == 0)
	{
		$query = $query." WHERE islog = 0";
	}
	$query = $query." ORDER BY post DESC LIMIT 0, $max;";
	$ret = mysql_query($query);
	if(!$ret) print "<br>".mysql_error();
	if($ret && ($rows = mysql_num_rows($ret)) > 0)
	{
		
		for($i = 0; $i < $rows; $i++) 
		{
			$row = mysql_fetch_assoc($ret);
			$row_id = ($i % 2) + 1;
			print '<tr class="row'.$row_id.'">';
			
			print '<td align="center">';
			print $row['post'];
			print '</td>';
			
			print '<td align="center">';
			print $row['fromun'];
			print '</td>';
			
			print '<td align="center">';
			print $row['whentime'];
			print '</td>';
			
			print '<td align="center">';
			print $row['content'];
			print '</td>';
			
			if($showlog == 1)
			{
				print '<td align="center">';
				if($row['islog']==0) print "No";
				else print "Yes";
				print '</td>';
			
			}
		
		
		}
		print "</tr>";
	}
	
?>

</table>

<br>
<br>
<br>
<form name="AdminMessageFormPost" method="POST" action="<?php echo $PHP_SELF; ?>">
<center>

<textarea id="message" name="message" style="width:700px;height:700px;">
		Print Message Here
	</textarea>
<input type='submit' name='submit' value='Post'>


</form>
