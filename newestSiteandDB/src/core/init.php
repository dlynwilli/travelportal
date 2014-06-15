<?php

/*
 * Include the necessary configuration info
 */
include_once '../testing/src/config/constants.php';

/*
 * Define constants for configuration info
 */
foreach ( $C as $name => $val )
{
    define($name, $val);
}

/*
 * Create a database connection object
 */
//$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($dbc->connect_errro){
	echo "Failed to connect to MySQL: (" .$dbc->connect_errno . ") " .$dbc->connect_error;
}

/*
 * Define the auto-load function for classes
 *  called when a script attempts to instantiate a class that hasn't been loaded yet
 */
function __autoload($class)
{
    $filename = "../testing/src/class/class." . $class . ".php";
    if ( file_exists($filename) )
    {
        include_once $filename;
    }
}

?>