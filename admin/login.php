<?php
	ob_start();
	session_start();
	require_once('../functions/util.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$goto = $config['admin_login_goto'];
	if(isset($_SESSION['AdminUser'])) header("Location: $goto");
?>

<html>
	<head>
		<title>admin login</title>
		<link rel='stylesheet' type='text/css' href='style/main.css'>
	</head>

<body>
	<center>
		<table border>
			<tr><td align=center valign=center>
			<h3>admin login</h3>
			<form method=POST action=<?php echo $PHP_SELF; ?>>
				username: <input type='text' name='AdminUser' size=10><p>
				password: <input type='password' name='pass' size=10><p>

			<?php
				if($_POST['submit'] == 'Login') 
				{
					$logname = SanitizeUsername($_POST['AdminUser']);
					$logpass =  HashPassword(SanitizePassword($_POST['pass']));
					$sql = my_quick_con($config) or die("MySQL problem"); 
					$table_a = $config['admin_table'];
					$ret = mysql_query("SELECT password FROM $table_a WHERE username='$logname';");
					$pass = mysql_fetch_assoc($ret); 
					$pass = $pass['password'];
		
					if(mysql_num_rows($ret) == 1 && $pass == $logpass) 
					{
						mysql_free_result($ret);
						$_SESSION['AdminUser'] = $logname;
						$_SESSION['pass_hash'] = $logpass;
			
						if(!$ret) { print mysql_error(); }
						header("Location:$goto");
					} 
					else 
					{
						echo "<font color=red>Invalid username/password</font><p>";
					}
		
					mysql_free_result($ret);
		

		
		
				}
			?>

			<input type='submit' name='submit' value='Login'>
		</form><p>
		<a href='register.php'>Register Administrator</a>
		</td>
		</tr>
	</table>
</body>
</html>

<?php
	ob_end_flush();
?>
