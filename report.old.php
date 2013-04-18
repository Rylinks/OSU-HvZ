<?php
	ob_start();
	session_start();
	
	//require_once('security.php');
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php'); 
	require_once('functions/util.php');
	
	$config = load_config('settings/config.dat'); 
	
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table'];
	$table_u = $config['user_table'];
	$table_t = $config['time_table'];
	// Set default time zone
	$ret = mysql_query("SELECT zone FROM $table_t");
	while($row = mysql_fetch_array($ret))
	   date_default_timezone_set($row['zone']);
	   
	$ret = mysql_query("UPDATE $table_u SET state = -4 WHERE now() > feed + INTERVAL 2 day;");
	$ret = mysql_query("UPDATE $table_u SET starved = feed + INTERVAL 2 day WHERE state = -4;");
	$ret = mysql_query("UPDATE $table_u SET state = 0 WHERE state = -4;");
	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey='oz-revealed';");
?>



<?php
	require_once('template_top.php');

	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey='game-started';");
	$game_started = mysql_fetch_assoc($ret); 
	$game_started = $game_started['value'];
	if($game_started == 0) 
	{
		mysql_free_result($ret);
		mysql_close($sql); 
		header("location:game_no_start.php");
	}
	$ret = mysql_query("SELECT value FROM $table_v WHERE zkey='game-over';");
	$game_over = mysql_fetch_assoc($ret);
	$game_over = $game_over['ret'];
	if($game_over == 1) 
	{
        mysql_free_result($ret);
        mysql_close($sql);
        header("location:game_over.php");
	}
	

	
	if($_POST['submit'] == 'Report Kill') 
	{
		$victim = strtoupper(ereg_replace("[^A-Za-z0-9]","",$_POST['victim_id']));
		$feed[0] = $_SESSION['id'];
		$feed[1] = ereg_replace("[^A-Za-z0-9]","",$_POST['feed1']);
		$hour = ereg_replace("[^0-9]","",$_POST['hour']);
		$minute = ereg_replace("[^0-9]","",$_POST['minute']);
		$today = ereg_replace("[^0-1]","",$_POST['day']);
		$err = 0; 
		$ret = mysql_query("SELECT value FROM $table_v WHERE zkey='oz-revealed';");
		$revealed = mysql_fetch_assoc($ret);
		$revealed = $revealed['value'];

		print "<table height=100% width=100%><tr><td align=center valign=center><font color='red'>";

		$ret = mysql_query("SELECT state FROM $table_u WHERE id='$victim';");
		$vstate = mysql_fetch_assoc($ret); 
		$vstate = $vstate['state'];
		if(mysql_num_rows($ret) == 0) 
		{
			print "The ID number you entered could not be found.<br>"; 
			$err = 1; 
		} 
		else if($vstate <= 0) 
		{
			print "Eating that person won't help you.<br>"; 
			$err = 1; 
		} 
		else 
		{
			$current_hour = date('H');
			$current_minute = date('i');
			
			if($today == 1 && (($hour > $current_hour) || ($hour == $current_hour && $minute > $current_minute))) 
			{
				print "You can't eat people from the future.<br>";
				$err = 1;
			}
		}
		
		print(sizeof($feed));
		for($i = 0; $i < sizeof($feed) && $err == 0; $i++) 
		{ 
			print("<br> $i");
			if(strlen($feed[$i]) > 0) 
			{
				if(!$revealed) 
				{
					$f = $feed[$i];
					$ret = mysql_query("SELECT state FROM $table_u WHERE id='$f';");
					$temp = mysql_fetch_assoc($ret);
					$temp = $temp['state'];
					
					if($temp == '-2') $feed[$i] = '1';
				}
				
				$ret = mysql_query("SELECT state, fname, lname, feed FROM $table_u WHERE id='$feed[$i]';"); 
				
				if(mysql_num_rows($ret) == 0) 
				{ 
					print "Something regarding IDs went wrong!";
					$err = 1; 
					break; 
				}

				$row = mysql_fetch_row($ret); 

				$f_state = $row[0]; 
				$feed_name = "$row[1] $row[2]";



				if($f_state == 0) 
				{		
					print "$feed_name is dead."; 
					$err = 1; 
					break; 
				}
				else if($f_state > 0) 
				{		
					print "That player is already a zombie!"; 
					$err = 1;
					break; 
				}

			}
		}
		if($err == 0) 
		{
			$of = $feed[0];
			$kill_time = date('Y-m-d') . " $hour:$minute:00";
			$ret = mysql_query("UPDATE $table_u SET kills = kills + 1 WHERE id='$of';");
			$ret = mysql_query("UPDATE $table_u SET state = -1 WHERE id='$victim';");
			$ret = mysql_query("UPDATE $table_u SET feed = TIMESTAMP '$kill_time' + INTERVAL 1 hour WHERE id='$victim';");
			if($today == 0)
			{			
				$ret = mysql_query("UPDATE $table_u SET feed = feed - INTERVAL 1 day WHERE id='$victim';");
			}

			$ret = mysql_query("UPDATE $table_u SET killed_by = '$of' WHERE id='$victim';");
			$ret = mysql_query("UPDATE $table_u SET killed = TIMESTAMP '$kill_time' WHERE id='$victim';");

			if($today == 0) 
			{
				$ret = mysql_query("UPDATE $table_u SET killed = killed - INTERVAL 1 day WHERE id='$victim';");
			}
			
			if(strlen($feed[1]) > 0) //FEEDSHARING IS HAPPENING
			{
				if(is_resource($ret)) 
				{
					mysql_free_result($ret);
				}
      
				$ret = mysql_query("UPDATE $table_u SET feed = TIMESTAMP '$kill_time' - INTERVAL 1 day WHERE id = '$feed[0]' and timediff(TIMESTAMP '$kill_time' + INTERVAL 1 day,now()) > timediff(feed + INTERVAL 2 day,now());");
				if($ret && $today == 0) 
				{
					$ret = mysql_query("UPDATE $table_u SET feed = feed - INTERVAL 1 day WHERE id='$feed[0]';");
				}

				$ret = mysql_query("UPDATE $table_u SET feed = TIMESTAMP '$kill_time' - INTERVAL 1 day WHERE id = '$feed[1]' and timediff(TIMESTAMP '$kill_time' + INTERVAL 1 day,now()) > timediff(feed + INTERVAL 2 day,now());");
				if($ret && $today == 0) 
				{
					$ret = mysql_query("UPDATE $table_u SET feed = feed - INTERVAL 1 day WHERE id='$feed[1]';");
				}
			} else { //NO FEEDSHARING
				if(is_resource($ret)) 
				{
					mysql_free_result($ret);
				}
      
				$ret = mysql_query("UPDATE $table_u SET feed = TIMESTAMP '$kill_time' WHERE id = '$feed[0]' and timediff(TIMESTAMP '$kill_time' + INTERVAL 2 day,now()) > timediff(feed + INTERVAL 2 day,now());");
				if($ret && $today == 0) 
				{
					$ret = mysql_query("UPDATE $table_u SET feed = feed - INTERVAL 1 day WHERE id='$feed[0]';");
				}

			}
			
			//TWITTER API
			// Get victim name
			$ret = mysql_query("SELECT * FROM $table_u WHERE id='$victim';");
			$vic_row = mysql_fetch_assoc($ret);
			// Get zombie name and state
			$ret = mysql_query("SELECT * FROM $table_u WHERE id='$of';");
			
			$zom_row = mysql_fetch_assoc($ret);
			$vfname = $vic_row['fname'];
			$vlname = $vic_row['lname'];
			$vemail = $vic_row['email'];
			
			$kfname = $zom_row['fname'];
			$klname = $zom_row['lname'];
			// The message you want to send
			// OZ is not revealed, OZ makes kill
			if(!$revealed && $zom_row['state'] < -1)
			{
				$message = "The original zombie has killed " . $vfname . " " . $vlname . ".";
			}
			// Non-OZ or OZ after reveal makes a kill
			else
			{
				$message = $kfname . " " . $klname . " has killed " . $vfname . " " . $vlname . ".";	
			}
			tweet($message);	
			
			$killedtext = "Greetings $vfname,\n\n";
			$killedtext = $killedtext." Welcome to the Horde.  You have been killed by $kfname, and are now part of the zombie horde";
			$killedtext = $killedtext."\nSo, now, put your armband on your head and have fun!\n\n-HvZ Mods";
			send_email($vemail, "Osu HvZ: Welcome to the Zombie Horde", $killedtext);
			
			
			
			
			print "Kill reported.<br><a href=$PHP_SELF>Go Back</a>";


		} 
		else 
		{
			print "<a href=$PHP_SELF>Go Back</a>";
		}
		print "</td></tr></table>";
	} 
	else 
	{

		if(!isset($_SESSION['user']))
		{
			print("<center><h2>You need to be logged in to do this!</h2></center>");
		}
		else
		{
			$id = $_SESSION['id'];
			$ret = mysql_query("SELECT state FROM $table_u WHERE id='$id';");
			$state = mysql_fetch_assoc($ret); 
			$state = $state['state'];
		if ($state == 1) {
			print("<center><h2>You need to be a zombie to report kills!</h2><br> Sometimes it takes some time for your killer to report you.  <br> if it's been more than an hour since you were tagged, emaul us at mods@osundead.com</center>");
		} else {
?>



<form method=POST action=<?php echo $PHP_SELF; ?>>
	<center><h1>Report Kills:</h1>
	
	
		<table border>
			<tr>
				<td>victim</td>
				<td><input type='text' name='victim_id' size=20></td>
			</tr>

			<tr>
				<td colspan=2>
					<select name="day">
					<option value='0'>yesterday</option>
					<option value='1' selected>today</option>
				</select>
					<select name="hour">
<?php 
					for($i = 0; $i < 24; $i++) 
					{
						print "<option value='$i'>$i</option>";
					}
					
?>
					</select>
					
					<select name="minute">
<?php
					for($i = 0; $i < 60; $i++) 
					{
						print "<option value='$i'>$i</option>";
					}
					
?>
					</select>
				</td>
			</tr>

			<tr>
				<td>feed share?</td>
				<td>
					<select name='feed1'>
					<option></option>
<?php

			$pid = $_SESSION['id']; 
			if($reveal_oz) 
			{
				$ret = mysql_query("SELECT id, fname, lname, timediff(feed + INTERVAL 2 day, now()) FROM $table_u WHERE state < 0 AND id != '$pid' ORDER BY feed ASC;"); 
			}
			else
			{
				$ret = mysql_query("SELECT id, fname, lname, timediff(feed + INTERVAL 2 day, now()) FROM $table_u WHERE state < 0 AND state != -2 AND id != '$pid' ORDER BY feed ASC;");
			}
			
			for($i = 0; $i < mysql_num_rows($ret); $i++) 
			{
				$row = mysql_fetch_row($ret); 
				$till_starve = $row[3];
				print "<option value='$row[0]'>$row[1] $row[2] ($till_starve)</option>"; 
			}

?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center>
					<input type='submit' name='submit' value='Report Kill'>
				</td>
			</tr>
		</table>
		
		
		
		<tr><td align=center valign=top>
			Victim ID is not case sensitive
			<br><b>Current Time: </b>
					


<?php 

				print date('l jS \of F Y h:i:s A');
				print "<br />";
				$ret = mysql_query("SELECT zone FROM $table_t");
				$row = mysql_fetch_array($ret);
				print "<b>Timezone: </b>" . ($row['zone']);
		}
				

	}
	}
	include('template_bottom.php');

?>
