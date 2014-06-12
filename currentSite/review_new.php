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
<script type="text/javascript">

<!--// 

function ConfirmChoice(user, id, action) 
{ 
	if (action == "Yes"){
		answer = confirm("You are about to disapprove this record. Click \"OK\" to proceed?")
	}
	else {
		answer = confirm("You are about to approve this record. Click \"OK\" to proceed?")
	}
	//answer = confirm("You are about to update this record. Click \"OK\" to proceed?")
	
	if (answer !="0") 
	{ 
		location = "action.php?user=" + user + "&id=" + id + "&action=" + action;
	} 
}
//-->
</script>

</head>
<body>

<?
@extract($_POST);
if ($_POST['bsubmit']) {
  if ($_POST['bsubmit'] == "ID"){$sort = "Order By id Desc";}
  else if ($_POST['bsubmit'] =="Confirmation"){$sort = "Order By conf_num Desc";}
  else if ($_POST['bsubmit'] =="Date Submitted"){$sort = "Order By datetime Desc";}
  else if ($_POST['bsubmit'] =="First Name"){$sort = "Order By firstname";}
  else if ($_POST['bsubmit'] =="M.I."){$sort = "Order By middleinitial";}
  else if ($_POST['bsubmit'] =="Last Name"){$sort = "Order By lastname";}
  else if ($_POST['bsubmit'] =="DO"){$sort = "Order By del_order";}
  else if ($_POST['bsubmit'] =="Expedited"){$sort = "Order By expedited";}
  else if ($_POST['bsubmit'] =="Client"){$sort = "Order By client";}
  else if ($_POST['bsubmit'] =="Company"){$sort = "Order By company";}
  else if ($_POST['bsubmit'] =="Purpose"){$sort = "Order By purpose";}
  else if ($_POST['bsubmit'] =="Start Date"){$sort = "Order By startdate";}
  else if ($_POST['bsubmit'] =="End date"){$sort = "Order By enddate";}
  else if ($_POST['bsubmit'] =="Departure date"){$sort = "Order By depdate";}
  else if ($_POST['bsubmit'] =="Return Date"){$sort = "Order By retdate";}
  else if ($_POST['bsubmit'] =="Total Cost"){$sort = "Order By total";}
  else if ($_POST['bsubmit'] =="Comments"){$sort = "Order By comments";}
  else if ($_POST['bsubmit'] =="Approved?"){$sort = "Order By approved";}
  else if ($_POST['bsubmit'] =="Reviewer Comments"){$sort = "Order By review_comments";}
  else if ($_POST['bsubmit'] =="Last Updated"){$sort = "Order By approved_date";}
} else { $sort = "Order By id";}
/* Requested Username error checking */
$req_user = trim($_GET['user']);

$req_user_info = $database->getUserInfo($req_user);
$fulluser = $req_user_info['firstname'];
if (strlen($req_user_info['middleinitial'])>0) {
  $fulluser = $fulluser.' '.$req_user_info['middleinitial'];
}
$fulluser = $fulluser.' '.$req_user_info['lastname'];
if (strlen($req_user_info['suffix'])>0) {
  $fulluser = $fulluser.' '.$req_user_info['suffix'];
}
echo "<strong>Logged in as: ".$fulluser." (" .$req_user_info['username'].")</strong><br>";
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
	
	$q="SELECT do FROM users WHERE username='$req_user'";
	$result = mysql_query($q)
	or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());
	if ($myrow = mysql_fetch_array($result)) {
		$mydo = $myrow["do"];
		if (empty($mydo)){
			$q="SELECT requests.*,users.firstname,users.lastname,users.middleinitial,users.suffix FROM requests left join users ON requests.user = users.username ".$sort;
		}
		else {
			$q="SELECT requests.*,users.firstname,users.lastname,users.middleinitial,users.suffix FROM requests left join users ON requests.user = users.username WHERE del_order='$mydo' ".$sort;
		}
	}
	
	$result = mysql_query($q)
	or die(" - Failed More Information:<br><pre>$q</pre><br>Error: " . mysql_error());

