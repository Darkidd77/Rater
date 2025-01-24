<?php
include "includes/header.html";
require "includes/connect_db.php"; // include file and fail if file is missing
include "functions.php";

$qSelect = "SELECT tradesmanName, availableAfter FROM Tradesman WHERE tradesmanId = '" . $_GET["tradesmanId"] . "'";
$rSelect = $dbc->query(query: $qSelect)->fetch_array(MYSQLI_ASSOC);
?>

<!-- Display body section with sticky form. -->
<form action="booking.php?tradesmanId=<?= $_GET["tradesmanId"] ?>" method="post" class="form-signin" role="form">
    <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = [];

            if (empty($_POST["userName"])) {
                $errors[] = "Enter your name";
            } else {
                // this function checks the charset of the database ($dbc) and formats the given string accordingly (see connect_db.php)
                // (The @ symbol suppresses errors)
                $n = $dbc->real_escape_string(trim($_POST["userName"]));
            }

            if (empty($_POST["userEmail"])) {
                $errors[] = "Enter your email address";
            } else {
                $e = $dbc->real_escape_string(trim($_POST["userEmail"]));
            }

            if (empty($_POST["userPhone"])) {
                $errors[] = "Enter your phone number";
            } else {
                $ph = $dbc->real_escape_string(trim($_POST["userPhone"]));
            }

            if (empty($_POST["userAddress"])) {
                $errors[] = "Enter your address";
            } else {
                $ad = $dbc->real_escape_string(trim($_POST["userAddress"]));
            }

            if (empty($_POST["bookingDate"])) {
                $errors[] = "Enter your booking date";
            } else {
                $bd = $dbc->real_escape_string(trim($_POST["bookingDate"]));
                if (!validateDate($bd)) {
                    $errors[] = "Enter Vaild Date."; 
                } else {
                    $q = "SELECT availableAfter FROM Tradesman WHERE tradesmanId='" . $_GET["tradesmanId"] . "'";
                    $r = $dbc->query($q)->fetch_array(MYSQLI_ASSOC);
                    if ($bd < $r["availableAfter"]) {
                        $errors[] = "Tradesman is not available before" .  $r["availableAfter"] . ".";
                    }
                }
            }

            if (empty($errors)) {
                $bookingToken = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890"), 0, 5);
                $q = "SELECT bookingId FROM Booking WHERE bookingToken='$bookingToken'";
                $r = $dbc->query($q);

                while ($r->num_rows > 0) {
                    $bookingToken = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890"), 0, 5);
                    
                    $q = "SELECT bookingId FROM Booking WHERE bookingToken='$bookingToken'";
                    $r = $dbc->query($q);
                }

                $q = "INSERT INTO Booking (userName, userEmail, userPhone, userAddress, bookingDate, bookingToken, tradesmanId) 
                    VALUES ('$n', '$e', '$ph', '$ad', '$bd', '$bookingToken', '" . $_GET["tradesmanId"] . "')";
                $r = $dbc->query($q);
                if ($r) {
                    echo "<h1>Booked!</h1><p>You are now booked. Your booking token is $bookingToken.</p>";
                }
                $dbc->close();
                include "includes/footer.html";
                exit();
            } else {
                echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>';
                foreach ($errors as $msg) {
                    echo " - $msg <br />";
                }
                echo 'Please <a href="booking.php">Try again</a>';
                $dbc->close();
            }
        } 
    ?>
    
    <h2 class="form-signin-heading">Booking page</h2>

    <input type="text" name="tradesmanName" size="20" value="<?= $rSelect["tradesmanName"] ?>" disabled>
    <input type="text" name="availableAfter" size="20" value="Available after <?= $rSelect["availableAfter"] ?>" disabled>

    <input type="text" name="userName" size="20" value="<?php if ( isset($_POST["userName"]) ) { echo $_POST["userName"]; } ?>" placeholder="Name">
    <input type="text" name="userEmail" size="20" value="<?php if ( isset($_POST["userEmail"]) ) { echo $_POST["userEmail"]; } ?>" placeholder="Email Address">

    <input type="text" name="userPhone" size="20" value="<?php if ( isset($_POST["userPhone"]) ) { echo $_POST["userPhone"]; } ?>" placeholder="Phone">
    <input type="text" name="userAddress" size="20" value="<?php if ( isset($_POST["userAddress"]) ) { echo $_POST["userAddress"]; } ?>" placeholder="Address">

    <input type="date" name="bookingDate" size="20" value="<?php if ( isset($_POST["bookingDate"]) ) { echo $_POST["bookingDate"]; } ?>" placeholder="Booking Date (YYY-MM-DD)">

    <p><button name="submit" type="submit" class="button">Book</button></p>
</form>


<?php include "includes/footer.html";
?>
