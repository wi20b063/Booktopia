<?php

// This API is used to access the database
// The API is the controller of the MVC pattern
// The API is called by the frontend via AJAX requests
// The API returns JSON objects

// api.php executes tasks from frontend and calls the needed services

// include necessary files
require (dirname(__FILE__) . "\config\dbaccess.php"); //??
require (dirname(__FILE__) . "\models\user.php");
require (dirname(__FILE__) . "\logic\services\userService.php");
require (dirname(__FILE__) . "\models\book.php");
require (dirname(__FILE__) . "\logic\services\bookService.php");

// an instance of the API class is created
$api = new Api($con, $tbl_user, $tbl_book);

// call processRequest for working on requests
$api->processRequest();

class Api {
    // constructor
    private $userService;
    private $bookService;

    // is called when a new instance of the service calsses is created
    public function __construct($con, $tbl_user, $tbl_book) {
        // create instances of the services
        $this->userService = new UserService($con, $tbl_user);
        $this->bookService = new BookService($con, $tbl_book);
    }

    
    public function processRequest() {
        $method = $_SERVER['REQUEST_METHOD'];   // GET, POST, DELETE, PUT
            switch ($method) {
    
                case "GET":
                    $this->processGet();
                    break;
    
                case "POST":
                    $this->processPost();
                    break;
                
                case "DELETE":
                    $this->processDelete();
                    break;

                case "PUT":
                    $this->processUpdate();
                    break;
    
                default:
                    $this->error(405, ["Allow: GET, POST, DELETE"], "Method not allowed");                
            }
    }

    

    // ************************************************************
    //          GET REQUESTS
    // ************************************************************
    
    // Verarbeitung von GET-Anfragen
    // Hier können verschiedene GET-Anfragen an die entsprechenden Services weitergeleitet werden.
    public function processGet() {

        // login user 
        if (isset($_GET["loginUser"])) {
            
            // echo "<script>console.log('processGet - loginUser - in api.php reached');</script>";
            
            // Verarbeite Login
            $username = $_GET["username"];
            $password = $_GET["password"];
            $rememberMe = $_GET["rememberMe"];
            // echo "username in api.php: " . $username . "<br>";
            // echo "password in api.php: " . $password . "<br>";
            
            $result = $this->userService->loginUser($username, $password, $rememberMe);

                if ($result === true) {
                    $this -> success(200,  "Login erfolgreich!", []);
                } else {
                    $this -> error(401, "Login fehlgeschlagen! " . $result);                
                }
        
        
        // geht Session Variables and check for cookie
        } elseif (isset($_GET["getSession"])) {

            $userSession = $this->userService->getSession();
            $this->success(200, "", $userSession);
        
        // get user data
        } elseif (isset($_GET["getUserData"])) {

            $userData = $this->userService->getUserData();
            $this->success(200, "", $userData);
            
        // get order data
        /* } elseif (isset($_GET["getOrderData"])) {

            $orderData = $this->userService->getOrderData();
            $this->success(200, "", $orderData); */
        
        // logout user
        } elseif (isset($_GET['logoutUser'])) {

            // echo " processGet - logoutUser - in api.php reached";
            $this->userService->logoutUser();
            $this->success(200, "Logout erfolgreich!", []);
            
        } elseif (isset($_GET["checkPasswordForSavingProfile"])) {

            $passwordForSavingProfile = $_GET["passwordForSavingProfile"];
            $result = $this->userService->checkPassword($passwordForSavingProfile);

                if ($result === true) {
                    $this -> success(200,  "true", []);
                } else {
                    $this -> error(401, $result);                
                }
        
        
        } /* } elseif (isset($_GET["book"])) {
            // Produkt erstellen
            // $this->productService->createBook(); 
        
            
        } */

            
        /* if (isset($_GET["users"])) {
            $users = $this->userService->findAll();
            $this->success(200, $users);
        } elseif (isset($_GET["book"])) {
            $books = $this->bookService->findAll();
            $this->success(200, $books);
        } */ else { 
            $this->error(400, [], "Bad Request - invalid parameters " . http_build_query($_GET));
        }
    }

    
    // ************************************************************
    //          POST REQUESTS
    // ************************************************************
    
    public function processPost() {

        if (empty($_POST)) {
            // Error
            echo "Empty post request";
        }
        
        // register user
        elseif (isset($_POST["user"])) { 
            $user = $_POST["user"];
            $this->userService->saveUser($user);        
        
        // Update User
        } if (isset($_POST["saveEditedUserData"])) {             
            $editedUser = $_POST["editedUser"];
            // echo "saveEditedUserData in api.php reached";
            // echo "editedUser in api.php: " . $editedUser["firstname"] . "<br>";
            $this->userService->saveEditedUserData($editedUser);
                    
           } else {
                $this->error(400, "Bad Request - invalide Parameter" . http_build_query($_GET), []);
        }
    }


    // ************************************************************
    //          DELETE REQUESTS
    // ************************************************************
    
    public function processDelete() {
        // to be implemented

        // Verarbeitung von DELETE-Anfragen
        // Hier können verschiedene DELETE-Anfragen an die entsprechenden Services weitergeleitet werden.

        /* if (isset($_GET["user"])) {
            // Benutzer löschen
            // $this->userService->deleteUser();
        } elseif (isset($_GET["book"])) {
            // Produkt löschen
            // $this->productService->deleteBook();
        } else {
            $this->error(400, [], "Bad Request - invalid parameters " . http_build_query($_GET));
        } */
    }


    // ************************************************************
    //          PUT REQUESTS
    // ************************************************************
    private function processUpdate() {

        /* if (isset($_PUT["saveEditedUserData"])) {             
            $editedUser = $_PUT["editedUser"];
            $this->userService->saveEditedUserData($editedUser);
        } /* elseif (isset($_GET["book"])) {
            // Verarbeite Produkt aktualisieren
            // $this->productService->updateBook();
        } else {
            $this->error(400, [], "Bad Request - invalid parameters " . http_build_query($_GET));
        } */
    }
    

    // format sucess response
    private function success (int $code, $message, array $array) {

        if ($array == null) {
            http_response_code($code);
        // header('Content-Type: application/json');
            echo($message);
            
        } else {
        http_response_code($code);
        // header('Content-Type: application/json');
        echo($message);
        echo(json_encode($array));
        }        
        exit;
        
    }
    
    // format error response
    private function error ($code, $message) {
        http_response_code($code);
        echo($message);
        exit;        
    }
}


?>