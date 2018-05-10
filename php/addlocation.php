<?php
	$servername = "localhost";
	$username = "000334810";
	$password = "19940705";
	$database = "000334810";
	
	$ERROR = -1;
	$VALIDATION_ERROR = 0;
	$SUCCESS = 1;
	
	$NAME_ERROR = 1;
	$ADDRESS_ERROR = 2;
	
	$status = array();

    // Create connection
	ob_start();
	$conn = new mysqli($servername, $username, $password, $database);
	ob_end_clean();
	
	if ($conn->connect_error) {
		echo "Failed to connect to the database";
		exit();
	}
	
	session_start();
	
	$userid = $_SESSION['userid'];
	$locationName = $_POST['locationName'];
	$locationAddress = $_POST['locationAddress'];
	
	if (strlen($locationName) > 50){
		echo "Location name cannot be more than 50 characters.";
		mysqli_close($conn);
		exit();
	} else if (strlen($locationName) == 0){
		echo "Location name cannot be blank.";
		mysqli_close($conn);
		exit();
	}
	
	if (strlen($locationName) > 250){
		echo "Location address cannot be more than 250 characters.";
		mysqli_close($conn);
		exit();
	} else if (strlen($locationName) == 0){
		echo "Location address cannot be blank.";
		mysqli_close($conn);
		exit();
	}
	
	ob_start();
	$sql = $conn->prepare("INSERT INTO Location (userId, locationName, locationAddress, createdTimestamp) " . 
		   "VALUES(?, ?, ?, date(\"Y-m-d H:i:s\")) ;");
	$sql->bind_param("iss", $userid, $locationName, $locationAddress);
	$status = $sql->execute();
	ob_end_clean();
	
	if($status === true){
        echo "Location successfully added.";
    } else{
        echo "Failed to create new location";
    }
    mysqli_close($conn);
?>