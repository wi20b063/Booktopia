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
        $creditCard = $user['creditCard'];
        $active = 1;
        $admin = 0;    

        echo "username in saveUser in userService.php: " . $username;

        // check if user already exists with prepared statement
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        echo " username if user exists in userServie.php: " . $row['username'];
        
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
            echo " User does not exist";
            // add user with prepared statement         
            $sqlIns = "INSERT INTO user (salutation, firstName, lastName, address, postcode, location, creditCard, email, username, password, active, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sqlIns);
            $stmt->bind_param("ssssssssssii", $salutation, $firstName, $lastName, $address, $postcode, $location, $creditCard, $email, $username, $password, $active, $admin);
            $stmt->execute();
            $result = mysqli_stmt_affected_rows($stmt);
            
            if ($result == 1) {
                // user created
                echo " User created";
                header("Refresh:0; url=../index.php");
                echo "<script>alert('Bitte loggen Sie sich ein, um fortzufahren.');</script>";
            } else {
                // error - user not created
                echo " ERROR - User not created";
            }
        }
    } 

    // login user with prepared statement (username and password) and server validation
    public function loginUser($username, $password) {

        echo "<script>console.log('loginUser in userServie.php reached');</script>";
        echo "username: " . $username;
        echo "password: " . $password;        
        
        // check if user exists with prepared statement
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        echo " username if user exists in userServie.php: " . $row['username'];
        echo " password if user exists in userServie.php: " . $row['password'];

        
        if ($result->num_rows > 0) {

            // User exists            
            echo " User exists";
            
            // check if password is correct with prepared statement (password is sha256 hashed)
            $sql = "SELECT * FROM user WHERE username = ? AND password = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            echo " data from database if entered data matches " . $row['username'] . " " . $row['password'];
            
            if ($result->num_rows > 0) {

                // entered data matches data in database
                echo " passed check process executed in userServie.php reached ";
                echo " result username: " . $row['username'];
                echo " result password: " . $row['password'];

                // set cookies
                $cookie_name = "username";
                $cookie_value = $username;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30)); // 86400 = 1 day / secure, http only
                

                
                // set session variables
                $_SESSION['username'] = $username;
                // get userid, admin and active from query saved in $result in userService.php
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['admin'] = $row['admin'];
                $_SESSION['active'] = $row['active'];
                          
                echo " session variables:";
                echo " session username: " . $_SESSION['username'];
                echo " sesion userid: " . $_SESSION['userid'];
                echo " session admin: " . $_SESSION['admin'];
                echo " sessioni active: " . $_SESSION['active'];              
                
                //header("Refresh:0; url=../../../Booktopia/Frontend/sites/index.php");
                return true;
                
            } else {
                // password incorrect
                // error - password incorrect
                return false;
            }
        } else {
            // error - user not found
            return false;
        }
    }

    /* public function logoutUser() {

        echo " logoutUser in userServie.php reached";

        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();

    } */


    // get session variables
    public function getSession() {

        echo " // getSession in userServie.php reached";

        $userSession = array();

        // check if user is logged in and which role he has
        if (isset($_SESSION["userid"]) && $_SESSION["active"] == 1) {
            
            $userSession['sessionUsername'] = $_SESSION['username'];
            $userSession['sessionUserid'] = $_SESSION['userid'];
            $userSession['sessionAdmin'] = $_SESSION['admin'];
            $userSession['sessionActive'] = $_SESSION['active'];

            // echo username from userSession array
            echo " // username from userSession array in userService.php: " . $userSession['sessionUsername'];
            
            return $userSession;
            
            
        } else {
            echo " // GUEST: session variables not set";

            $userSession['sessionUsername'] = "GUEST";
            $userSession['sessionUserid'] = "";
            $userSession['sessionAdmin'] = 0;
            $userSession['sessionActive'] = 0;
            
            return $userSession;
        }
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