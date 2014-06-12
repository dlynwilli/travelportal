<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<link href="css/jquery_ui_custom/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	
	<script src="js/jquery-1.10.2.js"></script>
	
	<script src="js/jquery-ui-1.10.4.custom.js"></script> 
	
	<script src="js/navigation.js"></script>

</head>

<body>
<?php 

//DB_SERVER, DB_USER, DB_NAME

$link = mysql_connect("localhost", "sthrtrav", "B00z@ll3n") or die('Could not connect: ' . mysql_error());
//DB_NAME
mysql_select_db("sthrtrav_joo1") or die('Could not select database');

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


?>
<form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post" name="travelRequestForm">

	<div>
		<div id="form_tabs">
			<ul>
				<li><a href="#travelerInfoTab">Traveler Information</a></li>
				<li><a href="#tripInfoTab">Trip Information</a></li>
				<li><a href="#costEstimateTab">Trip Estimated Cost</a></li>
			</ul>
			<div id="travelerInfoTab">
				<div>
					<p>Enter the traveler information and select Next to continue</p>
					<label>Traveler Name</label>
					<div id="traveler_name">
						<select>Select..
						</select>
					</div>
					<label>Company Represented</label>
					<div id="company_name">
						<select>Select..
						</select>
					</div>
					<label>Government POC for Trip</label>
					<div id="govt_poc">
						<select>Select..
						</select>
					</div>
					<label>Contract</label>
					<div id="contract_name">
						<select>
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
					<label>Delivery Order</label>
					<div id="delivery_order">
						<select>
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
					<label>Project / Task </label>
					<div id="project_name">
						<select>
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
					<button id="travelerInfoNextBtn" onclick="nextTravelerInfo()">Next</button>
					<button id="travelerInfoSaveBtn" onclick="saveTravelerInfo()">Save</button>
				</div>
			</div>
			<div id="tripInfoTab">
				<p>Enter the trip details and select Next to continue. Select
					Previous to return to the traveler information tab. Select Save to
					keep your work in progress.</p>
				<div>
					<label>Trip Title</label>
					<div id="trip_title">
						<input type="text" />
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
						<div id="o_countryInput">
							<select>Select..
							</select>
						</div>
						<label>State</label>
						<div id="o_stateInput">
							<select>Select..
							</select>
						</div>
						<label>City</label>
						<div id="o_cityInput">
							<select>Select..
							</select>
						</div>
						<label>Zip Code</label>
						<div id="o_zipInput">
							<select>Select..
							</select>
						</div>
						<label>Airport Code</label>
						<div id="o_airportCodeInput">
							<input type="text" />
						</div>
					</div>
					<hr />
					<div id="DestinationPanel">
						<label>Destination</label> <br /> <label>Country</label>
						<div id="d_countryInput">
							<select>Select..
							</select>
						</div>
						<label>State</label>
						<div id="d_stateInput">
							<select>Select..
							</select>
						</div>
						<label>City</label>
						<div id="d_cityInput">
							<select>Select..
							</select>
						</div>
						<label>Zip Code</label>
						<div id="d_zipInput">
							<select>Select..
							</select>
						</div>
						<label>Airport Code</label>
						<div id="d_airportCodeInput">
							<input type="text" />
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
				<div id="tripInfoBtnPanel">
					<button id="tripInfoPreviousBtn">Previous</button>
					<button id="tripInfoNextBtn">Next</button>
					<button id="tripInfoSaveBtn" >Save</button>
				</div>
			</div>
			<div id="costEstimateTab">
				<p>Enter the estimated costs for the trip and select Next to
					continue. Select Previous to return to the trip information tab.
					Select Save to keep your work in progress.</p>
				<h4>Per Diem</h4>
				<label>Max Lodging Rate</label>
				<div id="lodgeRateInput">
					<input type="text" />
				</div>
				<label>Max Meals and Incenditals Rate</label>
				<div id="meals_rate">
					<input type="text" />
				</div>
				<label>Total</label>
				<div id="perDiemTotal">
					<label>calculated</label>
				</div>
				<hr />
				<h4>Lodging</h4>
				<label>Cost Per Day</label>
				<div id="lodgeCost">
					<input type="text" />
				</div>
				<label>Tax Per Day</label>
				<div id="taxCost">
					<input type="text" />
				</div>
				<label>Total</label>
				<div id="lodgeTotal">
					<label>calculated</label>
				</div>
				<hr />
				<h4>Air Transportation</h4>
				<label>Total Airfare Cost</label>
				<div id="airfare_cost">
					<input type="text" />
				</div>
				<label>Total Airport/Airline Fees</label>
				<div id="air_fees">
					<input type="text" />
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
						<div id="rentalCarCost">
							<input type="text" />
						</div>
						<label>Fuel Cost</label>
						<div id="fuelCost">
							<input type="text" />
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
						<div id="oneWay_Miles">
							<input type="text" />
						</div>
						<label>Mileage In and Around</label>
						<div id="inAround_miles">
							<input type="text" />
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
						<div id="otherGroundCost">
							<input type="text" />
						</div>
					</div>
				</div>
				<h4>Other Expenses</h4>
				<div id="otherExpenses">
					<label>Parking</label>
					<div id="parkingExpense">
						<input type="text" />
					</div>
					<label>Tolls</label>
					<div id="tollsExpense">
						<input type="text" />
					</div>
					<label>Other Expense</label>
					<div id="otherExpense">
						<input type="text" />
					</div>
					<hr />
					<h4>Total Estimated Cost for Trip</h4>
					<div id="tripTotalEstimateCost">
						<label>calculated</label>
					</div>
				</div>
				<div id="expenseButtonset">
					<button id="expensePrevBtn">Previous</button>
					<button id="expenseSaveBtn">Save</button>
					<button id="expenseSubmitBtn">Submit</button>
				</div>
			</div>
		</div>
	</div>
</form>
</body>
</html>
<script>
function populateData(id_value)
{
    <?php 
		


?>
}
</script>