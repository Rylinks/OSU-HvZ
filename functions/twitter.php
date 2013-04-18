<?php
require_once(dirname(__FILE__).'/../lib/twitteroauth/twitteroauth.php');
require_once('load_config.php');

function tweet($msg) {
	if (strlen($msg) > 140 || strlen($msg) == 0)
		return;

	$config = load_config('settings/config.dat');

	$c_key = $config['twit_cKey'];
	$c_secret = $config['twit_cSecret'];
	$a_key = $config['twit_aKey'];
	$a_secret = $config['twit_aSecret'];

	print("$c_key <-CKEY");

	$t = new TwitterOAuth($c_key, $c_secret, $a_key, $a_secret);
	$result = $t->OAuthRequest('https://twitter.com/statuses/update.json', 'POST', array('status' => $msg));	
}
?>
