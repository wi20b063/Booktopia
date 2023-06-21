<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path .'/backend/logic/session.php');
require($path . '/backend/models/order.php');
require($path . '/backend/models/book.php');
require($path . '/backend/models/orderItem.php');
global $tbl_book;

 class OrderService {
    private $con;
    private $tbl_order;
    private $test;
    
  

    public function __construct($con, $tbl_order) {
        $this->con = $con;
        $this->tbl_order = $tbl_order;
                
    } 

    public function createOrder(Order $order) {
        $customerId = $order->getCustomerId();
        

        $productId = $order->getProductId();
        $quantity = $order->getQuantity();
        $totalPrice = $order->getTotalPrice();
        $deliveryDate = $order->getDeliveryDate();
        $orderDate = date("Y-m-d"); // set the order date as the current date

        // Check if the product is available in stock
        $checkStock = $this->checkStock($productId, $quantity);

        if (!$checkStock) {
            // Product is not available in stock
            return false;
        }

        // Create the order with prepared statement
        $sql = "INSERT INTO " . $this->tbl_order . " (userId, productId, quantity, totalPrice, deliveryDate, orderDate) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iiids", $customerId, $productId, $quantity, $totalPrice, $deliveryDate, $orderDate);
        $stmt->execute();
        $stmt->close();

        // Update the stock
        $this->updateStock($productId, $quantity);

        return true;
    }

    private function checkStock($productId, $quantity) {
        // Implement the logic to check if the product is available in stock
        // Example:
        // $product = $this->findProductById($productId);
        // if ($product->getStock() >= $quantity) {
        //     return true;
        // } else {
        //     return false;
        // }

        // For now, assume the product is always available in stock
        return true;
    }

    private function updateStock($productId, $quantity) {
        // Implement the logic to update the stock after the order is created
        // Example:
        // $product = $this->findProductById($productId);
        // $newStock = $product->getStock() - $quantity;
        // $this->updateProductStock($productId, $newStock);

        // For now, assume the stock is not updated
    }

    /* Other methods such as finding orders, updating orders, etc. can be implemented here */

    public function getAvailableProducts(){
        $availProductArr=[];
        global $tbl_book;
         $sql = "Select * from {$tbl_book}";
         $stmt = $this->con->prepare($sql);
         $stmt->execute();
         $result= $stmt->get_result();
         
         $i=0;
         while ($resSet=mysqli_fetch_assoc($result)){
             $availProductArr[$i++]= new Book($resSet['id'],$resSet['titel'],$resSet['autor'], $resSet['bewertung'], $resSet['isbn'], $resSet['kategorie'],$resSet['preis'],$resSet['preis'], $resSet['description'], $resSet['image_url'],$resSet['stock']);
         }
         
         $stmt->close();
         return($availProductArr);
    }
    public function closeConnection() {
        $this->con->close();
    }
}

?>

