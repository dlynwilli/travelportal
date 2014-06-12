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
<script language="JavaScript" src="scripts/datepicker.js"></script>
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }

//-->
</script>
<script type="text/javascript">

function showValue(obj) {
  if (obj.value=='CONUS') {
    document.getElementById('textarea').innerHTML='';
    document.getElementById('spancity').innerHTML='<input name="city" type="text" id="city" size="30" maxlength="50">';
    document.getElementById('citydesc').innerHTML='*City:';
    document.getElementById('statedesc').innerHTML='*State';
    document.getElementById('textareadesc').innerHTML='';
    document.getElementById('intlpocnumber').innerHTML='<input name="phone4" type="text" id="phone4" size="3" maxlength="3">&nbsp;&nbsp;<input name="phone5" type="text" id="phone5" size="3" maxlength="3">&nbsp;-&nbsp;<input name="phone6" type="text" id="phone6" size="4" maxlength="4">';
    document.getElementById('intlphone').innerHTML='<input name="phone1" type="text" id="phone1" size="3" maxlength="3">&nbsp;&nbsp;<input name="phone2" type="text" id="phone2" size="3" maxlength="3">&nbsp;-&nbsp;<input name="phone3" type="text" id="phone3" size="4" maxlength="4">';
    document.getElementById('intlmobilephone').innerHTML='<input name="mobile1" type="text" id="mobile1" size="3" maxlength="3">&nbsp;&nbsp;<input name="mobile2" type="text" id="mobile2" size="3" maxlength="3">&nbsp;-&nbsp;<input name="mobile3" type="text" id="mobile3" size="4" maxlength="4">';
    document.getElementById('state').style.display='';
    
    
  } else {
    document.getElementById('textarea').innerHTML='</td><td><textarea name="oconusdestination" id="oconusdestination" cols="40" rows="4"></textarea>';
    document.getElementById('spancity').innerHTML='';
    document.getElementById('citydesc').innerHTML='';
    document.getElementById('statedesc').innerHTML='';
    document.getElementById('textareadesc').innerHTML='OCONUS Destination:';
    document.getElementById('intlpocnumber').innerHTML='<input name="intlpocphone" type="text" size="30" maxlength="30">';
    document.getElementById('intlphone').innerHTML='<input name="intlphone" type="text" size="30" maxlength="30">';
    document.getElementById('intlmobilephone').innerHTML='<input name="intlmobilephone" type="text" size="30" maxlength="30">';
    document.getElementById('state').style.display='none';
    
  }    
} 
</script>

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

echo "<strong>Logged in as: ".$fulluser ." (" .$req_user_info['username'].")<br>";
echo "Company: ".$req_user_info['company']."</strong><br>";
echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a><br><br>";


if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){


$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$query = 'SELECT name FROM del_order ORDER BY name ASC';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

$query1 = 'SELECT name FROM projects ORDER BY name ASC';
$result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());

$query2 = 'SELECT firstname, lastname, middleinitial, suffix, username FROM users WHERE company=(SELECT company FROM users WHERE username=\''.$req_user.'\') ORDER BY firstname ASC';
$result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());

