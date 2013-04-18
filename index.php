<?php
	ob_start();
	session_start();
	require_once('functions/load_config.php');
	require_once('functions/quick_con.php');
	$config = load_config('settings/config.dat');
 
	// get the front content and save it into the session
	$sql = my_quick_con($config) or die("MySQL problem");
	include('template_top.php');
	
	
	if(isset($_GET['p']))
	{	
		$pagename = mysql_real_escape_string($_GET['p']);
	}
	else
	{
		$pagename = 'front';
	}
	
?>

<script type="text/javascript">
function convert(str) {
	x = str.split(":")
	h = parseInt(x[0])*60*60;
	m = parseInt(x[1])*60;
	s = parseInt(x[2]);
	t = h+m+s

	if (h < 0) return 0;
	return t;
}

function countdown(time) {
	t = time - 1;
	if (t < 0) t = 0;
	secs = time % 60;
	mins = Math.floor(time / 60) % 60;
	hours = Math.floor(time / (60*60));
	if (secs < 10) secs = "0"+secs;
	if (mins < 10) mins = "0"+mins;
	if (hours < 10) hours = "0"+hours;
	document.getElementById('time').innerHTML = hours + ":" + mins + ":" + secs;
	setTimeout("countdown(t);", 1000);
}

</script>

<?php
	if ($_SESSION['id']) {
		$id = $_SESSION['id'];
		$table_u = $config['user_table'];
		$ret = mysql_query("SELECT state FROM $table_u WHERE id='$id';"); 
		$state = mysql_fetch_assoc($ret);
		$state = $state['state'];
	
		if ($state < 0) { //they're a zombie-- display starve-out timer.
			$ret = mysql_query("SELECT timediff(feed + INTERVAL 2 day, now()) FROM $table_u WHERE id='$id';");
			$time_left = mysql_fetch_row($ret);
			$time_left = $time_left[0];
			print "<center><h3> Time Until You Starveout: </h3></center>";
			print "<div id='time' style='text-align:center; font-size:32pt'> $time_left </div>";
			print "<script type=\"text/javascript\">countdown(convert('$time_left'));</script>";
		}
	}

	$result = mysql_query("SELECT * FROM pages WHERE pagename='$pagename';");
	
	if(!$result || mysql_num_rows($result) <= 0)
	{	
		print("That Page Doesn't Exist");
	}
	else
	{
		$ret = mysql_fetch_assoc($result);
		print($ret['content']);
	}

?>

<?php include('template_bottom.php'); ?>



