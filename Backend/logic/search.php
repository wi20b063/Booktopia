<?php
// Verbindung zur Datenbank herstellen (Beispiel)
require (dirname(__FILE__,2) . "\config\dbaccess.php");

// Retrieve the search query from the AJAX request
$query = $_POST['query'];

// Perform the database query
$sql = "SELECT * FROM book WHERE titel LIKE '%$query%'";
$result = $con->query($sql);

// Prepare the search results as an array
$searchResults = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $searchResults[] = $row;
  }
}

// Return the search results as JSON
header('Content-Type: application/json');
echo json_encode($searchResults);

// Close the database connection
$con->close();
?>