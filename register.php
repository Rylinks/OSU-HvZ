<?php
	ob_start();
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php'); 
	require_once('functions/util.php');
	$config = load_config('settings/config.dat'); 
	$id = $_SESSION['id'];
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table']; 
	$table_c = $config['content_table'];
	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey = 'reg-open';"); 
	if($ret == false)
	{
		print "asdf! ". mysql_error();
	}
	$open = mysql_fetch_assoc($ret);
	$asdf = $open['value'];
	if($asdf == 0) 
	{
		mysql_free_result($ret);
		mysql_close($sql); 
		$_SESSION['regopen'] = 1;
		header("Location:reg_closed.php");

	}

	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey = 'reg-closed';");
	$closed = mysql_fetch_assoc($ret);
	$closed = $closed['value'];
	$asdf = $closed['value'];
	if($asdf == 1) 
	{
		$_SESSION['regopen'] = 2;
		mysql_free_result($ret); 
		mysql_close($sql); 
		header("Location:reg_closed.php");
	}
	$_SESSION['regopen'] = 0;

	include('template_top.php');

	if($_POST['submit'] == 'Register') 
	{

		$err = 0; 
		$fname = SanitizeWord($_POST['firstname']);
		$lname = SanitizeWord($_POST['lastname']);
		$password1 = SanitizePassword($_POST['password1']);
		$password2 = SanitizePassword($_POST['password2']); 
		$email_address = SanitizeEmail($_POST['email_address']);
		$email_confirm = SanitizeEmail($_POST['confirm_email']);
		$contact = SanitizeNumber($_POST['contact']);

		if($_POST['oz_opt'] == 'oz')
		{
			$oz_opt = 1; 
		}
		else 
		{
			$oz_opt = 0; 
		}
	
		if($_POST['agree'] == 'agree')
		{
			$agree = 1;
		} else {
			$agree = 0;
		}

		print "<table height=100% width=100%><tr><td align=center valign=center>";


		if(strlen($fname) > 30) 
		{
			$err = 1; 
			print "Your first name is too long. Get a new one.<br>"; 
		}

		if(strlen($lname) > 30) 
		{
			$err = 1; 
			print "Your last name is too long. Get a new one.<br>";
		}

		if(strlen($contact) != 10)
		{
			$err = 1;
			print "Your phone number needs to be ten digits long.<br>";
		}
		
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
	
		if($password1 != $password2) 
		{
			$err = 1;
			print "Passwords do not match.<br>";
		}
	
		if($email_address != $email_confirm)
		{
			$err = 1;
			 
			print "e-mails do not match. $email_address : $email_confirm<br>";
		}
	
		if(!$agree) {
			$err = 1;
			print "You must agree to the conduct agreement<br>";
		}

		if($err == 1)
		{
			print "<br><a href='register.php'>Try again</a>";
		} 
		else 
		{
			$sql = my_quick_con($config) or die("MySQL problem"); 
			$table_u = $config['user_table'];
			$password = HashPassword($password1);
			$hash = gen_hash();
			$ret = mysql_query("SELECT * FROM $table_u WHERE email='$email_address';");
			if(mysql_num_rows($ret) > 0) 
			{
				print "Someone has already registered with that e-mail.<br>"; 
				print "<a href='register.php'>Try again</a>";
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
				$ret = mysql_query("INSERT INTO $table_u (id, fname, lname, password, email, contact, oz_opt, hash, confirmed, state, kills) VALUES ('$id','$fname','$lname','$password','$email_address','$contact','$oz_opt','$hash',0,1, 0);");
				if(!ret)
				{
					print "ERROR: ". msql_error();
				}
			
				$ret = mysql_query("SELECT content FROM pages WHERE pagename='confirmemail';");
				$ret = mysql_fetch_array($ret);
				$email_text = $ret['content'];
				$email_text = str_replace( "!hash",$hash,$email_text);
				send_email("$email_address","OSUndead Registration Confirmation","$email_text");
				print "Registered.<br>";
		        	print "<a href='index.php'>Home</a><br><br>";
					print "We sent a confirmation e-mail to the e-mail that you registered with. <br>";
					print "When you get it, Click the link to activate your account, and you will get your ID number and be registered to play HvZ";
			    
			}
		}
		
		mysql_close($sql);

	print "</td></tr></table>";
	
	} 
	else 
	{ 
?>
	
<table height=100% width=100%><tr><td align=center valign=center>
<h3>Registration</h3>
<form method=POST action=<?php echo $PHP_SELF; ?>>
<table>
<tr>
<td>email address:</td>
<td><input type='text' name='email_address' size=20 maxlength=50></td>
</tr>
<tr>
<td>confirm email:</td>
<td><input type='text' name='confirm_email' size=20 maxlength=50></td>
</tr>
<tr>
<td colspan=2 align=center>(up to 50 alphanumerics)</td>
</tr>
<td>password:</td>
<td><input type='password' name='password1' size=20 maxlength=20></td>
</tr>
<tr>
<td>confirm password:</td>
<td><input type='password' name='password2' size=20 maxlength=20></td>
</tr>
<tr>
<td colspan=2 align=center>(between 4 and 20 alphanumerics)</td>
</tr>
<tr>
<td>first name:</td>
<td><input type='text' name='firstname' size=20 maxlength=20></td>
</tr>
<tr>
<td>last name:</td>
<td><input type='text' name='lastname' size=20 maxlength=20></td>
</tr>
<tr>
<td>emergency contact phone number: <br>(ex: 5551239876) </td>
<td><input type='text' name='contact' size=20 maxlength=20></td>
<tr>
<td colspan=2 align=center>(HvZ is a outdoor, active game. We need it incase you get hurt.)</td>
</tr>
</tr>
<tr>
<tr>
<td colspan=2 align=center>
<br>Conduct Agreement:<br>
<div style="overflow:auto; width:400px; height:200px; border:1px groove">

<?php
	$ret = mysql_query("SELECT content FROM pages WHERE pagename='agreement';");
	$res = mysql_fetch_array($ret);
	print($res['content']);
?>

</div></td>
</tr>
<tr>
<td colspan=2 align=center>
<input type='checkbox' name='agree' value='agree'> I agree to the Conduct Agreement. I also understand that failure to follow the agreement will get me removed from the game.  
</td>
</tr>
<tr>
<td colspan=2 align=center>
<input type='checkbox' name='oz_opt' value='oz'> Put me in the original zombie pool
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
<a href="login.php">Back to login</a>
</td></tr></table>

<?php
}
include('template_bottom.php');
ob_end_flush();
?>
