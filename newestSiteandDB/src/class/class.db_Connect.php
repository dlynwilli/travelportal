<?php 
/** This class file provides the access information for accessing the database and connecting
* to MySQL database using the MySQL Improved Extension mySQLi
*
*/

class DB_Connect {
	/**
     * Stores a mysqli database object
     *
     * @var object A database object
     */
    protected $db;
	
	/**
     * Checks for a DB connection object or creates one if one isn't found
     *
     * @param object $dbo A database connection object
     */
    protected function __construct($dbc=NULL)
    {
        if ( is_object($dbc) )
        {
            $this->db = $dbc;
        }
        else
        {
            // Constants are defined in constants.php
            
            try
            {
				$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                //$this->db = mysql_connect(DB_HOST, DB_USER, DB_PASS)or die('Could not connect: ' . mysql_error());
				//mysql_select_db(DB_NAME, $this->db) or die('Could not select database');
				
            }
            catch ( Exception $e )
            {
                // If the DB connection fails, output the error
                //die ( $e->getMessage() );
				echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " .$db->connect_error;
				
            }
        }
    }
}


?>