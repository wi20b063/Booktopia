<?php 

$salutation = $firstName = $lastName = $address = $postcode = $location = $creditCard = $email = $username = $password = "";


// UserService Class implements CRUD operations

class UserService {

    // get data from MySQL database with SQL statements
    private $con;
    private $tbl_user;

    public function __construct($con, $tbl_user) {
        $this->con = $con;
        $this->tbl_user = $tbl_user;
    }


    // ************************************************************
    //          SAVE / REGISTER USER
    // ************************************************************
    
    // create user with prepared statement with array as input
    public function saveUser($user) {
        // get data from array
        $salutation = $user['salutation'];
        $firstName = $user['firstName'];
        $lastName = $user['lastName'];
        $address = $user['address'];
        $postcode = $user['postcode'];
        $location = $user['location'];
        $email = $user['email'];
        $username = $user['username'];
        $password = $user['password'];
        // $creditCard = $user['creditCard'];
        $active = 1;
        $admin = 0;    

        // check if user already exists with prepared statement
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        // $row = $result->fetch_assoc();
                
        if ($result->num_rows > 0) {
            // User already exists
            echo "User exists";
            
        } else {
            // add user with prepared statement         
            $sqlIns = "INSERT INTO user (salutation, firstName, lastName, address, postcode, location, email, username, password, active, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sqlIns);
            $stmt->bind_param("sssssssssii", $salutation, $firstName, $lastName, $address, $postcode, $location, $email, $username, $password, $active, $admin);
            $stmt->execute();
            $result = mysqli_stmt_affected_rows($stmt);
            
            if ($result == 1) {
                // user created
                header("Refresh:0; url=../index.php");
            } else {
                // error - user not created
                echo " Fehler: Nutzer wurde nicht erstellt.";
            }
        }
    }
    
    

    // ************************************************************
    //          LOGIN USER
    // ************************************************************

