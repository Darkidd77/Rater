<?php

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', 'gue66me');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'rater_db');

// Create connection
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($dbc->connect_error) {
	die("Connection failed: " . $dbc->connect_error);
}

// echo "Connected successfully <br />";

// Check initial character set: utf8mb4 2025-01-07
// echo "Initial character set is: " . $dbc->character_set_name();

// Set character set to UTF-8
// $dbc->set_charset("utf8");

// Confirm updated character set
// echo "<br> Current character set is: " . $dbc->character_set_name();

// Close connection
// $dbc->close();

?>
