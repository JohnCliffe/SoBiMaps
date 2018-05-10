<?php
session_start();
if(isset($_SESSION['userid'])) {
    $servername = "localhost";
    $username = "000334810";
    $password = "19940705";
    $database = "000334810";

    $error = $_POST['error'];

    ob_start();
    $conn = new mysqli($servername, $username, $password, $database);
    ob_end_clean();

    if ($conn->connect_error) {
        echo "An error occurred.";
        exit();
    }

    ob_start();
    $sql = $conn->prepare("INSERT INTO errorlog (userId, loginId, createdTimestamp, errorDescription) " .
           "VALUES(? , ?, date(\"Y-m-d H:i:s\"), ?) ;");
	$sql->bind_param("iis", $_SESSION['userid'], $_SESSION['loginId'], $error);
	$result = $sql->execute();
    ob_end_clean();

    mysqli_query($conn, $sql);
    mysqli_close($conn);
}
?>