<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	$result = mysql_query("SELECT email, id_email FROM $config[content_table]");
	$row = mysql_fetch_assoc($result);
?>

<html>
<head><title>Edit Confirmation Email</title>
</head><body bgcolor="#000">
<p align="center"><h2><font color="#FFFFFF">Edit Confirmation Email</font></h2></p>
<form action="insertemail.php" method="post">
<p>
	<textarea id="email" name="email" style="width:700px;height:700px;">
		<?php print $row['email']; ?>
	</textarea>
</p>
<p><input name="save" type="submit" value="save"></p>
</form>
<form action="insert_idemail.php" method="post">
<p>
	<textarea id="id_email" name="id_email" style="width:700px;height:700px;">
		<?php print $row['id_email']; ?>
	</textarea>
</p>
<p><input name="save" type="submit" value="save"></p>
</form>
<?php
	mysql_close($sql);
?>
</body>
</html>
