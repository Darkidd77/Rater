<?php

session_start();

// the $_SESSION['user_id'] field will be set if a user has successfully logged in.
if (!isset($_SESSION["tradesmanId"])) {
    // if the SESSION 'user_id' is NOT set...
    require "login_tools.php";
    load(); // ... call the load() function (in login_tools.php) using the default argument ('login.php')
}

$page_title = "Welcome {$_SESSION["tradesmanName"]}";

// the <title> tag in home_header.html contains php code to display the value of $page_title.
include "includes/home_header.html";
require "includes/connect_db.php";
include "functions.php";

$qSelect = "SELECT * FROM Tradesman WHERE tradesmanId = '". $_SESSION["tradesmanId"] . "'";
$rSelect = $dbc->query(query: $qSelect)->fetch_array(MYSQLI_ASSOC);
?>


<form action="update_tradesman.php" method="post" enctype="multipart/form-data" class="form-signin" role="form">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];
        $shouldUpdate = false;

        // Variables for file upload
        if (!isset($_FILES['fileToUpload'])) { 
            $errors[] = "File is not uploaded.";
        } else {
            // Process the file
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $shouldUpdate = true;
            } else {
                $errors[] = "File is not an image.";
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $errors[] = "Sorry, file already exists.";
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                $errors[] = "Sorry, your file is too large.";
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $errors[] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
            }
        }
        
        if (empty($_POST["tradesmanName"])) {
            // Put old value if the user does not input a new value
            $n = $dbc->real_escape_string(trim($rSelect["tradesmanName"]));
        } else {
            // this function checks the charset of the database ($dbc) and formats the given string accordingly (see connect_db.php)
            // (The @ symbol suppresses errors)
            $n = $dbc->real_escape_string(trim($_POST["tradesmanName"]));
            $shouldUpdate = true;
        }


        if (empty($_POST["tradesmanEmail"])) {
            $e = $dbc->real_escape_string(trim($rSelect["tradesmanEmail"]));
        } else {
            $e = $dbc->real_escape_string(trim($_POST["tradesmanEmail"]));
            $shouldUpdate = true;
        }

        // TODO: Refactor nested if
        if (empty($_POST["pass0"])) { // (0, *, *)
            if (!empty($_POST["pass1"]) || !empty($_POST["pass2"])) { // Current password missing (0, 0, 1) (0, 1, 0) (0, 1, 1)
                $errors[] = "Enter Current Password";
            } else { // No passwords entered (0, 0, 0)
                $p = $dbc->real_escape_string(trim($rSelect["tradesmanPassword"])); // Fill old password
            }
        } else { // (1, *, *)
            if ($rSelect["tradesmanPassword"] !== SHA1($dbc->real_escape_string(trim($_POST["pass0"])))) {
                $errors[] = "Wrong Current Password";
            } else { // Wrong Current Password (1, *, *)
                if (empty($_POST["pass1"])) { // Current password entered but no pass1 (1, 0, *)
                    $errors[] = "Enter New Password."; 
                } else { // (1, 1, *)
                    if ($_POST["pass1"] != $_POST["pass2"]) {
                        $errors[] = "New Passwords do not match.";
                    } else {
                        $p = SHA1($dbc->real_escape_string(trim($_POST["pass1"])));
                        $shouldUpdate = true;
                    }
                }
            }
        }        

        if (empty($_POST["tradesmanPhone"])) {
            $ph = $dbc->real_escape_string(trim($rSelect["tradesmanPhone"]));
        } else {
            $ph = $dbc->real_escape_string(trim($_POST["tradesmanPhone"]));
            $shouldUpdate = true;
        }

        if (empty($_POST["hourlyRate"])) {
            $hr = $dbc->real_escape_string(trim($rSelect["hourlyRate"]));
        } else {
            $hr = $dbc->real_escape_string(trim($_POST["hourlyRate"]));
            $shouldUpdate = true;
        }

        if (empty($_POST["availableAfter"])) {
            $aa = $dbc->real_escape_string(trim($rSelect["availableAfter"]));
        } else {
            $aa = $dbc->real_escape_string(trim($_POST["availableAfter"]));
            if (!validateDate($aa)) {
                $errors[] = "Enter Vaild Date."; 
            } else {;
                $shouldUpdate = true;
            }
        }

        if (empty($_POST["skills"])) {
            $sk = $dbc->real_escape_string(trim($rSelect["skills"]));
        } else {
            $sk = $dbc->real_escape_string(trim(string: $_POST["skills"]));
            $shouldUpdate = true;
        }

        if ($_POST["tradeId"] === $rSelect["tradeId"]) {
            $t = $dbc->real_escape_string(trim($rSelect["tradeId"]));
        } else {
            $t = $dbc->real_escape_string(trim($_POST["tradeId"]));
            $shouldUpdate = true;
        }

        if ($shouldUpdate && empty($errors)) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], 
                $target_dir . $_SESSION["tradesmanId"] . "." . pathinfo($target_file,PATHINFO_EXTENSION))) {
                $q = "UPDATE Tradesman 
                SET tradesmanName = '$n', tradesmanEmail = '$e', tradesmanPassword = '$p', tradesmanPhone = '$ph',
                hourlyRate = $hr, verificationStatus = 0, availableAfter = '$aa', skills = '$sk', tradeId = $t
                WHERE tradesmanId = " . $rSelect['tradesmanId'];
                $r = $dbc->query(query: $q);
                if ($r) {
                    echo '<h1>Updated!</h1><p>Your profile is updated.</p><p><a href="login.php">Login</a></p>';
                }
                $dbc->close();
                include "includes/footer.html";
                exit();
            } else {
                echo '<h1>Error!</h1><p id="err_msg">Sorry, there was an error uploading your file.<br>';
                echo 'Please <a href="update_tradesman.php">Try again</a>';
                $dbc->close();
            }
        } elseif (!empty($errors)) {
            echo '<h1>Error!</h1><p id="err_msg">The following error(s) occurred:<br>';
            foreach ($errors as $msg) {
                echo " - $msg <br />";
            }
            echo 'Please <a href="update_tradesman.php">Try again</a>';
            $dbc->close();
        } else {
            echo '<h1>Error!</h1><p id="err_msg">Nothing entered.<br>';
            echo 'Please <a href="update_tradesman.php">Try again</a>';
            $dbc->close();
        }
    } ?>

    <p>You are now logged in, <?php echo "{$_SESSION["tradesmanName"]}"; ?> </p>

    <input type="text" name="tradesmanName" size="20" value="<?php if ( isset($_POST["tradesmanName"]) ) { echo $_POST["tradesmanName"]; } ?>" placeholder="<?= $rSelect["tradesmanName"] ?>">
    <input type="text" name="tradesmanEmail" size="20" value="<?php if ( isset($_POST["tradesmanEmail"]) ) { echo $_POST["tradesmanEmail"]; } ?>" placeholder="<?= $rSelect["tradesmanEmail"] ?>">

    <input type="password" name="pass0" size="20" value="<?php if ( isset($_POST["pass0"]) ) { echo $_POST["pass0"]; } ?>" placeholder="Current Password">
    <input type="password" name="pass1" size="20" value="<?php if ( isset($_POST["pass1"]) ) { echo $_POST["pass1"]; } ?>" placeholder="New Password">
    <input type="password" name="pass2" size="20" value="<?php if ( isset($_POST["pass2"]) ) { echo $_POST["pass2"]; } ?>" placeholder="Confirm New Password">

    <input type="text" name="tradesmanPhone" size="20" value="<?php if ( isset($_POST["tradesmanPhone"]) ) { echo $_POST["tradesmanPhone"]; } ?>" placeholder="<?= $rSelect["tradesmanPhone"] ?>">
    <input type="number" name="hourlyRate" size="20" value="<?php if ( isset($_POST["hourlyRate"]) ) { echo $_POST["hourlyRate"]; } ?>" placeholder="<?= $rSelect["hourlyRate"] ?>">

    <input type="text" name="availableAfter" size="20" value="<?php if ( isset($_POST["availableAfter"]) ) { echo $_POST["availableAfter"]; } ?>" placeholder="<?= $rSelect["availableAfter"] ?>">
    <input type="text" name="skills" size="20" value="<?php if ( isset($_POST["skills"]) ) { echo $_POST["skills"]; } ?>" placeholder="<?= $rSelect["skills"] ?>">
    
    <select name="tradeId">
        <?php
        $tn = $dbc->query("SELECT tradeId, tradeName From TradeCategory");
        while ($tnRow = $tn->fetch_array(MYSQLI_ASSOC)) {
            if ($tnRow["tradeId"] === $rSelect["tradeId"]) {
                echo "<option value=" . $tnRow["tradeId"] . " selected>" . $tnRow["tradeName"] . "</option>";
            } else {
                echo "<option value=" . $tnRow["tradeId"] . ">" . $tnRow["tradeName"] . "</option>";
            }
        }   
        ?>
    </select>

    <b>Upload image of registration for verification: </b><br />
    <input type="file" name="fileToUpload" id="fileToUpload"><br />

    <br />

    <p><button name="submit" type="submit" class="button">Update</button></p>
</form>


<?php include "includes/footer.html";
?>
