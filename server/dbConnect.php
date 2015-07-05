<?php
$db_host = "localhost" ;
$db_username = "" ;
$db_pass = "" ;
$db_name = "" ;
$con = mysql_connect("$db_host","$db_username","$db_pass") or die ("could not connect to MYSQL") ;
mysql_select_db("$db_name", $con) or die ("no database exists") ;
?>