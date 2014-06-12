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
<script language="JavaScript" src="scripts/datepicker.js"></script>
</head>
<body>

<?
/* Requested Username error checking */
$req_user = trim($_GET['user']);

$req_user_info = $database->getUserInfo($req_user);

$fulluser = $req_user_info['firstname'];
if (strlen($req_user_info['middleinitial']) > 0) {
  $fulluser = $fulluser . " " . $req_user_info['middleinitial'];
}
$fulluser = $fulluser . " " . $req_user_info['lastname'];
if (strlen($req_user_info['suffix']) > 0) {
  $fulluser = $fulluser . " " . $req_user_info['suffix'];
}


echo "<strong>Logged in as: ".$fulluser. " (" .$req_user_info['username'].")</strong><br>";
echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a><br><br>\n";


if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){

// Show simple format of the records so person can choose the reference name/number
// this is then passed to the next page, for all details

$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
mysql_select_db(DB_NAME) or die ('Unable to connect to database');
$q="SELECT * FROM requests WHERE user='$req_user' ORDER BY id DESC";
$result = mysql_query($q)
or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());

echo "<h1>My Travel Requests</h1>";
if ($myrow = mysql_fetch_array($result)) {

echo "<table border=\"1\">\n";
echo "<tr bgcolor=\"#0052A3\">
<td>Details</td>
<td nowrap>Travel Letter<br>(PDF)</td>
<td>PMDCGS-A<br>(PDF)</td>
<td>Confirmation</td>
<td>Date Submitted</td>
<td>DO</td>
<td>Client</td>
<td>Purpose</td>
<td>Start Date</td>
<td>End Date</td>
<td>Departure Date</td>
<td>Return Date</td>
<td>Total Cost</td>
<td>Comments</td>
<td>Approved?</td>
<td>Reviewer Comments</td>
<td>Date Updated</td></tr>\n";

do {
	$myid = $myrow["id"];
	$user = $myrow["user"];
	$confNum = $myrow["conf_num"];
	$mymobile = $myrow["mobile"];
	$mycomments = $myrow["comments"];
	$myreview_comments = $myrow["review_comments"];
	$myapproved_date = $myrow["approved_date"];
	
	if ($mymobile == ""){
		$mymobile = "&nbsp;";
	}
	if ($mycomments == ""){
		$mycomments = "&nbsp;";
	}
	if ($myreview_comments == ""){
		$myreview_comments = "&nbsp;";
	}
	if ($myapproved_date == ""){
		$myapproved_date = "&nbsp;";
	}
	
	
	$q="SELECT fullname FROM users WHERE username='$user'";
	$result2 = mysql_query($q)
	or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());
	if ($myrow2 = mysql_fetch_array($result2)) {
		$fullname = $myrow2["fullname"];
	}
	mysql_free_result($result2);
	
	if ($myrow["approved"] == "Yes"){

		printf("<tr>
		<td><a href=\"view.php?user=$session->username&id=$myid\" target=\"_blank\">View</a></td>
		<td><a href=\"pdf.php?user=$session->username&id=$myid&type=screen\" target=\"_blank\">View</a>&nbsp;|&nbsp;".
		"<a href=\"pdf.php?user=$session->username&id=$myid&type=save\" target=\"_blank\">Save</a></td>
		<td><a href=\"pdf2.php?user=$session->username&id=$myid&type=screen\" target=\"_blank\">View</a>&nbsp;|&nbsp;".
		"<a href=\"pdf2.php?user=$session->username&id=$myid&type=save\" target=\"_blank\">Save</a></td>
		<td>%s</td>
		<td nowrap>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td nowrap>%s</td></tr>\n", 
		$myrow["conf_num"], 
		$myrow["datetime"], 
		$myrow["del_order"], 
		$myrow["client"], 
		$myrow["purpose"], 
		$myrow["startdate"], 
		$myrow["enddate"], 
		$myrow["depdate"], 
		$myrow["retdate"], 
		$myrow["total"], 
		$mycomments, 
		$myrow["approved"],
		$myreview_comments, 
		$myapproved_date);
	}
	else {
		printf("<tr bgcolor=\"#FF0000\">
		<td><a href=\"view.php?user=$session->username&id=$myid\" target=\"_blank\">View</a></td>
		<td>DISAPPROVED</td>
		<td>%s</td>
		<td nowrap>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td nowrap>%s</td></tr>\n", 
		$myrow["conf_num"], 
		$myrow["datetime"], 
		$myrow["del_order"], 
		$myrow["client"], 
		$myrow["purpose"], 
		$myrow["startdate"], 
		$myrow["enddate"], 
		$myrow["depdate"], 
		$myrow["retdate"], 
		$myrow["total"], 
		$mycomments, 
		$myrow["approved"],
		$myreview_comments, 
		$myapproved_date);
	}
} 



while ($myrow = mysql_fetch_array($result));
echo "</table>\n";
} 

else 
{
echo "No records available"; 
} 

mysql_free_result($result);
mysql_close($link);

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
