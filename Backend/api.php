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
require (dirname(__FILE__) . "\logic\services\admin_manageBooks.php");
require (dirname(__FILE__) . "\logic\services\adminUser.php");
require (dirname(__FILE__) . "\logic\services\admin_manageOrders.php");
require (dirname(__FILE__) . "\logic\services\admin_manageVouchers.php");

// an instance of the API class is created
$api = new Api($con, $tbl_user, $tbl_books, $tbl_orders, $tbl_vouchers);

// call processRequest for working on requests
$api->processRequest();

class Api {
    // constructor
    private $userService;
    private $bookService;
    private $admin_manageBooks;
    private $admin_manageCustomers;
    private $admin_manageOrders;
    private $admin_manageVouchers;


    // is called when a new instance of the service calsses is created
    public function __construct($con, $tbl_user, $tbl_book) {
        // create instances of the services
        $this->userService = new UserService($con, $tbl_user);
        $this->bookService = new BookService($con, $tbl_books);
        $this->admin_manageBooks = new Admin_manageBooks($con, $tbl_books);
        $this->admin_manageCustomers = new Admin_manageUsers($con, $tbl_user);
        $this->admin_manageOrders = new Admin_manageOrders($con, $tbl_orders);
        

    }

    
    public function processRequest() {
        $method = $_SERVER['REQUEST_METHOD'];   // GET, POST, DELETE
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
    
    public function processGet() {
        // to be implemented

        // Verarbeitung von GET-Anfragen
        // Hier können verschiedene GET-Anfragen an die entsprechenden Services weitergeleitet werden.
        // Beispiel:

        // login user 
        if (isset($_GET["username"]) && isset($_GET["password"])) {
            
            echo "<script>console.log('processGet - loginUser - in api.php reached');</script>";
            
            // Verarbeite Login
            $username = $_GET["username"];
            $password = $_GET["password"];
            echo "username in api.php: " . $username . "<br>";
            echo "password in api.php: " . $password . "<br>";
            
            $userLoggedIn = $this->userService->loginUser($username, $password);

                if ($userLoggedIn) {
                    echo " user successfully logged in api.php";
                    // $this -> success(200,  "Login erfolgreich!");
                } else {
                    echo " user not logged api.php";
                    // $this -> error(401, "Login fehlgeschlagen!", []);                
                }
            
        } elseif (isset($_GET['getSession'])) {

            echo " processGet - getSession - in api.php reached";

            $userSession = $this->userService->getSession();

            echo " username from userSession array in api.php: " . $userSession['sessionUsername'];

            return $userSession;
        
        } /* elseif (isset($_GET['logout'])) {

            echo " processGet - logoutUser - in api.php reached";
            
            // Verarbeite Logout
            $this->userService->logoutUser();
        } */



            
        /* if (isset($_GET["users"])) {
            $users = $this->userService->findAll();
            $this->success(200, $users);
        } elseif (isset($_GET["book"])) {
            $books = $this->bookService->findAll();
            $this->success(200, $books);
        } else {
            $this->error(400, [], "Bad Request - invalid parameters " . http_build_query($_GET));
        } */
    }
    
    public function processPost() {
        // to be implemented

        // Verarbeitung von POST-Anfragen
        // Hier können verschiedene POST-Anfragen an die entsprechenden Services weitergeleitet werden.

        if (empty($_POST)) {
            // Error
            echo "<script>console.log('Empty post request');</script>";
        }
        
        // register user
        elseif (isset($_POST["user"])) { 
            // User erstellen
            echo "console.log('processPost - saveUser - in api.php reached');";
            // fetch data from posted body
            //$user = file_get_contents('php://input');
            $user = $_POST["user"];
            // print firstname of array user
            echo $user["username"];
            $this->userService->saveUser($user);
        }
        
        /* else if (isset($_POST["username"]) && isset($_POST["password"])) {
            
            echo "<script>console.log('processPost - loginUser - in api.php reached');</script>";
            
            // Verarbeite Login
            $username = $_POST["username"];
            $password = $_POST["password"];
            $userLoggedIn = $this -> userService -> loginUser($username, $password);

                if ($userLoggedIn) {
                    $this -> success(200,  "Login erfolgreich!");
                } else {
                    $this -> error(401, "Login fehlgeschlagen!", []);} */
                    
       /*  
        } elseif (isset($_GET["book"])) {
            // Produkt erstellen
            // $this->productService->createBook(); 
        
            
        } */ else {
            $this->error(400, "Bad Request - invalide Parameter" . http_build_query($_GET), []);
        }
    }
    
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

    private function processUpdate() {
        // to be implemented
        
        // Verarbeitung von PUT-Anfragen (Update)
        // Hier können verschiedene PUT-Anfragen an die entsprechenden Services weitergeleitet werden.

        /* if (isset($_GET["user"])) {
            // Verarbeite Benutzer aktualisieren
            // $this->userService->updateUser();
        } elseif (isset($_GET["book"])) {
            // Verarbeite Produkt aktualisieren
            // $this->productService->updateBook();
        } else {
            $this->error(400, [], "Bad Request - invalid parameters " . http_build_query($_GET));
        } */
    }
    
    
    // format sucess response
    private function success ($code, $message) {
        http_response_code($code);
        echo ($message);
    }
    
    // format error response
    private function error ($code, $message, $data) {
        http_response_code($code);
        echo ($message);
        
    }
}


?>