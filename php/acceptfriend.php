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
	$friendid = $_POST['friendid'];
	
	ob_start();
	$sql = $conn->prepare("UPDATE Friends SET acceptedStatus = 1 " .
		   "WHERE userId = ? and userFriendId = ? ;");
	$sql->bind_param("ii", $friendid, $userid);
	$status = $sql->execute();
	ob_end_clean();
	
	if($status === true){
        echo "Request accepted.";
    } else{
        echo "Unable to accept friend request.";
    }
    mysqli_close($conn);
?>