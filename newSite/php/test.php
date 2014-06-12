<?php
	if($_POST['user_name']) {
		echo "Welcome, ";
		echo htmlspecialchars($_POST['user_name']);
		
	}
	else {
		echo '<FORM method="post" action=';
		echo $_SERVER['PHP_SELF'];
		echo '>';
		echo 'Enter your name: <input type="text" name="user_name">';
		echo '<BR/>';
		echo '<INPUT type="submit" value="SUBMIT NAME">';
		echo '</FORM>';
	}
?>