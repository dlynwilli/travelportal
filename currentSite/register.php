<?
/**
 * Register.php
 * 
 */
include("include/session.php");
?>

<html>
<title>Registration Page</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<body>

<?
/**
 * The user is already logged in, not allowed to register.
 */
//if($session->logged_in){
//   echo "<h1>Registered</h1>";
//   echo "<p>We're sorry <b>$session->username</b>, but you've already registered. "
//       ."<a href=\"main.php\">Main</a>.</p>";
//}
/**
 * The user has submitted the registration form and the
 * results have been processed.
 */
if(isset($_SESSION['regsuccess'])){
   /* Registration was successful */
   if($_SESSION['regsuccess']){
      echo "<h1>Registered!</h1>";
	  //echo "<p>Thank you <b>".$_SESSION['reguname']."</b>, your information has been added to the database, you may now <a href=\"main.php\">log in</a>.</p>";
      echo "<p>New user has been added to the database, <a href=\"main.php\">Back to Menu</a>.</p>";
   }
   /* Registration failed */
   else{
      echo "<h1>Registration Failed</h1>";
      echo "<p>We're sorry, but an error has occurred and your registration for the username <b>".$_SESSION['reguname']."</b>, "
          ."could not be completed.<br>Please try again at a later time.</p>";
   }
   unset($_SESSION['regsuccess']);
   unset($_SESSION['reguname']);
}
/**
 * The user has not filled out the registration form yet.
 * Below is the page with the sign-up form, the names
 * of the input fields are important and should not
 * be changed.
 */
else{
?>

<?
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}

$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$query = 'SELECT name FROM companies ORDER BY name ASC';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

if (!$result) {    
	echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
	exit();
}
?>
<form action="process.php" method="POST">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="3"><h1>Register</h1></td>
  </tr>
<tr>
  <td colspan="3">
  <p><a href="main.php">Home</a> | <a href="process.php">Logout</a></p>  </td>
  </tr>


<tr><td>First Name:</td><td><input name="firstname" type="text" value="<? echo $form->value("firstname"); ?>" size="30" maxlength="30"></td><td><? echo $form->error("firstname"); ?></td></tr>
<tr><td>Middle Initial:</td><td><input name="middleinitial" type="text" value="<? echo $form->value("middleinitial"); ?>" size="4" maxlength="1"></td><td><? echo $form->error("middleinitial"); ?></td></tr>
<tr><td>Last Name:</td><td><input name="lastname" type="text" value="<? echo $form->value("lastname"); ?>" size="30" maxlength="30"></td><td><? echo $form->error("lastname"); ?></td></tr>
<tr><td>Suffix:(ex. Jr.,Sr.,III)</td><td><input name="suffix" type="text" value="<? echo $form->value("suffix"); ?>" size="6" maxlength="5"></td><td><? echo $form->error("suffix"); ?></td></tr>
<tr><td>Username:</td><td><input name="user" type="text" value="<? echo $form->value("user"); ?>" size="30" maxlength="30"></td><td><? echo $form->error("user"); ?></td></tr>
<tr><td>Password:</td><td><input name="pass" type="text" value="<? echo $form->value("pass"); ?>" size="30" maxlength="30"></td><td><? echo $form->error("pass"); ?></td></tr>
<tr>
  <td>E-mail:</td><td><input name="email" type="text" value="<? echo $form->value("email"); ?>" size="30" maxlength="50"></td><td><? echo $form->error("email"); ?></td></tr>
<tr>
  <td>Company:</td>
  <td><select name="company">
    <option>Select One...</option>
      <?php
	  while($row = mysql_fetch_row($result)){ echo("<option value=\"$row[0]\">$row[0]</option>\n"); }
	  ?>
      <option value="Other">Other</option>
    </select>
  
	</td><td><? echo $form->error("company"); ?></td></tr>
<tr><td colspan="2" align="right">
<input type="hidden" name="subjoin" value="1">
<input type="submit" value="Add User"></td></tr>
<tr>
  <td colspan="2" align="left">    </td>
</tr>
</table>
</form>

<?
mysql_free_result($result);
mysql_close($link);
}
?>

</body>
</html>
