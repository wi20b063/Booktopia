<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path."/Frontend/sites/components/head.php");
    require_once ($path. '/Backend/models/book.php');
    require_once ($path. '/Backend/logic/session.php');
    require_once ($path . '/Backend/logic/services/bookService.php');
    require_once ($path . '/Frontend/sites/components/bookDetailMod.php');

    ?>
    <title>Booktopia | Buch-Artikel Verwaltung</title>

</head>
<?php 

$myServ=new BookService($con , $tbl_book);
// test Only:

$_SESSION["admin"]=1;
// End test
$myBooks=[];
$myBooks=$myServ->fetchBook(0); //fetch all books


?>

<!-- --------This block is inserted by the Ajax result -------- -->

    <div class="component" name="dynComponentDivBook" id="dynComponentDivBook" >

    <div class="content">
            <div class="container">
                <h2 class="headline">Buch-Artikel Verwaltung</h2>

                <div class="row g-8">
                    <div class="col-lg-6 mb-4" style="background-color:lightgrey"><?php echo "" ?></div>
                </div>

                <div class="row g-3 py-3">
                    <label for="userListFilter"><strong>Suchfilter wählen:</strong></label>
                    
                    <div class="col-md-3">                        
                        <select id="userListFilter1" name="userListFilter1" onchange ="filterUserTable( 'mainTable', this.value)" class="form-select" aria-label="Select filter option">
                            <option selected></option>
                            <option value="">Alle</option>
                            <option value="1">Allgemein</option>
                            <option value="2">Biographie</option>
                            <option value="3">Krimi</option>
                            <option value="4">Sachbuch</option>
                            <option value="5">Englisch</option>
                            <option value="6">Deutsch</option>
                            <option value="7">Französisch</option>
                            <option value="8">Anderes</option>
                            <option value="9">Niedr</option>
                            <option value="10">Hoch</option>
                            <option value="11">Aus</option>
                        </select> 

                    </div>
                    <div class="col-md-3">  </div>
                    <div class="col-md-3">  </div>    <!--- the following Modal"0" was created for the empty BookObject --->
                    <div class="col-md-3"> <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#showBookDetails0">Neuer Artikel</button>  </div> 

                </div>
            <div class = row>
            <div class="mt-4 table-responsive">
                <table class="table" id="mainTable">
                    <thead>
                        <tr>
                            <th scope="col-sm-1">BuchId</th>
                            <th scope="col-sm-1">Bild</th>
                            <th scope="col-sm-1">Titel</th>
                            <th scope="col-sm-1">Autor</th>
                            <th scope="col-sm-1">Kategorie</th>
                            <th scope="col-sm-1">Sprache</th>
                            <th scope="col-sm-1">Preis</th>
                            <th scope="col-sm-1">Lager</th>
                            <th scope="col-sm-1">Artikel/Bearb</th>
                            <th scope="col-sm-1">Löschen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $line=0;
                    ?>

<?php
                    $myNewBook=new Book(0, "", "",0,"","","",0,"","",0 ); // newBook form prepare in case...
                    array_push($myBooks, $myNewBook);
                    foreach($myBooks as $myBook){
                        $book_ID=$myBook->getBookId();
                        
                         // all but the new book 
                            $line++;
                            $userformID="bookDataForm_". $book_ID; //needed to identify the form in myFunctions.JS amongst all forms in each modal. we 'll pass it to the onclick action...
                            ?>
                            <tr  style="<?php if($book_ID==0) echo 'display:none;'; ?>">
                            <?php
                                    ?>
                                    <td><?php echo $book_ID; ?></td>
                                    <td>
                                    <?php //php-inject the image source default img if no DB entry
                                        (empty($myBook->getImage())) ?  ($img = "default.png") : ($img=$myBook->getImage());?>
                                        <img src="<?php echo $img_path.$img; ?>" class="rounded float-start" width="60" alt="<?php echo $myBook->getImage(); ?>">
                                    </td>
                                    <td><?php echo $myBook->getTitle(); ?></td>
                                    <td><?php echo $myBook->getAuthor(); ?></td>
                                    <td><?php echo $myBook->getGenre(); ?></td>
                                    <td><?php echo $myBook->getLanguage(); ?></td>
                                    <td><?php echo $myBook->getPrice(); ?></td>
                                    <?php 
                                    $stock=$myBook->getStock();
                                        if ($stock>10){ ?>
                                            <td style="background-color:lightgreen"><?php echo $stock?>-Hoch </td>
                                        <?php } elseif ($stock >1) { ?>
                                            <td style="background-color:lightgrey"><?php echo $stock ?>-Niedr</td>
                                        <?php } else { ?>
                                            <td style="background-color:lightpink"><?php echo "0" ?>-Aus!</td> 
                                    <?php }?>
                                    <!-- Button trigger modal__ change status and send corresponding-->
                                    <td><button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#showBookDetails<?php echo $book_ID; ?>">Details</button></td>
    
                                    <!--  Modification Button and Moddal___    -->
                                    <td><div><button type="button" class="btn btn-outline-warning" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#bookedit<?php echo $book_ID; ?>">Löschen </button></div></td>
                                    
                                    <!-- Modal for Details, code injected from separate php script for re-use -->
                    <!-- ------------------------including Detail form for bookdetailModal-php ---------------------------->
                        <?php 
                                insertBookDetailMod($myBook, $book_ID);  ?> 
                    <!-- ------------------------End including Detail from bookdetailModal-php ---------------------------->
                                <div class="container">
                                </div>   
                        <?php
                    
                    }
                    
                    ?>
             <script> console.log("DONE With book TABLE") </script>
            </tbody>
        </table>
         
        </div>                                                               
    </div>
</div> 
<!-- -------- End of  Ajax result insert block ---------->               
    </main>

    <footer class="py-3 my-4 fixed-bottom">
        <?php include_once ($path."/Frontend/sites/components/footer.php");?>
    </footer>
<script type="text/javascript">


       $(document).ready(function() {
        localStorage.setItem("isNewBookImage", false);

      }); 


    </script>
</body>

</html>

<?php

// for testing add, delete, modify user...




?>