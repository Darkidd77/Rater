<?php
session_start();

if (!isset($_SESSION['tradesmanId'])) { 	// if user_id is not set, redirect to the login page by calling load() with its default parameter (which is 'login.php')
	require ('login_tools.php');		// the load() function is defined in login_tools.php, so need to 'include' it.
	load() ;
}

include ('includes/header.html');

// clear the session variables
$_SESSION = array();
session_destroy();

echo '<h1>Goodbye!</h1>
	<p style="color:purple;">You are now logged out.</p>
	<p style="color:hotpink;">See you soon. Ta-ra! Adiós! </p>';

include ('includes/footer.html');
?>
