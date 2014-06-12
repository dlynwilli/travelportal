<?
/**
 * Main.php
 *
 * This is an example of the main page of a website. Here
 * users will be able to login. However, like on most sites
 * the login form doesn't just have to be on the main page,
 * but re-appear on subsequent pages, depending on whether
 * the user has logged in or not.
 *
 */
include("include/session.php");
?>

<html>
<title>Web Travel Portal</title>
<meta name="TITLE" content="Web Travel Portal">
<meta name="DESCRIPTION" content="Welcome to the Web Travel Portal">
<meta name="KEYWORDS" content="Web, Portal, Travel Portal">
<link href="styles/style.css" rel="stylesheet" type="text/css">
<body>

<table border="0" cellpadding="0" cellspacing="0">
<tr><td>
<?
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
 $req_user_info = $database->getUserInfo($session->username);
 
 $fullname = $req_user_info['firstname'];
 if (strlen($req_user_info['middleinitial']) > 0) {
    $fullname = $fullname . " " . $req_user_info['middleinitial'];
 }
 $fullname = $fullname . " " . $req_user_info['lastname'];
 if (strlen($req_user_info['suffix']) > 0) {
   $fullname = $fullname . " " . $req_user_info['suffix'];
 }
 
 if($session->logged_in){
   echo "<h1>Web Travel Portal Site</h1>";
   echo "<p>Welcome <strong>$fullname ($session->username)</strong>, you are logged in.</p>"
       //."<ul><li><strong><a href=\"userinfo.php?user=$session->username\">My Account</a></li>"
       ."<ul><li><a href=\"useredit.php\">My Account</a></li>"
	   ."<li><a href=\"request.php?user=$session->username\">Submit Travel Request</a></li>"
	   ."<li><a href=\"requests.php?user=$session->username\">My Travel Requests</a></li>";
   if($session->isAdmin()){
      echo "<li><a href=\"admin/admin.php\">Administration</a></li>";
	  echo "<li><a href=\"review.php?user=$session->username\">Review Requests</a></li>";
	  echo "<li><a href=\"register.php\">Add User</a></li>";
   }
   if($session->isReview()){
      echo "<li><a href=\"review.php?user=$session->username\">Review Requests</a></li>";
   }
   echo "<li><a href=\"process.php\">Logout</strong></a></li></ul>";
   echo "<p>The latest version of <a href=\"http://www.adobe.com/products/acrobat/readstep2.html\" target=\"_blank\">Adobe Acrobat Reader</a> is recommended when using this site.</p>";
   echo "<p>For any questions or issues regarding this site E-mail us at <a href=\"mailto:support@webtravelportal.net\">support@webtravelportal.net</a>.</p>";
}
else{
?>
<h1>Web Travel Portal Login</h1>
<?
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}
?>
<form action="process.php" method="POST">
<table border="0" cellpadding="0" cellspacing="0">
<tr><td width="68">Username:</td>
<td width="416"><input name="user" type="text" value="<? echo $form->value("user"); ?>" size="25" maxlength="30"></td>
</tr>
<tr><td>Password:</td>
  <td><input name="pass" type="password" value="<? echo $form->value("pass"); ?>" size="25" maxlength="30"></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
    <input type="hidden" name="sublogin" value="1">    <input type="submit" value="     Login     ">  </td>
  </tr>
<tr><td colspan="2" align="left">

    </td></tr>
<tr>
  <td colspan="2"><input type="checkbox" name="remember" <? if($form->value("remember") != ""){ echo "checked"; } ?>>
   Remember me next time</td>
  </tr>
<tr>
  <td colspan="2" align="left">&nbsp;<a href="forgotpass.php">Forgot Password?</a></td>
</tr>
<tr>
  <td colspan="2" align="left">For any questions or issues regarding this site E-mail us at <a href="mailto:support@webtravelportal.net">support@webtravelportal.net</a>.</td>
</tr>
</table>

</form>

<?
}
/*echo "</td></tr><tr><td align=\"center\">";
echo "<b>Member Total:</b> ".$database->getNumMembers()."<br>";
echo "There are $database->num_active_users registered members and ";
echo "$database->num_active_guests guests viewing the site.<br><br>";

include("include/view_active.php");*/

?>
</td>
</tr>
</table>
</body>
</html>