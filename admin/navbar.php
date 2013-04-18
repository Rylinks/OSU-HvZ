<?php
ob_start();
session_start();
require_once('security.php'); 
require_once('../functions/load_config.php'); 
require_once('../functions/quick_con.php'); 
require_once('include/postmessage.php');
?>

<html> 
<head> 
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>

<body> 
<h3>Navagation</h3>
<br>
<a href='../index.php' target='display'> View Front Page </a>
<br>
<br>
<br>
<strong><font size="2">
<?php
$msg = GetUnreadMessages();

if($msg > 1)
{
	print "<a href='messages.php' target='display'>You Have $msg Unread Messages</a>";
}
else if( $msg == 1)
{
	print "<a href='messages.php' target='display'>You Have $msg Unread Message</a>";
}
else
{
	print "No Unread Messages";
}

?>
<a href='navbar.php'> (Refresh)</a>
</font>
</strong>
<br>
<br>
<strong>PreGame</strong>
<br>
<a href='flow.php' target='display'>Game Flow</a><br>
<a href='time.php' target='display'>Time Zone</a><br>
<a href='addnpcs.php' target='display'>Add NPCs</a><br>
<br>
<strong>Player Management</strong>
<br>
<a href='players.php' target='display'>Edit Players</a><br>
<a href="addplayer.php" target='display'>Add Player</a><br>
<a href='mailer.php' target='display'>Mailing Lists</a><br>
<br>
<strong>Website Management</strong>
<br>
<a href='blag_update.php' target='display'>Post Blog Entry </a><br>
<a href='pages.php' target='display'>Edit Pages</a><br />

<br>
<strong>Admins</strong>
<br>
<a href='messages.php' target='display'>Message Center</a><br>
<a href='admins.php' target='display'>Admin List</a><br>

<br>
<strong>Account</strong>
<br>
<a href='account.php' target='display'>My Account</a><br>
<a href='logout.php' target=_top>Logout</a>
<br>
<br>
<br>

</body> 
</html>

<?php
ob_end_flush();
?>
