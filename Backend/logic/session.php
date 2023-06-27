<?php
//necessary for every session access

// BUG !!!! now deactivated cause otherwise the session is not working
session_start();

require (dirname(__FILE__,2) . "..\config\dbaccess.php");

// check if DB access is available
$con = mysqli_connect($dbhost, $dbuser, $dbpassword, $db);
if(!$con){
	echo "Datenbankverbindung fehlgeschlagen!";
	exit();
}

?>