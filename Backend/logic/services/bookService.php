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

    
 // ************************************************************
    //          BOOK Admin and utility functions
    // ************************************************************  

    public function arr2Book($myBook){
        
        $myBook2=new Book($myBook["bookId"],$myBook["title"],$myBook["author"],$myBook["rating"],$myBook["isbn"],$myBook["genre"],$myBook["language"],$myBook["price"],$myBook["descr"],$myBook["image"],$myBook["stock"] );
        return($myBook2);

    }

    public function addBook($book)
	{	if($_SESSION["admin"]==1){
			
			$insertQuery = "INSERT INTO book (titel, autor, isbn,  kategorie, language, preis, description, image_url, stock)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->con, $insertQuery);
			mysqli_stmt_bind_param($stmt, "sssssissi", $book["title"], $book["author"],  $book["isbn"],  $book["genre"], $book["language"], $book["price"], $book["desc"], $book["image"], $book["stock"]);
			$insertResult = mysqli_stmt_execute($stmt);

			if ($insertResult) {
				echo "Buch wurde erfolgreich hinzugefügt!";
			} else {
				echo "Fehler beim Hinzufügen des Buches: " . mysqli_error($this->con);
			}
			return ($insertResult);
		}
			else return 0;
		} 
	

	public function deleteBook($bookId)
	{
		if($_SESSION["admin"]==1){
		
			$deleteQuery = "DELETE FROM book WHERE id = ?";
			$stmt = mysqli_prepare($this->con, $deleteQuery);
			mysqli_stmt_bind_param($stmt, "i", $bookId);
			$deleteResult = mysqli_stmt_execute($stmt);

			if ($deleteResult) {
				echo "Buch wurde erfolgreich gelöscht!";
			} else {
				echo "Fehler beim Löschen des Buches: " . mysqli_error($this->con);
			}
			return($deleteResult);
		}		
	}


	public function updateBook( $book)
	{ 
		if($_SESSION["admin"]==1) {
			// Update the book record in the database
			$updateQuery = "UPDATE book SET titel = ?, autor = ?,  isbn = ?,  kategorie = ?, language = ?, preis = ?, description = ?, image_url = ?, stock = ? WHERE id = ?";
			$stmt = mysqli_prepare($this->con, $updateQuery);
			mysqli_stmt_bind_param($stmt, "sssssissii", $book["title"] , $book["author"],  $book["isbn"], $book["genre"],  $book["language"], $book["price"], $book["description"], $book["image"], $book["stock"], $book["bookId"]);
			$updateResult = mysqli_stmt_execute($stmt);

			if ($updateResult) {
				echo "Buch wurde erfolgreich aktualisiert!";
			} else {
				echo "Fehler beim Aktualisieren des Buches: " . mysqli_error($this->con);
			}
            return $updateResult;
		} return false;
	}

    public function fetchAllBooks(){

        if($_SESSION["admin"]==1){
            $bookItems = [];
            $sql = "SELECT * FROM book";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $i=0;
        while ($resSet=mysqli_fetch_assoc($result)){
            //creat one object each from the row  fetched and add  to the array of the Book object
            $bookItems[$i++]= new Book($resSet['id'],$resSet['titel'],$resSet['autor'],$resSet['bewertung'],$resSet['isbn'],$resSet['kategorie'],$resSet['language'], $resSet['preis'],$resSet['description'],$resSet['image_url'], $resSet['stock']);
        }
        }
        $stmt->close();
        return($bookItems);
    }

	 
public function fetchBook(int $bookId){ //0=all, other integer=bookId
    if($bookId==0){
        return $this->fetchAllBooks();
    }   
    else{
        return $this->fetchBook($bookId);
    }
}

       
    public function closeConnection() {
        $this->con->close();
    }
}