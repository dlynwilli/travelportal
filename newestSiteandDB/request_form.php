<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="css/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<link href="css/custom_portal.css" rel="stylesheet">
	
	

</head>

<body>
<?php 

//DB_SERVER, DB_USER, DB_NAME

$link = mysql_connect("127.0.0.1", "admin", "adminpw") or die('Could not connect: ' . mysql_error());
//DB_NAME
mysql_select_db("mytestdb") or die('Could not select database');

//query for all of the Contracts 
$contractQuery = 'SELECT id, contract_name FROM bah_contract';
$contractResult = mysql_query($contractQuery) or die('Query failed: ' . mysql_error());
if (!$contractResult) {
	echo("<p>Error performing Contract query: " . mysql_error() . "</p>\n");
	exit();
}

//query for the Delivery Order's
$dOrderQuery = 'SELECT delivery_order FROM bah_del_order WHERE contract_id=1';
$dOrderResult =  mysql_query($dOrderQuery) or die('Query failed: ' . mysql_error());
if (!$dOrderResult) {
	echo("<p>Error performing Delivery Order query: " . mysql_error() . "</p>\n");
	exit();
}
		
//query for the  Projects
$projQuery = 'SELECT project_name FROM bah_project WHERE delivery_order_id=5';
$projResult =  mysql_query($projQuery) or die('Query failed: ' . mysql_error());
if (!$projResult) {
	echo("<p>Error performing Project query: " . mysql_error() . "</p>\n");
	exit();
}

