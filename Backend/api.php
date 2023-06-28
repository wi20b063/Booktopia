<?php

// This API is used to access the database
// The API is the controller of the MVC pattern
// The API is called by the frontend via AJAX requests
// The API returns JSON objects

// api.php executes tasks from frontend and calls the needed services

// include necessary files
$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path . '\Backend\config\dbaccess.php');
require_once ($path. '/Backend/models/user.php');
require_once ($path . '/Backend/logic/services/userService.php');
require_once ($path . '/Backend/models/book.php');
require_once ($path . '/Backend/logic\services\bookService.php');
require_once ($path . '/Backend/logic\services\admin_manageBooks.php');
require_once ($path . '/Backend/logic\services\admin_manageUsers.php');
require_once ($path . '/Backend/logic\services\admin_manageOrders.php');
require_once ($path . '/Backend/logic\services\admin_manageVouchers.php');
require_once ($path . '/Backend/logic\services\orderService.php');

// an instance of the API class is created
$api = new Api($con, $tbl_user, $tbl_book, $tbl_payment_items, $tbl_order, $tbl_order_details);

// call processRequest for working on requests
$api->processRequest();

class Api {
    // constructor
    private $userService;
    private $bookService;
    private $admin_manageBooks;
    private $admin_manageUsers;
    private $admin_manageOrders;
    private $admin_manageVouchers;
    private $orderService;

    // is called when a new instance of the service calsses is created
    public function __construct($con, $tbl_user, $tbl_book, $tbl_payment_items, $tbl_order, $tbl_order_details) {
        // create instances of the services
        $this->userService = new UserService($con, $tbl_user);
        $this->bookService = new BookService($con, $tbl_book);
       // $this->admin_manageBooks = new Admin_manageBooks($con, $tbl_book);
        $this->admin_manageUsers = new adminUser($con, $tbl_user, $tbl_payment_items);
        $this->orderService = new OrderService($con, $tbl_order, $tbl_book, $tbl_order_details, $tbl_user,  $tbl_payment_items);
       // $this->admin_manageOrders = new Admin_manageOrders($con, $tbl_orders);
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
        }
          elseif (isset($_GET['user']) && ($_GET['user']=='all')) {
            echo " processGet - getUserData - in api.php reached";
            $admin_manageUsers = $this->admin_manageUsers->getUsers();
           
        } 
        
        elseif (isset($_GET['deleteId']) ) {
            echo " processGet - getUserData - in api.php reached";
            $this->admin_manageUsers->deleteUser($_GET["deleteId"]);
           
        } // get User's order data
        elseif (isset($_GET['userOrder']) ) {
            echo " processGet - getUserData - in api.php reached";
            $this->orderService->fetchUserOrders($_GET["userOrder"]);
           
        } 
        /*   elseif (isset($_GET['logout'])) {

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
        elseif (isset($_POST["user"]) && isset($_POST["callerRole"])) { 
            // User exists, updatem
            echo "console.log('processPost - saveUser - in api.php reached');";
            // fetch data from posted body
            $user = $_POST["user"];
            // print firstname of array user
            echo $user["username"];
            $this->admin_manageUsers->updateUser($user,$_POST["callerRole"]);
        }
        elseif(isset($_POST["user"]) && isset($_POST["action"]) && $_POST["action"]=="registration"){
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
        if (empty($_DELETE)) {
            // Error
            echo "<script>console.log('Empty update request');</script>";
        }
        
        // register user
        elseif (isset($_DELETE["userid"])) { 
            // User erstellen
            echo "console.log('processPost - updateUser - in api.php reached');";
            // fetch data from posted body
            
            $user = $_DELETE["userid"];
            // print firstname of array user
            echo $user["username"];
            // calling 
            $this->admin_manageUsers->deleteUser($user); 
            //$this->admin_manageUsers->deleteMultPaymentMethods($user); //hanled by DB now
            
        }
        
         else {
            $this->error(400, "Bad Request - invalide Parameter" . http_build_query($_GET), []);
        }
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