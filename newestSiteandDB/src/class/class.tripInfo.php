<?php
/*
 * Include necessary files
 */
include_once 'class.location.php';

/**
 * Stores Trip Information as a record for the traveler
 *
 *
 */
class TripInfo
{

    //the database id for the trip info record
    private $id;

    //The title of the trip
    private $title;

    //The justification / purpose description of the trip
    private $description;

    //The departure date of the trip as a php date object
    private $departDate;

    //The return date of the trip as a php date object
    private $returnDate;
	
	//The origin as a Location object
	private $originLocation;
	
	//The destination as a Location object
	private $destLocation;
	
	//The number of Days for the duration of the trip
	private $numOfDays;
	
	//The number of nights for the duration of the trip
	private $numOfNights;

    /**
     * Constructs a new Trip Information Object 
     *
     * @param name - the given title of the trip
	 * @param depDate - the departure date associated with the trip
	 * @param retDate - the return date associated with the trip
	 * 
     * @return void
     */
    public function __construct($name, $depart, $return)
    {
        if ($name != null or $depart != null or $return != null)
        {
            $this->title = $name;
			$this->departDate = strtotime($depart);
			$this->returnDate = strtotime($return);
        }
        else
        {
            throw new Exception("The Trip Title and associated departure and return dates must be provided as parameters");
        }
    }
	
	//description
	function setDescription ($newValue)
	{
		if($newValue != null)
		{
			$this->description = $newValue;
		}
		else
		{
			throw new Exception("The Justification/Purpose must be provided as a parameter");	
		}	
	}
	
	//originLocation
	function setOriginLocation ($city, $state)
	{
		if ($city != null or $state != null)
        {
			$this->originLocation = new Location($city, $state);
        }
        else
        {
            //if the city or state is not supplied then an exception is thrown
			throw new Exception("The City and State must be provided as parameters");
        }
		
	}
	
	//destLocation
	function setDestLocation ($city, $state)
	{
		if ($city != null or $state != null)
        {
			$this->destLocation = new Location($city, $state);
        }
        else
        {
            //if the city or state is not supplied then an exception is thrown
			throw new Exception("The City and State must be provided as parameters");
        }
		
	}
	
	//return the calculated number of days for the trip
	function getCalculatedNumOfDays()
	{
		$date1=date_create("2013-03-15");
		$date2=date_create("2013-12-12");
		$diff=date_diff($date1,$date2);
		echo $diff->format("%R%a days");
	
	}
	
	//return the calculated number of nights for the trip
	function getCalculatedNumOfNights()
	{
		
	}
	
	//function to update the database with the TripInfo
	
	

}

?>