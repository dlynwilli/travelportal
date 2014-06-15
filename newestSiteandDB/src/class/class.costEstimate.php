<?php

/**
 * Stores Trip Estimated Cost information
 *
 * 
 */
class CostEstimate
{

    protected $id = "";
    protected $lodgePerDiem = "";
    protected $mealPerDiem = "";
	protected $airfare = "";
	protected $lodgeCost = "";
	protected $lodgeTax = "";
	protected $airlineFees = "";
	protected $autoRental = "";
	protected $fuel = "";
	protected $groundTransport = "";
	protected $povMilesOneWay = "";
	protected $povMilesAround = "";
	protected $parking = "";
	protected $tolls = "";
	protected $otherDescription = "";
	protected $otherExpense = "";
	protected $total = "";
   

    /**
     * Accepts an array of Trip Estimated Costs data and stores it
     *
     * @param array $costEstimateArray Associative array of contract data
     * @return void
     */
    public function __construct($costEstimateArray)
    {
        if ( is_array($costEstimateArray) )
        {
            $this->id = $costEstimateArray['id'];
            $this->lodgePerDiem = $costEstimateArray['max_lodging_per_diem_rate'];
            $this->mealPerDiem = $costEstimateArray['meal_incidental_per_diem_rate'];
			$this->airfare = $costEstimateArray['airfare'];
			$this->lodgeCost = $costEstimateArray['lodging_cost_per_day'];
			$this->lodgeTax = $costEstimateArray['lodging_tax_per_day'];
			$this->airlineFees = $costEstimateArray['airline_fees'];
			$this->autoRental = $costEstimateArray['auto_rental'];
			$this->fuel = $costEstimateArray['fuel'];
			$this->groundTransport = $costEstimateArray['ground_transportation'];
			$this->povMilesOneWay = $costEstimateArray['pov_mileage_one_way'];
			$this->povMilesAround = $costEstimateArray['pov_mileage_in_around'];
			$this->parking = $costEstimateArray['parking'];
			$this->tolls = $costEstimateArray['tolls'];
			$this->otherDescription = $costEstimateArray['other_expense_description'];
			$this->otherExpense = $costEstimateArray['other_expense_cost'];
			$this->total = $costEstimateArray['total_trip_cost'];
			
            
            
        }
        else
        {
            throw new Exception("No cost estimate data was supplied.");
        }
    }
	
	/**
	*  functions to retrieve data from the database - DESERIALIZE 
	*/
	
	//protected function to select data from the database
	protected static function dbSelect($dbc, $myQuery)
	{
		$costEstimateArray = array();
		
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
			
			//create a new Cost Estimate object
			$aCostEstimate = new CostEstimate($row);
			$costEstimateArray[] = $aCostEstimate;
			
			--$row_count;	
		}
		return $costEstimateArray;
	}
	
	
	/**
	* Select A Cost Estimate by ID
	*
	* @param dbc - database connect 
	* @param id_in - the id of the cost estimate record to select by
	*
	* @return the result as an Array of Contract Objects
	*/
	public static function selectAllContracts($dbc, $id_in)
	{
		$myQuery = "SELECT * FROM bah_contract WHERE id={$id_in}";
		
		//make the select statement and return the Array of Delivery Orders	
		return CostEstimate::dbSelect($dbc, $myQuery);		
	}
	
		
	/**
	*  functions to save date into the database - SEARLIZE
	*/
	
	public function insert($dbc, $costEstimate)
	{
		
		$myQuery = "INSERT INTO bah_estimated_cost (id, max_lodging_per_diem_rate, meal_incidental_per_diem_rate, airfare, lodging_cost_per_day, lodging_tax_per_day, airline_fees, auto_rental, fuel, ground_transportation, pov_mileage_one_way, pov_mileage_in_around, parking, tolls, other_expense_description, other_expense_cost, total_trip_cost) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16],[value-17])";
		
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
	
	/*
	*  data access functions
	*
	*/
	// $lodgePerDiem;	
    // $mealPerDiem;
	// $airfare;
	// $lodgeCost;
	// $lodgeTax;
	// $airlineFees;
	// $autoRental;
	// $fuel;
	// $groundTransport;
	// $povMilesOneWay;
	// $povMilesAround;
	// $parking;
	// $tolls;
	// $otherDescription;
	// $otherExpense;
	// $total;
	
	/**
	*  user functions
	*/
	public function calTotalPerDiem()
	{
	
	}
	
	public function calTotalLodging()
	{
	
	}
	
	public function calTotalAirTransport()
	{
	
	}
	
	public function calTotalRentalCar()
	{
	
	}
	
	public function calTotalPOVMiles()
	{
	
	}
	
	public function calTotalPrivateVehicle()
	{
	}
	
	
	public function calTotalGroundTransport()
	{
	
	}
	
	public function calTotalCostEstimate()
	{
	
	}
	

}

?>