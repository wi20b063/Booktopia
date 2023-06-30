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
require_once ($path . '/Backend/logic\services\userAdmin.php');
require_once ($path . '/Backend/logic\services\orderService.php');
require_once ($path . '/Backend/logic\session.php');


// an instance of the API class is created
$api = new Api($con, $tbl_user, $tbl_book,$tbl_payment_items, $tbl_order, $tbl_order_details);

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
     
        $this->userService = new UserService($con, $tbl_user);
        $this->bookService = new BookService($con, $tbl_book);
        $this->admin_manageUsers = new adminUser($con, $tbl_user, $tbl_payment_items);
        $this->orderService = new OrderService($con, $tbl_order, $tbl_book, $tbl_order_details, $tbl_user,  $tbl_payment_items);
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
    
    // Hier können verschiedene GET-Anfragen an die entsprechenden Services weitergeleitet werden.
    public function processGet() {

        // login user 
        if (isset($_GET["loginUser"])) {
            $username = $_GET["username"];
            $password = $_GET["password"];
            $rememberMe = $_GET["rememberMe"];
           
            $result = $this->userService->loginUser($username, $password, $rememberMe);
                if ($result === true) {
                    $this -> success(200,  "Login erfolgreich!", []);
                } else {
                    $this -> error(401, "Login fehlgeschlagen! " , $result);                
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
        } elseif (isset($_GET["getOrderData"])) {
            $orderData = $this->userService->getOrderData();
            $this->success(200, "", $orderData);
        
        // get order data by Id
        } elseif (isset($_GET["getOrderDataByOrderId"])) {
            $orderId = $_GET["orderId"];
            $orderData = $this->userService->getOrderDataByOrderId($orderId);
            $this->success(200, "", $orderData);
        
        // get order details for specific orderId
        } elseif (isset($_GET["getOrderDetails"])) {
            $clickedOrderId = $_GET["clickedOrderId"];
            $orderDetails = $this->userService->getOrderDetails($clickedOrderId);
            $this->success(200, "", $orderDetails);
        
        // get book details for specific articleId / itemId
        } elseif (isset($_GET["getBookDetails"])) {
            $orderDetailsArticleId = $_GET["orderDetailsArticleId"];
            $bookDetails = $this->bookService->getBookDetails($orderDetailsArticleId);
            $this->success(200, "", $bookDetails);
    
        // logout user
        } elseif (isset($_GET['logoutUser'])) {
            $this->userService->logoutUser();
            $this->success(200, "Logout erfolgreich!", []);

        // check if correct password was entered for saving profile
        } elseif (isset($_GET["checkPasswordForSavingProfile"])) {
            $passwordForSavingProfile = $_GET["passwordForSavingProfile"];
            $result = $this->userService->checkPassword($passwordForSavingProfile);

                if ($result === true) {
                    $this -> success(200,  "true", []);
                } else {
                    $this -> error(401, "CheckPWForSaving Profile fehlgeschlagen",  $result);                
                }
        
            } 
        elseif (isset($_GET['deleteId']) ) {
            // echo " processGet - getUserData - in api.php reached";
            $this->admin_manageUsers->deleteUser($_GET["deleteId"]);
            
        } // get User's order data
        elseif (isset($_GET['userOrder']) ) {
            //echo " processGet - getUserData - in api.php reached";
            $this->orderService->fetchUserOrders($_GET["userOrder"]);
        } // get User's order data
        elseif (isset($_GET['allOrders']) ) {
            //echo " processGet - getUserData - in api.php reached";
            $this->orderService->fetchAllOrders();
            
        /* } elseif (isset($_GET["book"])) {
            // Produkt erstellen
            // $this->productService->createBook(); */       
        /* } elseif (isset($_GET["book"])) {
            $books = $this->bookService->findAll();
            $this->success(200, $books); */
        } else { 
            $this->error(400, [], "GET: Bad Request - invalid parameters " . http_build_query($_GET));
        }
    }

    
    // ************************************************************
    //          POST REQUESTS
    // ************************************************************
    
    // Hier können verschiedene POST-Anfragen an die entsprechenden Services weitergeleitet werden.
    public function processPost() {

        if (empty($_POST)) {
            // Error
            echo "Empty post request.";
        }
        
        
        elseif (isset($_POST["user"]) && isset($_POST["callerRole"])) { 
            // User exists, updatem
            echo "console.log('processPost - saveUser - in api.php reached');";
            // fetch data from posted body
            $user = $_POST["user"];
            // print firstname of array user
            echo $user["username"];
            $this->admin_manageUsers->updateUser($user,$_POST["callerRole"]);
        }
        // register user
        elseif (isset($_POST["user"])) { 
            $user = $_POST["user"];
            $this->userService->saveUser($user);        
        
        // Update User
        } elseif (isset($_POST["saveEditedUserData"])) {             
            $editedUser = $_POST["editedUser"];
            $this->userService->saveEditedUserData($editedUser);
                    
        } else {
            $this->error(400, "POST: Bad Request - invalide Parameter" . http_build_query($_GET), []);
        }
    }


    // ************************************************************
    //          DELETE REQUESTS
    // ************************************************************
    
    public function processDelete() {
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
        // Hier können verschiedene PUT-Anfragen an die entsprechenden Services weitergeleitet werden.
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
    private function error ($code, $message, $dummyVar) {
        http_response_code($code);
        echo($message);
        exit;        
    }
}

?>