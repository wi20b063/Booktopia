<?php
// necessary for every session access
session_start();

require(dirname(__FILE__, 2) . "/config/dbaccess.php");
require(dirname(__FILE__, 2) . "/logic/services/userService.php");

// check if DB access is available
$con = mysqli_connect($dbhost, $dbuser, $dbpassword, $db);
if (!$con) {
    echo "Datenbankverbindung fehlgeschlagen!";
    exit();
}

// check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // create an instance of the UserService
    $userService = new UserService($con, $tbl_user);

    // authenticate the user
    $user = $userService->authenticateUser($username, $password);

    if ($user) {
        // set session variables
        $_SESSION["userid"] = $user->getUserID();
        $_SESSION["active"] = $user->getActive();
        $_SESSION["admin"] = $user->getAdmin();

        // send a success response
        echo "success";
    } else {
        // send an error response
        echo "error";
    }
} else {
    // send an error response if the request method is not POST
    echo "Invalid request";
}