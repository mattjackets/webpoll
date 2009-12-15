<?php
// turtle = hostname
// pie = username
// sky = password
$db_hostname="localhost";
$db_username="poll";
$db_password="poll";
$database="webpoll";
$db=@mysql_connect($db_hostname,$db_username, $db_password);
if ($db)
  $dbselect=@mysql_select_db($database);
?>

