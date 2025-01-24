<?php
require 'includes/connect_db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tradesmanId'])) {
    $tradesmanId = $dbc->real_escape_string($_POST['tradesmanId']);

    // Update query to set verificationStatus to 1 for the given tradesmanId
    $q = "UPDATE Tradesman SET verificationStatus = 1 WHERE tradesmanId = $tradesmanId";

    if ($dbc->query($q)) {
        // Success: Redirect back to the admin page
        header('Location: admin.php');
        exit();
    } else {
        // Handle query failure
        echo '<p class="error">Error updating verification status: ' . $dbc->error . '</p>';
    }
}
?>
