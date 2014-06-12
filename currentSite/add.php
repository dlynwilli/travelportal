<?
/**
 * UserInfo.php
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
@extract($_POST);
$client = stripslashes($client);
$name = stripslashes($name);
$company = stripslashes($company);
$purpose = stripslashes($purpose);
$city = stripslashes($city);
$state = stripslashes($state);
$poc = stripslashes($poc);
$hotel = stripslashes($hotel);
$address = stripslashes($address);
$pocphone = ($pocphone);
$phone = ($phone);
$mobile = ($mobile);
$comments = stripslashes($comments);
$req_user = stripslashes($user);
$approved = "Yes";
$username = stripslashes($username);
$projectname = stripslashes($projectname);

$table = "requests";
$datetime = Date("m\/d\/Y h:i:s A T");
$datenum = Date("mdy");

/* Requested Username error checking */

$req_user_info = $database->getUserInfo($req_user);
echo "<strong>Logged in as: ".$req_user_info['username']."</strong><br>";
echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"requests.php?user=$session->username\">My Travel Requests</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a><br><br>";

if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){

	$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	//$name = mysql_real_escape_string($name)
	
	/* determine user  */
	$query2 = "SELECT username,email FROM users WHERE username='".mysql_real_escape_string($username)."'";
	$result2 = mysql_query($query2);
	if (!$result2) {
		echo("<p>Error finding usrname and email: " . mysql_error() . "</p>");
		exit();
	}
	while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC))
	{
		$myUser = $row2['username'];
		$myUserEmail = $row2['email'];
	} 
	mysql_free_result($result2);	
	
	if ($req_user_info['username'] == $myUser) // username is same as requestor so send mail only to requestor
	{
		$to  = $req_user_info['email'];
		$myuser = $user;
	}
	else //username is different from requestor so send to both individuals
	{
		$to  = ($req_user_info['email'].",".$myUserEmail);
		$myuser = $myUser;
	}
	

	$result = mysql_query("INSERT INTO $table VALUES('$id','$conf_num','$myuser','$datetime','$order','$expedited','".mysql_real_escape_string($client)."','".mysql_real_escape_string($name)."','".mysql_real_escape_string($company)."','".mysql_real_escape_string($purpose)."','$city','$state','".mysql_real_escape_string($poc)."','$pocphone','".mysql_real_escape_string($hotel)."','".mysql_real_escape_string($address)."','$phone','$mobile','$fsdate','$fedate','$ddate','$rdate','$total','".mysql_real_escape_string($comments)."','".mysql_real_escape_string($review_comments)."','$approved','$approved_date','".mysql_real_escape_string($destination)."','".mysql_real_escape_string($oconusdestination)."','".mysql_real_escape_string($projectname)."')");	
	
	if (!$result){
		echo("<p>Error submitting information: " . mysql_error() . "</p>");
		exit();
	}
	echo "<p>Thank you, your request has been sent successfully.</p>";

	/* update confnum */
	
	$query = "SELECT MAX(id) AS maxID FROM $table";
	$result = mysql_query($query);
	if (!$result) {
		echo("<p>Error finding max id: " . mysql_error() . "</p>");
		exit();
	}
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$maxID = $row['maxID'];
	} 
	mysql_free_result($result);
	
	$confNum = ("S3" . $datenum . $maxID);
	echo "<p>Your confirmation code: <strong>$confNum</strong></p>";
	
	$result =  mysql_query("UPDATE $table SET conf_num='$confNum' WHERE id=$maxID");
	if (!$result){
		echo("<p>Error submitting information: " . mysql_error() . "</p>");
		exit();
	}
	
	/* Generate E-mail to requestor */
	

	
	$subject = 'Web Travel Request Confirmation - ' . $confNum;
	
	if ($destination == 'CONUS') {
	  $destinationtravel = 'City:</td><td>'.$city.'</td></tr><tr><td>State:</td><td>'.$state;
	} else {
	  $destinationtravel = 'OCONUS Destination:</td><td>'.$oconusdestination;
	}
	
	
	$message = "
	<html>
	<head>
	  <title>Web Travel Portal</title>
	</head>
	<body>
	  <p><strong>Web Travel Request Confirmation</strong></p>
	  <table border=1 cellspacing=0 cellpadding=3>
		<tr>
		<td>Confirmation code:</td>
		  <td><strong>$confNum</strong></td>
		</tr>
		<tr>
		<td>Travelers Name:</td>
		  <td>$name</td>
		</tr>
		<tr>
		<td>Delivery Order:</td>
		  <td>$order</td>
		</tr>
		<tr>
		<td>Project:</td>
		  <td>$projectname</td>
		</tr>			
		<tr>
		<td>Expedited?</td>
		  <td>$expedited</td>
		</tr>
		<tr>
		<td>Client Requesting Travel:</td>
		  <td>$client</td>
		</tr>
		<tr>
		<td>Company Representing:</td>
		  <td>$company</td>
		</tr>
		<tr>
		<td>Trip Purpose:</td>
		  <td>$purpose</td>
		</tr>
		<tr>
		<td>Destination:</td>
		  <td>$destination</td>
		</tr>
		<tr>
		<td>$destinationtravel</td>
		</tr>		
		<tr>
		<td>POC at TDY Location:</td>
		  <td>$poc</td>
		</tr>		
		<tr>
		<td>POC Phone Number:</td>
		  <td>$pocphone</td>
		</tr>		
		<tr>
		<td>Hotel:</td>
		  <td>$hotel</td>
		</tr>
		<tr>
		<td>Address:</td>
		  <td>$address</td>
		</tr>
		<tr>
		<td>Phone:</td>
		  <td>$phone</td>
		</tr>
		<tr>
		<td>Mobile:</td>
		  <td>$mobile</td>
		</tr>
		<tr>
		<td>State Date:</td>
		  <td>$fsdate</td>
		</tr>
		<tr>
		<td>End Date:</td>
		  <td>$fedate</td>
		</tr>
		<tr>
		<td>Departure Date:</td>
		  <td>$ddate</td>
		</tr>
		<tr>
		<td>Return Date:</td>
		  <td>$rdate</td>
		</tr>
		<tr>
		<td>Estimated Trip Total:</td>
		  <td>$total</td>
		</tr>
		<tr>
		<td>Comments:</td>
		  <td>$comments</td>
		</tr>
	  </table>
	  <p><a href=\"https://www.webtravelportal.net\">https://www.webtravelportal.net</a></p>
	</body>
	</html>
	";
	
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Additional headers
	$headers .= 'From: noreply@webtravelportal.net <noreply@webtravelportal.net>' . "\r\n";
	//$headers .= 'Bcc: bolinger_duncan@bah.com' . "\r\n";
	
	// Mail it
	if (mail($to, $subject, $message, $headers)) {
	  echo("<p>A confirmation has been sent to your e-mail account.</p>");
	 } else {
	  echo("<p>Message delivery failed...</p>");
	 }
	
	
	function SendEmail($email,$maxID)
	{
		$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
		mysql_select_db(DB_NAME) or die ('Unable to connect to database');
		
		$result = mysql_query("SELECT * FROM requests WHERE id='".$maxID."'");
		
		if ($myrow = mysql_fetch_array($result)) {
			$conf_num = $myrow["conf_num"];
			$user = $myrow["user"];
			$order = $myrow["del_order"];
			$datetime = $myrow["datetime"];
			$expedited = $myrow["expedited"];
			$client = $myrow["client"];
			$name =  $myrow["name"];
			$company = $myrow["company"];
			$purpose = $myrow["purpose"];
			$city = $myrow["city"];
			$state = $myrow["state"];
			$poc = $myrow["poc"];
			$pocphone = $myrow["pocphone"];
			$hotel = $myrow["hotel"];
			$address = $myrow["address"];
			$phone = $myrow["phone"];
			$mobile = $myrow["mobile"];
			$fsdate = $myrow["startdate"];
			$fedate = $myrow["enddate"];
			$ddate = $myrow["depdate"];
			$rdate = $myrow["retdate"];
			$total = $myrow["total"];
			$comments = $myrow["comments"];
			$review_comments = $myrow["review_comments"];
			$approved = $myrow["approved"];
			$approved_date = $myrow["approved_date"];
		}
		else 
		{
			echo "No records available, please contact database administrator"; 
		} 

		mysql_free_result($result);
		mysql_close($link);
	
		$to  = $email;
		
		// subject
		$subject = 'Review Web Travel Request - ' . $confNum;
		
		if ($destination == 'CONUS') {
	          $destinationtravel = 'City:</td><td>'.$city.'</td></tr><tr><td>State:</td><td>'.$state;
	        } else {
	          $destinationtravel = 'OCONUS Destination:</td><td>'.$oconusdestination;
	        }
		
		$message = "
		<html>
		<head>
		  <title>Web Travel Portal</title>
		</head>
		<body>
		  <p><strong>Web Travel Request Confirmation</strong></p>
		  <table border=1 cellspacing=0 cellpadding=3>
			<tr>
			<td>Confirmation code:</td>
			  <td><strong>$conf_num</strong></td>
			</tr>
			<tr>
			<td>Travelers Name:</td>
			  <td>$name</td>
			</tr>
			<tr>
			<td>Delivery Order:</td>
			  <td>$order</td>
			</tr>
			<tr>
			<td>Project:</td>
			  <td>$projectname</td>
			</tr>			
			<tr>
			<td>Expedited?</td>
			  <td>$expedited</td>
			</tr>
			<tr>
			<td>Client Requesting Travel:</td>
			  <td>$client</td>
			</tr>
			<tr>
			<td>Company Representing:</td>
			  <td>$company</td>
			</tr>
			<tr>
			<td>Trip Purpose:</td>
			  <td>$purpose</td>
			</tr>
			<td>Destination:</td>
			  <td>$destination</td>
			</tr>
			<tr>
			<td>$destinationtravel</td>
			</tr>
						<tr>
			<td>POC at TDY Location:</td>
			  <td>$poc</td>
			</tr>
						<tr>
			<td>POC Phone Number:</td>
			  <td>$pocphone</td>
			</tr>		
			<tr>
			<td>Hotel:</td>
			  <td>$hotel</td>
			</tr>
			<tr>
			<td>Address:</td>
			  <td>$address</td>
			</tr>
			<tr>
			<td>Phone:</td>
			  <td>$phone</td>
			</tr>
			<tr>
			<td>Mobile:</td>
			  <td>$mobile</td>
			</tr>
			<tr>
			<td>State Date:</td>
			  <td>$fsdate</td>
			</tr>
			<tr>
			<td>End Date:</td>
			  <td>$fedate</td>
			</tr>
			<tr>
			<td>Departure Date:</td>
			  <td>$ddate</td>
			</tr>
			<tr>
			<td>Return Date:</td>
			  <td>$rdate</td>
			</tr>
			<tr>
			<td>Estimated Trip Total:</td>
			  <td>$total</td>
			</tr>
			<tr>
			<td>Comments:</td>
			  <td>$comments</td>
			</tr>
		  </table>
		  <p><a href=\"https://www.webtravelportal.net\">https://www.webtravelportal.net</a></p>
		</body>
		</html>
		";
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= 'From: noreply@webtravelportal.net <noreply@webtravelportal.net>' . "\r\n";
		//$headers .= 'Bcc: bolinger_duncan@bah.com' . "\r\n";
		
		// Mail it
		if (!mail($to, $subject, $message, $headers)) {
		  echo("<p>Message delivery failed...</p>");
		}
	}
	
	/* Generate E-mail to reviewer*/
	$q="SELECT userlevel, email, sendemail FROM users WHERE do='" . $order . "'";
	$result2 = mysql_query($q)
	or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());
	if ($myrow2 = mysql_fetch_array($result2)) {
		
		$myemail = $myrow2["email"];
		$mylevel = $myrow2["userlevel"];
		$mysend = $myrow2["sendemail"];
		
		if ($mylevel == 8 && $mysend == 1) {
			SendEmail($myemail,$maxID);
		}
	}
	mysql_free_result($result2);
	

/* Link to PDF generator */
		echo "<p><a href=\"pdf.php?user=$myuser&id=$maxID&type=screen\" target=\"_blank\">Print PDF Travel Letter</a>&nbsp;|&nbsp;".
			 "<a href=\"pdf2.php?user=$myuser&id=$maxID&type=screen\" target=\"_blank\">Print PMDCGS-A Directed</a></p>"
			 
?>

<?
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
?>

</body>
</html>