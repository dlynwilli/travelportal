<?php
include("include/session.php");

$req_user = trim($_POST['user']);
$myid = $_POST['id'];
$myreviewer_comments = $_POST['reviewer_comments'];
$myaction = $_POST['action'];

$req_user_info = $database->getUserInfo($req_user);

if(!$req_user || strlen($req_user) == 0 || !eregi("^([0-9a-z])+$", $req_user) || !$database->usernameTaken($req_user)){
   die("Username not registered");
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){
	
	$link = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$query = "UPDATE requests SET review_comments='$myreviewer_comments' WHERE id='$myid'";
 
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if (!$result) {    
		echo("<p>Error performing query: " . mysql_error() . "</p>\n");    
		exit();
	}
	mysql_close($link);
	$URL="review.php?user=$req_user";
	header ("Location: $URL");
}
else{
   echo "<h1>User Info</h1>";
}
?>
