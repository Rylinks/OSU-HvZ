<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	$result = mysql_query("SELECT regclosed, regnotopen FROM $config[content_table]");
	$row = mysql_fetch_assoc($result);
?>

<html>
<head><title>Edit Error Pages</title>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>

<script type="text/javascript">
	window.onload = function()
	{
		var oFCKeditor = new FCKeditor( 'regclosed' ) ;
		oFCKeditor.BasePath = "../fckeditor/" ;
		oFCKeditor.ReplaceTextarea() ;
		
		var iFCKeditor = new FCKeditor( 'regnotopen' ) ;
		iFCKeditor.BasePath = "../fckeditor/" ;
		iFCKeditor.ReplaceTextarea() ;
	}
</script>

</head><body bgcolor="#000">
<p align="center"><h2><font color="#FFFFFF">Edit Error Pages</font></h2></p>

<form action="insertclosed.php" method="post">
<p>
	<font color="#FFFFFF">Registration Closed</font>
	<textarea id="regclosed" name="regclosed" style="width:700px;height:300px;">
		<?php print $row['regclosed']; ?>
	</textarea>
</p>
<p><input name="save" type="submit" value="save"></p>
</form>

<form action="insertnotopen.php" method="post">
<p>
	<font color="#FFFFFF">Registration Not Open Yet</font>
	<textarea id="regnotopen" name="regnotopen" style="width:700px;height:300px;">
		<?php print $row['regnotopen']; ?>
	</textarea>
</p>
<p><input name="save" type="submit" value="save"></p>
</form>
<?php
	mysql_close($sql);
?>
</body>
</html>
