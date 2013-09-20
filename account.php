<?php
ob_start();
session_start();

require_once('security.php');
require_once('functions/load_config.php');
require_once('functions/quick_con.php');
$config = load_config('settings/config.dat'); 
$sql = my_quick_con($config) or die("SQL problem"); 
$table_u = $config['user_table'];
$game_name = $config['game_name'];
$id = $_SESSION['id'];
$ret = mysql_query("SELECT pic_path FROM $table_u WHERE id='$id';"); 
$c_pic_path = mysql_fetch_assoc($ret);
$c_pic_path = $c_pic_path['pic_path'];
?>
<?php include('template_top.php'); ?>

<?php
	if($_POST['submit'] == 'Change Password') 
	{
		$ret = mysql_query("SELECT password FROM $table_u WHERE id='$id';");
		$pass_ret = mysql_fetch_assoc($ret); 
		$pass_ret = $pass_ret['password'];
		$pass_cur = HashPassword(SanitizePassword($_POST['pass_original']));
		$pass_new = HashPassword(SanitizePassword($_POST['pass_new']));
		$pass_con = HashPassword(SanitizePassword($_POST['pass_confirm']));
		print "<table width=100% height=100%><tr><td align=center valign=center>";
		
		if($pass_ret == $pass_cur) 
		{
			if(strlen($_POST['pass_new']) >= 4 && strlen($_POST['pass_new']) <= 100) 
			{
				if($pass_new == $pass_con) 
				{
					$ret = mysql_query("UPDATE $table_u SET password = '$pass_new' WHERE id='$id';");
					print "Password successfully changed.<br>";
					print "<a href='account.php'>Back</a>";
				} 
				else 
				{
					print "The passwords you entered did not match."; 
					print "<a href='account.php'>Back</a>";
				}
			} 
			else 
			{
				print "Your new password must be between 4 and 100 alphanumerics.<br>";
				print "<a href='account.php'>Back</a>";
			}
		} 
		else 
		{
			print "The password you entered was incorrect.<br>"; 
			print "<a href='account.php'>Back</a>";
		}
		
		print "</td></tr></table>";
	} 
	else if($_POST['submit'] == 'Submit') 
	{
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
		$pic =  preg_replace($search, '', $_POST['newpic']);
		print "<table width=100% height=100%><tr><td align=center valign=center>";
		//print $pic;
		print "Great Success! <br> <a href='account.php'>Back</a>";
		$ret = mysql_query("UPDATE $table_u SET pic_path = '$pic' WHERE id='$id';");
		print "</td></tr></table>";
	} 
	else 
	{
?>
<body>
<h1>My Account:</h1>
<form method=POST action=<?php echo $PHP_SELF; ?>>
<table>
<tr><td colspan=2 align=center><b><h2>Change Password:</h2></b></td></tr>
<tr>
<td>Original Password:</td>
<td><input type='password' name='pass_original' size=20 maxlength=20></td>
</tr>
<tr>
<td>New Password:</td>
<td><input type='password' name='pass_new' size=20 maxlength=20></td>
</tr>
<tr>
<td>Confirm New Password:</td>
<td><input type='password' name='pass_confirm' size=20 maxlength=20></td>
</tr>
<tr><td colspan=2 align=center>
<input type='submit' name='submit' value='Change Password'>
</td></tr>
</table>
</form>

<form method=POST enctype='multipart/form-data' action=<?php echo $PHP_SELF; ?>>
<table>
<tr>
<td><img src='<?php echo $c_pic_path; ?>' height=200></td>
<td>
Use a new picture (URL): <input type='text' name='newpic' value=<?php echo $c_pic_path;?> >
 <br>
<small>
You must use an image hosting site to host the image.  Imageshack, Tinypic, and others. <br>
Acceptable formats are JPEG, JPG, GIF, PNG.<br>
Your picture should be of you, but this is not required.<br>
Any picture deemed inappropriate will be removed, and repeated attempts to use said picture may result in a ban.<br>
</small><br><br>
<center>
<input type='submit' name='submit' value='Submit'>
</center>
</td>
</tr>
</table>
</form>
<center><br><br>
<font size="3">Your ID is <?php echo $id; ?>.</font><br>
</center>
<?php
}
?>
<?php include('template_bottom.php'); ?>


<?php
mysql_free_result($ret);
mysql_close($sql);
ob_end_flush();
?>
