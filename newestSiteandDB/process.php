<?php
//enable sessions

/*
 * Include the necessary configuration info
 */
include_once '../testing/src/config/constants.php';

/*
 * Define constants for configuration info
 */
foreach ( $C as $name => $val )
{
    define($name, $val);
}


//create a lookup array for form actions
$actions = array(
		'save_travelerInfo' => array(
			'object' => 'TravelerInfo',
			'method' => 'saveTravelerInfo'
		),
		'save_tripInfo' => array(
			'object' => 'TripInfo',
			'method' => 'saveTripInfo'
		),
		'save_costEstimate' => array(
			'object' => 'CostEstimate',
			'method' => 'saveCostEstimate'
		),
		'submit_travelRequest' => array(
			'object' => 'TravelRequest',
			'method' => 'submit_travelRequest'
		)
);
	
/*
 * Make sure the anti-CSRF token was passed and that the
 * requested action exists in the lookup array
 */
if ( isset($actions[$_POST['action']]) )
{
    $use_array = $actions[$_POST['action']];
    $obj = new $use_array['object']($dbo);

    /*
     * Check for an ID and sanitize it if found
     */
    if ( isset($_POST['request_id']) )
    {
        $id = (int) $_POST['request_id'];
    }
    else { $id = NULL; }

    echo $obj->$use_array['method']($id);
}

?>