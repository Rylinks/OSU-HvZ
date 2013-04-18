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
</head><body bgcolor="#000">
<p align="center"><h2><font color="#FFFFFF">Add a Blog Post</font></h2></p>
<form action="insert_blog.php" method='post'>
<p>
	<h3>Title:</h3> <input type='text' name='title' size=50 maxlength=255><br>
	<b>Post:</b><br>
	<textarea id="front" name="blag" style="width:700px;height:700px;"></textarea>
</p>
<p><input name="save" type="submit" value="save"></p>
</form>
<?php
	mysql_close($sql);
?>
</body>
</html>
