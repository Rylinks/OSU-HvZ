<?php 

require_once('../functions/load_config.php');
require_once('../functions/quick_con.php');

function PostMessage($message)
{
	PostMessageUN($message, $_SESSION['AdminUser']);
}

function PostMessageUN($message, $username)
{
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	
	$mtable = $config['message_table'];
	
	$from = $username;
	$when = GetCurrDateTime();
	$content = $message;
	
	$ret = mysql_query("SELECT fromun FROM $mtable");
	$rows = mysql_num_rows($ret) + 1;
		
	$query = "INSERT INTO $mtable (post, fromun,whentime,content) VALUES ('$rows','$from','$when','$content');";
	
	$ret = mysql_query($query);
	if(!$ret)
	{
		print "<br>".mysql_error();
	}


}
function GetTotalMessages()
{
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	
	$mtable = $config['message_table'];
	
	$ret = mysql_query("SELECT fromun FROM $mtable WHERE islog = 0");
	$rows = mysql_num_rows($ret);
	
	return $rows;

}

function GetUnreadMessages()
{
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem");
	
	$atable = $config['admin_table'];
	$mtable = $config['message_table'];
	
	$ret = mysql_query("SELECT fromun FROM $mtable");
	$messages = intval(mysql_num_rows($ret));
	$username = $_SESSION['AdminUser'];
	
	$ret = mysql_query("SELECT readto FROM $atable WHERE username = '$username';");
	$to = mysql_fetch_assoc($ret);
	$to = intval($to['readto']);
	
	$unread = $messages - $to;
	
	return $unread;

}

function GetCurrDateTime()
{
	return date('l jS \of F Y h:i:s A');
}

?>