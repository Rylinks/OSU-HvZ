<?php
	//General functions for use in the code base

	//Twitter function
	require_once(dirname(__FILE__).'/../lib/twitteroauth/twitteroauth.php');

	function tweet($msg) {
		if (strlen($msg) > 140 || strlen($msg) == 0)
			return;
		$c_key = 'p77aRB7mG0kTlLFFujm0g';
		$c_secret = 'kwo9naO3BVd5jUkgsZNo69JCDrtLeAdkTCX4G3L368';
		$a_key = '79081400-ineODiHXblSrLaN1jwcYl44AUSVfb9pLwdeck8XbY'; //do not underestimate how
		$a_secret = 'HvvqpY5kN20UdyEveUHuF5SISk1UOgxwXTNBvcWJ7w'; //huge of a pain it is to get these
	
		$t = new TwitterOAuth($c_key, $c_secret, $a_key, $a_secret);
		$result = $t->OAuthRequest('https://twitter.com/statuses/update.json', 'POST', array('status' => $msg));	
	}

	function send_email($to, $sub, $msg) {
		//at some point this will send an email instead of be debuggy
		//print("<!-- SENDING AN EMAIL TO $to : $sub / $msg -->");
		$headers = 'From: noreply@OSUndead.com';
		mail($to, $sub, $msg, $headers);
	}

	function gen_hash() {
		$z = md5(microtime());
		return substr($z, 3, 13);
	}

	//Password Best Practice rule 1: Salt your passwords (beats rainbow tables)
	//Password Best Practice rule 2: double hash your passwords (beats brute forcing and again rainbow tables)
	//Garrett's best practice: Lets just do both!
	function HashPassword($password)
	{
		$Salt1 = 'bob';
		$Salt2 = 'lol';
	
		$ret = md5($password.$salt1);
		$ret = md5(md5($salt1).$ret.$salt2);
		return $ret;
	}
	
	//Sanitization Functions.  Call these when we need to validate any input from the user.  
	//
	function SanitizeEmail($inputstring)
	{
		return ereg_replace("[^A-Za-z0-9_.-\+]","",$inputstring);
	}

	function SanitizePassword($inputstring)
	{
		return ereg_replace("[^A-Za-z0-9_.-]","",$inputstring);
	}
	function SanitizeNumber($inputstring)
	{
		return ereg_replace("[^0-9\-]","",$inputstring);
	
	}
	function SanitizeWord($inputstring)
	{
		return ereg_replace("[^A-Za-z]","",$inputstring);
	}
	function SanitizeUsername($inputstring)
        {
                return ereg_replace("[^A-Za-z0-9]","",$inputstring);
        }
	function SanitizeAlphaNum($inputstring)
	{
		return ereg_replace("[^A-Za-z0-9]","",$inputstring);
	}

?>


