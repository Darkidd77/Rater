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
?>

<div class="grid-container">

    <?php
    // Get all information from the Tradesman table and tradeName from the TradeCategory table
    $q = "SELECT * FROM Booking WHERE tradesmanId='" . $_SESSION["tradesmanId"] . "'";
    $r = $dbc->query($q);

    $num = $r->num_rows;

    // Draw a table
    if ($num > 0) {
    echo '<p>There are currently <span class="badge">' . $num . '</span> bookings.</p>';
    echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
        <tr>
            <th align="left"><b>userName</b></th>
            <th align="left"><b>userEmail</b></th>
            <th align="left"><b>userPhone</b></th>
            <th align="left"><b>userAddress</b></th>
            <th align="left"><b>bookingDate</b></th>
            <th align="left"><b>bookingToken</b></th>
        </tr>';

        while ($row = $r->fetch_array(MYSQLI_ASSOC)) {
        echo '<tr>
            <td align="left">' . $row["userName"] . '</td>
            <td align="left">' . $row["userEmail"] . '</td>
            <td align="left">' . $row["userPhone"] . '</td>
            <td align="left">' . $row["userAddress"] . '</td>
            <td align="left">' . $row["bookingDate"] . '</td>
            <td align="left">' . $row["bookingToken"] . '</td>
        </tr>';
        }
    echo '</form>
    </table>';

    // Memory management
    $r->free_result();
    } else {
    echo '<p class="error">There are currently no booking.</p>';
    }

    // Memory management
    $dbc->close();
    ?>

</div>

<?php include "includes/footer.html"; ?>
