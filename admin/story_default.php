<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	$result = mysql_query("SELECT story_default FROM $config[content_table]");
	$row = mysql_fetch_assoc($result);
?>

<html>
<head><title>Edit Front Page</title>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>

<script type="text/javascript">
	window.onload = function()
	{
		var oFCKeditor = new FCKeditor( 'front', '100%', '70%' ) ;
		oFCKeditor.BasePath = "../fckeditor/" ;
		oFCKeditor.ReplaceTextarea() ;
		
	}
</script>

</head><body bgcolor="#000">
<p align="center"><h2><font color="#FFFFFF">Edit Default Story</font></h2></p>
<form action="insertstory_d.php" method="post">
<p>
	<textarea id="front" name="story_default" style="width:700px;height:700px;">
		<?php print $row['story_default']; ?>
	</textarea>
</p>
<p><input name="save" type="submit" value="save"></p>
</form>
<?php
	mysql_close($sql);
?>
</body>
</html>
