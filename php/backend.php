<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
    $database = "000334810";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

    $sql = "SELECT userId, userName, email FROM user";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "id: " . $row["userId"]. " - Name: " . $row["userName"]. " Email: " . $row["email"]. "<br>";
        }
    } else {
        echo "0 results";
    }

    mysqli_close($conn);
	echo "Connected successfully";
