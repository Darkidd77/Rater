<?php
include "includes/header.html";
require "includes/connect_db.php"; // include file and fail if file is missing
include "functions.php";
?>

<!-- Display body section with sticky form. -->
<form action="rating.php" method="post" class="form-signin" role="form">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        if (empty($_POST["bookingToken"])) {
            $errors[] = "Enter your booking token";
        } else {
            // this function checks the charset of the database ($dbc) and formats the given string accordingly (see connect_db.php)
            // (The @ symbol suppresses errors)
            $tk = $dbc->real_escape_string(trim($_POST["bookingToken"]));
        }

        if (empty($_POST["ratingScore"])) {
            $errors[] = "Enter your rating score";
        } else {
            $rs = $dbc->real_escape_string(trim($_POST["ratingScore"]));
        }

        if (empty($_POST["review"])) {
            $errors[] = "Enter your review";
        } else {
            $re = $dbc->real_escape_string(trim($_POST["review"]));
        }

        if (empty($errors)) {
            $q = "SELECT bookingId, tradesmanId FROM Booking WHERE bookingToken='$tk'";
            $r = $dbc->query($q);

            if ($r->num_rows !== 1) {
                $errors[] = "Enter valid token";
            } else {
                $q = "SELECT ratingId FROM Rating WHERE bookingId='". 
                $r->fetch_array(MYSQLI_ASSOC)['bookingId'] . "'";
                $r = $dbc->query($q);

                if ($r->num_rows === 1) {
                    $errors[] = "Already given rating";
                }
            }
        }

        if (empty($errors)) {
            $q = "SELECT bookingId, tradesmanId FROM Booking WHERE bookingToken='$tk'";
            $r = $dbc->query($q)->fetch_array(MYSQLI_ASSOC);

            $q = "INSERT INTO Rating (ratingScore, review, tradesmanId, bookingId)
                VALUES ('$rs', '$re', '". $r['tradesmanId'] . "', '". $r['bookingId'] . "')";
            $r = $dbc->query(query: $q);
            if ($r) {
                echo '<h1>Successfully rated!</h1><p>You have rated.</p>';
            }
            $dbc->close();
            include "includes/footer.html";
            exit();
        } else {
            echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>';
            foreach ($errors as $msg) {
                echo " - $msg <br />";
            }
            echo 'Please <a href="rating.php">Try again</a>';
            $dbc->close();
        }
    } ?>

    <h2 class="form-signin-heading">Rate Tradesman</h2>
    <input type="text" name="bookingToken" size="20" value="<?php if ( isset($_POST["bookingToken"]) ) { echo $_POST["bookingToken"]; } ?>" placeholder="Booking Token">
    <select name="ratingScore">
       	<option value="0">Rating score (1 to 5):</option>
        <?php
        for ( $i = 1; $i <= 5; $i++ ) {
            echo "<option value=" . (string) $i . ">" . (string) $i . "</option>"; }
        ?>
    </select>
    <textarea name="review" size="20" value="<?php if ( isset($_POST["review"]) ) { echo $_POST["review"]; } ?>" placeholder="Write a review"></textarea>

    <p><button name="submit" type="submit" class="button">Submit</button></p>
</form>


<?php include "includes/footer.html";
?>
