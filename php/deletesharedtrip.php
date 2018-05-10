<?php
	$servername = "localhost";
	$username = "000334810";
	$password = "19940705";
	$database = "000334810";
	
	$ERROR = -1;
	$VALIDATION_ERROR = 0;
	$SUCCESS = 1;
	
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
	$tripid = $_POST['tripid'];
	
	ob_start();
	$sql = $conn->prepare("DELETE from SharedTrips " .
		   "WHERE userFriendId = ? and sharedTripsId = ? ;");
	$sql->bind_param("ii", $userid, $tripid);
	$result = $sql->execute();
	ob_end_clean();
	
	if($result === true){
        echo "Shared trip deleted successfully.";
    } else{
        echo "Failed to delete shared trip.";
    }
    mysqli_close($conn);
?>