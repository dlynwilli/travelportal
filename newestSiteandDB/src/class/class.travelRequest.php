<?php
/*
 * Include the necessary configuration info
 */
include '/src/class/class.db_Connect.php';

class TravelRequest extends DB_Connect
{
    private $id;	
	// a timestamp of the submitted date
	private $submitStamp;	
	private $travelerUserID;
	private $govtPOC;
	private $projectID;
	private $attachmentRef;

	private $travelerName;	
	private $projectName;
	private $deliveryOrder;	
	private $contract;
	
	
	private $company;
	/**
     * Creates a database object and stores relevant data
     *
     * Upon instantiation, this class accepts a database object
     * that, if not null, is stored in the object's private $_db
     * property. If null, a new PDO object is created and stored
     * instead.
     *
     * Additional info is gathered and stored in this method,
	 *   Accepts a array of TravelR Request data and stores it
     *
     * @param object $dbo a database object
     * @param array $tRequestArray Associative array of travel request data
     * @return void
     */
	public function __construct($dbc=NULL, $tRequestArray)
    {
        /*
         * Call the parent constructor to check for
         * a database object
         */
        parent::__construct($dbc);
		
		echo 'The class "', __CLASS__, '" was initiated!<br />';
			
		if ( is_array($tRequestArray) )
        {
            $this->id = $tRequestArray['id'];
            $this->submitStamp = $tRequestArray['date_submitted'];
            $this->travelerUserID = $tRequestArray['traveler_user_id'];
			$this->govtPOC = $tRequestArray['govt_poc'];
			$this->projectID = $tRequestArray['project_id'];
			$this->attachmentRef = $tRequestArray['attachment_ref'];
                       
        }
        else
        {
            throw new Exception("No project data was supplied.");
        }
    }
		
	
	/**
	* Load all contracts from the database
	* 
	* @param int $id is an optional contract ID to filter results
	* @return array of contracts from the database
	*/
	private function loadContractData($id=NULL)
	{
		//query SQL statement for all of the Contracts fromt he bah_contract table 
		$contractQueryStmt = "SELECT id, contract_name, contract_number, contract_acronym FROM bah_contract";
		
		/*
		*  If a contract id to a contract is provided then add[concat] a WHERE clause so that only that contract
		*      record is returned
		*/
		if(!empty($id))
		{
			$contractQueryStmt .= "WHERE id =".$id;
		}
		
		try
		{
			$contractResult = mysql_query($contractQueryStmt);
			if (!$contractResult) 
			{
				echo("<p>Error performing Contract query: " . mysql_error() . "</p>\n");
				exit();
			}
			return $contractResult;
			
		}
		catch (Exception $e)
		{
			die($e->getMessage());
			echo("<p>Exception performing Contract query: " . mysql_error() . "</p>\n");
		}
		
	}
	
	/**
	*  load all delivery orders
	*
	* @param int $id is an optional delivery order ID to filter results
	* @return array of delivery orders from the database
	*/
	public function loadDeliveryOrders($id=NULL)
	{
	
	}
	
	/**
	*  load all Projects
	*
	* @param int $id is an optional project/task ID to filter results
	* @return array of projects/tasks from the database
	*/
	public function loadProjects($id=NULL)
	{
	
	}
	
	/**
	*  load all States
	*
	* @param int $id is an optional state ID to filter results
	* @return array of states from the database
	*/
	public function loadStates($id=NULL)
	{
	
	}
	
	public function calTotalDays()
	{
	
	}
	
	public function calTotalNights()
	{
	
	}
	
	public function displayContracts()
	{
		$result = $this->loadContractData();
		while($row = mysql_fetch_row($result))
		{
			$contract_id = $row[0];
			$contract_name = $row[1];	
			$contract_number = $row[2];
			$contract_acronym = $row[3];
			echo("<p>$contract_id \t $contract_name \t $contract_number \t $contract_acronym</p>\n"); 						
			
			//echo("<option id=\"$contract_id\" value=\"$contract_name\" onchange=\"populateData(this.id)\">$contract_name</option>\n"); 						
		}	
	
	}
    
    public function setTravelerName($newval)
    {
        $this->travelerName = $newval;
    }

    
	
	public function setCompany($newval)
    {
        $this->company = $newval;
    }

    private function getCompany()
    {
        return $this->company . "<br />";
    }
	
	//govtPOC
	public function setGovtPOC($newval)
    {
        $this->govtPOC = $newval;
    }

    private function getGovtPOC()
    {
        return $this->govtPOC . "<br />";
    }
	//contract
	public function setContract($newval)
    {
        $this->contract = $newval;
    }

    private function getContract()
    {
        return $this->contract . "<br />";
    }
	//delOrder
	public function setDeliveryOrder($newval)
    {
        $this->deliveryOrder = $newval;
    }

    private function getDeliveryOrder()
    {
        return $this->delOrder . "<br />";
    }
	//project
	public function setProjectName($newval)
    {
        $this->projectName = $newval;
    }

    private function getProject()
    {
        return $this->project . "<br />";
    }
	
    public function getID()
	{
		return 	$this->id;
	}
	
	
	
	private function timestamp()
    {
        $temp = date_create();
		$submitStamp = date_timestamp_get($temp);
		
		//for testing
		//echo "Submitted on: .date_format($submitStamp, "Y/m/d H:i:s");
    }
	
	/**
	*  functions to retrieve data from the database - DESERIALIZE 
	*/
	//protected function to select data from the database
	protected static function dbSelect($dbc, $myQuery)
	{	
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
			
			//create a new Travel Request object 
			$aTravelRequest = new TravelRequest($row);
				
		}
		return $aTravelRequest;
	}
	
	/**
	* Select a TravelRequest Order Records using the TravelRequest ID
	*
	* @param dbc - database connect 
	* @param id_in - the travel request id to select the project by
	*
	* @return the result as a Travel Request Object
	*/
	public static function selectTravelRequestbyID($dbc, $id_in)
	{
		$myQuery = "SELECT * FROM bah_travel_requests WHERE id={$id_in} LIMIT 1";
		
		//make the select statement and return the Array of Delivery Orders	
		return TravelRequest::dbSelect($dbc, $myQuery);	
	}
	
	public static function selectTravelerNameByID($dbc, $user_id)
    {
        $myQuery = "SELECT name FROM g6lrd_users a WHERE id={$user_id} LIMIT 1";
		
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
			$row = $result->fetch_assoc();
			
			//traveler name as a result
			$aName = $row["name"];
			
		}
		
		//$this->travelerName = $aName;
		
		return $aName;
    }
	
}







?>