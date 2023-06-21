<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path .'/backend/logic/session.php');

class admin_manageBooks
{
	private $con;
	private $tbl_books;

	public function __construct($con, $tbl_books)
	{
		$this->con = $con;
		$this->tbl_books = $tbl_books;
	}

	public function addBook($book)
	{	if($_SESSION["admin"]==1){
			$title = $book->title;
			$author = $book->author;
			$publisher = $book->publisher;
			$isbn = $book->isbn;
			$year = $book->year;
			$genre = $book->genre;
			$language = $book->language;
			$price = $book->price;
			$description = $book->description;
			$bookImage = $book->bookImage;
			$stock = $book->stock;


			$insertQuery = "INSERT INTO tbl_books (title, author, publisher, isbn, year, genre, language, price, description, bookImage, stock)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->con, $insertQuery);
			mysqli_stmt_bind_param($stmt, "ssssissdssi", $title, $author, $publisher, $isbn, $year, $genre, $language, $price, $description, $bookImage, $stock);
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
		
			$deleteQuery = "DELETE FROM tbl_books WHERE bookId = ?";
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


	public function updateBook($bookId, $book)
	{
		if($_SESSION["admin"]==1) {
			// Update the book record in the database
			$updateQuery = "UPDATE tbl_books SET title = ?, author = ?, publisher = ?, isbn = ?, year = ?, genre = ?, language = ?, price = ?, description = ?, bookImage = ?, stock = ? WHERE bookId = ?";
			$stmt = mysqli_prepare($this->con, $updateQuery);
			mysqli_stmt_bind_param($stmt, "ssssissdssii", $book->title, $book->author, $book->publisher, $book->isbn, $book->year, $book->genre, $book->language, $book->price, $book->description, $book->Image, $book->stock, $book->bookId);
			$updateResult = mysqli_stmt_execute($stmt);

			if ($updateResult) {
				echo "Buch wurde erfolgreich aktualisiert!";
			} else {
				echo "Fehler beim Aktualisieren des Buches: " . mysqli_error($this->con);
			}
		}
	}

	 }
?>