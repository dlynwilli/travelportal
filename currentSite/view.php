<?
/**
 * UserInfo.php
 *
 * This page is for users to view their account information
 * with a link added for them to edit the information.
 *
 */
include("include/session.php");

?>
<html>
<head>
<title>Web Travel Portal</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?


/* Requested Username error checking */
$req_user = trim($_GET['user']);

$req_user_info = $database->getUserInfo($req_user);
//echo "<strong>Logged in as: ".$req_user_info['fullname']. " (" .$req_user_info['username'].")</strong><br>";
//echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a><br><br>";


if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){

	$record = $_GET['id'];
	echo "Record ID: $record<br><br>\n";
	
	$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME) or die ('Unable to connect to database');
	
	$result = mysql_query("SELECT requests.*,users.firstname,users.lastname,users.middleinitial,users.suffix FROM requests left join users on requests.user = users.username WHERE id='$record'");
	$num = mysql_num_rows($result);
	$i = 0;
	
	while ($i < $num) {
		$user = mysql_result($result, $i, "user");
		$fullname = mysql_result($result, $i,"firstname");
		if (strlen(mysql_result($result,$i,"middleinitial"))>0){
                  $fullname = $fullname.' '.mysql_result($result,$i,"middleinitial");
                }
		$fullname = $fullname.' '.mysql_result($result,$i,"lastname");
                if (strlen(mysql_result($result,$i,"suffix"))>0){
                  $fullname = $fullname.' '.mysql_result($result,$i,"suffix");
                }
		$order = mysql_result($result, $i, "del_order");
		$datetime = mysql_result($result, $i, "datetime");
		$expedited = mysql_result($result, $i, "expedited");
		$client = mysql_result($result, $i, "client");
		$name =  mysql_result($result, $i, "name");
		$company = mysql_result($result, $i, "company");
		$purpose = mysql_result($result, $i, "purpose");
		$city = mysql_result($result, $i, "city");
		$state = mysql_result($result, $i, "state");
		$hotel = mysql_result($result, $i, "hotel");
		$address = mysql_result($result, $i, "address");
		$phone = mysql_result($result, $i, "phone");
		$mobile = mysql_result($result, $i, "mobile");
		$startdate = mysql_result($result, $i, "startdate");
		$enddate = mysql_result($result, $i, "enddate");
		$depdate = mysql_result($result, $i, "depdate");
		$retdate = mysql_result($result, $i, "retdate");
		$total = mysql_result($result, $i, "total");
		$comments = mysql_result($result, $i, "comments");
		$review_comments = mysql_result($result, $i, "review_comments");
		$approved = mysql_result($result, $i, "approved");
		$approved_date = mysql_result($result, $i, "approved_date");
		$destination = mysql_result($result,$i,"destination");
		$oconusdestination = mysql_result($result,$i,"oconusdestination");
		mysql_free_result($result);
		mysql_close($link);
		
		if ($destination == 'CONUS') {
	          $destinationtravel = 'City:</td><td>'.$city.'</td></tr><tr><td>State:</td><td>'.$state;
	        } else {
	          $destinationtravel = 'OCONUS Destination:</td><td>'.$oconusdestination;
	        }
		
?>

<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <h1>Web Travel Request Form</h1>
    <table border="0" cellspacing="0" cellpadding="0">
          <tr>
        <td>Traveler Name:</td>
        <td><? echo "$fullname" ?></td>
      </tr>
        <tr>
        <td>Date Submitted:</td>
        <td><? echo "$datetime" ?></td>
      </tr>
        <tr>
        <td>Delivery Order:</td>
        <td><? echo "$order" ?></td>
      </tr>
      <tr>
        <td>Expedited?</td>
        <td><? echo "$expedited" ?></td>
      </tr>
      <tr>
        <td>Client Requesting Travel:</td>
        <td><? echo "$client" ?></td>
      </tr>
      <tr>
        <td>Company Representing:</td>
        <td><? echo "$company" ?></td>
      </tr>
      <tr>
        <td>Trip Purpose:</td>
        <td><? echo "$purpose" ?></td>
      </tr>
      <tr>
        <td>Destination:</td>
        <td><? echo "$destination" ?></td>
      </tr>
      <tr>
        <td><? echo "$destinationtravel" ?></td>
      </tr>
      <tr>
        <td colspan="2"><h2><strong>Contact Information</strong> </h2>
          <hr size="1" noshade></td>
        </tr>
      <tr>
        <td>Hotel:</td>
        <td><? echo "$hotel" ?></td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><? echo "$address" ?></td>
      </tr>
      <tr>
        <td>Phone:</td>
        <td><? echo "$phone" ?></td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><? echo "$mobile" ?></td>
      </tr>
      <tr>
        <td>Funtion Start Date:</td>
        <td><? echo "$startdate" ?></td>
      </tr>
      <tr>
        <td>Funtion End Date:</td>
        <td><? echo "$enddate" ?></td>
      </tr>
      <tr>
        <td>Departure Date:</td>
        <td><? echo "$depdate" ?></td>
      </tr>
      <tr>
        <td>Return Date:</td>
        <td><? echo "$retdate" ?></td>
      </tr>
      <tr>
        <td>Estimated Trip Total:</td>
        <td><? echo "$total" ?></td>
      </tr>
      <tr>
        <td>Comments:</td>
        <td><? echo "$comments" ?></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><h2><strong>Review Approve/Disapprove</strong> </h2>
          <hr size="1" noshade></td>
        </tr>
      <tr>
        <td valign="top">Reviewer Comments:</td>
        <td><?php echo "$review_comments" ?></td>
      </tr>
      <tr>
        <td>Approved?</td>
        <td><?php echo "$approved" ?></td>
      </tr>
      <tr>
        <td>Date Updated:</td>
        <td><? echo "$approved_date" ?></td>
      </tr>
    </table>
    </td>
  </tr>
</table>

<?
++$i;
}
}
/* Visitor not viewing own account */
else{
   echo "<h1>User Info</h1>";
}


/**
 * Note: when you add your own fields to the users table
 * to hold more information, like homepage, location, etc.
 * they can be easily accessed by the user info array.
 *
 * $session->user_info['location']; (for logged in users)
 *
 * ..and for this page,
 *
 * $req_user_info['location']; (for any user)
 */

/* Link back to main */
?>

</body>
</html>