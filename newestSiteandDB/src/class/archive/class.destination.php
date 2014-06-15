<?php

/**
 * Stores Destination information
 *
 * 
 */
class Destination
{

    public $id;
    public $returnDate;
    public $airport;
	public $city;
	public $state;
	public $zip;
	public $country;
   

    /**
     * Accepts an array of Destination data and stores it
     *
     * @param array $destinationArray Associative array of contract data
     * @return void
     */
    public function __construct($destinationArray)
    {
        if ( is_array($destinationArray) )
        {
            $this->id = $destinationArray['id'];
            $this->returnDate = $destinationArray['return_date'];
            $this->airport = $destinationArray['airport_code'];
			$this->city = $destinationArray['city_name'];
			$this->state = $destinationArray['state'];
			$this->zip = $destinationArray['zip_code'];
			$this->country = $destinationArray['country'];
            
            
        }
        else
        {
            throw new Exception("No destination data was supplied.");
        }
    }

}

?>