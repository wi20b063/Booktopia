<?php

$bookid = $title = $author = $publisher = $isbn = $year = $genre = $language = $price = $description = $image = $stock = "";


// BookService Class implements CRUD operations

class BookService {

    // get data from MySQL database with SQL statements
    private $con;
    private $tbl_book;

    public function __construct($con, $tbl_book) {
        $this->con = $con;
        $this->tbl_book = $tbl_book;
    } 

    // find all books mit prepared statement
    public function findAll() {        
        $sql = "SELECT * FROM " . $this->tbl_book;
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $books = [];
        while ($row = $result->fetch_assoc()) {
            $book = new Book($row['bookId'], $row['title'], $row['author'], $row['publisher'], $row['isbn'], $row['year'], $row['genre'], $row['language'], $row['price'], $row['description'], $row['image'], $row['stock']);
            $books[] = $book;
        }

        $stmt->close();

        return $books;
    }


    // find book by id mit prepared statement
    public function findByID(int $id) {        
        $sql = "SELECT * FROM " . $this->tbl_book . " WHERE bookid = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        
        $stmt -> close();
        
        if (!$row) {
            return null; // Benutzer nicht gefunden
        }

        $book = new Book($row['bookId'], $row['title'], $row['author'], $row['publisher'], $row['isbn'], $row['year'], $row['genre'], $row['language'], $row['price'], $row['description'], $row['image'], $row['stock']);
        return $book;
    }


   
  
    

    // Close connection
    public function closeConnection() {
        $this->con->close();
    }
}

?>