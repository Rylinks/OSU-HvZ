<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Humans vs. Zombies :: Source</title>
	<link rel="stylesheet" type="text/css" href="style/main.css" />
	
	<script type="text/javascript" src="/js/gotos.js"></script>
	
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td height="29" bgcolor="#2E2C2D">&nbsp;</td>
	<td width="900" height="300" rowspan="2" valign="top" bgcolor="#FFFFFF">
	<table id="Table_01" height="301" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="29" colspan="18" background="images/Top_01.jpg">
		<div align="right" class="style1">
			<div align="right">
<?php
	require_once('functions/util.php');
	if($_POST['submit'] == 'Login') 
	{
		$logname = SanitizeEmail($_POST['user']);
		$logpass = HashPassword(SanitizePassword($_POST['pass']));
		$sql = my_quick_con($config) or die("MySQL problem"); 
		$table_u = $config['user_table'];
	
		$ret = mysql_query("SELECT password, confirmed  FROM $table_u WHERE email='$logname';");
		$res = mysql_fetch_assoc($ret);
		$pass = $res['password'];
		$confirmed = $res['confirmed'];
		
		if(mysql_num_rows($ret) == 1 && $pass == $logpass) 
		{
			$ret = mysql_query("SELECT id, confirmed FROM $table_u WHERE email='$logname';");
			$asc = mysql_fetch_assoc($ret);
			$id = $asc['id'];
			$c = $asc['confirmed']; 
			mysql_free_result($ret);
			
			//check if they're confirmed yet
			if ($c == 1)
			{
				$_SESSION['user'] = $logname;
				$_SESSION['id'] = $id;
				header('Location:'.$PHP_SELF);
			} else {
				echo "You have not confirmed your account!";
				echo "if you did not recieve the confirmation e-mail, or you already confirmed your account, e-mail us at mods@osundead.com";
				
			}
		} 
		else 
		{
			echo "&nbsp;&nbsp;|&nbsp;&nbsp;<font color=red>Invalid email/password</font>";
		}
		if(is_resource($ret)) 
		{
			mysql_free_result($ret);
		}
	}

	if(!isset($_SESSION['user'])) 
	{
		echo'<form method=POST action=';
		echo $PHP_SELF;
		echo '>
			<a href="register.php">Register</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<input name="user" type="text" value="Email" size="15" maxlength=50/>
			<input name="pass" type="password" value="Password" size="15" maxlength=30 />
			<input type="submit" name="submit" value="Login">
			&nbsp;&nbsp;|&nbsp;&nbsp;<a href="pass_reset.php">Forgot your Password?</a>
			</form>';
	} 
	else 
	{ 
		echo $_SESSION['user'].' <a href="account.php">My Account</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="logout.php">Log Out</a>';
	}     
	
	$sql = my_quick_con($config) or die("MySQL problem");
	$query = "SELECT * FROM pages WHERE pagename='sidebar';";
	$result = mysql_query($query);
	if($result == false)
	{
		die( "mysql error" . mysql_error());
	}
	$row2 = mysql_fetch_array($result);
	$sidebar = $row2['content'];
	
