<?php
   
   $dbhost = "localhost";
   $dbuser = "root";
   $dbpassword = ""; //derzeit noch kein Passwort gesetzt
   $db = "bookstopia";
   $tbl_user = "user";
   $tbl_book = "book";

    // Create connection
   $con = new mysqli($dbhost, $dbuser, $dbpassword, $db);

   // Check connection mit genauer Fehlerausgabe
   if($con->connect_error) {
       die("Connection failed: " . $con->connect_error);
   }
    // echo console.log "Connected successfully!"
    // echo "<script>console.log('Connected successfully!');</script>";

    // Close connection
    //$con->close();
  
  ?>