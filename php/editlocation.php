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
		array_push($status, $ERROR, "Failed to connect to the database");
		echo json_encode($status);
		exit();
	}
	
	session_start();
	
	$userid = $_SESSION['userid'];
	$locationid = $_POST['locationid'];
	$locationName = $_POST['locationName'];
	$locationAddress = $_POST['locationAddress'];
	
	if (strlen($locationName) > 50){
		array_push($status, $VALIDATION_ERROR, $NAME_ERROR, "Location name cannot be more than 50 characters.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	} else if (strlen($locationName) == 0){
		array_push($status, $VALIDATION_ERROR, $NAME_ERROR, "Location name cannot be blank.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	}
	
	if (strlen($locationName) > 250){
		array_push($status, $VALIDATION_ERROR, $ADDRESS_ERROR, "Location address cannot be more than 250 characters.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	} else if (strlen($locationName) == 0){
		array_push($status, $VALIDATION_ERROR, $ADDRESS_ERROR, "Location address cannot be blank.");
		echo json_encode($status);
		mysqli_close($conn);
		exit();
	}
	
	ob_start();
	$sql = $conn->prepare("UPDATE Location SET locationName = ?, " .
		   "locationAddress = ? " .
		   "WHERE userid = ? and locationId = ? ;");
	$sql->bind_param("ssii", $locationName, $locationAddress, $userid, $locationid);
	$result = $sql->execute();
	ob_end_clean();
	
	if($result === true){
        array_push($status, $SUCCESS);
		echo json_encode($status);
    } else{
        array_push($status, $ERROR, "Failed to update location.");
		echo json_encode($status);
    }
    mysqli_close($conn);
?>