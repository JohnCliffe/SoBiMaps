<?php
	$serverName = "localhost";
	$username = "root";
	$password = "";
    $database = "000334810";

    // Create connection
    $conn = new mysqli($serverName, $username, $password, $database);

    $userName = $_POST['userName'];
    $userPassword = md5($_POST['password']);
    $email = $_POST['email'];

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

    $sql = "INSERT INTO user (userName, password, email, createdTimestamp) VALUES ('$userName', '$userPassword', '$email', date(\"Y-m-d H:i:s\"))";
    if(mysqli_query($conn, $sql)){
        echo "Records inserted successfully.";
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    mysqli_close($conn);
	echo "Connected successfully";
    header('location:..\index.html');