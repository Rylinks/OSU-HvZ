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
	
	$page = mysql_real_escape_string($_GET['page']);
	$query = "SELECT * FROM pages WHERE pagename = '$page';";
	$ret = mysql_query($query);
	$row = mysql_fetch_assoc($ret);
	
	
?>

<html>
<head><title>Add a Blog Page</title>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>

<script type="text/javascript">
	window.onload = function()
	{
		var oFCKeditor = new FCKeditor( 'front', '100%', '70%' ) ;
		oFCKeditor.BasePath = "../fckeditor/" ;
		oFCKeditor.ReplaceTextarea() ;
		
	}
</script>
	<link rel="stylesheet" type="text/css" href="style/main.css" />
</head>


<body>
	<center><h2>Editing Page: <?php print($row['pagename']);?></h2></center>
	
	<form action="insertpage.php?pagename=<?php print($row['pagename']); ?>" method="post">
<p>
	<textarea id="front" name="content" style="width:700px;height:700px;">
		<?php print $row['content']; ?>
	</textarea>
</p>
<p><input name="submit" type="submit" value="save"></p>
</form>
<?php
	mysql_close($sql);
?>
</body>
</html>










</body>




