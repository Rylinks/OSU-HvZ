<?php
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	require_once('../functions/util.php');
	
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	$table_t = $config['time_table'];
	
	// Set default time zone
	$ret = mysql_query("SELECT zone FROM $table_t");
	while($row = mysql_fetch_array($ret))
	   date_default_timezone_set($row['zone']);
	   
	$table_u = $config['user_table'];
	$pid = $_GET['id'];

?>

<html>
<head>
<link rel='stylesheet' type='text/css' href='style/main.css'>

<script type="text/javascript">
function setTimeNow(fieldname) {
	var f = document.editPlayerForm;
	
	var today = new Date();
	
	var year = today.getFullYear();
	var mon = (today.getMonth() + 1);
	mon = (mon < 10) ? "0" + mon : mon;
	var day = today.getDate();
	day = (day < 10) ? "0" + day : day;
	
	var hour = today.getHours();
	hour = (hour < 10) ? "0" + hour : hour;
	var mins = today.getMinutes();
	mins = (mins < 10) ? "0" + mins : mins;
	var secs = today.getSeconds();
	secs = (secs < 10) ? "0" + secs : secs;
	
	eval("f." + fieldname + ".value = '" + year + "-" + mon + "-" + day + " " + hour + ":" + mins + ":" + secs + "'");
}
</script>