/**  **/
?>

	<div>
		<div id="form_tabs">
			<ul>
				<li><a href="#travelerInfoTab">Traveler Information</a></li>
				<li><a href="#tripInfoTab">Trip Information</a></li>
				<li><a href="#costEstimateTab">Trip Estimated Cost</a></li>
			</ul>
			<div id="travelerInfoTab">
            <form action=/src/class/class.travelerInfo.php method="post" name="travelerInfoTabForm">
            <fieldset>
				<input type="hidden" name="request_id" value="$request->getid()" />
            	<input type="hidden" name="token" value="$_SESSION[token]" />
           		<input type="hidden" name="action" value="save_travelerInfo" />
              <div>
					<p>Enter the traveler information and select Next to continue</p>
				  <div>
                    <label>Traveler Name</label>
					<select id="traveler_name" name="traveler_name">
                   	  <option>Select..</option>
                      <!--add method to pull other user's names from DB here-->
                    </select>
				  </div>
                  <div>
                    <label>Company Represented</label>
                    <select id="company_name" name="company_name">
                      <option>Select..</option>
                          <!--add method to pull company names from DB here-->
                    </select>
			    </div>
                  <div>
			  	    <label>Government POC for Trip</label>
				    <input id="govt_poc" name="govt_poc" type="text" />
				  </div>
                  <div>
				  <label>Contract</label>
					<select id="contract_name">
						<option>Select..</option>
						<?php	
							  while($row = mysql_fetch_row($contractResult)){
									$contract_id = $row[0];
						            $contract_name = $row[1];	
						            echo("<option id=\"$contract_id\" value=\"$contract_name\" onchange=\"populateData(this.id)\">$contract_name</option>\n"); 						
						          }					
						  ?>
					</select>
				</div>
                    <div >
					<label>Delivery Order</label>
						<select id="delivery_order">
						<option>Select..</option>
						<?php	
							  while($row = mysql_fetch_row($dOrderResult)){
									//$contract_id = $row[0];
						            $delivery_order = $row[0];	
						            echo("<option value=\"$delivery_order\">$delivery_order</option>\n"); 						
						          }					
						  ?>
						</select>
					</div>
                    <div >
					<label>Project / Task </label>
						<select id="project_name">
						<option>Select..</option>
						<?php	
							  while($row = mysql_fetch_row($projResult)){
									//$contract_id = $row[0];
						            $project = $row[0];	
						            echo("<option value=\"$project\">$project</option>\n"); 						
						          }					
						  ?>
						</select>
					</div>
				</div>
				<div id="travelerInfoButtonMenu">					
			  <button id="travelerInfoSaveBtn" onclick="SaveTravelerInfo()"> 
                    Save
                    </button>
					<button id="travelerInfoNextBtn">
                    Next &#187;
              </button>
				</div>
                </fieldset>
              </form>
			</div>
			<div id="tripInfoTab">
            <form action=/src/class/class.tripInfo.php method="post" name="tripInfoTabForm">
				<p>Enter the trip details and select Next to continue. Select
					Previous to return to the traveler information tab. Select Save to
					keep your work in progress.</p>
				<div>
					<label>Trip Title</label>
					<div>
						<input id="trip_title" type="text" />
					</div>
					<label>Departure Date</label>
					<div id="depart_date">
						<input id="Depart_Datepicker" type="text" />
					</div>
					<label>Return Date</label>
					<div id="return_date">
						<input id="Return_Datepicker" type="text" />
					</div>
					<hr />
					<div id="originPanel">
						<label>Origination</label> <br /> <label>Country</label>
						<div >
							<input id="o_countryInput" type="text" />
						</div>
						<label>State</label>
						<div >
							<select id="o_stateInput" >Select..</select>
						</div>
						<label>City</label>
						<div >
							<input id="o_cityInput" type="text" />
						</div>
						<label>Zip Code</label>
						<div >
							<input id="o_zipInput" type="text" />
						</div>
						<label>Airport Code</label>
						<div >
							<input id="o_airportCodeInput" type="text" />
						</div>
					</div>
					<hr />
					<div id="DestinationPanel">
						<label>Destination</label> <br /> <label>Country</label>
						<div>
							<input id="d_countryInput" type="text" />
						</div>
						<label>State</label>
						<div>
							<select id="d_stateInput">Select..
							</select>
						</div>
						<label>City</label>
						<div>
							<input id="d_cityInput"  type="text" />
						</div>
						<label>Zip Code</label>
						<div>
							<input id="d_zipInput" type="text" />
						</div>
						<label>Airport Code</label>
						<div>
							<input id="d_airportCodeInput" type="text" />
						</div>
					</div>
					<label>Justification (Trip Purpose)</label>
					<div id="trip_purpose"></div>
					<label>Number of Days </label>
					<div id="calculated_days">
						<label>calculated</label>
					</div>
					<label>Number of Nights </label>
					<div id="calculated_nights">
						<label>calculated</label>
					</div>
					<p>Click to Add an Attachment to this Travel Request</p>
					<div id="fileAttachment">
						<input type="file" />
					</div>
				</div>
				<br/>
				<div id="tripInfoBtnPanel">
					<button id="tripInfoPreviousBtn">&#171; Previous</button>
					<button id="tripInfoSaveBtn" onclick="SaveTripInfo()" >Save</button>
					<button id="tripInfoNextBtn">Next &#187;</button>
					
				</div>
              </form>  
			</div>
			<div id="costEstimateTab">
            <form action=/src/class/class.costEstimate.php method="post" name="travelerInfoTabForm">
				<p>Enter the estimated costs for the trip and select Next to
					continue. Select Previous to return to the trip information tab.
					Select Save to keep your work in progress.</p>
				<h4>Per Diem</h4>
				<label>Max Lodging Rate</label>
				<div >
					<input id="lodgeRateInput" type="text" />
				</div>
				<label>Max Meals and Incenditals Rate</label>
				<div >
					<input id="meals_rate" type="text" />
				</div>
				<label>Total</label>
				<div id="perDiemTotal">
					<label>calculated</label>
				</div>
				<hr />
				<h4>Lodging</h4>
				<label>Cost Per Day</label>
				<div >
					<input id="lodgeCost" type="text" />
				</div>
				<label>Tax Per Day</label>
				<div >
					<input id="taxCost" type="text" />
				</div>
				<label>Total</label>
				<div id="lodgeTotal">
					<label>calculated</label>
				</div>
				<hr />
				<h4>Air Transportation</h4>
				<label>Total Airfare Cost</label>
				<div >
					<input id="airfare_cost" type="text" />
				</div>
				<label>Total Airport/Airline Fees</label>
				<div >
					<input id="air_fees" type="text" />
				</div>
				<label>Total</label>
				<div id="airTotal">
					<label>calculated</label>
				</div>
				<hr />
				<h4>Ground Transportation</h4>
				<div id="groundTransportAccordion">
					<h3>
						<a href="#rentalTransSelect">Rental Car</a>
					</h3>
					<div>
						<label>Rental Cost</label>
						<div >
							<input id="rentalCarCost" type="text" />
						</div>
						<label>Fuel Cost</label>
						<div >
							<input id="fuelCost" type="text" />
						</div>
						<label>Total</label>
						<div id="rentalTotal">
							<label>calculated</label>
						</div>
					</div>
					<h3>
						<a href="#povTransSelect">Privately Owned Vehicle</a>
					</h3>
					<div>
						<label>Mileage One Way</label>
						<div >
							<input id="oneWay_Miles" type="text" />
						</div>
						<label>Mileage In and Around</label>
						<div >
							<input id="inAround_miles" type="text" />
						</div>
						<label>Total Miles</label>
						<div id="povMilesTotal">
							<label>calculated</label>
						</div>
						<label>Cost Per Mile</label>
						<div id="costPerMile">
							<label>FIXED_Value_SET_by_Admin</label>
						</div>
						<label>Total POV Cost</label>
						<div id="povTotal">
							<label>calculated</label>
						</div>
					</div>
					<h3>
						<a href="#otherTransSelect">Other (Taxi/Limo, Bus, Train, etc.)</a>
					</h3>
					<div>
						<label>Other Ground Transportation Cost</label>
						<div >
							<input id="otherGroundCost" type="text" />
						</div>
					</div>
				</div>
				<h4>Other Expenses</h4>
				<div id="otherExpenses">
					<label>Parking</label>
					<div >
						<input id="parkingExpense" type="text" />
					</div>
					<label>Tolls</label>
					<div >
						<input id="tollsExpense" type="text" />
					</div>
					<label>Other Expense</label>
					<div >
						<input id="otherExpense" type="text" />
					</div>
					<hr />
					<h4>Total Estimated Cost for Trip</h4>
					<div id="tripTotalEstimateCost">
						<label>calculated</label>
					</div>
				</div>
				<br/>
				<div id="expenseButtonset">
					<button id="expensePrevBtn">&#171; Previous</button>
					<button id="expenseSaveBtn" onclick="SaveExpenseInfo()">Save</button>
					<button id="expenseSubmitBtn" onclick="SubmitTravelRequest()">Submit</button>
				</div>
			</div>
		</div>
        </form>
	</div>



<div id="travReqDialog" Title="Travel Request"></div>
<div id="tripInfoDialog" Title="Trip Information"></div>
<div id="estimateInfoDialog" Title="Trip Estimated Cost"></div>


<script src="js/jquery-1.10.2.js"></script>
	
<script src="js/jquery-ui-1.10.4.custom.js"></script> 
	
<script src="js/navigation.js"></script>
	
</body>
</html>
