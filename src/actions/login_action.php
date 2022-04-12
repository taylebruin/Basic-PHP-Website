<?php

session_start();

// ./actions/login_action.php

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
	//	echo "Database Connection Success";
}

// TODO: Log the user in
$stmt = $conn->prepare("SELECT * FROM user");
$stmt->execute();

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// $stmt = $conn->prepare("SELECT username FROM user");
	// $stmt->execute();
	// $pass = $conn->prepare("SELECT password FROM user");
	if (empty(trim($_POST["username"]))) {
		$_SESSION["nameerror"] = "Please enter a username";
		header("Location: ../views/login.php");
	} else {
		$username = trim($_POST["username"]);
	}
	if (empty(trim($_POST["password"]))) {
		$_SESSION["passerror"] = "Please enter a password";
		header("Location: ../views/login.php");
	}
	else{
		$password = trim($_POST["password"]);
	}
	$stmt->close();
	$stmt = $conn->prepare("SELECT `username` FROM `user` WHERE `username` = ?");
	$stmt->bind_param('s', $username);
	if ($stmt->execute()) {
		$result = $stmt->get_result()->fetch_assoc();
		$_SESSION["username"] = $result['username'];
		$stmt = $conn->prepare("SELECT `password` FROM `user` WHERE `username` = ?");
		$stmt->bind_param('s', $username);
		if($stmt->execute()){
			$result = $stmt->get_result()->fetch_assoc();
			$hash = $result['password'];
			if(password_verify($password, $hash)){
			$_SESSION["loggedin"] = true;
			$_SESSION["username"] = $username;
			$stmt->close();
			$stmt = $conn->prepare("SELECT `id` FROM `user` WHERE `username` = ?");
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();
			$_SESSION["id"] = $result['id'];
			//echo "got past session variable";
			header("Location: ../index.php");
			}
			else{
				$_SESSION["nameerror"] = "username or password is incorrect";
				$_SESSION["passerror"] = "";
				header("Location: ../views/login.php");
			}
		}
		//password stuff
	} else {
		$_SESSION["nameerror"] = "username or password is incorrect";
		$_SESSION["passerror"] = "";
		header("Location: ../views/login.php");
	}
}
