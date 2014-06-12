<?
include("include/session.php");
?>

<html>
<head>
<title>Web Travel Portal</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
</head>
<body>

<p>
  <?
$datenum = Date("m\/d\/Y");
/* Requested Username error checking */
$req_user = trim($_GET['user']);
$myid = $_GET['id'];
$action = $_GET['action'];

if ($action == 'Yes'){
	$myaction = "No";
	$updated = "disapproved";
	$mystatus = '(DISAPPROVED)';
}
if ($action == 'No'){
	$myaction = "Yes";
	$updated= "approved";
	$mystatus = '(APPROVED)';
}

$req_user_info = $database->getUserInfo($req_user);
echo "<strong>Logged in as: ".$req_user_info['fullname']. " (" .$req_user_info['username'].")</strong><br>";
echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a><br><br>";


if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){
	
	$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$query = "UPDATE requests SET approved='$myaction', approved_date='$datenum' WHERE id='$myid'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if (!$result) {    
		echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
		exit();
	}
	$query = "SELECT review_comments FROM requests WHERE id='$myid'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if (!$result) {    
		echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
		exit();
	}
	if ($myrow = mysql_fetch_array($result)) {
		$myreview_comments = $myrow["review_comments"];
	}



	$query = "SELECT * FROM requests WHERE id='$myid'";
	$result3 = mysql_query($query) or die('Query failed: ' . mysql_error());
	if (!$result3) {    
		echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
		exit();
	}
	if ($myrow = mysql_fetch_array($result3)) {
			$confNum = $myrow["conf_num"];
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
	mysql_free_result($result3);
	
	$query = "SELECT email FROM users WHERE username='$user'";
	$result4 = mysql_query($query) or die('Query failed: ' . mysql_error());
	if (!$result4) {    
		echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
		exit();
	}
	if ($myrow = mysql_fetch_array($result4)) {
			$email = $myrow["email"];
	}
	mysql_free_result($result4);
	
	
	/* Generate E-mail to requestor */
	
	$to  = $email; 
	
	$subject = 'Web Travel Request - '.$confNum .' '.$mystatus;

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
	  <p><a href=\"https://www.webtravelportal.net\">Open Web Travel Portal</a></p>
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
	 
	 
	 
	function SendEmail($email,$confNum,$mystatus)
	{
		$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
		mysql_select_db(DB_NAME) or die ('Unable to connect to database');
		
		$result = mysql_query("SELECT * FROM requests WHERE conf_num='".$confNum."'");
		
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
		$subject = 'Review Web Travel Request - ' . $confNum.' '.$mystatus;
		
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
		  <p><a href=\"https://www.webtravelportal.net\">Open Web Travel Portal</a></p>
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
	if ($myrow = mysql_fetch_array($result2)) {
		
		$myemail = $myrow["email"];
		$mylevel = $myrow["userlevel"];
		$mysend = $myrow["sendemail"];
		
		if ($mylevel == 8 && $mysend == 1) {
			echo ("myemail = ".$myemail);
			SendEmail($myemail,$confNum,$mystatus);
		}
	}
	mysql_free_result($result2);
	
	
	echo "<p>Record id=$myid has been $updated</p>\n";
	echo "<p><a href=\"review.php?user=$req_user\">Return to List</a></p>\n";
?>

<form name="form1" method="post" action="save.php">
<input name="id" type="hidden" value="<?php echo $myid ?>">
<input name="user" type="hidden" value="<?php echo $req_user ?>">
<input name="action" type="hidden" value="<?php echo $action ?>">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>Reviewer Comments (optional):</td>
</tr>
<tr>
<td><textarea name="reviewer_comments" id="reviewer_comments" cols="35" rows="3"><?php echo $myreview_comments ?></textarea></td>
  </tr>
  <tr>
    <td><div align="right">
      <input type="submit" name="button" id="button" value="Save and Return to List">
      </div></td>
  </tr>
</table>
</form>
  
 <?
	mysql_close($link);
}

/* Visitor not viewing own account */
else{
   echo "<h1>User Info</h1>";
}
?>
</body>
</html>
