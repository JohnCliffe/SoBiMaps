<?php
	$servername = "localhost";
	$dbUsername = "000334810";
	$password = "19940705";
	$database = "000334810";
	
	$ERROR = -1;
	$VALIDATION_ERROR = 0;
	$SUCCESS = 1;
	
	$USERNAME_ERROR = 1;
	$EMAIL_ERROR = 2;
	$PASSWORD_ERROR = 3;
	$PASSWORD_CONFIRM_ERROR = 4;
	
	$status = array();

    // Create connection
	ob_start();
	$conn = new mysqli($servername, $dbUsername, $password, $database);
	ob_end_clean();
	
	if ($conn->connect_error) {
		array_push($status, $ERROR, "Failed to connect to database.");
		echo json_encode($status);
		exit();
	}
	
    $userName = $_POST['userName'];
    $userPassword = md5($_POST['password']);
	$userPasswordConfirm = md5($_POST['passwordConfirm']);
    $email = $_POST['email'];
	
	if (!preg_match('/^[a-zA-z0-9_]{1,20}$/', $userName)){
		array_push($status, $VALIDATION_ERROR, $USERNAME_ERROR, "Username is in invalid format");
		echo json_encode($status);
		exit();
	}
	
	$sql = $conn->prepare("SELECT username FROM User " .
		   "WHERE LOWER(TRIM(username)) = LOWER(TRIM(?));");
	$sql->bind_param("s", $userName);
	$sql->execute();
	$result = $sql->get_result();
		   
	if ($result === false) {
	  array_push($status, $ERROR);
	  echo json_encode($status);
	  exit();
	} else {
		if (mysqli_num_rows($result) > 0) {
			array_push($status, $VALIDATION_ERROR, $USERNAME_ERROR, "Username is already taken");
			mysqli_close($conn);
			echo json_encode($status);
			exit();
		}
	}
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($status, $VALIDATION_ERROR, $EMAIL_ERROR, "Email is in invalid format");
		echo json_encode($status);
		exit();
	}
	
	if (strlen($_POST['password']) < 4){
		array_push($status, $VALIDATION_ERROR, $PASSWORD_ERROR, "Password is too short");
		echo json_encode($status);
		exit();
	}
	
	if (!$userPassword == $userPasswordConfirm){
		array_push($status, $VALIDATION_ERROR, $PASSWORD_CONFIRM_ERROR, "The passwords do not match");
		echo json_encode($status);
		exit();
	}

	ob_start();
    $sql = $conn->prepare("INSERT INTO User (userName, password, email, createdTimestamp) " .
						  "VALUES (TRIM(?), ?, TRIM(?), date(\"Y-m-d H:i:s\"));");
	$sql->bind_param("sss", $userName, $userPassword, $email);
	$result = $sql->execute();
	ob_end_clean();
	
    if($result === true){
        array_push($status, $SUCCESS);
		echo json_encode($status);
    } else{
        array_push($status, $ERROR, "Failed to create new user");
		echo json_encode($status);
    }
    mysqli_close($conn);
?>