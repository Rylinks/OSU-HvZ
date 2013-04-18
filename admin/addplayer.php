<?php
	//*******************************
	//Admin Register Player
	//Original Copyright 2010 Garrett 'Kylegar' Fleenor
	//Written for Oregon State University's game
	//This file is free to use, do not remove this header.
	//Edited by: Garrett
	
	
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	require_once('../functions/util.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table']; 
	$table_u = $config['user_table'];
	$err = 0; 
?>


<html>
<head>
<title>Player Registratitron</title>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>

<?php
	if($_POST['submit'] == 'Register') 
	{
		$err = 0; 
		$password1 = SanitizePassword($_POST['password1']);
		$password2 = SanitizePassword($_POST['password2']);
		$email_address = SanitizeEmail($_POST['email_address']);
		$email_confirm = SanitizeEmail($_POST['email_confirm']);
		
		$fname = SanitizeWord($_POST['fname']);
		$lname = SanitizeWord($_POST['lname']);
		
		if($_POST['oz_opt'] == 'oz')
		{
			$oz_opt = 1; 
		}
		else 
		{
			$oz_opt = 0; 
		}

		print "<body><table height=100% width=100%><tr><td align=center valign=center>";
		
		
		if(strlen($password1) < 4) 
		{
			$err = 1; 
			print "Password is too short.<br>"; 
		}
		
		if(strlen($password1) > 20) 
		{
			$err = 1; 
			print "Password is too long.<br>";
		}
		
		if(strlen($email_address) > 30 || strlen($email_confirm) > 30)
		{
			$err = 1;
			print "e-mail is too long. <br>";
		}
		
		if($email_address != $email_confirm)
		{
			$err = 1;
			print "Emails do not match. <br>";
		}
		if($password1 != $password2) 
		{
			$err = 1; 
			print "Passwords do not match.<br>";
		}
		
		if($err == 1) 
		{
			print "<a href='register.php'>Try again</a>";
		} 
		else 
		{
			$sql = my_quick_con($config) or die("MySQL Problem");
			$table_a = $config['user_table'];
			$password = HashPassword($password1);
			$ret = mysql_query("SELECT * FROM $table_a WHERE email='$email_address';");
			
			if(mysql_num_rows($ret) > 0) 
			{
				print "E-mail already used!";
			} 
			else 
			{
				$id = '';
				$valid_id = 0; 
				$c_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789";
				srand(); 
	
				while($valid_id == 0) 
				{
					$id = '';
					for($i = 0; $i < $config['id_length']; $i++) 
					{
						$n = rand() % 34;
						$id .= substr($c_list, $n, 1); 
					}
					$ret = mysql_query("SELECT * FROM $table_u WHERE id='$id';");
					if(mysql_num_rows($ret) == 0) 
					{
						$valid_id = 1; 
					}
				}
				
				$ret = mysql_query("INSERT INTO $table_u (id, fname, lname, password, email, oz_opt, state, kills, confirmed) VALUES ('$id','$fname','$lname','$password','$email_address','$oz_opt', 1, 0, 1);");
				if(!$ret) print "ERROR." . mysql_error($sql) . "<br>";
				print "<b>ID: $id </br></b>";
				print "Registered.<br>";
				
			}
			if(is_resource($ret)) 
			{
				mysql_free_result($ret);
			}

			mysql_close($sql);
		}
		print "</td></tr></table></body>";

	} 
	else 
	{
?>


<body>
	<table width=100% border><tr><td align=center valign=center>
		<h3>Register Player</h3>
		<form method=POST action=<?php echo $PHP_SELF; ?>>
			<table width=50% border>
				<tr>
					<td>Email Address:</td>
					<td><input type='text' name='email_address' size=20 maxlength=30></td>
				</tr>
				<tr>
					<td>Confirm Email:</td>
					<td><input type='text' name='email_confirm' size=20 maxlength=30></td>
				</tr>
				<tr>
					<td colspan=2 align=center>(up to 30 alphanumerics, @, or .)</td>
				</tr>
				
				<tr>
					<td>Password:</td>
					<td><input type='password' name='password1' size=20 maxlength=20></td>
				</tr>			
				<tr>
					<td>Confirm Password:</td>
					<td><input type='password' name='password2' size=20 maxlength=20></td>
				</tr>			
				<tr>
					<td colspan=2 align=center>(between 4 and 20 alphanumerics)</td>
				</tr>
				<tr>
					<td>First Name</td>
					<td><input type='text' name='fname' size=20 maxlength=30></td>
				</tr>	
				<tr>
					<td>Last Name</td>
					<td><input type='text' name='lname' size=20 maxlength=30></td>
				</tr>			
				<tr>
					<td colspan=2 align=center>
						<input type='checkbox' name='oz_opt' value='oz'> Put player in the original zombie pool
					</td>
				</tr>
			
				<tr>
					<td colspan=2 align=center>
						<input type='submit' name='submit' value='Register'>
						<input type='reset' value='Reset' onClick="return confirm('Reset?');">
					</td>
				</tr>
			</table>
		</form>
	</table>
<?php
	}
?>
</body>
</html>
<?php
	ob_end_flush();
?>
