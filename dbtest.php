<HTML>
<head>
	<title>dbtest</title>
</head>
<body>
	<p>Hello from HTML</p>

	<?php
	echo "Hello from PHP<br>";

	include('includes/connect_db.php');

	$sql="SELECT * FROM User";

	if ($result = $dbc->query($sql))
	{
		$rowcount = $result->num_rows;						// Return the number of rows in result set
		printf("<br>Result set has %d rows.<br>", $rowcount);

		foreach($result as $row){
			echo $row['userId'], " ", $row['userName'], " ", $row['userEmail'], " ", $row['userPassword'], " ", $row['userPhone'], " ", $row['userAddress'], "<br>";
		}

		$result->free_result();								// Free result set
	}

	$dbc->close();

	echo "<br>Goodbye from PHP";
	?>

	<p>Hello from HTML again!</p>
</body>
</HTML>
