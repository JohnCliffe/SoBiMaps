<?php
	$servername = "localhost";
	$username = "000334810";
	$password = "19940705";
	$database = "000334810";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "SELECT userid, username, password FROM User " .
		   "WHERE LOWER(username) = LOWER('" . $_POST['username'] . "');";
	$result = mysqli_query($conn,$sql);
	if ( false===$result ) {
	  die("error: " . mysqli_error($conn));
	} else {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				if ($row["password"] == md5($_POST["password"])){
					echo "Login successful!";
				} else {
					die("Password is incorrect.");
				}
			}
		} else{
			die("User not found");
		}
	}
?>