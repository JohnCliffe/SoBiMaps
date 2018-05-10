<?php
	$servername = "localhost";
	$username = "000334810";
	$password = "19940705";
	$database = "000334810";
	
    // Create connection
	ob_start();
	$conn = new mysqli($servername, $username, $password, $database);
	ob_end_clean();
	
	if ($conn->connect_error) {
		echo "ERROR";
		exit();
	}
	
	session_start();
	
	$trips = array();
	$userid = $_SESSION['userid'];
	
	ob_start();
	$sql = $conn->prepare("SELECT tripname, start, destination, racks, sharedTripsId FROM SharedTrips " .
		   "WHERE userFriendId = ? ;");
	$sql->bind_param("i", $userid);
	$sql->execute();
	$result = $sql->get_result();
	ob_end_clean();
	
	if ( false===$result ) {
	  echo "ERROR";
	} else {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$tripData = array($row['tripname'], $row['start'],
								  $row['destination'], $row['racks'], $row['sharedTripsId']);
				array_push($trips, $tripData);
			}
		}
		echo json_encode($trips);
	}
	
	mysqli_close($conn);
?>