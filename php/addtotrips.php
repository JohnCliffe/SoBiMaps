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
        echo "connection ERROR";
        exit();
    }
	
	session_start();
	
	$userid = $_SESSION['userid'];
	$tripid = $_POST['tripid'];
	
	$tripName;
	$start;
	$destination;
	$racks;
	
	ob_start();
	$sql = $conn->prepare("SELECT tripName, start, destination, racks FROM SharedTrips " .
		   "WHERE userFriendId = ? AND sharedTripsId = ? ;");
	$sql->bind_param("ii", $userid, $tripid);
	$sql->execute();
	$result = $sql->get_result();
	ob_end_clean();
	
	if ( false===$result ) {
	  echo "ERROR";
	} else {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$tripName = $row['tripName'];
				$start = $row['start'];
				$destination = $row['destination'];
				$racks = $row['racks'];
				
				ob_start();
				$sql = $conn->prepare("INSERT INTO Trips (userId, tripName, start, destination, racks, createdTimestamp) " . 
					   "VALUES(?, ?, ?, ?, ?, date(\"Y-m-d H:i:s\")) ;");
				$sql->bind_param("isssi", $userid, $tripName, $start, $destination, $racks);
				$status = $sql->execute();
				ob_end_clean();
				
				if($status === true){
					ob_start();
					$sql = $conn->prepare("DELETE from SharedTrips " .
						   "WHERE userFriendId = ? AND sharedTripsId = ? ;");
					$sql->bind_param("ii", $userid, $tripid);
					$sql->execute();
					ob_end_clean();
					
					echo "Trip has been added to your trips.";
				} else{
					echo "Unable to add to your trips.";
				}
			}
		} else {
			echo "Unable to add to your trips.";
		}
		
		mysqli_close($conn);
	}
?>