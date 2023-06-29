<?php

// bookService Class implements CRUD operations

class BookService {

    // get data from MySQL database with SQL statements
    private $con;
    private $tbl_book; // ????

    public function __construct($con, $tbl_book) {
        $this->con = $con;
        $this->tbl_book = $tbl_book; // ????
    }

    // find all books mit prepared statement
    /* public function findAll() {        
        $sql = "SELECT * FROM " . $this->tbl_book;
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
    //          GET BOOK DETAILS BY ARTICLE / ITEM ID
    // ************************************************************

    // get book details by article / item id
    public function getBookDetails($orderDetailsArticleId) {

        // echo " // getBookDetails in bookService.php reached";

        $bookDetails = null;

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

                // user exits: check if article ID exists with prepared statement
                $sql = "SELECT * FROM book WHERE id = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("i", $orderDetailsArticleId);
                $stmt->execute();
                $result = $stmt->get_result();
                // $row = $result->fetch_assoc();
                
                if ($result->num_rows > 0) {
                    
                    // book exits: get all details for this book with prepared statement and save in array
                    $sql = "SELECT * FROM book WHERE id = ?";
                    $stmt = $this->con->prepare($sql);
                    $stmt->bind_param("i", $orderDetailsArticleId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // $row = $result->fetch_assoc();
                    
                    $bookDetails = $result->fetch_all(MYSQLI_ASSOC);

                    // echo " // bookDetails in bookService.php: " . $bookDetails . "<br>";
                    
                }
                
            }
            
            // return $bookDetails;
            
        }
        
        return $bookDetails;

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