<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	require_once('../functions/util.php');
	$config = load_config('../settings/config.dat'); 
	$sql = my_quick_con($config) or die("SQL problem"); 
	$table_u = $config['admin_table'];
	
	$username = $_SESSION['AdminUser'];
?>

<html>
<head>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>

<?php
	if($_POST['submit'] == 'Change Password') 
	{
		$ret = mysql_query("SELECT password FROM $table_u WHERE username='$username';");
		$pass_ret = mysql_fetch_assoc($ret);
		$pass_ret = $pass_ret['password'];
		$pass_cur = HashPassword(SanitizePassword($_POST['pass_original']));
		$pass_new = HashPassword(SanitizePassword($_POST['pass_new']));
		$pass_con = HashPassword(SanitizePassword($_POST['pass_confirm']));
		
		print "<table width=100% height=100%><tr><td align=center valign=center>";
	
		if($pass_ret == $pass_cur)
		{
			if(strlen($_POST['pass_new']) >= 4 && strlen($_POST['pass_new']) <= 20) 
			{
				if($pass_new == $pass_con) 
				{
					$ret = mysql_query("UPDATE $table_u SET password = '$pass_new' WHERE username='$username';");
					print "Password successfully changed.<br>";
					print "<a href='" . $PHP_SELF . "'>Back</a>";
				} 
				else 
				{
					print "The passwords you entered did not match."; 
					print "<a href='" . $PHP_SELF . "'>Back</a>";
				}
			} 
			else 
			{
				print "Your new password must be between 4 and 20 alphanumerics.<br>";
				print "<a href='" . $PHP_SELF . "'>Back</a>";
			}
		} 
		else 
		{
			print "The password you entered was incorrect.<br>"; 
			print "<a href='" . $PHP_SELF . "'>Back</a>";
		}
		
		print "</td></tr></table>";
	} 
	else if($_POST['submit'] == 'Change Info')
	{
		$fname = SanitizeWord($_POST['fname']);
		$lname = SanitizeWord($_POST['lname']);
		$phone = SanitizeNumber($_POST['phone']);
		
		$ret = mysql_query("UPDATE $table_u SET fname = '$fname', lname = '$lname', phone = '$phone' WHERE username='$username';");
		print "UPDATE $table_u SET fname = '$fname', lname = '$lname', phone = '$phone' WHERE username='$username';";
		print "<br>Success!";
	}
	else 
	{
?>
<body>
<form method=POST action=<?php echo $PHP_SELF; ?>>
	<table border>
		<tr>
			<td colspan=2 align=center><b>Edit Admin Info</b></td>
		</tr>
		<tr>
			<td>First Name:</td>
			<td><input type='text' name='fname' size=20 maxlength=20></td>
		</tr>
		<tr>
			<td>Last Name:</td>
			<td><input type='text' name='lname' size=20 maxlength=20></td>
		</tr>
		<tr>
			<td>Phone Number</td>
			<td><input type='text' name='phone' size=20 maxlength=20></td>
		</tr>
		
		<tr><td colspan=2 align=center>
		<input type='submit' name='submit' value='Change Info'>
		</td></tr>
	
	</table>
</form>



<h3>my account</h3>
<form method=POST action=<?php echo $PHP_SELF; ?>>
	<table border>
		<tr><td colspan=2 align=center><b>change password</b></td></tr>
		<tr>
			<td>original password:</td>
			<td><input type='password' name='pass_original' size=20 maxlength=20></td>
		</tr>
		<tr>
			<td>new password:</td>
			<td><input type='password' name='pass_new' size=20 maxlength=20></td>
		</tr>
		<tr>
			<td>confirm new password:</td>
			<td><input type='password' name='pass_confirm' size=20 maxlength=20></td>
		</tr>
		<tr><td colspan=2 align=center>
		<input type='submit' name='submit' value='Change Password'>
		</td></tr>
	</table>
</form>

</body>
<?php
	}
?>

</html>

<?php
	mysql_close($sql);
	ob_end_flush();
?>
