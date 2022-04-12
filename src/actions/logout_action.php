<?php
session_start();
// ./actions/logout_action.php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$log = true;
	$stmt = $conn->prepare("UPDATE user SET logged_in = 0 WHERE `username` = ?");
	$stmt->bind_param('s', $_SESSION["username"]);
	$stmt->execute();
	$_SESSION["loggedin"] = false;
	$_SESSION["username"] = "";
	$login = false;
	$_SESSION["id"] = '';
	session_destroy();
	header("Location: ../views/login.php");
}
