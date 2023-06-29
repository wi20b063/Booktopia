<?php

//require(dirname(__FILE__, 3) . "/config/dbaccess.php");
require (dirname(__FILE__, 2) . "\session.php");


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

    // find all users mit prepared statement
    /* public function findAll() {        
        $sql = "SELECT * FROM " . $this->tbl_user;
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new User($row['userid'], $row['salutation'], $row['firstName'], $row['lastName'], $row['address'], $row['postcode'], $row['location'], $row['creditCard'], $row['email'], $row['username'], $row['password']);
            $users[] = $user;
        }

        $stmt->close();

        return $users;
    } */


    // find user by id mit prepared statement
    /* public function findByID(int $id) {        
        $sql = "SELECT * FROM " . $this->tbl_user . " WHERE userid = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        
        $stmt -> close();

        if (!$row) {
            return null; // Benutzer nicht gefunden
        }

        $user = new User($row['userid'], $row['salutation'], $row['firstName'], $row['lastName'], $row['address'], $row['postcode'], $row['location'], $row['creditCard'], $row['email'], $row['username'], $row['password']);
        return $user;
    } */



    // ************************************************************
    //          SAVE / REGISTER USER
    // ************************************************************
    
    // create or update user mit prepared statement with array as input
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

            echo " User exists";
            
            
            // update user with prepared statement
            /* $sqlUpd = "UPDATE user SET salutation = ?, firstName = ?, lastName = ?, address = ?, postcode = ?, location = ?, creditCard = ?, email = ?, username = ?, password = ? WHERE username = ?";
            $stmt = $this->con->prepare($sqlUpd);
            $stmt->bind_param("sssssssssss", $salutation, $firstName, $lastName, $address, $postcode, $location, $creditCard, $email, $username, $password, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->affected_rows > 0) {
                // user updated
                header("Refresh:0; url=../index.php");
            } else {
                // error - user not updated
            } */
        } else {
            // echo " User does not exist";
            // add user with prepared statement         
            $sqlIns = "INSERT INTO user (salutation, firstName, lastName, address, postcode, location, email, username, password, active, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sqlIns);
            $stmt->bind_param("sssssssssii", $salutation, $firstName, $lastName, $address, $postcode, $location, $email, $username, $password, $active, $admin);
            $stmt->execute();
            $result = mysqli_stmt_affected_rows($stmt);
            
            if ($result == 1) {
                // user created
                // echo " User created";
                header("Refresh:0; url=../index.php");
                // echo "<script>alert('Bitte loggen Sie sich ein, um fortzufahren.');</script>";
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

                // set session variables
                $_SESSION['username'] = $username;
                // get userid, admin and active from query saved in $result in userService.php
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['admin'] = $row['admin'];
                $_SESSION['active'] = $row['active'];
                          
                // if rememberMe checked, set cookie to expire after 30 days
                if ($rememberMe == "true") {
                    
                    // echo " rememberMe checked: " . $rememberMe;
                    //setcookie("rememberLogin", $username, time() + (86400 * 30), "/"); // 86400 = 1 day / secure, http only
                    
                    // set secure cookie with salt and encode with base64
                    $salt = "!1salt#@"; // salt for secure cookie
                    $encodedCookieValue = base64_encode($salt . $username);
                    // setcookie(key, value, expire, path, domain, secure, httponly);
                    setcookie("rememberLogin", $encodedCookieValue, time() + (86400 * 30), "/"); // 86400 = 1 day / secure, http only
                    
                // if rememberMe not checked, set cookie to expire after 30 minutes and when closing browser
                } else {
                    // echo " rememberMe NOT checked: " . $rememberMe;
                    // setcookie("username", $username, time() - (86400)); // 86400 = 1 day / secure, http only
                    // setcookie("password", $password, time() - (86400)); // 86400 = 1 day / secure, http only
                    // session_set_cookie_params(0);
                }
            
                // echo " // the following was set cookie ins userService.php: " . $_COOKIE["username"];
                
                //header("Refresh:0; url=../../../Booktopia/Frontend/sites/index.php");
                return true;
                
            } else {
                // password incorrect
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
            // echo " // Unset cookie: " . $_COOKIE['rememberLogin'];
            // unset($_COOKIE['username']);
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

                // return $userData;
            }
            
            // return $userData;
            
        }
        
        return $userData;

    }




    // ************************************************************
    //          GET SESSION VARIABLES
    // ************************************************************
    
    // get session variables
    public function getSession() {

        // echo " // getSession in userServie.php reached";

        $userSession = array();

        // check if user is logged in and which role he has
        if (isset($_SESSION["userid"]) && $_SESSION["active"] == 1) {
            
            $userSession['sessionUsername'] = $_SESSION['username'];
            $userSession['sessionUserid'] = $_SESSION['userid'];
            $userSession['sessionAdmin'] = $_SESSION['admin'];
            $userSession['sessionActive'] = $_SESSION['active'];
            // $userSession['sessionLoggedIn'] = $_SESSION['loggedIn'];

            // echo username from userSession array
            // echo " // username from userSession array in userService.php: " . $userSession['sessionUsername'];
            
            return $userSession;

        } elseif (isset($_COOKIE["rememberLogin"])) {
            
            // echo " // COOKIE SET: " . $_COOKIE["username"];

            $encodedCookieRememberLogin = $_COOKIE["rememberLogin"];

            $decodedCookieRememberLogin = base64_decode($encodedCookieRememberLogin);
            // $decodedSalt = substr($decodedCookieRememberLogin, 0, 8);
            $decodedUsernameFromCookie = substr($decodedCookieRememberLogin, 8);

            // echo " // decodedSalt: " . $decodedSalt;
            // echo " // decodedCookieRememberLogin: " . $decodedUsernameFromCookie;

            // check if user exists with prepared statement
            $sql = "SELECT * FROM user WHERE username = ?";
            // $sql = "SELECT * FROM user WHERE username = ? LIMIT 1";
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
                    // password incorrect
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

        // echo " // saveEditedUserData in userService.php reached for user: " . $username . "<br>";

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
                // echo " // Nutzerdaten aktualisiert.";
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
    
    // get user data
    public function getOrderData() {

        // echo " // getOrderData in userServie.php reached";

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
                $sql = "SELECT * FROM orders WHERE userid = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("i", $row['userid']);
                $stmt->execute();
                $result = $stmt->get_result();
                // $row = $result->fetch_assoc();
                
                // save array with orders in $orderData
                $orderData = $result->fetch_all(MYSQLI_ASSOC);

                // $orderData['orderId'] = $row['orderId'];
                // $orderData['orderDate'] = $row['orderDate'];
                
                // echo " // orderData in userService.php: " . $orderData;
                // echo " // orderId in getOrderData in userService.php row 0: " . $orderData[0]['orderId'];
                // echo " // orderDate in getOrderData in userService.php: " . $orderData['orderDate'];
                

                // return $orderData;
            }
            
            // return $orderData;
            
        }
        
        return $orderData;

    }

       
    /* public function delete(User $user) {
        
        // Implementieren Sie den Code, um einen vorhandenen Benutzer aus der Datenquelle zu löschen
        // Beispiel:
        // Database::execute("DELETE FROM users WHERE id = ?", [$user->getId()]);
        
        return true;
    } */

    // Close connection
    public function closeConnection() {
        $this->con->close();
    }
}