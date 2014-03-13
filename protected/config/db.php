<!--
   This is for dynamic db connection upon login in yii - user enters the db details

  Joanie C. Antonio <j.antonio@irri.org>
-->
<?php

$path = dirname(__FILE__) . '/../../json_files/database.json';

if(file_exists($path)){
    
$file = file_get_contents($path);
$json_a = json_decode($file,true);

//print_r($json_a);
//echo "<br>".$json_a['local_db_host'];

$host = $json_a['central_db_host'];
$port = $json_a['central_db_port'];
$dbname = $json_a['central_db_name'];
$username = $json_a['central_db_username'];
$password = $json_a['central_db_password'];

return array(
    'connectionString' => 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname . '',
    'emulatePrepare' => true,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
    'enableProfiling' => true,
    'enableParamLogging' => true,
);
}  else {
    return array(
         /* 'connectionString' => 'mysql:host=localhost;dbname=central6',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          'enableProfiling' => true,
           'enableParamLogging'=>true,   */
         'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=iris_mysiam_20121002',
          'emulatePrepare' => true,
          'username' => 'datasourceuser',
          'password' => 'ici$rule$',
          'charset' => 'utf8',
          'enableProfiling' => true,
          'enableParamLogging'=>true,
    );    
}

 ?>
 