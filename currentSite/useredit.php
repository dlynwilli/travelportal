<?
/**
 * UserEdit.php
 *
 * This page is for users to edit their account information
 * such as their password, email address, etc. Their
 * usernames can not be edited. When changing their
 * password, they must first confirm their current password.
 *
 */
include("include/session.php");
?>

<html>
<title>Web Travel Portal</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
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
<body>

<?
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['useredit'])){
   unset($_SESSION['useredit']);
   
   echo "<h1>User Account Edit Success!</h1>";
   echo "<p><strong>$session->username</strong>, your account has been successfully updated.</p>";
   echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a>";
}
else{
?>

<?
/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if($session->logged_in){
?>
<h1>User Account Edit : <? echo $session->username; ?></h1>
<?
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}
?>
<form action="process.php" method="POST" onSubmit="MM_validateForm('curpass','','R','email','','RisEmail');return document.MM_returnValue">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td nowrap>Old Password:</td>
<td><input name="curpass" type="password" id="curpass" value="
<?echo $form->value("curpass"); ?>" size="30" maxlength="30"></td>
<td><? echo $form->error("curpass"); ?></td>
</tr>
<tr>
<td nowrap>New Password:</td>
<td><input name="newpass" type="password" value="
<? echo $form->value("newpass"); ?>" size="30" maxlength="30"></td>
<td><? echo $form->error("newpass"); ?></td>
</tr>
<tr>
<td nowrap>Email:</td>
<td><input name="email" type="text" id="email" value="
<?
if($form->value("email") == ""){
   echo $session->userinfo['email'];
}else{
   echo $form->value("email");
}
?>" size="30" maxlength="50"></td>
<td><? echo $form->error("email"); ?></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
  <input type="hidden" name="subedit" value="1">
<input type="submit" value="Save Changes">
  </td>
  <td>&nbsp;</td>
</tr>
<tr><td colspan="2">
</td></tr>
<tr>
  <td colspan="2">
  <a href="main.php">Home</a>&nbsp;|&nbsp;<a href="process.php">Logout</a>
  </td>
</tr>
<tr><td colspan="2"></td></tr>
</table>

</form>

<?
}
}
?>
</body>
</html>