if (!$result) {    
	echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
	exit();
}
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <h1>Web Travel Request Form</h1>
    <form action="confirm.php" method="post" name="form1" onSubmit="MM_validateForm('client','','R','name','','R','company','','R','city','','R','hotel','','R','address','','R','phone1','','RisNum','phone2','','RisNum','phone3','','RisNum','mobile1','','NisNum','mobile2','','NisNum','mobile3','','NisNum','fsdate','','R','fedate','','R','ddate','','R','rdate','','R','total','','R','purpose','','R');return document.MM_returnValue">
    <input name="user" type="hidden" value="<?php echo $req_user ?>">
    <input name="company" type="hidden" value="<?php echo $req_user_info['company'] ?>">
    
    <table border="0" cellpadding="0" cellspacing="0">
    <?php 
	   if($session->isCoordinator() || $session->isAdmin()){
      ?>
    <td>*Travelers Name:</td>
    <td><select name="name" id="name">
    <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result2)){ 
            
            $name = $row[0];
            if (strlen($row[2]) > 0) {
              $name = $name . " " . $row[2];
            }
            $name = $name . " " . $row[1];
            if (strlen($row[3]) > 0) {
              $name = $name . " " . $row[3];
            }
            echo("<option value=\"$row[4].$name\">$name</option>\n"); 
          }
	  ?>
    </select>   </td>
  </tr>
      
      <?php } 
	  else {
	  	echo "<input name=\"name\" type=\"hidden\" value=\"$req_user.$fulluser\">";
	  }?>
  <tr>
    <td>*Delivery Order:</td>
    <td><select name="order" id="order">
    <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select></td>
  </tr>
  <tr>
  	<td>*Project</td>
  	<td><select name="projectname" id="projectname">
  	<option>Select One...</option>
  	<?php
	  while($row = mysql_fetch_row($result1)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select>
  </tr>
  <tr>
    <td width="92">*Expedited?</td>
    <td width="361"><input name="expedited" type="radio" id="radio2" value="No" checked>
No
  <input type="radio" name="expedited" id="radio" value="Yes">
      Yes        </td>
  </tr>
  <tr>
    <td>*Client Requesting Travel:</td>
    <td><input name="client" type="text" id="client" size="30" maxlength="50"></td>
  </tr>
  <tr>
    <td valign="top">*Trip Purpose:</td>
    <td><textarea name="purpose" id="purpose" cols="40" rows="4"></textarea></td>
  </tr>
  <tr>
    <td>
      Destination
    </td>
    <td>
      <input name="destination" type="radio" id="conus" value="CONUS" onclick="showValue(this);" checked />CONUS
      <input name="destination" type="radio" id="oconus" value="OCONUS" onclick="showValue(this);"/>OCONUS 
    </td>
  </tr>
  <tr>
    <td valign="top">
      <span id="textareadesc"></span>
    </td>
    <td>
      <span id="textarea"></span>
    </td>
    </td>
   </tr>
   <tr>   
    <td><span id="citydesc">*City:</span></td>
    <td><span id="spancity"><input name="city" type="text" id="city" size="30" maxlength="50"></span>
    </td>
  </tr>
  <tr>
    <td><span id="statedesc">*State:</span></td>
    <td><span id="spanstate" >
    <select id="state" name="state">
    <option value="Select One...">Select One...</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
       </select> 
       </span>
     </td>
  </tr>
  <tr>
    <td>POC at TDY Location:</td>
    <td nowrap><input name="poc" type="text" id="poc" size="30" maxlength="50">
      necessary if traveling for PMDCGS-A</td>
  </tr>
  <tr>
    <td>POC Phone Number:</td>
    <td nowrap><span id="intlpocnumber"><input name="phone4" type="text" id="phone4" size="3" maxlength="3">
      &nbsp;
      <input name="phone5" type="text" id="phone5" size="3" maxlength="3">
-
<input name="phone6" type="text" id="phone6" size="4" maxlength="4"></span> 
necessary if traveling for PMDCGS-A</td>
  </tr>
  <tr>
    <td colspan="2"><h2><strong>Contact Information</strong>
    </h2>
      <hr size="1" noshade></td>
    </tr>
  <tr>
    <td>*Hotel:</td>
    <td><input name="hotel" type="text" id="hotel" size="30" maxlength="50"></td>
  </tr>
  <tr>
    <td>*Address:</td>
    <td><input name="address" type="text" id="address" size="30" maxlength="50"></td>
  </tr>
  <tr>
    <td>*Phone:</td>
    <td><span id="intlphone">
      <input name="phone1" type="text" id="phone1" size="3" maxlength="3">&nbsp;
      <input name="phone2" type="text" id="phone2" size="3" maxlength="3">
      -
      <input name="phone3" type="text" id="phone3" size="4" maxlength="4"></span></td>
        </tr>
  <tr>
    <td>Mobile:</td>
    <td><span id="intlmobilephone">
      <input name="mobile1" type="text" id="mobile1" size="3" maxlength="3">&nbsp;
<input name="mobile2" type="text" id="mobile2" size="3" maxlength="3">
-
<input name="mobile3" type="text" id="mobile3" size="4" maxlength="4"></span></td>
  </tr>
  <tr>
    <td>*Function Start Date:</td>
    <td><input readonly name="fsdate" type="text" id="fsdate" size="10" style="background-color: #A8B9C9;">
      <a href="javascript:void(0)"><img src="images/calendar.gif" alt="" width="16" height="15" hspace="3" border="0" onClick="displayDatePicker('fsdate', this);return false;"></a></td>
  </tr>
  <tr>
    <td>*Function End Date:</td>
    <td><input readonly name="fedate" type="text" id="fedate" size="10" style="background-color: #A8B9C9;">
      <a href="javascript:void(0)"><img src="images/calendar.gif" alt="" width="16" height="15" hspace="3" border="0" onClick="displayDatePicker('fedate', this);return false;"></a></td>
  </tr>
  <tr>
    <td>*Departure Date:</td>
    <td><input readonly name="ddate" type="text" id="ddate" size="10" style="background-color: #A8B9C9;">
      <a href="javascript:void(0)"><img src="images/calendar.gif" alt="" width="16" height="15" hspace="3" border="0" onClick="displayDatePicker('ddate', this);return false;"></a></td>
  </tr>
  <tr>
    <td>*Return Date:</td>
    <td><input readonly name="rdate" type="text" id="rdate" size="10" style="background-color: #A8B9C9;">
      <a href="javascript:void(0)"><img src="images/calendar.gif" alt="" width="16" height="15" hspace="3" border="0" onClick="displayDatePicker('rdate', this);return false;"></a></td>
  </tr>
  <tr>
    <td>*Estimated Trip Total:</td>
    <td>$
      <input name="total" type="text" id="total" size="7" maxlength="7" style="text-align:right"></td>
  </tr>
  <tr>
    <td valign="top">Comments:</td>
    <td><textarea name="comments" id="comments" cols="40" rows="3"></textarea></td>
  </tr>
  <tr>
    <td valign="top">*=required fields</td>
    <td><input type="submit" name="submit" id="submit" value="Submit Request"></td>
  </tr>
</table>
</form>
    
    </td>
  </tr>
</table>

<?
mysql_free_result($result);
mysql_free_result($result2);
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