<?php
	$servername = "localhost";
	$username = "000334810";
	$password = "19940705";
	$database = "000334810";
	
	$ERROR = -1;
	$VALIDATION_ERROR = 0;
	$SUCCESS = 1;
	
	$START_ERROR = 1;
	$DESTINATION_ERROR = 2;
	$TRIP_NAME_ERROR = 3;
	$RACKS_ERROR = 4;
	
	$status = array();

    // Create connection
	ob_start();
	$conn = new mysqli($servername, $username, $password, $database);
	ob_end_clean();
	
	if ($conn->connect_error) {
		array_push($status, $ERROR, "Failed to connect to the database");
		echo json_encode($status);
		exit();
	}
	
	session_start();
	
	$userid = $_SESSION['userid'];
	$start = $_POST['start'];
	$destination = $_POST['destination'];
	$tripName = $_POST['tripName'];
	$minRacks = $_POST['minRacks'];
	
	$minRacks = intval($minRacks);
	
	if (!is_int($minRacks) || $minRacks < 5){
		$minRacks = 5;
	} else if ($minRacks > 25){
		$minRacks = 25;
	}
	
	if (strlen($tripName) > 50){
		echo "Trip name cannot be more than 50 characters.";
		mysqli_close($conn);
		exit();
	} else if (strlen($tripName) == 0){
		echo "Trip name cannot be blank.";
		mysqli_close($conn);
		exit();
	}
	
	if (strlen($start) == 0){
		echo "Start location cannot be blank.";
		mysqli_close($conn);
		exit();
	}
	
	if (strlen($destination) == 0){
		echo "Destination location cannot be blank.";
		mysqli_close($conn);
		exit();
	}
	
	ob_start();
	$sql = $conn->prepare("INSERT INTO Trips (userId, tripName, start, destination, racks, createdTimestamp) " . 
		   "VALUES(?, ?, ?, ?, ?, date(\"Y-m-d H:i:s\")) ;");
	$sql->bind_param("isssi", $userid, $tripName, $start, $destination, $minRacks);
	$status = $sql->execute();
	ob_end_clean();
	
	if($status === true){
        echo "Trip added successfully.";
    } else{
        echo "Failed to create new trip.";
    }
    mysqli_close($conn);
?>