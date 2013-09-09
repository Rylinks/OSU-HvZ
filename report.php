<?php
	ob_start();
	session_start();
	
	//require_once('security.php');
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php'); 
	require_once('functions/util.php');
	require_once 'functions/player.class.php';
	
	$config = load_config('settings/config.dat'); 
	
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table'];
	$table_u = $config['user_table'];
	$table_t = $config['time_table'];
	// Set default time zone
	$ret = mysql_query("SELECT zone FROM $table_t");
	while($row = mysql_fetch_array($ret))
	   date_default_timezone_set($row['zone']);

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
		$feed[0] = $_SESSION['id'];
		$feed[1] = ereg_replace("[^A-Za-z0-9]","",$_POST['feed1']);
		$hour = ereg_replace("[^0-9]","",$_POST['hour']);
		$minute = ereg_replace("[^0-9]","",$_POST['minute']);
		$today = ereg_replace("[^0-1]","",$_POST['day']);
		$ret = mysql_query("SELECT value FROM $table_v WHERE zkey='oz-revealed';");
		$revealed = mysql_fetch_assoc($ret);
		$revealed = $revealed['value'];

		print "<table height=100% width=100%><tr><td align=center valign=center><font color='red'>";

		try {
			$victim = new Player(strtoupper(ereg_replace("[^A-Za-z0-9]","",$_POST['victim_id'])));
			
			if ($victim->data['state'] <=0){
				throw new Exception('Eating that person won\'t help you.');
			}
			
			$current_hour = date('H');
			$current_minute = date('i');
				
			if($today == 1 && (($hour > $current_hour) || ($hour == $current_hour && $minute > $current_minute))){
				throw new Exception('You can\'t eat people from the future.');
			}
			
			foreach ($feed as $id){
				if(strlen($id) > 0){
					$zed = new Player($id);
					$zed_name = $zed->data['fname']." ".$zed->data['lname'];
			
					if($zed->data['state'] == 0){
						throw new Exception("$zed_name is dead.");
					}
					if($zed->data['state'] > 0){
						throw new Exception("$zed_name is not (yet?) a zombie.");
					}
				}
			}
			
			$killer = new Player($feed[0]);
			$is_share = FALSE;
			
			if (strlen($feed[1])>0){
				$friend = new Player($feed[1]);
				$is_share = TRUE;
			}

			if ($today == 0){
				$kill_day = time()-86400; //86400 is one day
			} else {
				$kill_day = time();
			}
			
			if ($is_share){
				$starve_day = $kill_day + 86400; //one day from kill
				$feed_day = $kill_day - 86400;
			} else {
				$starve_day = $kill_day + 86400*2; //two days from kill
				$feed_day = $kill_day;
			}
			
			
			$kill_time = date('Y-m-d', $kill_day) . " $hour:$minute:00";
			$starve_time = date('Y-m-d', $starve_day) . " $hour:$minute:00";
			$feed_time = date('Y-m-d', $feed_day) . " $hour:$minute:00";
			
			
			$victim->kill($killer, $kill_time);
			$killer->nom($feed_time, $kill_time);
			if ($is_share){$friend->receiveShare($feed_time);}
			
			// twitter code
				
			if(!$revealed && $killer->state < -1) {
				$message = "The original zombie has killed " . $victim->fname . " " . $victim->lname . ".";
			} else {   // Non-OZ or OZ after reveal makes a kill
				$message = $killer->fname . " " . $killer->lname . " has killed " . $victim->fname . " " . $victim->lname . ".";
			}
			//include("twitter.php");
			
			print "Kill reported.<br>";

		} catch (Exception $e) {
			print $e->getMessage(). "<br>";
		}
		print "<a href=$PHP_SELF>Go Back</a>";
		print "</td></tr></table>";
		$ret = mysql_query("UPDATE $table_u SET feed = TIMESTAMP '2013-04-21 00:00:00' WHERE state < 0 AND feed < TIMESTAMP '2013-04-21 00:00:00'"); //people can't starve til monday ends
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
				
						$hour = date("H");
					for($i = 0; $i < 24; $i++) 
					{
						print "<option value='$i' ";
						if($i == $hour) print "selected";
						print ">$i</option>";
					}
					
?>
					</select>
					
					<select name="minute">
<?php
					$min = date("i");
					for($i = 0; $i < 60; $i++) 
					{
						print "<option value='$i' ";
						if($i == $min) print "selected";
						print">$i</option>";
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
				$ret = mysql_query("SELECT id, fname, lname, timediff(feed + INTERVAL 2 day, now()) FROM $table_u WHERE state < 0 AND id != '$pid' AND isnpc = 0 ORDER BY feed ASC;"); 
			}
			else
			{
				$ret = mysql_query("SELECT id, fname, lname, timediff(feed + INTERVAL 2 day, now()) FROM $table_u WHERE state < 0 AND state != -2 AND id != '$pid' AND isnpc = 0 ORDER BY feed ASC;");
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
