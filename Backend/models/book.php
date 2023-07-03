<?php

// Book Model --> only suggestion, needs to be adapted
enum GenreType {
    case Biographie;
    case Sachbuch;
    case Krimi;
    case Allgemein;
}

enum LanguageType {
    case Deutsch;
    case Englisch;
    case Französisch;
    case Andere ;
}

class Book {

    public $bookId;
    public $title;
    public $author;
    public $rating;
    public $isbn;
  
    public $genre;
    public $language;
    public $price;
    public $description;
    public $image;
    public $stock;
    


    public function __construct(int $bookId, string $title, string $author,
                                int $rating, string $isbn,
                                 string $genre, string $language, int $price,
                                string $description, string $image, int $stock) { 
        $this->bookId         = $bookId;
        $this->title          = $title;
        $this->author         = $author;
        $this->rating         = $rating;
        $this->isbn           = $isbn;
        $this->genre          = $genre;
        $this->language       = $language;
        $this->price          = $price;
        $this->description    = $description;
        $this->image          = $image;
        $this->stock          = $stock;
    }

    public function getBookId() {
        return $this->bookId;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }



    public function getIsbn() {
        return $this->isbn;
    }


    public function getGenre() {
        return $this->genre;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getStock() {
        return $this->stock;
    }    

    public function getRating(){
        return $this->rating;
        }
    

}