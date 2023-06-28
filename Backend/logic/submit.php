<?php

session_start();
$path = $_SERVER['DOCUMENT_ROOT'];

require ($path."config/dbaccess.php");

$salutation = $_POST['salutation'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$address = $_POST['address'];
$postcode = $_POST['postcode'];
$location = $_POST['location'];
$email = $_POST['email'];
$username = $_POST['username'];
$password  = hash("sha512", $_POST["password"]);
$creditCard = $_POST['creditCard'];

$conn = new mysqli($dbhost, $dbuser, $dbpassword, $db);

$stmt = $conn->prepare("INSERT INTO user (salutation, firstName, lastName, address, postcode, location, creditCard, email, username, password, active, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0)");

$stmt->bind_param("ssssssssss", $salutation, $firstName, $lastName, $address, $postcode, $location, $creditCard, $email, $username, $password);

if ($stmt->execute()) {
    echo "Successful";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>