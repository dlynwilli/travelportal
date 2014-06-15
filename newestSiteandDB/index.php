<?php

/*
 * Include necessary files
 */
include_once 'src/core/init.php';

/*
 * Load the TravelRequest with a database connection if it exist already.
 *
 *
 */
$request = new TravelRequest($dbc);
if(is_object($request)) 
{
	echo "<pre>", var_dump($request),"</pre>";
}


?>