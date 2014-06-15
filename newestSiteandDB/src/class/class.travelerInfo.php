<?php

/*
 * Include the necessary configuration info
 */
include '/src/class/class.travelRequest.php';
include '/src/class/class.project.php';
include '/src/class/class.deliveryOrder.php';
include '/src/class/class.contract.php';

include_once '/src/config/constants.php';
/*
 * Define constants for configuration info
 */
foreach ( $C as $name => $val )
{
    define($name, $val);
}

/**
 * Stores Traveler information as a record for the traveler to enter 
 *    their information for the trip
 *
 * 
 */
class TravelerInfo 
{

/*
 * Create a database connection object
 */
//$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
public $dbc = "";


/*
 * Define the auto-load function for classes
 *  called when a script attempts to instantiate a class that hasn't been loaded yet
 */
function __autoload($class)
{
    $filename = "/src/class/class." . $class . ".php";
    if ( file_exists($filename) )
    {
        include_once $filename;
    }
}



	
	//database record id for this travelerInfo
	private $id;
		  	
	//name of the traveler
    private $travelerName;
	
	//name of the company
	private $company;
	
	//name of the government point of contact for this traveler's trip
	private $govtPOC;
	
	//contract, delivery order and project assoicted with this trip
	private $contract;
	private $delOrder;
	private $project;
	
	/**
     * Creates a database object and stores traveler data
	 *    params are the required minimum data
     *
	 *
     * @param name - Traveler Name
	 * @param cTract - Name of the contract 
	 * @param dOrder - Delivery order for the traveler
	 * @param proj	 - Project associated with this trip
     * 
     * @return void
     */
	public function __construct($name, $cTract, $dOrder, $proj)
    {
        
		
		if($name != null or $cTract != null or $dOrder != null or $proj != null)
		{
			$this->travelerName = $name;
			$this->contract = $cTract;
			$this->delOrder = $dOrder;
			$this->project = $proj;
		}
		else
		{
			throw new Exception("The Traveler name and associated contract, delivery order, and project must be provided in the constructor parameter");	
		}
    }
	
	//set the company data
	public function setCompany($newval)
    {
		if($newval != null)
		{
			$this->company = $newval;
		}
		else
		{
			throw new Exception ("The Company must be provided as a parameter");	
		}
    }
	
	private function getCompany()
    {
        return $this->company;
    }	
	
	//set the govtPOC
	public function setGovtPOC($newval)
    {
       
		if($newval != null)
		{
			 $this->govtPOC = $newval;
		}
		else
		{
			throw new Exception ("The Company must be provided as a parameter");	
		}
    }
	
    private function getGovtPOC()
    {
        return $this->govtPOC;
    }
	
	//to reset the traveler name
    public function setTravelerName($newval)
    {       
		if($newval != null)
		{
			 $this->travelerName = $newval;
		}
		else
		{
			throw new Exception ("The Company must be provided as a parameter");	
		}
    }
	
    private function getTravelerName()
    {
        return $this->travelerName;
    } 
	
	//to reset the contract
	public function setContract($newval)
    {
		if($newval != null)
		{
			$this->contract = $newval;
		}
		else
		{
			throw new Exception ("The Company must be provided as a parameter");	
		}
    }

    private function getContract()
    {
        return $this->contract;
    }
	
	//to reset the delivery order 
	public function setDeliveryOrder($newval)
    {
        
		if($newval != null)
		{
			$this->delOrder = $newval;
		}
		else
		{
			throw new Exception ("The Company must be provided as a parameter");	
		}
    }

    private function getDeliveryOrder()
    {
        return $this->delOrder;
    }
	
	//to reset the project
	public function setProject($newval)
    {
       
		if($newval != null)
		{
			 $this->project = $newval;
		}
		else
		{
			throw new Exception ("The Company must be provided as a parameter");	
		}
    }

    private function getProject()
    {
        return $this->project;
    }
	
