ob_start();
session_start();
require_once('security.php');
require_once('../functions/load_config.php');
require_once('../functions/quick_con.php');
$config = load_config('../settings/config.dat');
$sql = my_quick_con($config) or die("MySQL problem"); 
$table_v = $config['var_table']; 
$table_u = $config['user_table'];

<html>
<head>
<title>ID Lookup</title>
<link rel='stylesheet' type='text/css' href='style/main.css'>
</head>

</html>
