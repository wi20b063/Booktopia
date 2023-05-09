<?php
   
   $dbhost = "localhost";
   $dbuser = "root";
   $dbpassword = ""; //derzeit noch kein Passwort gesetzt
   $db = "bookstopia";
   $tbl_user = "user";

    // Create connection
   $con = new mysqli($dbhost, $dbuser, $dbpassword, $db);

   // Check connection mit genauer Fehlerausgabe
   if($con->connect_error) {
       die("Connection failed: " . $con->connect_error);
   }
    echo "Connected successfully!";

    /*
    // Create new data
    $sql = "INSERT INTO user (salutation, firstName, lastName, address, postcode, location, email, username, password, active, admin) VALUES ('Frau', 'Denise', 'Denise', 'Straße', '1010', 'Wien', 'denise@bookstopia.at', 'Denise', 'PW', 1, 1)";

    // Check if record was created
    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " .$con->error;
    }
    */
    
    // Read data
    $sql = "SELECT * FROM user";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        echo "<br>";
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["userid"]. " - Name: " . $row["username"]. " - E-Mail: " . $row["email"]. "<br>";
        }
    } else {
        echo "0 results";
    }

    // Close connection
    $con->close();
  
  ?>