<?php
	session_start();
	if(isset($_SESSION['userid'])) {
		$servername = "localhost";
		$username = "000334810";
		$password = "19940705";
		$database = "000334810";
		
		ob_start();
		$conn = new mysqli($servername, $username, $password, $database);
		ob_end_clean();
		
		if ($conn->connect_error) {
			session_unset();
			session_destroy();
			exit();
		}
		
		ob_start();
		$sql = $conn->prepare("INSERT INTO LoginLog (userId, loginEvent, createdTimestamp) " . 
			   "VALUES(?, 'logout', date(\"Y-m-d H:i:s\")) ;");
		$sql->bind_param("i", $_SESSION['userid']);
		$sql->execute();
		ob_end_clean();
		
		session_unset();
		mysqli_close($conn);
	}
	session_destroy();
?>