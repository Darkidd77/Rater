<?php
include "includes/header.html";
require "includes/connect_db.php"; // Needs to be connected to the database to show the information of tradesmen
?>
<div class="grid-container">

    <form action="tradesman_catalog.php" method="post" style="padding-top:20px">
        <table>
            <tr>
                <td width="70%">
                    <select name="selectTrade" style="margin:auto">
                        <option value="0">Select trade:</option>
                        <?php
                            $tn = $dbc->query( "SELECT tradeName From TradeCategory");
                            while ($tnRow = $tn->fetch_array(MYSQLI_ASSOC)) {
                                echo "<option value=" . $tnRow["tradeName"] . ">" . $tnRow["tradeName"] . "</option>";
                            }
                        ?>
                    </select>
                </td>
                <td width="20%">
                    <input type="date" name="selectDate" id="selectDate" style="margin:auto"></input>
                </td>
                <td width="10%">
                    <input type="submit" value="Filter" class="button" style="margin:auto" />
                </td>
            </tr>
        </table>
    </form>

    <?php
    // Get all information from the Tradesman table and tradeName from the TradeCategory table
    $q = "SELECT Tradesman.tradesmanId, Tradesman.tradesmanName, TradeCategory.tradeName, 
    Tradesman.hourlyRate, Tradesman.availableAfter, Tradesman.skills, Tradesman.verificationStatus
    FROM Tradesman
    INNER JOIN TradeCategory
    ON Tradesman.tradeId = TradeCategory.tradeId
    ORDER BY Tradesman.verificationStatus DESC";

    // Select a trade from the dropdown menu to filter tradesmen
    // if (!empty($_POST["selectTrade"])) {
    // $sn = $dbc->real_escape_string(trim($_POST["selectTrade"]));
    // $q = "SELECT Tradesman.tradesmanId, Tradesman.tradesmanName, TradeCategory.tradeName, 
    // Tradesman.hourlyRate, Tradesman.availableAfter, Tradesman.skills, Tradesman.verificationStatus
    // FROM Tradesman
    // INNER JOIN TradeCategory
    // ON Tradesman.tradeId = TradeCategory.tradeId
    // AND TradeCategory.tradeName LIKE '$sn%'
    // ORDER BY Tradesman.verificationStatus DESC";
    // }

    // Initialize query
    $q = "SELECT Tradesman.tradesmanId, Tradesman.tradesmanName, TradeCategory.tradeName, 
            Tradesman.hourlyRate, Tradesman.availableAfter, Tradesman.skills, Tradesman.verificationStatus
            FROM Tradesman
            INNER JOIN TradeCategory
            ON Tradesman.tradeId = TradeCategory.tradeId";

    // Check if inputs are provided
    $conditions = [];
    
    if (!empty($_POST["selectTrade"])) {
        $sn = $dbc->real_escape_string(trim($_POST["selectTrade"]));
        $conditions[] = "TradeCategory.tradeName LIKE '$sn%'";
    }
    
    if (!empty($_POST["selectDate"])) {
        $date = $dbc->real_escape_string(trim($_POST["selectDate"]));
        $conditions[] = "Tradesman.availableAfter >= '$date'";
    }

    // Append conditions to the query
    if (!empty($conditions)) {
        $q .= " WHERE " . implode(" AND ", $conditions);
    }

    $q .= " ORDER BY Tradesman.verificationStatus DESC";


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
            <th align="left"><b>Book</b></th>
        </tr>';

        while ($row = $r->fetch_array(MYSQLI_ASSOC)) {
        echo '<tr>
            <td align="left">' . $row["tradesmanName"] . '</td>
            <td align="left">' . $row["tradeName"] . '</td>
            <td align="left">£' . $row["hourlyRate"] . '</td>
            <td align="left">' . $row["skills"] . '</td>
            <td align="left">' . $row["availableAfter"] . '</td>
            <td align="left">' . ($row["verificationStatus"] ? "&check;" : "&#10005;") . '</td>
            <td><a href="booking.php?tradesmanId=' . $row["tradesmanId"] . '" ><input type="submit" value="Book" class="button"/></a></td>
        </tr>';
        }
    echo '</form>
    </table>';

    $r->free_result();
    } else {
    echo '<p class="error">There are currently no registered tradesman.</p>';
    }

    $dbc->close();
    ?>

</div>

<?php include "includes/footer.html"; ?>
