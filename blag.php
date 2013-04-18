<?php
ob_start();
session_start();
require_once('functions/load_config.php');
require_once('functions/quick_con.php');
$config = load_config('settings/config.dat');
 

$sql = my_quick_con($config) or die("MySQL problem");

$singlepost = false;
$postid = $_GET['postid'];
$archive = $_GET['archive'];
if(!$postid)
{
	$result = mysql_query("SELECT postid, postedby, time, title, content, postedbyun FROM $config[blog_table] ORDER BY postid DESC;");
	if($result == false)
	{
		die( "mysql error" . mysql_error());
	}
	$row = mysql_fetch_assoc($result);
	print mysql_error();
	$postid = $row['postid'];

}
else if(!$archive)
{
	
	$result = mysql_query("SELECT postid, postedby, time, title, content, postedbyun FROM $config[blog_table] WHERE postid = '$postid';");
	if($result == false)
	{
		die( "mysql error" . mysql_error());
	}
	$row = $row = mysql_fetch_assoc($result);
	print mysql_error();

	$singlepost = true;
	
}
?>

<?php include('template_top.php'); ?>
 
<p>
<?php 
	if(!$archive)
	{
		print "<h1>".$row['title']."</h1><hr>";
		print "<h4> By ".$row['postedby']." (".$row['postedbyun'].") on ".$row['time']."</h4>";
		print "<table> <tr>";
		print $row['content']; 
		print "</tr> </table>";
		print "<hr>";
?>
		<a href= <?php print $PHP_SELF."?postid=".$postid."#disqus_thread"; ?>>Comments</a>
		
	</p>
<?php
		if($singlepost)
		{
?>
		<div id="disqus_thread"></div>
		<script type="text/javascript">
			var disqus_developer = true;
			(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = 'http://osuhvz.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript=osuhvz">comments powered by Disqus.</a></noscript>
		<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
 <?php
		}
	print "<b><br><a href=".$PHP_SELF."?archive=1> Blog Archive </a></b>";
	}
	else
	{
		print "<h1>Blog Archive:</h1><hr>";
		$result = mysql_query("SELECT postid, postedby, time, title, content, postedbyun FROM $config[blog_table] ORDER BY postid DESC;");
		if($result == false)
		{
			die( "mysql error" . mysql_error());
		}
		print mysql_error();
		
		if($row && ($rows = mysql_num_rows($result)) > 0)
		{
			print "<h3>Total Posts: ".$rows."</h3>";
			for($i = 0; $i < $rows; $i++) 
			{
				$row = mysql_fetch_assoc($result);
				print "<a href=".$PHP_SELF."?postid=".$row['postid'].">".$row['title']."</a><br>";
				
			}
		}
	
	
	}
 
 ?>
 
<script type="text/javascript">
//<![CDATA[
(function() {
	var links = document.getElementsByTagName('a');
	var query = '?';
	for(var i = 0; i < links.length; i++) {
	if(links[i].href.indexOf('#disqus_thread') >= 0) {
		query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
	}
	}
	document.write('<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/osuhvz/get_num_replies.js' + query + '"></' + 'script>');
})();
//]]>
</script>
 
<?php include('template_bottom.php'); ?>
 
