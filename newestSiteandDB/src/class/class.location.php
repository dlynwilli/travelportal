<?php

/**
 * Stores Location information as a record for the traveler to enter 
 *    where they are leaving from and going to.
 *
 * 
 */
class Location
{
	//database record id for the location record
    private $id;
    
	//if traveling by AIR th traveler can enter the airport code 
    private $airport;
	
	
	private $city;
	private $state;
	private $zip;
	
	//important if OCONUS travel is involved
	private $country;
   

    /**
     * Constructs a new location object with the reqired city and state data provided 
     *
     * @param inCity - city of the location
	 * @param inState - state of the location
	 *
     * @return void
     */
    function __construct($inCity, $inState)
    {
		if ($inCity != null or $inState != null)
        {
			$this->city = trim($inCity);
			$this->state = trim($inState);
        }
        else
        {
            //if the city or state is not supplied then an exception is thrown
			throw new Exception("The City and State must be provided");
        }
    }
	
	//set the airport code
	function setAirportCode ($code)
	{
		if($code != null)
		{
			$this->airport = $code;	
		}	
		else 
		{
			//if the airport code is not provided then we cant set it
			throw new Exception("The Airport Code must be provided in the parameters");	
		}	
	}
	
	//set the zip code
	function setZipCode ($code)
	{
		if($code != null)
		{
			$this->zip -> $code;
		}
		else 
		{
			//if the zip code is not provided then we cant set it
			throw new Exception("The Zip Code must be provided in the parameters");	
		}	
	}
	
	//set the country 
	function setCountry($inCountry)
	{
		if($inCountry != null)
		{
			$this->country = $inCountry;
		}	
		else 
		{
			//if the country is not provided then we cant set it
			throw new Exception("The Country must be provided in the parameters");	
		}	
	}

}

?>