<?php

session_start();

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");

// This section for DEBUGGING ONLY! COMMENT-OUT WHEN FINISHED
// echo "<p>mysql_servername: $mysql_servername</p>";
// echo "<p>mysql_user: $mysql_user</p>";
// echo "<p>mysql_password: $mysql_password</p>";
// echo "<p>mysql_database: $mysql_database</p>";

$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
		//echo "Database Connection Success";
}
$taskid = $_POST["task_id"];
$currentuserid = $_SESSION["id"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskdone = $_POST["task_done"];
    if($taskdone == 1){
        $taskdone = 0;
    }
    else if ($taskdone == 0){
        $taskdone = 1;
    }
    //echo "is the task done?: ";
    //echo $taskdone;
    $stmt = $conn->prepare("UPDATE task SET done = $taskdone WHERE `id` = ? ");
	$stmt->bind_param('i', $taskid);
    $stmt->execute();
    header("Location: ../index.php");


}
?>