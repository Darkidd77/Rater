<?php
include "includes/header.html";
require "includes/connect_db.php"; // include file and fail if file is missing
include "functions.php";

?>

<!-- Display body section with sticky form. -->
<form action="register_tradesman.php" method="post" class="form-signin" role="form">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        if (empty($_POST["tradesmanName"])) {
            $errors[] = "Enter your name";
        } else {
            // this function checks the charset of the database ($dbc) and formats the given string accordingly (see connect_db.php)
            // (The @ symbol suppresses errors)
            $n = $dbc->real_escape_string(trim($_POST["tradesmanName"]));
        }

        if (empty($_POST["tradesmanEmail"])) {
            $errors[] = "Enter your email address";
        } else {
            $e = $dbc->real_escape_string(trim($_POST["tradesmanEmail"]));
        }

        if (!empty($_POST["pass1"])) {
            if ($_POST["pass1"] != $_POST["pass2"]) {
                $errors[] = "Passwords do not match.";
            } else {
                $p = $dbc->real_escape_string(trim($_POST["pass1"]));
            }
        } else {
            $errors[] = "Enter your password.";
        }

        if (empty($_POST["tradesmanPhone"])) {
            $errors[] = "Enter your phone number";
        } else {
            $ph = $dbc->real_escape_string(trim($_POST["tradesmanPhone"]));
        }

        if (empty($_POST["hourlyRate"])) {
            $errors[] = "Enter your hourly rate";
        } else {
            $hr = $dbc->real_escape_string(trim($_POST["hourlyRate"]));
        }

        if (empty($_POST["availableAfter"])) {
            $errors[] = "Enter your availability date";
        } else {
            $aa = $dbc->real_escape_string(trim($_POST["availableAfter"]));
            if (!validateDate($aa)) {
                $errors[] = "Enter Vaild Date."; 
            }
        }

        if (empty($_POST["skills"])) {
            $errors[] = "Enter your skills";
        } else {
            $sk = $dbc->real_escape_string(trim($_POST["skills"]));
        }

        if ($_POST["tradeId"] === "0") {
            $errors[] = "Select your trade";
        } else {
            $t = $dbc->real_escape_string(trim($_POST["tradeId"]));
        }

        // Check if the email is already registered
        if (empty($errors)) {
            $q = "SELECT tradesmanId FROM Tradesman WHERE tradesmanEmail='$e'";
            $r = $dbc->query($q);
            $rowcount = $r->num_rows;
            if ($rowcount != 0) {
                $errors[] = 'Email address already registered. <a href="login.php">Login</a>';
            }
        }

        if (empty($errors)) {
            // Secure Hash Algorithm 1
            $q = "INSERT INTO Tradesman (tradesmanName, tradesmanEmail, tradesmanPassword, tradesmanPhone, hourlyRate, availableAfter, skills, verificationStatus, tradeId)
                VALUES ('$n', '$e', SHA1('$p'), '$ph', '$hr', '$aa', '$sk', '0', '$t')";
            $r = $dbc->query($q);
            if ($r) {
                echo '<h1>Registered!</h1><p>You are now registered.</p><p><a href="login.php">Login</a></p>';
            }
            $dbc->close();
            include "includes/footer.html";
            exit();
        } else {
            echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>';
            foreach ($errors as $msg) {
                echo " - $msg <br />";
            }
            echo 'Please <a href="register.php">Try again</a>';
            $dbc->close();
        }
    } ?>

    <h2 class="form-signin-heading">Tradesman</h2>
    <h3>Register:</h3>
    <input type="text" name="tradesmanName" size="20" value="<?php if ( isset($_POST["tradesmanName"]) ) { echo $_POST["tradesmanName"]; } ?>" placeholder="Name">
    <input type="text" name="tradesmanEmail" size="20" value="<?php if ( isset($_POST["tradesmanEmail"]) ) { echo $_POST["tradesmanEmail"]; } ?>" placeholder="Email Address">

    <input type="password" name="pass1" size="20" value="<?php if ( isset($_POST["pass1"]) ) { echo $_POST["pass1"]; } ?>" placeholder="Password">
    <input type="password" name="pass2" size="20" value="<?php if ( isset($_POST["pass2"]) ) { echo $_POST["pass2"]; } ?>" placeholder="Confirm Password">

    <input type="text" name="tradesmanPhone" size="20" value="<?php if ( isset($_POST["tradesmanPhone"]) ) { echo $_POST["tradesmanPhone"]; } ?>" placeholder="Phone">
    <input type="number" name="hourlyRate" size="20" value="<?php if ( isset($_POST["hourlyRate"]) ) { echo $_POST["hourlyRate"]; } ?>" placeholder="Hourly Rate">

    <input type="date" name="availableAfter" size="20" value="<?php if ( isset($_POST["availableAfter"]) ) { echo $_POST["availableAfter"]; } ?>" placeholder="">
    <input type="text" name="skills" size="20" value="<?php if ( isset($_POST["skills"]) ) { echo $_POST["skills"]; } ?>" placeholder="Enter your skills">
    
    <select name="tradeId">
       	<option value="0">Select trade:</option>
        <?php
        $tn = $dbc->query("SELECT tradeId, tradeName From TradeCategory");
        while ($tnRow = $tn->fetch_array(MYSQLI_ASSOC)) {
            echo "<option value=" . $tnRow["tradeId"] . ">" . $tnRow["tradeName"] . "</option>"; }
        ?>
    </select>
    <p><button name="submit" type="submit" class="button">Register</button></p>
</form>


<?php include "includes/footer.html";
?>
