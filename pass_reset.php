<?php
ob_start();
session_start();
require_once('functions/load_config.php');
require_once('functions/quick_con.php');
require_once('functions/util.php');
$config = load_config('settings/config.dat');
$table_u = $config['user_table'];
include('template_top.php'); 

	print "<center>";
	if($_POST['submit'] == 'Reset Password') 
	{
		
		//print $_POST['lname'];
		$fname = SanitizeWord($_POST['fname']);
		$lname = SanitizeWord($_POST['lname']);
		$email = SanitizeEmail($_POST['email']);
		$sql = my_quick_con($config) or die("mysql problem");
		
		$query = "SELECT * FROM $table_u WHERE email='$email' AND lname='$lname';";
		$ret = mysql_query($query);
		if(mysql_num_rows($ret) > 0)
		{
			$new = "";
			$c_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			srand();
			for($i = 0; $i < 8; $i++) 
			{
				$n = rand() % 25;
				$new .= substr($c_list, $n, 1); 
			}
			
			$new_hash = HashPassword($new);
			$ret = mysql_query("UPDATE $table_u SET password = '$new_hash' WHERE email='$email';");
			$header = "From: hvsz.osu@gmail.com \r\n";
			$body  = "Your password has been reset.\n\n\n";
			$body .= "Your new password is: $new\n\n";
			$body .= "You can change your password in your account page, once you login.\n\n";
			$body .= "--OSU Humans vs Zombies";
			
			//mail($email, "OSU HVZ: Password Reset", $body, $header);
			
			send_email($email, "OSU HVZ: Password Reset", $body);
			
			print "Your password has been reset.<br>Check your email address for your new password.<br><a href='index.php'>Back to login</a>";
		} 
		else 
		{
		
			print "That username or email address could not be found.<br>Please email your game administrator.<br><a href='index.php'>Back to login</a><br>";
		
		}
		mysql_close($sql); 
		print "</center>";
	} 
	else 
	{
		
?>
<td>
<h1>Password Reset:</h1>
         <center>
<form method=POST action=<?php echo $PHP_SELF; ?>>
<table>
<tr>
	<td>First Name:</td>
	<td><input type='text' name='fname' size=20 maxlength=20></td>
</tr>
<tr>
	<td>Last Name:</td>
	<td><input type='text' name='lname' size=20 maxlength=20></td>
</tr>
<tr>
	<td>Email Address:</td>
	<td><input type='text' name='email' size=20 maxlength=30></td>
</tr>
<tr><td colspan=2 align=center valign=center>
	<input type='submit' name='submit' value='Reset Password'>
</td></tr>
</table>
</form>
</center>
<small>
<ul>
<li>You can use this page to reset your password in case you've forgotten it.</li>
<li><b>Both</b> email <b>and</b> password are case sensitive.</li>
</ul>
</small>
</body>

<?php
}
?>
</td>
<?php include('template_bottom.php');

ob_end_flush();
?>
