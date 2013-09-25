<?php
ob_start();
session_start();
require_once('functions/util.php');
require_once('functions/load_config.php');
require_once('functions/quick_con.php');
$config = load_config('settings/config.dat');

$sql = my_quick_con($config) or die(print("MySQL Problem"));
$table_u = $config['user_table'];
$table_c = $config['content_table'];
 
include_once('template_top.php');

	if (isset($_GET['k'])) {
		$key = SanitizeAlphaNum($_GET['k']);
		$result = mysql_query("SELECT * FROM $table_u WHERE hash='$key';");
		$res = mysql_fetch_assoc($result);
		if (mysql_num_rows($result) > 0 && $res['confirmed'] != 1 ) {
			mysql_query("UPDATE $table_u SET confirmed='1' WHERE hash='$key';") or die();
			$ret = mysql_fetch_array($result);
			$email = $res['email'];
			$id = $res['id']
			?>
			<center>
			Confirmed! You can now log in!
			<br />
			<br />
			Your ID Number is: <b><?php print($id); ?></b>.  Please Write this down on TWO notecards or pieces of paper. 
			<br />
			<br />
			If you are tagged by a zombie, give them one id and keep the other on you.  
			<br />
			<br />
			We have also e-mailed your ID number to you, and you can view it the My Account section as well.
			<br />
			<br />
			<?php
				if($res['oz_opt'] =='1' && !isset($_GET['s']))
				{
					?>
					You also checked the OZ Opt-in button, and you have been entered into the random drawing for Original Zombies.  
					<br />
					We will make the OZ selection days before the game begins, and we will contact you at your registration e-mail if you have been selected
					<br />
					If you do not want to be an Original Zombie, send us an e-mail and we will get that sorted.
					<?php
				}
			
			$fname = $res['fname'];
			$lname = $res['lname'];
			$query = "SELECT content FROM pages WHERE pagename='finalemail';";
			$result = mysql_query($query);
			$res = mysql_fetch_array($result);
			$email_text = $res['content'];
			$email_text = str_replace( "!id",$id,$email_text);
			send_email($email,"OSUndead Your ID Number",$email_text);
			print ("</center>");
			
			$fname = $ret['fname'];
			$lname = $ret['lname'];
			$twit = $fname." ".$lname." has registered for HvZ!";
			//tweet($twit);
			
		} else { 
			if (mysql_num_rows($result) <= 0)
			{
				print("Bad confirmation code :(");
			}
			else
			{
				print("This account is already confirmed! You can log in now!");
			}
		}
	} else {
		print("YOU MOVED WRONGLY");
	}

include('template_bottom.php'); 
?>
 