?>
			</div>
			<label><div align="left"></div></label>
		</div>
		</td>
		<td rowspan="8"><img src="images/Top_02.jpg" width="2" height="300" alt="" /></td>
	</tr><tr>
		<td colspan="18"><img src="images/Top_03.jpg" width="898" height="14" alt="" /></td>
	</tr><tr bgcolor="#FFFFFF">
		<td rowspan="3"><img src="images/Top_04.jpg" width="21" height="210" alt="" /></td>
		<td colspan="8" rowspan="2"><img src="images/Top_05.png" width="544" height="195" alt="" /></td>
		<td colspan="9"><img src="images/Top_06.png" width="333" height="39" alt="" /></td>
	</tr><tr>
		<td colspan="3" rowspan="2"><img src="images/Top_07.jpg" width="51" height="171" alt="" /></td>
		<td height="156" colspan="5" align="left" valign="top" background="images/Top_08.jpg"><div align="center" class="style2"><?php print $sidebar ?></div></td>
		<td rowspan="3"><img src="images/Top_09.jpg" width="57" height="173" alt="" /></td>
	</tr><tr>
		<td colspan="8"><img src="images/Top_10.jpg" width="544" height="15" alt="" /></td>
		<td colspan="5"><img src="images/Top_11.jpg" width="225" height="15" alt="" /></td>
	</tr><tr>
		<td colspan="2" rowspan="2"><a href="index.php"><img src="images/Top_12.png" alt="" width="112" height="44" border="0" /></a></td>
		<td rowspan="3"><img src="images/Top_13.jpg" width="1" height="47" alt="" /></td>
		<td rowspan="3"><a href="report.php"><img src="images/Top_14.png" alt="" width="161" height="47" border="0" /></a></td>
		<td rowspan="3"><a href="players.php"><img src="images/Top_15.png" alt="" width="113" height="47" border="0" /></a></td>
		<td rowspan="3"><img src="images/Top_16.jpg" width="1" height="47" alt="" /></td>
		<td rowspan="2"><a href="index.php?p=rules"><img src="images/Top_17.png" alt="" width="113" height="44" border="0" /></a></td>
		<td rowspan="3"><img src="images/Top_18.jpg" width="1" height="47" alt="" /></td>
		<td colspan="2" rowspan="2"><a href="http://www.humansvszombies.org" target="_blank"><img src="images/Top_19.png" alt="" width="93" height="42" border="0" /></a></td>
		<td rowspan="3"><img src="images/Top_20.jpg" width="1" height="47" alt="" /></td>
		<td colspan="2" rowspan="2"><a href="story.php"><img src="images/Top_21.png" alt="" width="93" height="44" border="0" /></a></td>
		<td colspan="4"><img src="images/Top_22.jpg" width="152" height="2" alt="" /></td>
	</tr><tr>
		<td rowspan="2"><img src="images/Top_23.jpg" width="1" height="45" alt="" /></td>
		<td><img src="images/Top_24.png" alt="" width="93" height="42" border="0" /></a></td>
		<td rowspan="2"><img src="images/Top_25.jpg" width="1" height="45" alt="" /></td>
		<td colspan="2"><a href="http://forums.humansvszombies.org" target="_blank"><img src="images/Top_26.png" alt="" width="114" height="42" border="0" /></a></td>
	</tr><tr>
		<td colspan="2"><img src="images/Top_27.jpg" width="112" height="3" alt="" /></td>
		<td><img src="images/Top_28.jpg" width="113" height="3" alt="" /></td>
		<td colspan="2"><img src="images/Top_29.jpg" width="93" height="3" alt="" /></td>
		<td colspan="2"><img src="images/Top_30.jpg" width="93" height="3" alt="" /></td>
		<td><img src="images/Top_31.jpg" width="93" height="3" alt="" /></td>
		<td colspan="2"><img src="images/Top_32.jpg" width="114" height="3" alt="" /></td>
	</tr><tr>
		<td><img src="images/spacer.gif" width="21" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="91" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="161" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="113" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="113" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="63" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="30" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="20" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="73" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="93" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="57" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="57" height="1" alt="" /></td>
		<td><img src="images/spacer.gif" width="2" height="1" alt="" /></td>
	</tr>
	</table>
	</td>
	<td height="29" align="left" valign="top" bgcolor="#2E2C2D">&nbsp;</td>
</tr><tr>
	<td style="background:url(images/LeftBKGRNDImage.jpg) no-repeat right top" bgcolor="#000000">&nbsp;</td>
	<td align="left" style="background:url(images/RightBKGRNDImage.jpg) no-repeat left top" bgcolor="#000000">&nbsp;</td>
</tr><tr>
	<td style="background:url(images/LeftColumn.jpg) right top repeat-y">&nbsp;</td>
	<td width="900" bgcolor="#FFFFFF">
	<table width="100%" border="0" cellspacing="10" cellpadding="10">
	<tr>
		<td valign="top">
