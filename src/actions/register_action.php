<?php
session_start();
// ./actions/register_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	//echo "Database Connection Success";
}
$stmt = $conn->prepare("SELECT username FROM user");
$stmt->execute();
// TODO: Register a new user

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty(trim($_POST["password"]))) {
		$_SESSION["passworderror"] = "Please enter a password";
		header("Location: ../views/register.php");
	} else {
		$password = trim($_POST["password"]);
	}
	if (empty(trim($_POST["confirm_password"]))) {
		$_SESSION["error"] = "Please confirm password";
		header("Location: ../views/register.php");
	} else {
		$confirm_password = trim($_POST["confirm_password"]);
		if ($confirm_password != $password) {
			$_SESSION["error"] = "Password did not match.";
			header("Location: ../views/register.php");
			exit();
		}
	}
	if (empty(trim($_POST["username"]))) {
		$_SESSION["usernamerror"] = "Please enter a username";
		header("Location: ../views/register.php");
	} else {
		$username = trim($_POST["username"]);
		$stmt->bind_result($currentname);
	//	echo "got here";
		$checker = false;
		while ($row = $stmt->fetch()) {
			if ($username == $currentname) {
				$_SESSION["usernamerror"] = "Please enter a different username";
				header("Location: ../views/register.php");
				$checker = true;
			}
		}
		
	}
	if($checker == false){
		$stmt->close();
		$log = true;
		$stmt = $conn->prepare("INSERT INTO `user` (`username`,`password`,`logged_in`) VALUES (?,?,?)");
		$stmt->bind_param('ssi', $name, $pass, $login);
		$name = $username;
		$pass = password_hash($password, PASSWORD_DEFAULT);
		$login = true;
		$stmt->execute();

		if($stmt->execute()){
			//echo "user added";

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
			echo $name;
			echo $pass;
			echo $login;
			echo "something went wrong";
		}
	}
}
