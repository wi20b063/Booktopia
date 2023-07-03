
 <?php
    $path = $_SERVER['DOCUMENT_ROOT'];
 

function insertBookDetailMod($myBook, $book_ID) {
    $title=$myBook->getTitle();
    $genre=$myBook->getGenre();
    $language=$myBook->getLanguage();
    $stock=$myBook->getStock();
    $price=$myBook->getPrice();
    $descr=$myBook->getDescription();
    $author=$myBook->getAuthor();
    $rating=$myBook->getRating();
    $imgpath=$myBook->getImage(); 
    $isbn=$myBook->getIsbn();
    $user_ID= (isset($_SESSION["userid"]))? $_SESSION["userid"] : 0;

    //if no image-> use default for display
    $img = empty($myBook->getImage()) ?  ('/Frontend/res/img/default.png') : $myBook->getImage();
    

    echo "<!DOCTYPE html> <html lang='EN'>
<div class='modal fade' id='showBookDetails$book_ID' tabindex='-1' role='dialog' aria-labelledby='statusModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-xl' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title'>Buchdetails</h4>
                <input type='hidden' id='$book_ID' name='bookID' value='$book_ID'>
            </div>
            <div class='modal-body'>                            
                <!---- -----------  Form Book Details---------- --->
                <form class='data-form' id='bookIdform$book_ID' action='' method='POST' autocomplete='on' enctype='multipart/form-data'>
                    <div class='content'>
                        <div class='container-fluid'>
                            <div class='row col-md-11'>
                                <div class='col-8'>
                                    <div class='row p-4'>
                                    <strong> Buch Details </strong>
                                        <div class='row'>
                                            <div class='col-6 p-4'> 
                                            
                                            <div class='row py-1'> 
                                            <label for='bookId'>Buch Titel*</label>
                                                            <input type='text' name='bookTitleform' id='bookTitleform$book_ID' selected value='$title'  placeholder='' required
                                                                class='form-control' > 
                                            </div>
                                            <div class='row py-1'> 
                                            <label for='Autor'>Autor:*</label>
                                                            <input type='text' name='authorform' id='authorform$book_ID' selected value='$author'  placeholder='Autor Name'
                                                                class='form-control' required>        
                                        </div>
                                            <div class='row py-1'> 
                                                <label for='Kategorie'>Kategorie:</label><br>
                                                    <select name='category' id='genreform$book_ID' class='form-control' aria-label='Select filter option'>
                                                        <option value='Allgemein' >Allgemein</option>
                                                        <option value='Biographie' >Biografie</option>
                                                        <option value='Krimi' >Krimi</option>
                                                        <option value='Sachbuch' >Sachbuch</option>
                                                    </select>
                                            </div>
                                            <div class='row py-1'> 
                                                <label for='Sprache'>Sprache:</label><br>
                                                    <select name='language' id='languageform$book_ID'  class='form-control' aria-label='Select filter option'>
                                                        
                                                        <option value='Deutsch' >Deutsch</option>
                                                        <option value='Englisch' >Englisch</option>
                                                        <option value='Französisch' >Französisch</option>
                                                        <option value='Andere'>Andere</option>
                                                    </select>        
                                            </div>
                                        </div>
                                            <div class='col-4 p-4'>
                                                <div class='row py-1'> 
                                                    <label for='Lagerstand'>Lagerstand:*</label> <input type='text' name='stockform' id='stockform$book_ID' selected value='$stock'  placeholder='Anzahl' class='form-control' required>    
                                                </div>
                                                <div class='row py-1'> 
                                                    <label for='isbn'>ISBN:</label> <input type='text' name='isbnform' id='isbnform$book_ID' selected value='$isbn'  placeholder='ISBN' class='form-control' required>    
                                                </div>
                                                <div class='row py-1'> 
                                                    <label for='isbn'>Bewertung:</label> <input type='text' name='ratingform' id='ratingform$book_ID' selected value='$rating'  placeholder='0-5' class='form-control' required>    
                                                </div>
                                                <div class='row py-1'> 
                                                        <label for='isbn'>Preis:*</label> <input type='text' name='priceform' id='priceform$book_ID' selected value='$price'  placeholder='Preis' class='form-control' required>    
                                                </div> 
                                            </div>
                                        </div>
                                            <div class='row p-4'> Beschreibung <textarea class='span6' rows='3' name='bookinfoform' id='bookinfoform$book_ID' selected value='$descr'  placeholder='Kurzbeschreibung' required></textarea>
                                            </div>
                                        <div class='row py-1'> Error or Feedback messages...</div>
                                    </div>
                                </div>
                                <div class='col-4 bg-lightgrey p-4'>
                                <div class='row py-4'> </div>
                                    <div class='container col-md-11'>
                                        <div class='mb-5'>
                                            <label for='Image' class='form-label'><strong>Titelfoto</strong></label>
                                        </div>
                                        
                                        <img id='frame$book_ID' src='$img'  class='img-fluid' />
                                        <input type='text' name='imgpathformA$book_ID' id='imgpathformA$book_ID' inactive selected value='$img' class='form-control'> 
                                        <div class='row py-4'> </div>
                                        
                                        <input class='form-control' type='file' id='imgfileform$book_ID' value='$img' onchange='preview($book_ID)' name='imgfileform$book_ID' accept='image/*' >
                                            <button onclick='clearImageUploadInput($book_ID,\"$img\")' class='btn  btn-secondary mt-3'>Verwerfen</button>
                                    </div>       
                                </div>
                                <div class='row'>
                                    <div class='col-md-3 p-4'> 
                                    <button type='button' onclick='updateProductToDB($book_ID)' class='btn btn-warning'  name='btnSubmitUpdate($book_ID)' id='btnSubmitUpdate($book_ID)' class= 'btn-secondary'
                                                            >Speichern</button> 
                                        </div>
                                    <div class='col-md-3 p-4'> 
                                    <button type='button' onclick='deleteBookFromDB($book_ID)' class='btn btn-danger' name='btnSubmitDelete($book_ID)' id='btnSubmitDelete($book_ID)' class=' btn btn-secondary'
                                                            >Löschen</button> 
                                        </div>
                                    <div class='col-md-3 p-4'> 
                                    <button type='button' name='btnSubmitCancel' class='btn btn-secondary'
                                                            id='btnSubmitCancel' data-bs-dismiss='modal'>Zurück</button> 
                                        </div>
                                    </div>
                                </div>
                        </div> 
                    </div>
                </form>



                <!---- ----------- END Form Book Details---------- --->
            </div> <!-- Modal this body end-->
    </div>
    ";}