if ($myrow = mysql_fetch_array($result)) {

echo "<form action=review_new.php?user=".$req_user." method='post' name='form1'><table border=\"1\">\n";
echo "<tr bgcolor=\"#0052A3\">
<td>Detail</td>
<td align=center><input type=submit value=ID name=bsubmit></td>
<td align=center><input type=submit value=Confirmation name=bsubmit></td>
<td align=center><input type=submit value='Date Submitted' name=bsubmit></td>
<td align=center><input type=submit value='First Name' name=bsubmit></td>
<td align=center><input type=submit value='M.I.' name=bsubmit</td>
<td align=center><input type=submit value='Last Name' name=bsubmit></td>
<td align=center><input type=submit value=DO name=bsubmit></td>
<td align=center><input type=submit value=Expedited name=bsubmit></td>
<td align=center ><input type=submit value=Client name=bsubmit></td>
<td align=center><input type=submit value=Company name=bsubmit></td>
<td align=center><input type=submit value=Purpose name=bsubmit></td>
<td align=center><input type=submit value='Start Date' name=bsubmit></td>
<td align=center><input type=submit value='End Date' name=bsubmit></td>
<td align=center><input type=submit value='Departure Date' name=bsubmit></td>
<td align=center><input type=submit value='Return Date' name=bsubmit></td>
<td align=center><input type=submit value='Total Cost' name=bsubmit></td>
<td align=center><input type=submit value=Comments name=bsubmit></td>
<td align=center><input type=submit value=Approved? name=bsubmit></td>
<td align=center><input type=submit value='Reviewer Comments' name=bsubmit></td>
<td align=center>Change Status</td>
<td align=center><input type=submit value='Last Updated' name=bsubmit></td></tr>\n";

do {
	$user = $myrow["user"];
	$myid = $myrow["id"];
	
	$mymobile = $myrow["mobile"];
	$myreview_comments = $myrow["review_comments"];
	$myapproved_date = $myrow["approved_date"];
	$mycomments = $myrow["comments"];
	
	if ($mymobile == ""){
		$mymobile = "&nbsp;";
	}
	if ($myreview_comments == ""){
		$myreview_comments = "&nbsp;";
	}
	if ($myapproved_date == ""){
		$myapproved_date = "&nbsp;";
	}
	if ($mycomments == ""){
		$mycomments = "&nbsp;";
	}
	$firstname = $myrow["firstname"];
        $lastname = $myrow["lastname"];
        $middleinitial = $myrow["middleinitial"];
        $suffix = $myrow["suffix"];
        if (strlen($middleinitial) < 1){$middleinitial = "&nbsp";}
        if (strlen($suffix) > 0){$lastname = $lastname." ".$suffix;}
	if ($myrow["approved"] == "Yes"){
		printf("<tr>
		<td><a href=\"view.php?user=$session->username&id=$myid\" target=\"_blank\">View</a></td>
		<td>%s</td> 
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
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td nowrap>%s</td>
		</tr>\n\n", $myrow["id"],
		$myrow["conf_num"],
		$myrow["datetime"],
		$myrow["firstname"],
                $middleinitial,
                $lastname,
		$myrow["del_order"],
		$myrow["expedited"],
		$myrow["client"],
		$myrow["company"],
		$myrow["purpose"],
		$myrow["startdate"],
		$myrow["enddate"],
		$myrow["depdate"],
		$myrow["retdate"],
		$myrow["total"],
		$mycomments,
		$myrow["approved"],
		$myreview_comments,
		"<a href=\"#\" onclick=\" ConfirmChoice('$req_user','$myid','".$myrow["approved"]."'); return false;\">Disapprove</a>",
		$myapproved_date);

	}
	else{
		printf("<tr bgcolor=\"#FF0000\">
		<td><a href=\"view.php?user=$session->username&id=$myid\" target=\"_blank\">View</a></td>
		<td>%s</td>
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
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td nowrap>%s</td>
		</tr>\n\n", $myrow["id"],
		$myrow["conf_num"],
		$myrow["datetime"],
		$firstname,
                $middleinitial,
                $lastname,
		$myrow["del_order"],
		$myrow["expedited"],
		$myrow["client"],
		$myrow["company"],
		$myrow["purpose"],
		$myrow["startdate"],
		$myrow["enddate"],
		$myrow["depdate"],
		$myrow["retdate"],
		$myrow["total"],
		$mycomments,
		$myrow["approved"],
		$myreview_comments,
		"<a href=\"#\" onclick=\" ConfirmChoice('$req_user','$myid','".$myrow["approved"]."'); return false;\">Approve</a>",
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
