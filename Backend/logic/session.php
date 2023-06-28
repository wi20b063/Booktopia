<?php
//necessary for every session access
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path . '\Backend\config\dbaccess.php');

// check if DB access is available
$con = mysqli_connect($dbhost, $dbuser, $dbpassword, $db);
if(!$con){
	echo "Datenbankverbindung fehlgeschlagen!";
	exit();
}


// check if user is logged in and which role he has
/* if (isset($_SESSION["userid"]) && $_SESSION["active"] == 1) {
	if ($_SESSION["admin"] == 1){
		echo "<script>console.log(' ADMIN: session variables set');</script>";
		return "admin";
	} else {
		echo "<script>console.log(' USER: session variables set');</script>";
		return "user";
	}
} else {
	echo "<script>console.log(' GUEST: session variables not set');</script>";
	return "guest";
} */
?>