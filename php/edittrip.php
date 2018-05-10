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
	$tripid = $_POST['tripid'];
	
	$minRacks = intval($minRacks);
	
	if (!is_int($minRacks) || $minRacks < 5){
		$minRacks = 5;
	} else if ($minRacks > 25){
		$minRacks = 25;
	}
	
	if (strlen($tripName) > 50){
		array_push($status, $VALIDATION_ERROR, $TRIP_NAME_ERROR, "Trip name cannot be more than 50 characters.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	} else if (strlen($tripName) == 0){
		array_push($status, $VALIDATION_ERROR, $TRIP_NAME_ERROR, "Trip name cannot be blank.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	}
	
	if (strlen($start) == 0){
		array_push($status, $VALIDATION_ERROR, $START_ERROR, "Start location cannot be blank.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	}
	
	if (strlen($destination) == 0){
		array_push($status, $VALIDATION_ERROR, $DESTINATION_ERROR, "Destination location cannot be blank.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	}
	
	ob_start();
	$sql = $conn->prepare("UPDATE Trips SET start = ?, destination = ?, " .
		   "racks = ?, tripname = ? " .
		   "WHERE userid = ? and tripid = ? ;");
	$sql->bind_param("ssisii", $start, $destination, $minRacks, $tripName, $userid, $tripid);
	$result = $sql->execute();
	ob_end_clean();
	
	if($result === true){
        array_push($status, $SUCCESS);
		echo json_encode($status);
    } else{
        array_push($status, $ERROR, "Failed to update trip");
		echo json_encode($status);
    }
    mysqli_close($conn);
?>