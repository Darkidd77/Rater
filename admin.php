<?php
include "includes/home_header.html";
require "includes/connect_db.php"; // Needs to be connected to the database to show the information of tradesmen
require "functions.php";
?>
<div class="grid-container">

    <?php
    // Get all information from the Tradesman table and tradeName from the TradeCategory table
    $q = "SELECT Tradesman.tradesmanId, Tradesman.tradesmanName, TradeCategory.tradeName,
    Tradesman.hourlyRate, Tradesman.availableAfter, Tradesman.skills, Tradesman.verificationStatus
    FROM Tradesman
    INNER JOIN TradeCategory
    ON Tradesman.tradeId = TradeCategory.tradeId
    ORDER BY Tradesman.verificationStatus DESC";

    $r = $dbc->query($q);

    $num = $r->num_rows;

    // Draw a table
    if ($num > 0) {
    echo "<p>There are currently $num registered tradesmen.</p>\n";
    echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
        <tr>
            <th align="left"><b>Name</b></th>
            <th align="left"><b>Trade</b></th>
            <th align="left"><b>Hourly Rate</b></th>
            <th align="left"><b>Skills</b></th>
            <th align="left"><b>Available After</b></th>
            <th align="left"><b>Verification Status</b></th>
            <th align="left"><b>Verify</b></th>
        </tr>';

        while ($row = $r->fetch_array(MYSQLI_ASSOC)) {
        echo '<tr>
            <td align="left">' . $row["tradesmanName"] . '</td>
            <td align="left">' . $row["tradeName"] . '</td>
            <td align="left">£' . $row["hourlyRate"] . '</td>
            <td align="left">' . $row["skills"] . '</td>
            <td align="left">' . $row["availableAfter"] . '</td>
            <td align="left">' . ($row["verificationStatus"] ? "&check;" : "&#10005;") . '</td>
            <td>
                <form action="admin_verify.php" method="post">
                    <input type="hidden" name="tradesmanId" value="' . $row["tradesmanId"] . '">
                    <input type="submit" value="Verify" class="button">
                </form>
            </td>
        </tr>';
        }
    echo '</form>
    </table>';

    }

    $dbc->close();
    ?>

</div>

<?php include "includes/footer.html"; ?>
