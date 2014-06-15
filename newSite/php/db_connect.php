<?php 
/** This file provides the information for accessing the database.and connecting 
to MySQL. It also sets the language coding to utf-8**/

//Constants Defined
DEFINE ('DB_USER', 'sthrtrav');
DEFINE ('DB_PASSWORD', 'B00z@ll3n');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'sthrtrav_joo1');

//Connect to the desired database host
$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Could not connect: ' . mysql_error());

//Select the correct database
mysql_select_db(DB_NAME) or die('Could not select database');

//Set the Language Encoding as utf-8**/
mysqli_set_charset($dbconn, 'utf8');


?>