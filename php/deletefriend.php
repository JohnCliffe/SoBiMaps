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
	$sql = $conn->prepare("DELETE from Friends " .
		   "WHERE (userId = ? and userFriendId = ?) " .
		   "OR (userId = ? and userFriendId = ?);");
	$sql->bind_param("iiii", $friendid, $userid, $userid, $friendid);
	$result = $sql->execute();
	ob_end_clean();
	
	if($result === true){
        echo "Friend deleted.";
    } else{
		echo $sql;
        echo "Unable to delete friend.";
    }
    mysqli_close($conn);
?>