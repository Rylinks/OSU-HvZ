<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	require_once('../functions/util.php');

	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	

?>



<?php
	if($_POST['submit']=="add")
	{
		$arr = $_POST['emails'];
		$arr = explode(",", $arr);
		$content = $_POST['content'];
		echo $content;
		foreach($arr as $str)
		{
			
			send_email($str,"OSU HvZ Spring Registration is Open",$content);	
			
		}
	
		
	
	
	
	}
	else
	{




?>


<html>
<head><title>Add a Blog Page</title>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>


	<link rel="stylesheet" type="text/css" href="style/main.css" />
</head><body bgcolor="#000">
<p align="center"><h2><font color="#FFFFFF">Add a Page</font></h2></p>
<form action='<?php echo $PHP_SELF;?>' method='post'>
<p>
	<h3>Page Name:</h3> <textarea id="emails" name="emails" style="width:700px;height:700px;"></textarea><br>
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
<?php } ?>