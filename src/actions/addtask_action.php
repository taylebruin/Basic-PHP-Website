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
$taskdescription = "";
$taskdate = $_POST["date_completed"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//echo "yay were here!!";
	$taskdescription = trim($_POST["action"]);

	//$taskdate = $_POST["date completed"];
	//echo ' <br> ';
	//echo $taskdescription;
	//echo ' <br> ';
	//echo $taskdate;
	$stmt = $conn->prepare("INSERT INTO `task` (`text`, `date`, `user_id`, `done`) VALUES (?,?,?,?) ");
	$stmt->bind_param('ssii', $taskinfo, $dateoftask, $currentuserid, $istaskdone);
	$taskinfo = $taskdescription;
	$dateoftask = $taskdate;
	$currentuserid = $_SESSION["id"];
	$istaskdone = 0;
	$stmt->execute();
	header("Location: ../index.php");

}
?>