</head>
<?php
	if($_POST['submit'] == 'Update Table Values')  // I blame Garrett
	{
		$n_fname = SanitizeWord($_POST['fname']); 
		$n_lname = SanitizeWord($_POST['lname']);
		$n_email =  SanitizeEmail($_POST['email']);
		$n_state = SanitizeNumber($_POST['state']);
		$n_killed_by = SanitizeWrod($_POST['killed_by']);
		$n_kills = SanitizeNumber($_POST['kills']);
		$n_killed = SanitizeAlphaNum($_POST['killed']);
		$n_feed = SanitizeTimestamp($_POST['feed']);
		$n_starved = SanitizeTimestamp($_POST['starved']);
		$phone = SanitizeNumber($_POST['phone']);
		$confirmed = SanitizeNumber($_POST['confirmed']);
		$ozopt = SanitizeNumber($_POST['ozopt']);
		$orientation = SanitizeNumber($_POST['orientation']);
		$liability = SanitizeNumber($_POST['liability']);
	
		$query = "UPDATE $table_u SET fname = '$n_fname', lname = '$n_lname', email = '$n_email', state = $n_state, killed_by = '$n_killed_by', kills = $n_kills, contact='$phone', oz_opt='$ozopt', confirmed='$confirmed', liability='$liability', orientation='$orientation'  WHERE id = '$pid'";
		$ret = mysql_query($query);
	
		if(strlen($n_killed) > 0) 	$ret = mysql_query("UPDATE $table_u SET killed = TIMESTAMP '$n_killed' WHERE id='$pid';");
		if(strlen($n_feed) > 0) 	$ret = mysql_query("UPDATE $table_u SET feed = TIMESTAMP '$n_feed' WHERE id='$pid';");
		if(strlen($n_starved) > 0) 	$ret = mysql_query("UPDATE $table_u SET starved = TIMESTAMP '$n_starved' WHERE id='$pid';");
		
		
		print "<body><table height=100% width=100% border='0'><tr><td align=center valign=center>";
		print "Database values updated.<br><a href=$PHP_SELF>Back to edit page</a>";
		print "</td></tr></table></body>";
		
	} 
	else if($_POST['submit'] == 'Change Password') 
	{
		print "<body><table height=100% width=100%><tr><td align=center valign=center>";
		
		$pass1 = HashPassword(SanitizePassword($_POST['pass1']));
		$pass2 = HashPassword(SanitizePassword($_POST['pass2'])); 

		if($pass1 == $pass2) 
		{
			$ret = mysql_query("UPDATE $table_u SET password = '$pass1' WHERE id='$pid';");
			print "Password updated.<br><a href=$PHP_SELF>Back to edit page</a>";
		} 
		else 
		{
			print "The passwords you entered did not match.<br><a href=$PHP_SELF>Back to edit page</a>";
		}
		
		print "</td></tr></table></body>";
		
	} 
	else if($_POST['submit'] == 'Save') 
	{
		print "<body><table height=100% width=100%><tr><td align=center valign=center>";
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
		$pic =  preg_replace($search, '', $_POST['newpic']);
		print "<table width=100% height=100%><tr><td align=center valign=center>";
		print $pic;
		print "Great Success! <br> <a href='$PHP_SELF'>Back</a>";
		$ret = mysql_query("UPDATE $table_u SET pic_path = '$pic' WHERE id='$pid';");
		print "</td></tr></table>";
		
		print "</td></tr></table></body>";
	} 
	else 
	{
		$ret = mysql_query("SELECT * FROM $table_u WHERE id='$pid';");
		print "<br>" . mysql_error() . "<br>";
		$row = mysql_fetch_assoc($ret);
?>
<body>
<table width=100% border>

<tr><td colspan=2 align=center>
<h4>Editing: <?php print "$row[0] $row[1] (ID: $pid)"; ?></h4>
</td></tr>

<tr><td width=50% valign=top>

<form name="editPlayerForm" method="POST" action="<?php echo $PHP_SELF; ?>">
<table>
<tr>
	<td>email:</td>
	<td><input type='text' name='email' size=20 value=<?php echo $row["email"]; ?>></td>
</tr>
<tr>
	<td>fname:</td>
	<td><input type='text' name='fname' size=20 value=<?php echo $row["fname"]; ?>></td>
</tr>
<tr>
	<td>lname:</td>
	<td><input type='text' name='lname' size=20 value=<?php echo $row["lname"]; ?>></td>
</tr>

<tr>
	<td>Emergency Contact Phone:</td>
	<td><input type='text' name='phone' size=20 value=<?php echo $row["contact"]; ?>></td>
</tr>
<tr>
	<td>OZ Opt:</td>
	<td><input type='checkbox' name='ozopt' value='1' <?php if($row["oz_opt"] == 1) print "checked"; ?>> 
</td>
</tr>
<tr>
	<td>Acct Conf</td>
	<td><input type='checkbox' name='confirmed' value='1' <?php if($row["confirmed"] == 1 ) print("checked"); ?>> 
</td>
</tr>
<tr>
	<td>Attended Orientation</td>
	<td><input type='checkbox' name='orientation' value='1' <?php if($row["orientation"] == 1 ) print("checked"); ?>> 
</td>
</tr>

<tr>
	<td>Liability Waiver</td>
	<td><input type='checkbox' name='liability' value='1' <?php if($row["liability"] == 1 ) print("checked"); ?>> 
</td>
</tr>

<tr>
	<td>state:</td>
	<td><select name='state'>
		<?php
			$st_sel = array('-2' => 'Orig. Zombie', '-1' => 'Zombie', '0' => 'Deceased', '1' => 'Resistance');
			
			if($row["state"] == '-3') {
				print "<option value='-3'>Zombie</option>";
			} else {
				while(list($k,$v) = each($st_sel)) {
					print "<option value='$k'";
					if($row["state"] == $k) print " selected";
					print ">$v</option>";
				}
			}
		?>
	</select></td>
</tr>
<?php
	$rest = array(
		'4' => 'killed_by', 
		'5' => 'kills',
		'6' => 'killed',
		'7' => 'feed',
		'8' => 'starved');
	while(list($k,$v) = each($rest)) 
	{
		$add_time_button = ($k == 6 || $k == 7 || $k == 8) ? '&nbsp;<input type="button" onclick="setTimeNow(\''.$v.'\');" value="now" />' : "";
		print "<tr><td>$v:</td><td><input type='text' name='$v' size='20' value='$row[$v]' />".$add_time_button."</td></tr>\n";
	}
?>
<tr><td colspan=2 align=center><input type='submit' name='submit' value='Update Table Values'></td></tr>
</table>
</form>
<p>
time format: yyyy-mm-dd hh:mm:ss<br>
eg. <?php echo date( 'Y-m-d H:i:s' ); ?><br>
(24hr time)<br>

</td><td valign=top>

<center><form name="changePasswordForm" method=POST action="<?php echo $PHP_SELF; ?>"><table>
<tr><td>new password:</td><td><input type='password' name='pass1' size=20 maxlength=20></td></tr>
<tr><td>retype password:</td><td><input type='password' name='pass2' size=20 maxlength=20></td></tr>
<tr><td colspan=2 align=center><input type='submit' name='submit' value='Change Password'></td></tr>
</table></form>
<hr>
<?php
	if($row[9])
	{
?>
<img src='<?php echo $row[9]; ?>' height=200 alt='no image available'></img><p>
<?php
	}
	else
	{
?>
No Image Avalible
<?php
	}
?>

<form name="editPictureForm" method=POST action="<?php echo $PHP_SELF; ?>">
<input type='text' name='newpic' size=20 value='<?php echo $row[9];?>' >

<center><input type='submit' name='submit' value='Save'></center>

</center>


</td>
</tr>
</table>
</body>

<?php
		mysql_free_result($ret);
	}
?>
</html>

<?php
	mysql_close($sql);
	ob_end_flush();
?>
