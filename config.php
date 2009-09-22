<?php
// turtle = hostname
// pie = username
// sky = password
$turtle="localhost";
$database="webpoll";
$pie=$sky="poll";
$db=@mysql_connect($turtle,$pie, $sky);
if ($db)
  $dbselect=@mysql_select_db($database);
$baseurl="http://localhost/~matt/webpoll/";
?>

