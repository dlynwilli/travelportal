<?php

/**
 * Stores Project information
 *
 * 
 */
class Project
{

    protected $id;
    protected $projName;
    protected $delOrderID;	
   

    /**
     * Constructs a Project/Task data object 
     *
     * @param id - database id for the project record
	 * @param proj - the name of the project for this record
	 * @param dodrID - the associated Delivery order ID for this project
	 * @param userID - the assoicated User ID for this project
	 *
     * @return void
     */
    public function __construct($id_in, $proj, $dodrID)
    {
        $this->id = $id_in;
        $this->projName = $proj;
        $this->delOrderID = $dodrID;
    }
	
	/**
	*  functions to retrieve data from the database - DESERIALIZE 
	*/
	//protected function to select data from the database
	protected static function dbSelect($dbc, $myQuery)
	{
		$projArray = array();
		
		//check the connection
		if($dbc->connect_errno)
		{
			printf("connection failed: %s\n", $dbc->connect_error);
			exit();	
		}
		
		$result = $dbc->query($myQuery);
		$row_count = $result->num_rows;
		
		while($row_count > 0)
		{
			//return a row of data as an associative and numberic array
			$row = $result->fetch_array(MYSQLI_BOTH);
			
			//create a new Project object 
			$aProject = new Project($row[0], $row[1], $row[2]);
			$projArray[] = $aProject;
			
			--$row_count;	
		}
		return $projArray;
	}
	
	/**
	*  Select all Project Records using the Delivery ID
	* @param dbc - database connect 
	* @param id_in - the delivery order id to select the project by
	*
	* @return the result as an Array of Project Objects
	*/
	public static function selectProjRecdsByDelID($dbc, $id_in)
	{
		$myQuery = "SELECT * FROM bah_project WHERE delivery_order_id ={$id_in}";
		
		//make the select statement and return the Array of Projects	
		return Project::dbSelect($dbc, $myQuery);		
	}
	
	public static function selectProjectByID($dbc, $project_id)
    {
        $myQuery = "SELECT * FROM bah_project a WHERE id={$project_id} LIMIT 1";
		
		//check the connection
		if($dbc->connect_errno)
		{
			printf("connection failed: %s\n", $dbc->connect_error);
			exit();	
		}
		
		$result = $dbc->query($myQuery);
		
		$row_count = $result->num_rows;
		
		if($row_count > 0)
		{
			//return a row of data as an associative and numberic array
			$row = $result->fetch_array(MYSQLI_BOTH);
			
			//project object as result 
			$aProject = new Project($row[0], $row[1], $row[2]);
			
		}
		
		return $aProject;
    }
	
	public static function selectProjectByUserID($dbc, $user_id)
    {
        $myQuery = "SELECT * FROM bah_project AS p, bah_user_project_map AS m WHERE m.user_id={$user_id} AND p.id=m.project_id LIMIT 1";
		
		//check the connection
		if($dbc->connect_errno)
		{
			printf("connection failed: %s\n", $dbc->connect_error);
			exit();	
		}
		
		$result = $dbc->query($myQuery);
		
		$row_count = $result->num_rows;
		
		if($row_count > 0)
		{
			//return a row of data as an associative and numberic array
			$row = $result->fetch_array(MYSQLI_BOTH);
			
			//project object as result 
			$aProject = new Project($row[0], $row[1], $row[2]);
			
		}
		
		return $aProject;
    }
	
		
	/**
	*  functions to save date into the database - SEARLIZE
	*/
	
	public function insert($dbc)
	{
		$myQuery = "INSERT statement";
		
		//check the connection
		if($dbc->connect_errno)
		{
			printf("connection failed: %s\n", $dbc->connect_error);
			exit();	
		}
		
		if($dbc->query($myQuery) === TRUE)
		{
			printf("insert was successful");	
		} 
		
	}
	
	public function update($dbc)
	{
		$myQuery = "UPDATE statement";
		
		//check the connection
		if($dbc->connect_errno)
		{
			printf("connection failed: %s\n", $dbc->connect_error);
			exit();	
		}
		
		if($dbc->query($myQuery) === TRUE)
		{
			printf("update was successful");	
		}
	}
	
	//project
	public function setProjectName($newval)
    {
        $this->projectName = $newval;
    }

    public function getID()
    {
        return $this->id;
    }
	
	public function getName()
	{
		return $this->projName;	
	}
	
	public function getDOrderID()
	{
		return $this->delOrderID;	
	}

}

?>