    // login user with prepared statement (username and password) and server validation
    public function loginUser($username, $password, $rememberMe) {      
        
        // check if user exists with prepared statement
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($result->num_rows > 0) {
            // User exists          
              
            // check if password is correct with prepared statement (password is sha256 hashed)
            $sql = "SELECT * FROM user WHERE username = ? AND password = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {                
                // password correct
                
                // set session variables
                $_SESSION['username'] = $username;
                // get userid, admin and active from query saved in $result in userService.php
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['admin'] = $row['admin'];
                $_SESSION['active'] = $row['active'];
                          
                // if rememberMe checked, set cookie to expire after 30 days
                if ($rememberMe == "true") {
                    
                    // set secure cookie with salt and encode with base64
                    $salt = "!1salt#@"; // salt for secure cookie
                    $encodedCookieValue = base64_encode($salt . $username);
                    // setcookie(key, value, expire, path, domain, secure, httponly);
                    setcookie("rememberLogin", $encodedCookieValue, time() + (86400 * 30), "/"); // 86400 = 1 day / secure, http only
                    
                // if rememberMe not checked, don't set cookie
                } else {
                    // echo " rememberMe NOT checked: ";
                }
               
                //header("Refresh:0; url=../../../Booktopia/Frontend/sites/index.php");
                return true;
                
            } else {
                // error - password incorrect
                return "Passwort ist nich korrekt.";
            }
        } else {
            // error - user not found
            return "Username wurde nicht gefunden.";
        }
    }

    

    // ************************************************************
    //          LOGOUT USER
    // ************************************************************

    
    public function logoutUser() {

        echo " logoutUser in userServie.php reached";

        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();        

        // unset cookies
        if (isset($_COOKIE['rememberLogin'])) {
            setcookie("rememberLogin", "", time() - 3600, "/"); // 86400 = 1 day / secure, http only
        }       

        return true;
    }


    // ************************************************************
    //          GET USER DATA
    // ************************************************************
    
    // get user data
    public function getUserData() {

        $userData = array();

        if (isset($_SESSION["username"])) {

            $username = $_SESSION["username"];

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {

                $userData["salutation"] = $row['salutation'];
                $userData["firstName"] = $row['firstName'];
                $userData["lastName"] = $row['lastName'];
                $userData["address"] = $row['address'];
                $userData["postcode"] = $row['postcode'];
                $userData["location"] = $row['location'];
                $userData["email"] = $row['email'];
                $userData["username"] = $row['username'];
                $userData["password"] = $row['password'];
                // $userData["creditCard"] = $row['creditCard'];
                $userData["active"] = $row['active'];
                $userData["admin"] = $row['admin'];
            }
                        
        }
        
        return $userData;

    }




    // ************************************************************
    //          GET SESSION VARIABLES
    // ************************************************************
    
    // get session variables
    public function getSession() {

        $userSession = array();

        // check if user is logged in and which role he has
        if (isset($_SESSION["userid"]) && $_SESSION["active"] == 1) {
            
            $userSession['sessionUsername'] = $_SESSION['username'];
            $userSession['sessionUserid'] = $_SESSION['userid'];
            $userSession['sessionAdmin'] = $_SESSION['admin'];
            $userSession['sessionActive'] = $_SESSION['active'];

            return $userSession;

        } elseif (isset($_COOKIE["rememberLogin"])) {
            
            $encodedCookieRememberLogin = $_COOKIE["rememberLogin"];

            $decodedCookieRememberLogin = base64_decode($encodedCookieRememberLogin);
            $decodedUsernameFromCookie = substr($decodedCookieRememberLogin, 8);

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $decodedUsernameFromCookie);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {

                // set session variables
                // get username, userid, admin and active from query saved in $result in userService.php
                $userSession['sessionUsername'] = $row['username'];
                $userSession['sessionUserid'] = $row['userid'];
                $userSession['sessionAdmin'] = $row['admin'];
                $userSession['sessionActive'] = $row['active'];
            
                return $userSession;
            }            
            
        } else {
            
            // return empty array            
            return $userSession;
                        
        }
    }


    // ************************************************************
    //          CHECK PASSWORD
    // ************************************************************

    // check password
    public function checkPassword($passwordToBeChecked) {

        if (isset($_SESSION["username"])) {
            $username = $_SESSION["username"];

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            // $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {

                // User exists            
                // check if password is correct with prepared statement (password is sha256 hashed)
                $sql = "SELECT * FROM user WHERE username = ? AND password = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("ss", $username, $passwordToBeChecked);
                $stmt->execute();
                $result = $stmt->get_result();
                // $row = $result->fetch_assoc();
                
                if ($result->num_rows > 0) {

                    // password correct
                    return true;
                    
                } else {
                    // error - password incorrect
                    return "Passwort ist nich korrekt.";
                }
            } else {
                // error - user not found
                return "Username wurde nicht gefunden. Bitte einloggen.";
            }
            
            
        }
        
    }


    // ************************************************************
    //          SAVE EDITED USER PROFILE
    // ************************************************************
    
    public function saveEditedUserData($editedUser) {

        // get data from array
        $salutation = $editedUser['salutation'];
        $firstName = $editedUser['firstName'];
        $lastName = $editedUser['lastName'];
        $address = $editedUser['address'];
        $postcode = $editedUser['postcode'];
        $location = $editedUser['location'];
        $email = $editedUser['email'];
        $username = $editedUser['username'];
        // $password = $editedUser['password'];
        // $creditCard = $editedUser['creditCard'];
        
        // check if user already exists with prepared statement
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        // $row = $result->fetch_assoc();
                
        if ($result->num_rows > 0) {
            // User already exists            
            
            // update user with prepared statement
            $sqlUpd = "UPDATE user SET salutation = ?, firstName = ?, lastName = ?, address = ?, postcode = ?, location = ?, email = ? WHERE username = ?";
            $stmt = $this->con->prepare($sqlUpd);
            $stmt->bind_param("ssssssss", $salutation, $firstName, $lastName, $address, $postcode, $location, $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->affected_rows > 0) {
                
                // user updated
                header("Refresh:0; url=../profile.php");
                
            } else {
                
                // error - user not updated
                echo " // Fehler: Nutzerdaten konnten nicht aktualisiert werden.";
            }
            
        } else {
            echo " // Nutzer existiert nicht.";
        }

        
    }


    // ************************************************************
    //          GET ORDER DATA
    // ************************************************************
    
    // get order data
    public function getOrderData() {

        $orderData = array();

        if (isset($_SESSION["username"])) {
            // active session --> user is logged in

            $username = $_SESSION["username"];

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {

                // user exits: get all orders from this user with prepared statement and save in array
                $sql = "SELECT * FROM orders WHERE userid = ? ORDER BY orderDate ASC";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("i", $row['userid']);
                $stmt->execute();
                $result = $stmt->get_result();
                // $row = $result->fetch_assoc();
                
                // save array with orders in $orderData
                $orderData = $result->fetch_all(MYSQLI_ASSOC);

            }
            
        }
        return $orderData;
    }




    // ************************************************************
    //          GET ORDER DATA BY ORDER ID
    // ************************************************************
    
    // get order data
    public function getOrderDataByOrderId($orderId) {

        $orderData = array();

        if (isset($_SESSION["username"])) {
            // active session --> user is logged in

            $username = $_SESSION["username"];

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            // $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {

                // user exits: check if order exists with prepared statement
                $sql = "SELECT * FROM orders WHERE orderId = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("i", $orderId);
                $stmt->execute();
                $result = $stmt->get_result();
                // $row = $result->fetch_assoc();
                

                if ($result->num_rows > 0) {
                                        
                    // order exits: save array with order data in $orderData
                    $orderData = $result->fetch_all(MYSQLI_ASSOC);                    
                    
                }
            }
        }
        return $orderData;
    }



    // ************************************************************
    //          GET ORDER DETAILS BY ORDER ID
    // ************************************************************

    // get order details for specific orderId
    public function getOrderDetails($clickedOrderId) {

        $orderDetails = array();

        if (isset($_SESSION["username"])) {
            // active session --> user is logged in

            $username = $_SESSION["username"];

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            // $row = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {

                // user exits: check if order exists with prepared statement
                $sql = "SELECT * FROM orders WHERE orderId = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("i", $clickedOrderId);
                $stmt->execute();
                $result = $stmt->get_result();
                // $row = $result->fetch_assoc();
                
                if ($result->num_rows > 0) {
                    
                    // order exits: get all order items from this order with prepared statement and save in array
                    $sql = "SELECT * FROM order_details WHERE orderId = ?";
                    $stmt = $this->con->prepare($sql);
                    $stmt->bind_param("i", $clickedOrderId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // $row = $result->fetch_assoc();
                    
                    // save array with order items in $orderDetails
                    $orderDetails = $result->fetch_all(MYSQLI_ASSOC);
                    
                }
                
            }
        }
        return $orderDetails;
    }

       
    // Close connection
    public function closeConnection() {
        $this->con->close();
    }
} 