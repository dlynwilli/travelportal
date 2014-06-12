<?

/**

 * UserInfo.php

 *

 * This page is for users to view their account information

 * with a link added for them to edit the information.

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

$client = stripslashes($client);

$name = stripslashes($name);

$username = substr($name,0,strpos($name,"."));

$name = substr($name,strpos($name,".")+1);

$company = stripslashes($company);

$purpose = stripslashes($purpose);

$city = stripslashes($city);

$state = stripslashes($state);

$poc = stripslashes($poc);

$hotel = stripslashes($hotel);

$address = stripslashes($address);

$pocphone = ("(" . $phone4 . ") " . $phone5 . "-" . $phone6);

$phone = ("(" . $phone1 . ") " . $phone2 . "-" . $phone3);

$mobile = ("(" . $mobile1 . ") " . $mobile2 . "-" . $mobile3);

$comments = stripslashes($comments);

$req_user = stripslashes($user);

$destination = stripslashes($destination);

$projectname = stripslashes($projectname);



if ($order == "Select One..."){

	echo "<p><strong>Error: Please select a Delivery Order</strong></p>\n";

	echo "<p><a href=\"javascript: history.back()\">&lt; Return to form</a></p>\n";

	exit();

}



if ($projectname == "Select One..."){

	echo "<p><strong>Error: Please select a Project</strong></p>\n";

	echo "<p><a href=\"javascript: history.back()\">&lt; Return to form</a></p>\n";

	exit();

}





if ($mobile == "()-")

{

	$mobile = "";

}

$total = ("$" . $total);

/* Requested Username error checking */



$req_user_info = $database->getUserInfo($req_user);



$fulluser = $req_user_info['firstname'];

if (strlen($req_user_info['middleinitial']) > 0){

  $fulluser = $fulluser.' '.$req_user_info['middleinitial'];

}

$fulluser = $fulluser.' '.$req_user_info['lastname'];

if (strlen($req_user_info['suffix']) > 0){

  $fulluser = $fulluser.' '.$req_user_info['suffix'];

}

echo "<strong>Logged in as: ".$fulluser." (" .$req_user_info['username'].")</strong><br>";

echo "<a href=\"main.php\">Home</a>&nbsp;|&nbsp;<a href=\"process.php\">Logout</a><br><br>";





if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){

   die("Username not registered");

}



/* Logged in user viewing own account */

