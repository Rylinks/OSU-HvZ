<? 
  ob_start();
  session_start();
  require_once('security.php');
  require_once('../functions/load_config.php');
  require_once('../functions/quick_con.php');
  $config = load_config('../settings/config.dat');
  $sql = my_quick_con($config) or die("MySQL problem"); 
  $table_v = $config['var_table']; 
  $table_u = $config['user_table'];
  
?>
<html>
<head>
<title>ID Lookup</title>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>
<body><center>
<?
  if($_POST['submit'] == 'Lookup') {
    $id_len  = $config['id_length'];
    $bad_id  = $_POST['id'];
    $bad_len = strlen($bad_id);
    $test = '';
    $results = array();
    
    if (abs($id_len - $bad_len) > 1){
      print ('Error: id length is incorrect');
      exit(0);
    }
    
    if ($id_len > $bad_len){
      $replacement = '_';
      $length      = 0;
    } elseif ($id_len = $bad_len) {
      $replacement = '_';
      $length      = 1;
    } else {
      $replacement = '';
      $length      = 1;
    }

      for ($ii=0; $ii < $bad_len ;$ii++){
        $test = substr_replace($bad_id, $replacement, $ii, $length);
        $ret = mysql_query("SELECT id, fname, lname FROM $table_u WHERE id LIKE '$test'");
        
        while ($row = mysql_fetch_assoc($ret)){
          $results[] = $row['fname']." ".$row['lname']." | ".$row['id'];
        }
      }
      
      foreach ($results as $value){
        print($value.'<br>');
      }
      print('<a href=osundead.com/\'index.php\'>Go Back</a>');
      print('</body></center>');
  } else {
    ?>
    ID: <form name="lookupForm" action="lookup.php" method="post">
    <input type="text" name="id" size=20 maxlength=20><br>
    <input type="submit" name="submit" value="Lookup"><br>
    <a href=../\'index.php\'>Go Back</a>
    </body></center>
    <?
  }
?>
</html>
