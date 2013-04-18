<?php
	//*******************************
	//Admin Register Player
	//Original Copyright 2010 Garrett 'Kylegar' Fleenor
	//Written for Oregon State University's game
	//This file is free to use, do not remove this header.
	//Edited by: Garrett
	
	
	ob_start();
	session_start();
	require_once('security.php');
	require_once('../functions/load_config.php');
	require_once('../functions/quick_con.php');
	$config = load_config('../settings/config.dat');
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_v = $config['var_table']; 
	$table_u = $config['user_table'];
	$err = 0; 
?>

<html>
<head>
<title>NPC Adder</title>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>

<?php
	if($_POST['submit'] == 'Register') 
	{
		
			$sql = my_quick_con($config) or die("MySQL Problem");
			$table_a = $config['user_table'];
			$npcs = intval($_POST['add_count']);

			for($b = 0; $b <= $npcs; $b++)
			{
				$num = $b;
			
				$password1 = $num . "NPC";			
				$email_address = "NPC".$num;			
				$fname = "NPC".$num;
				$lname = "NPC".$num;
		
				$oz_opt = 0; 
		
		
			
				$password = md5($password1);
			
				$id = '';
				$valid_id = 0; 
				$c_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789";
				srand(); 
	
				while($valid_id == 0) 
				{
					$id = '';
					for($i = 0; $i < $config['id_length']; $i++) 
					{
						$n = rand() % 34;
						$id .= substr($c_list, $n, 1); 
					}
					$ret = mysql_query("SELECT * FROM $table_u WHERE id='$id';");
					if(mysql_num_rows($ret) == 0) 
					{
						$valid_id = 1; 
					}
				}
				
				$ret = mysql_query("INSERT INTO $table_u (id, fname, lname, password, email, oz_opt, state, kills, isnpc) VALUES ('$id','$fname','$lname','$password','$email_address','$oz_opt', 1, 0, 1);");
				if(!$ret) print "ERROR." . mysql_error($sql) . "<br>";

				
			
				
		
				print "added $email_address <br>";
			

			}
			
			if(is_resource($ret)) 
			{
				mysql_free_result($ret);
			}

			mysql_close($sql);
			
	} 
	else 
	{
?>




<body>
	<table width=100% border><tr><td align=center valign=center>
		<h3>Add NPCs</h3>
		<form method=POST action=<?php echo $PHP_SELF; ?>>
			<table>
				<tr>
					<td>NPCs to Add:</td>
					<td><input type='text' name='add_count' size=20 maxlength=5></td>
				</tr>
				
					<tr> Right, so:<br> </tr>
					<tr> Before you do anything: <b> DO NOT UNDER ANY CIRCUMSTANCES DO THIS WHILE THE GAME IS PLAYING </b> <br></tr>
					<tr> This will raw add X number of NPCs to the database where X is the number you just put in that input box <br></tr>
					<tr> If you do this multiple times, It will overwrite any NPC already in the database. <br> </tr>
					<tr> Now that you know that: <b> DO NOT UNDER ANY CIRCUMSTANCES DO THIS WHILE THE GAME IS PLAYING </b> <br></tr>
					<tr> To see the NPCs you just added, click the Show NPCs box in the player list.  This will show all NPCs in the database.  Click Edit to see their ID number<br> </tr>
					<tr> NPCs will be named NPCxx where xx is thier number (NPC01, NPC02, etc).  Their password will be xxNPC. (example: un: NPC00 pass: 00NPC) <br></tr>
					<tr> NPCS will not show up in the normal player list.  DO NOT TELL PLAYERS ANYTHING ABOUT NPCS <br></tr>
					<tr> Finally <b> DO NOT UNDER ANY CIRCUMSTANCES DO THIS WHILE THE GAME IS PLAYING </b><br> </tr>

				
		
				<tr>
					<td colspan=2 align=center>
						<input type='submit' name='submit' value='Register'>
						<input type='reset' value='Reset' onClick="return confirm('Reset?');">
					</td>
				</tr>
			</table>
		</form>
	</table>

<?php
	}
?>
</body>
</html>
<?php
	ob_end_flush();
?>