	public static function displayForm()
	{
		$dbc = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				if($dbc->connect_errno){
					echo "Failed to connect to MySQL: (" .$dbc->connect_errno . ") " .$dbc->connect_error;
				}
		
		/*
         * Check if an ID was passed
         */
        if ( isset($_POST['request_id']) )
        {
            $id = (int) $_POST['request_id']; // Force integer type to sanitize data
        }
        else
        {
            $id = NULL;
        }
		
		/*
         * If an ID is passed, loads the associated travel request
         
        if ( !empty($id) )
        {
            $event = $this->_loadEventById($id);

            /*
             * If no object is returned, return NULL
             
            if ( !is_object($event) ) { return NULL; }

            $submit = "Edit This Event";
        }*/
		
		//data needed to be displayed to the screen
		/*
		*  if the user is in the coordinator group then the user_id needs to be selected by the current (coordinator) user
		*   	else the user_id is that of the current user and we use that to get the traveler's name
		*/
		
		/**************************************/
		//TESTING ONLY
		$user_id = 534;
		/**************************************/
		$trName = TravelRequest::selectTravelerNameByID($dbc, $user_id);
		$companyName = TravelerInfo::selectCompanyByUserID($dbc, $user_id);		
		$projectObj = Project::selectProjectByUserID($dbc, $user_id);
		$projectID = $projectObj->getID();
		$projectName = $projectObj->getName();
		$deliveryOrderID = $projectObj->getDOrderID();
		
		$contractObj = Contract::selectContractByDelOdrID($dbc, $deliveryOrderID);
		$contractName = $contractObj->getName(); 
				
		$delOrderObj = DeliveryOrder::selectDelOdrByProjID($dbc, $projectID);
		$deliveryOrderName = $delOrderObj->getName(); 
		
		$govtPOC;
		
		$attachmentRef;
		$html = "";
		//key:request_id   value:{$request->getID()}
		//key:token		   value:$_SESSION[token]
		$html .= "<form action=\"/src/class/class.travelerInfo.php\" method=\"post\" name=\"travelerInfoTabForm\">
				<input type=\"hidden\" name=\"request_id\" value=\"\" />
            	<input type=\"hidden\" name=\"token\" value=\"\" />
           		<input type=\"hidden\" name=\"action\" value=\"save_travelerInfo\" />
              <div>
					<p>Enter the traveler information and select Next to continue</p>
				  <div>
                    <label>Traveler Name</label>
					<input id=\"traveler_name\" name=\"traveler_name\" type=\"text\" value=\"{$trName}\" />
				  </div>
                  <div>
                    <label>Company Represented</label>
                    <input id=\"company_name\" name=\"company_name\" type=\"text\" value=\"{$companyName}\" />
			    </div>
                  <div>
			  	    <label>Government POC for Trip</label>
				    <input id=\"govt_poc\" name=\"govt_poc\" type=\"text\" />
				  </div>
                  <div>
				  <label>Contract</label> 
					<input id=\"contract_name\" name=\"contract_name\" type=\"text\" value=\"{$contractName}\" />
						
				</div>
                    <div >
					<label>Delivery Order</label>
						<input id=\"delivery_order\" name=\"delivery_order\" type=\"text\" value=\"{$companyName}\" />
						
					</div>
                    <div >
					<label>Project</label> 
						<input id=\"project_name\" name=\"project_name\" type=\"text\" value=\"{$deliveryOrderName}\" />						
					</div>
				</div>
				<div id=\"travelerInfoButtonMenu\">					
			  <button id=\"travelerInfoSaveBtn\" onclick=\"SaveTravelerInfo()\"> 
                    Save
                    </button>
					<button id=\"travelerInfoNextBtn\">
                    Next &#187;
              </button>
				</div>
              </form>";	
			echo $html;
	}
	
	public static function selectCompanyByUserID($dbc, $user_id)
    {
        $myQuery = "SELECT company_name FROM bah_company AS c, bah_company_user_map AS m WHERE m.user_id={$user_id} AND c.id=m.company_id LIMIT 1";
		
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
			$aName = $row["company_name"];
			
		}
		
		//$this->travelerName = $aName;
		
		return $aName;
    }	
	
	
   
}


?>