if(strcmp($session->username,$req_user) == 0){

?>



<table border="0" cellpadding="0" cellspacing="0">

  <tr>

    <td>

    <h1>Review and confirm request</h1>

    <form name="form2" method="post" action="add.php">

    <input name="user" type="hidden" value="<?php echo $user ?>">

    <input name="username" type="hidden" value="<?php echo $username ?>">

    <table border="0" cellspacing="0" cellpadding="0">

          <tr>

        <td>Traveler Name:</td>

        <td><?php echo $name ?><input name="name" type="hidden" value="<?php echo $name ?>"></td>

      </tr>

      <tr>

        <td>Delivery Order:</td>

        <td><?php echo $order ?><input name="order" type="hidden" value="<?php echo $order ?>"></td>

      </tr>

      <tr>

        <td>Project:</td>

        <td><?php echo $projectname ?><input name="projectname" type="hidden" value="<?php echo $projectname ?>"></td>

      </tr>

      <tr>

        <td width="92">Expedited?</td>

        <td><?php echo $expedited ?><input name="expedited" type="hidden" value="<?php echo $expedited ?>"></td>

      </tr>

      <tr>

        <td>Client Requesting Travel:</td>

        <td><?php echo $client ?><input name="client" type="hidden" value="<?php echo $client ?>"></td>

      </tr>

      <tr>

        <td>Company Representing:</td>

        <td><?php echo $company ?><input name="company" type="hidden" value="<?php echo $company ?>"></td>

      </tr>

      <tr>

        <td valign="top">Trip Purpose:</td>

        <td><?php echo $purpose ?><input name="purpose" type="hidden" value="<?php echo $purpose ?>"></td>

      </tr>

      <tr>

        <td>Destination:</td>

        <td><?php echo $destination ?><input name="destination" type="hidden" value="<?php echo $destination ?>"></td>

      </tr>

      <tr>

        <td align="top">

      <?      

        if ($destination == 'CONUS') {

          echo ('City:</td><td>'.$city.'<input name="city" type="hidden" value="'.$city.'"></td></tr>');

          echo ('<tr><td>State:</td><td>'.$state.'<input name="state" type="hidden" value="'.$state.'">');

        } else {

          echo ('OCONUS Destination:</td><td>'.$oconusdestination.'<input name="oconusdestination" type="hidden" value="'.$oconusdestination.'">');

          echo ('<input name="city" type="hidden" value="N/A">');

          echo ('<input name="state" type="hidden" value="N/A">'); 

        }

      ?> 

        </td>

      </tr>   

      <tr>

        <td>POC at TDY Location:</td>

        <td><?php echo $poc ?>

            <input name="poc" type="hidden" value="<?php echo $poc ?>"></td>

      </tr>

      <tr>

        <td>POC Phone Number:</td><td>

      <?

      

      if ($destination == 'CONUS') {

          echo ($pocphone.'<input name="pocphone" type="hidden" value="'.$pocphone.'">');

        } else {

          echo ($intlpocphone.'<input name="pocphone" type="hidden" value="'.$intlpocphone.'">');

          

        }    

      ?>

      </td>

      </tr>

      <tr>

        <td colspan="2"><h2><strong>Contact Information</strong> </h2>

          <hr size="1" noshade></td>

        </tr>

      <tr>

        <td>Hotel:</td>

        <td><?php echo $hotel ?><input name="hotel" type="hidden" value="<?php echo $hotel ?>"></td>

      </tr>

      <tr>

        <td>Address:</td>

        <td><?php echo $address ?><input name="address" type="hidden" value="<?php echo $address ?>"></td>

      </tr>

      <tr>

        <td>Phone:</td>

        <td>

        <?

      

      if ($destination == 'CONUS') {

          echo ($phone.'<input name="phone" type="hidden" value="'.$phone.'">');

        } else {

          echo ($intlphone.'<input name="phone" type="hidden" value="'.$intlphone.'">');

        }    

        

      ?>

        </td>

      </tr>

      <tr>

        <td>     

        Mobile:</td>

        <td>

        <?

      

      if ($destination == 'CONUS') {

          echo ($mobile.'<input name="mobile" type="hidden" value="'.$mobile.'">');

        } else {

          echo ($intlmobilephone.'<input name="mobile" type="hidden" value="'.$intlmobilephone.'">');

        }    

        

      ?>      

        </td>

      </tr>

      <tr>

        <td>Funtion Start Date:</td>

        <td><?php echo $fsdate ?><input name="fsdate" type="hidden" value="<?php echo $fsdate ?>"></td>

      </tr>

      <tr>

        <td>Funtion End Date:</td>

        <td><?php echo $fedate ?><input name="fedate" type="hidden" value="<?php echo $fedate ?>"></td>

      </tr>

      <tr>

        <td>Departure Date:</td>

        <td><?php echo $ddate ?><input name="ddate" type="hidden" value="<?php echo $ddate ?>"></td>

      </tr>

      <tr>

        <td>Return Date:</td>

        <td><?php echo $rdate ?><input name="rdate" type="hidden" value="<?php echo $rdate ?>"></td>

      </tr>

      <tr>

        <td>Estimated Trip Total:</td>

        <td><?php echo $total ?><input name="total" type="hidden" value="<?php echo $total ?>"></td>

      </tr>

      <tr>

        <td valign="top">Comments:</td>

        <td><?php echo $comments ?><input name="comments" type="hidden" value="<?php echo $comments ?>"></td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td><input type="submit" name="submit" id="submit" value="Confirm Request"></td>

      </tr>

    </table>    

    </form>

    </td>

  </tr>

</table>



<?

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