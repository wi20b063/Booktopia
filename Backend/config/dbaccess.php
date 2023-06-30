<?php
   
   $dbhost = "localhost";
   $dbuser = "root";
   $dbpassword = ""; //derzeit noch kein Passwort gesetzt
   $db = "bookstopia";
   $tbl_user = "user";
   $tbl_book = "book";
   $tbl_voucher = "voucher";
   $tbl_order = "orders";
   $tbl_order_details = "order_details";
   $tbl_payment_items="paymentitems";
   
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