<?php

/**
 * Stores delivery order information
 *
 * 
 */
class DeliveryOrder
{

    protected $id;
    protected $delOrder;
    protected $contractID;
   

    /**
     * A delivery order object
     *
     * @param id_in - the database id for this Delivery order record
	 * @param dOrder - the name of the delivery order associated with this record
	 * @param contID - the associated contract ID for this delivery order
	 *
     * @return void
     */
    public function __construct($id_in, $dOrder, $contID)
    {
         $this->id = $id_in;
         $this->delOrder = $dOrder;
         $this->contractID = $contID;
    }
	
	/**
	*  functions to retrieve data from the database - DESERIALIZE 
	*/
	//protected function to select data from the database
	protected static function dbSelect($dbc, $myQuery)
	{
		$delOdrArray = array();
		
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
			
			//create a new Delivery Order object with id, delivery order name, and contractID to easily access the data
			$aDelOrder = new DeliveryOrder($row[0], $row[1], $row[2]);
			$delOdrArray[] = $aDelOrder;
			
			--$row_count;	
		}
		return $delOdrArray;
	}
	
	
	/**
	* Select all Delivery Order Records using the Contract ID
	*
	* @param dbc - database connect 
	* @param id_in - the contract id to select the project by
	*
	* @return the result as an Array of Delivery Order Objects
	*/
	public static function selectDelOdrRecdsByContID($dbc, $id_in)
	{
		$myQuery = "SELECT * FROM bah_del_order WHERE contract_id={$id_in}";
		
		//make the select statement and return the Array of Delivery Orders	
		return DeliveryOrder::dbSelect($dbc, $myQuery);		
	}
	
	public static function selectDelOdrByProjID($dbc, $project_id)
    {
		/*
		* SELECT * FROM t1,t2 WHERE t1.primary_key=1 AND t2.primary_key=t1.id;
		*/
        $myQuery = "SELECT * FROM bah_del_order AS d, bah_project AS p WHERE p.id={$project_id} AND d.id=p.delivery_order_id LIMIT 1";
		
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
			$aDelOrder = new DeliveryOrder($row[0], $row[1], $row[2]);
			
		}
				
		return $aDelOrder;
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
	
	//delOrder
	public function setDeliveryOrder($newval)
    {
        $this->deliveryOrder = $newval;
    }

    public function getName()
    {
        return $this->delOrder;
    }

}

?>