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
	
	$userid = $_SESSION['userid'];
	$tripid = $_POST['tripid'];
	$friendid = $_POST['friendid'];
	
	$tripName;
	$start;
	$destination;
	$racks;
	
	ob_start();
	$sql = $conn->prepare("SELECT tripname, start, destination, racks FROM Trips " .
		   "WHERE userid = ? and tripid = ? ;");
	$sql->bind_param("ii", $userid, $tripid);
	$sql->execute();
	$result = $sql->get_result();
	ob_end_clean();
	
	if ( false===$result ) {
	  echo "ERROR";
	} else {
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$tripName = $row['tripname'];
				$start = $row['start'];
				$destination = $row['destination'];
				$racks = $row['racks'];
				
				ob_start();
				$sql = $conn->prepare("INSERT into SharedTrips (userId, userFriendId, tripName, start, destination, racks) " .
					   "VALUES(?, ?, ?, ?, ?, ?);" );
				$sql->bind_param("iisssi", $userid, $friendid, $tripName, $start, $destination, $racks);
				$result1 = $sql->execute();
				ob_end_clean();
				
				if($result1 === true){
					echo "Trip shared successfully.";
				} else{
					echo $sql;
				}
			}
		} else {
			echo "Unable to find trip.";
		}
		
		mysqli_close($conn);
	}
?>