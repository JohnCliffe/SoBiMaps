<?php
	$servername = "localhost";
	$username = "000334810";
	$password = "19940705";
	$database = "000334810";
	
	$ERROR = -1;
	$USER_PASSWORD_ERROR = 0;
	$SUCCESS = 1;
	
	$status = array();

    // Create connection
	ob_start();
	$conn = new mysqli($servername, $username, $password, $database);
	ob_end_clean();
	
	if ($conn->connect_error) {
		array_push($status, $ERROR);
		echo json_encode($status);
		exit();
	}
	
	// prepared statement are used to prevent SQL injection
	$sql = $conn->prepare("SELECT userid, username, password FROM User " .
		   "WHERE LOWER(TRIM(username)) = LOWER(TRIM(?));");
	$sql->bind_param("s", $_POST['username']);
	$sql->execute();

	$result = $sql->get_result();
	
	if ( false===$result ) {
	  array_push($status, $ERROR);
	  echo json_encode($status);
	} else {
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				if ($row["password"] == md5($_POST["password"])){
					ob_start();
					$sql = "INSERT INTO LoginLog (userId, loginEvent, createdTimestamp) " . 
						   "VALUES(" . $row['userid'] . ", 'login', date(\"Y-m-d H:i:s\"))";
					ob_end_clean();
					
					if(mysqli_query($conn, $sql)){
						session_start();
					
						$_SESSION['userid'] = $row['userid'];
						$_SESSION['username'] = $row['username'];
						
						array_push($status, $SUCCESS, $_SESSION['username']);
						echo json_encode($status);
					} else{
						array_push($status, $ERROR);
						echo json_encode($status);
					}
				} else {
					array_push($status, $USER_PASSWORD_ERROR);
					echo json_encode($status);
				}
			}
		} else{
			array_push($status, $USER_PASSWORD_ERROR);
			echo json_encode($status);
		}
	}
    $_SESSION['loginId'] = mysqli_insert_id($conn);
	
	mysqli_close($conn);
?>