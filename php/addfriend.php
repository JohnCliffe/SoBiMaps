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
	$friendname = $_POST['friendname'];
	$friendid;
	
	ob_start();
    $sql = $conn->prepare("SELECT userid from User " .
           "WHERE LOWER(TRIM(username)) = LOWER(TRIM(?))");
	$sql->bind_param("s", $friendname);
	$sql->execute();
	$result = $sql->get_result();
    ob_end_clean();
	
	if ( $result === false ) {
        echo "sql ERROR";
    } else {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
                $friendid = $row['userid'];
				
				if ($friendid == $userid){
					echo "You cannot add yourself as a friend.";
					mysqli_close($conn);
					exit();
				}
				
				ob_start();
				$sql = $conn->prepare("SELECT * from Friends " .
					   "WHERE userId = ? AND userFriendId = ? " .
					   "UNION " .
					   "SELECT * from Friends " .
					   "WHERE userId = ? AND userFriendId = ? ;");
					   
				$sql->bind_param("iiii", $userid, $friendid, $friendid, $userid);
				$sql->execute();
				$result1 = $sql->get_result();
				ob_end_clean();
				if ( $result1 === false ) {
					echo "sql ERROR";
					exit();
				} else {
					if (mysqli_num_rows($result1) > 0) {
						echo "Failed to add friend. You may have already added them.";
						mysqli_close($conn);
						exit();
					}
				}
				
            }
			
			ob_start();
			$sql = $conn->prepare("INSERT INTO Friends (userId, userFriendId, acceptedStatus, createdTimestamp) " . 
				   "VALUES(?, ?, 0, date(\"Y-m-d H:i:s\")) ;");
			$sql->bind_param("ii", $userid, $friendid);
			$status = $sql->execute();
			ob_end_clean();
			
			if($status === true){
				echo "Friend request sent.";
			} else{
				echo "Failed to add friend. You may have already added them.";
			}
			
		} else {
			echo "User does not exist";
		}
	}
	
	mysqli_close($conn);
?>