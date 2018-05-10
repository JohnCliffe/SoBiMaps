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
		echo "An error occurred.";
		exit();
	}
	
	session_start();
	
	$userid = $_SESSION['userid'];
	$locationid = $_POST['locationid'];
	
	ob_start();
	$sql = $conn->prepare("DELETE from Location " .
		   "WHERE userId = ? and locationId = ? ;");
	$sql->bind_param("ii", $userid, $locationid);
	$result = $sql->execute();
	ob_end_clean();
	
	if($result === true){
        echo "Location deleted.";
    } else{
        echo "Unable to delete location.";
    }
    mysqli_close($conn);
?>