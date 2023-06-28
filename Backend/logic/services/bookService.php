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
            $book = new Book($row['bookid'], $row['title'], $row['author'], $row['publisher'], $row['isbn'], $row['year'], $row['genre'], $row['language'], $row['price'], $row['description'], $row['image'], $row['stock']);
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

        $book = new Book($row['bookid'], $row['title'], $row['author'], $row['publisher'], $row['isbn'], $row['year'], $row['genre'], $row['language'], $row['price'], $row['description'], $row['image'], $row['stock']);
        return $book;
    }


    // create or update book mit prepared statement
    public function saveBook(Book $book) {
        $title = $book->getTitle();
        $author = $book->getAuthor();
        $publisher = $book->getPublisher();
        $isbn = $book->getIsbn();
        $year = $book->getYear();
        $genre = $book->getGenre();
        $language = $book->getLanguage();
        $price = $book->getPrice();
        $description = $book->getDescription();
        $image = $book->getImage();
        $stock = $book->getStock();

        // check if book already exists with prepared statement
        $sql = "SELECT * FROM book WHERE isbn = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Book already exists
            // code for updating book
            $sqlUpd = "UPDATE book SET title = ?, author = ?, publisher = ?, isbn = ?, year = ?, genre = ?, language = ?, price = ?, description = ?, image = ?, stock = ? WHERE isbn = ?";
            $stmt = $this->con->prepare($sqlUpd);
            $stmt -> bind_param("ssssssssssss", $title, $author, $publisher, $isbn, $year, $genre, $language, $price, $description, $image, $stock, $isbn);
            $stmt -> execute();
            $stmt -> close();
            $result = $stmt->get_result();
            if ($result->affected_rows > 0) {
                // book updated
                header("Refresh:0; url=../index.php");
            } else {
                // error - book not updated
            }
        } else {
            // add book with prepared statement
            $sqlIns = "INSERT INTO book (title, author, publisher, isbn, year, genre, language, price, description, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sqlIns);
            $stmt -> bind_param("sssssssssss", $title, $author, $publisher, $isbn, $year, $genre, $language, $price, $description, $image, $stock);
            $stmt -> execute();
            $stmt -> close();
            $result = $stmt->get_result();
            if ($result->affected_rows > 0) {
                // book created
                header("Refresh:0; url=../index.php");
            } else {
                // error - book not created
            }
        }
    }
  
    /* public function delete(User $user) {
        
        // Implementieren Sie den Code, um einen vorhandenes Buch aus der Datenquelle zu löschen
        // Beispiel:
        // Database::execute("DELETE FROM book WHERE id = ?", [$book->getId()]);
        
        return true;
    } */

    // Close connection
    public function closeConnection() {
        $this->con->close();
    }
}

?>