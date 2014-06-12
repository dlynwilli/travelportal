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
echo $approved;
$review_comments = stripslashes($review_comments);
$req_user = stripslashes($user);

if ($review_comments == ""){
	$review_comments = "&nbsp;";
}

$table = "requests";
$datetime = Date("m.d.y h:i:s A T");

/* Requested Username error checking */

$req_user_info = $database->getUserInfo($req_user);
echo "<strong>Logged in as: ".$req_user_info['username']."</strong><br>";
echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a>&nbsp;|&nbsp;<a href=\"request.php?user=$session->username\">Travel Request</a><br><br>";

if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){

	$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$query = mysql_query("UPDATE $table SET review_comments='$review_comments', approved='$approved', approved_date='$datetime' WHERE id='$record'");
	mysql_close($link);
?>
<p>Record ID: <?php echo $record ?> has been updated successfully</p>
<?
	if ($approved == 'No'){
		// send e-mail to requestor
		$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
	
		$query = mysql_query("SELECT * FROM $table WHERE id='$record'");
		mysql_close($link);
		
		// multiple recipients
		$to  = $req_user_info['email']; // note the comma
		
		// subject
		$subject = 'The following Web Travel Request was Disapproved - ' . $confNum;
		
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
			<td>User:</td>
			  <td>$user</td>
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
			<td>Travelers Name:</td>
			  <td>$name</td>
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
			<td>City:</td>
			  <td>$city</td>
			</tr>
			<tr>
			<td>State:</td>
			  <td>$state</td>
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
		  <p><a href=\"http://www.webtravelrequest.com/\">http://www.webtravelrequest.com/</a></p>
		</body>
		</html>
		";
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= 'From: noreply@webtravelrequest.com <noreply@webtravelrequest.com>' . "\r\n";
		//$headers .= 'Bcc: bolinger_duncan@bah.com' . "\r\n";
		
		// Mail it
		if (mail($to, $subject, $message, $headers)) {
		  echo("<p>A confirmation has been sent to your e-mail account.</p>");
		 } else {
		  echo("<p>Message delivery failed...</p>");
		 }
		
		
		
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