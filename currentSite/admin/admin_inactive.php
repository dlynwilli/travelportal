<?
/**
 * Admin.php
 *
 */
include("../include/session.php");


$days = $_POST['inactdays'];
if ($_REQUEST["btnDelete"]) {
   foreach ($_REQUEST["chkDelete"] as $value) {
      deleteUsers($value);  

   }
}

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function deleteUsers($del_user){
   global $database;

   $q = "DELETE FROM ".TBL_USERS." WHERE username = '$del_user'";
         $database->query($q);
}

function displayUsers(){
   global $database,$session;

   $inact_time = $session->time - $_POST['inactdays']*24*60*60;
  
   $q = "SELECT username,firstname,lastname,middleinitial,suffix,userlevel,company,email,do,sendemail,timestamp "
       ."FROM ".TBL_USERS." WHERE timestamp < $inact_time AND userlevel <> 9 ORDER BY username ASC";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "Database table empty";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr>
   <td><b>Username</b></td>
   <td><b>Fullname</b></td>
   <td><b>Level</b></td>
   <td><b>Company</b></td>
   <td><b>Email</b></td>
   <td><b>DO</b></td>
   <td><b>E-mail to Reviewer</b></td>
   <td><b>Last Activity Date</td>
   <td><b>Delete</td>

   </tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $fname  = mysql_result($result,$i,"firstname");
      if (strlen(mysql_result($result,$i,"middleinitial")) >0){
        $fname = $fname.' '.mysql_result($result,$i,"middleinitial");
      }
      $fname = $fname.' '.mysql_result($result,$i,"lastname");
      if (strlen(mysql_result($result,$i,"suffix")) >0){
        $fname = $fname.' '.mysql_result($result,$i,"suffix");
      }
      $ulevel = mysql_result($result,$i,"userlevel");
	  $ucomp = mysql_result($result,$i,"company");
      $email  = mysql_result($result,$i,"email");
	  $do  = mysql_result($result,$i,"do");
	  if ($do == "") { $do = "&nbsp;"; }
	  $sendmail  = mysql_result($result,$i,"sendemail");
	  if ($sendmail == "1") { $sendmail = "On"; } else { $sendmail = "Off"; }		
      $timestamp  = date("Y/m/d",mysql_result($result,$i,"timestamp"));

      echo "<tr>
	  <td>$uname</td>
	  <td>$fname</td>
	  <td>$ulevel</td>
	  <td>$ucomp</td>
	  <td>$email</td>
	  <td>$do</td>
	  <td>$sendmail</td>
          <td>$timestamp</td>
          <td><input type=checkbox value=".$uname." name=chkDelete[]>
	  </tr>\n";
   }
   echo "</table><br>\n";
}
   
/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAdmin()){
   header("Location: ../main.php");
}
else{
/**
 * Administrator is viewing page, so display all
 * forms.
 */
?>
<html>
<title>Web Travel Portal</title>
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<body>
<h1>Admin Center</h1>
Logged in as <? echo $session->username; ?><br>
<a href="../main.php">Home</a>
<?
echo " | <a href=\"../process.php\">Logout</strong></a><br>";
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}
?>

<table border="0" cellspacing="0" cellpadding="0">
<tr><td>
<?
/**
 * Display Users Table
 */
?>
<h3>Delete Inactive Users</h3>
This will delete all users (not administrators), who have not logged in to the site<br>
within <? echo $days; ?> days.
</p>
<form action="admin_inactive.php" method="POST">
<?
displayUsers();
?>
</td></tr>
<tr>
<td>
<?
/**
 * Delete Inactive Users
 */
?>
<table>

<tr><td>
<br>
<input type="hidden" name="inactdays" value=<? echo $days; ?>>
<input type="submit" value="Delete Checked Inactive" name="btnDelete"></td>
</form>
</table></td>
</tr>
</table>
</body>
</html>
<?
}
?>
