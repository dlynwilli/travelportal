<?php

/**
 * Stores contract information
 *
 * 
 */
class Contract
{
    protected $id;   
    protected $conName;
    protected $conNum;
    protected $conAcronym;


    /**
     * A Contract object
     *
     * @param id - the database id for the the contract record
	 * @param name - the name of the contract
	 * @param number - the contract number
	 * @param acronym - the acronym for the contract
	 *
     * @return void
     */
    public function __construct($id_in, $name, $number, $acronym)
    {
        $this->id = $id_in;
		$this->conName = $name;
		$this->conNum = $number;
		$this->conAcronym = $acronym;
    }
	
	/**
	*  functions to retrieve data from the database - DESERIALIZE 
	*/
	//protected function to select data from the database
	protected static function dbSelect($dbc, $myQuery)
	{
		$contractArray = array();
		
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
			
			//create a new Contract object with id, contract name, number and acronym to easily access the data
			$aContract = new Contract($row[0], $row[1], $row[2], $row[3]);
			$contractArray[] = $aContract;
			
			--$row_count;	
		}
		return $contractArray;
	}
	
	
	/**
	* Select all Contract Records
	*
	* @param dbc - database connect 
	*
	* @return the result as an Array of Contract Objects
	*/
	public static function selectAllContracts($dbc)
	{
		$myQuery = "SELECT * FROM bah_contract";
		
		//make the select statement and return the Array of Delivery Orders	
		return Contract::dbSelect($dbc, $myQuery);		
	}
	
	public static function selectContractByDelOdrID($dbc, $del_order_ID)
    {
		/*
		* SELECT * FROM t1,t2 WHERE t1.primary_key=1 AND t2.primary_key=t1.id;
		*/
        $myQuery = "SELECT c.* FROM bah_del_order AS d, bah_contract AS c WHERE d.id={$del_order_ID} AND c.id=d.contract_id LIMIT 1";
		
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
			
			//project name as result 
			$aContract = new Contract($row[0], $row[1], $row[2], $row[3]);
			
		}
				
		return $aContract;
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
	
	/**
	*  data object methods
	*/
	public function getContractId()
	{
		return $this->id;	
	}
	
	public function getName()
	{
		return $this->conName;	
	}

}

?>