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
	
	$locations = array();
	$userid = $_SESSION['userid'];
	
	ob_start();
	$sql = $conn->prepare("SELECT locationName, locationAddress, locationId from Location " .
		   "WHERE userId = ? ;");
	$sql->bind_param("i", $userid);
	$sql->execute();
	$result = $sql->get_result();
	ob_end_clean();
	
	if ( false===$result ) {
	  echo "ERROR";
	} else {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$locationData = array($row['locationName'], 
									  $row['locationAddress'], $row['locationId']);
				array_push($locations, $locationData);
			}
		}
		echo json_encode($locations);
	}
	
	mysqli_close($conn);
?>