<?
/**
 * Admin.php
 *
 */
include("../include/session.php");

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers(){
   global $database;
   $q = "SELECT username,firstname,lastname,middleinitial,suffix,userlevel,company,email,do,sendemail,timestamp "
       ."FROM ".TBL_USERS." ORDER BY username ASC";
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

   </tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $fname  = mysql_result($result,$i,"firstname");
      if (strlen(mysql_result($result,$i,"middleinitial")) > 0) {
        $fname = $fname.' '.mysql_result($result,$i,"middleinitial");
      }
      $fname = $fname.' '.mysql_result($result,$i,"lastname");
      if (strlen(mysql_result($result,$i,"suffix")) > 0) {
        $fname = $fname.' '.mysql_result($result,$i,"suffix");
      }
      $ulevel = mysql_result($result,$i,"userlevel");
	  $ucomp = mysql_result($result,$i,"company");
      $email  = mysql_result($result,$i,"email");
	  $do  = mysql_result($result,$i,"do");
	  if ($do == "") { $do = "&nbsp;"; }
	  $sendmail  = mysql_result($result,$i,"sendemail");
	  if ($sendmail == "1") { $sendmail = "On"; } else { $sendmail = "Off"; }		

      echo "<tr>
	  <td>$uname</td>
	  <td>$fname</td>
	  <td>$ulevel</td>
	  <td>$ucomp</td>
	  <td>$email</td>
	  <td>$do</td>
	  <td>$sendmail</td>
	  </tr>\n";
   }
   echo "</table><br>\n";
}

/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 */
function displayBannedUsers(){
   global $database;
   $q = "SELECT username,timestamp "
       ."FROM ".TBL_BANNED_USERS." ORDER BY username";
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
   echo "<tr><td><b>Username</b></td><td><b>Time Banned</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname = mysql_result($result,$i,"username");
      $time  = mysql_result($result,$i,"timestamp");

      echo "<tr><td>$uname</td><td>$time</td></tr>\n";
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
<h3>Users Table Contents:</h3>
<p>Level 1 = Requestor<br>
  Level 7 = Coordinator<br>
Level 8 = Reviewer<br>
Level 9 = Administrator</p>
<?
displayUsers();
?>
</td></tr>
<tr>
<td>
<br>
<?
/**
 * Update User Level
 */
global $database;
$q = "SELECT username "."FROM ".TBL_USERS." ORDER BY username ASC,username";
$result = $database->query($q);
?>
<h3>Update User Level<br>
  <? echo $form->error("upduser"); ?></h3>
<table>
<form action="adminprocess.php" method="POST">
<tr><td>
Username:<br>
<select name="upduser">
    <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select>
</td>
<td>
Level:<br>
<select name="updlevel">
<option value="1">1</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
</select></td>
<td>
<br>
<input type="hidden" name="subupdlevel" value="1">
<input type="submit" value="Update Level"></td></tr>
</form>
</table></td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>

<?
/**
 * Assign a DO
*/
$q2 = "SELECT username "."FROM ".TBL_USERS." WHERE userlevel='8' ORDER BY username ASC";
$q3 = "SELECT name "."FROM ".TBL_PROJECTS." ORDER BY name ASC";
$result2 = $database->query($q2);
$result3 = $database->query($q3);
?>
<h3>Assign Reviewer to Project</h3>
<? echo $form->error("douser"); ?>

<table>
<form action="adminprocess.php" method="POST">
  <tr>
    <td>Reviewer:<br>
    <select name="douser">
      <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result2)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select></td>
    <td>Project:<br>
    <select name="do">
      <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result3)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select></td>
    <td><br>
    <input type="hidden" name="subdouser" value="1">
      <input type="submit" value="Assign Project"></td></tr>
      </form>
</table>
</td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?
mysql_free_result($result2);
mysql_free_result($result3);

/**
 * Change Reviewers e-mail setting
*/
$q4 = "SELECT username "."FROM ".TBL_USERS." WHERE userlevel='8' ORDER BY username ASC";
$result4 = $database->query($q4);

?>
<h3>Change Reviewers E-mail Status</h3>
<? echo $form->error("statuser"); ?>

<table>
<form action="adminprocess.php" method="POST">
  <tr>
    <td>Reviewer:<br>
    <select name="statuser">
      <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result4)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select></td>
    <td>Send E-mail on/off:<br>
      <select name="status">
        <option>Select One...</option>
        <option value="1">On</option>
        <option value="0">Off</option>
            </select></td>
    <td><br>
    <input type="hidden" name="substatuser" value="1">
      <input type="submit" value="Change Status"></td></tr>
      </form>
</table>
</td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?
mysql_free_result($result4);

/**
 * Delete User
 */
 $result = $database->query($q);
?>
<h3>Delete User</h3>
<? echo $form->error("deluser"); ?>
<form action="adminprocess.php" method="POST">
Username:<br>
<select name="deluser">
  <option>Select One...</option>
  <?php
	  while($row = mysql_fetch_row($result)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
</select>
<input type="hidden" name="subdeluser" value="1">
<input type="submit" value="Delete User">
</form></td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?
/**
 * Delete Inactive Users
 */
?>
<h3>Delete Inactive Users</h3>
This will delete all users (not administrators), who have not logged in to the site<br>
within a certain time period. You specify the days spent inactive.<br><br>
<table>
<form action="admin_inactive.php" method="POST">
<tr><td>
Days:<br>
<select name="inactdays">
<option value="3">3
<option value="7">7
<option value="14">14
<option value="30">30
<option value="100">100
<option value="365">365
</select>
</td>
<td>
<br>
<input type="hidden" name="subdelinact" value="1">
<input type="submit" value="View All Inactive"></td>
</form>
</table></td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?
/**
 * Ban User
 */
  $result = $database->query($q);
?>
<h3>Ban User</h3>
<? echo $form->error("banuser"); ?>
<form action="adminprocess.php" method="POST">
Username:<br>
<select name="banuser">
    <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select>
<input type="hidden" name="subbanuser" value="1">
<input type="submit" value="Ban User">
</form></td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr><td>
<?
/**
 * Display Banned Users Table
 */
?>
<h3>Banned Users Table Contents:</h3>
<?
displayBannedUsers();
?>
</td></tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?
/**
 * Delete Banned User
 */
  $result = $database->query($q);
?>
<h3>Delete Banned User</h3>
<? echo $form->error("delbanuser"); ?>
<form action="adminprocess.php" method="POST">
Username:<br>
<select name="delbanuser">
    <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
    </select>
<input type="hidden" name="subdelbanned" value="1">
<input type="submit" value="Delete Banned User">
</form></td>
</tr>
</table>
</body>
</html>
<?
}
?>

