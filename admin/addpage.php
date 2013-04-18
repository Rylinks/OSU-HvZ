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
<p align="center"><h2><font color="#FFFFFF">Add a Page</font></h2></p>
<form action="insertpage.php" method='post'>
<p>
	<h3>Page Name:</h3> <input type='text' name='name' size=50 maxlength=255><br>
	<b>Content:</b><br>
	<textarea id="front" name="content" style="width:700px;height:700px;"></textarea>
</p>
<p><input name="submit" type="submit" value="add"></p>
</form>
<?php
	mysql_close($sql);
?>
</body>
</html>
