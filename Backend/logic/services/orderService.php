<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path .'/backend/logic/session.php');
require_once($path . '/backend/models/order.php');
require_once($path . '/backend/models/book.php');
require_once($path . '/backend/models/orderItem.php');
global $tbl_book;

 class OrderService {
    private $con;
    private $tbl_order, $tbl_book, $tbl_user, $tbl_payment_items;
    private $tbl_order_details;
    private $test;
    
  

    public function __construct($con, $tbl_order, $tbl_book, $tbl_order_details, $tbl_user,  $tbl_payment_items) {
        $this->con = $con;
        $this->tbl_order = $tbl_order;
        $this->tbl_book= $tbl_book;
        $this->tbl_user=$tbl_user;
        $this->tbl_payment_items=$tbl_payment_items;
                
    } 

    function fetchUserOrders($userID){
        $sql = "Select CONCAT(lastName, ' ', firstName) as Customer, {$this->tbl_order}.userId, {$this->tbl_order}.orderId, {$this->tbl_order}.quantity, {$this->tbl_order}.paymentId, {$this->tbl_order}.orderDate, {$this->tbl_order}.totalPrice, {$this->tbl_order}.deliveryStatus as Status, {$this->tbl_payment_items}.paymentMethod as Bezahlart, {$this->tbl_payment_items}.payMethodDetail as Bezahldetail from {$this->tbl_order} JOIN {$this->tbl_user} ON {$this->tbl_order}.userId={$this->tbl_user}.userId JOIN {$this->tbl_payment_items} ON paymentitems.itemId={$this->tbl_order}.paymentId WHERE {$this->tbl_order}.userId = ? GROUP BY orderId";
        $query = $this->con->prepare($sql);
        $query->bind_param("s", $userID);
        $query->execute();
        $result= $query->get_result();
        $orders=[];
        $i=0;
        while ($resSet=mysqli_fetch_assoc($result)){
            //get each order's details 
            $orderItemDetails=$this->fetchOrderDetails($resSet['orderId']);

            $orders[$i++]= new Order($resSet['userId'], $resSet['orderId'],  $resSet['quantity'], $resSet['totalPrice'], $resSet['orderDate'], $resSet['Customer'], $resSet['Bezahlart'], $resSet['Bezahldetail'], $orderItemDetails, $resSet['Status']);
        }
        
        $query->close();
        return($orders);
    }

    


    function fetchAllOrders(){

        $sql = "Select CONCAT(lastName, ' ', firstName) as Customer, {$this->tbl_order}.userId, {$this->tbl_order}.orderId, {$this->tbl_order}.quantity, {$this->tbl_order}.paymentId, {$this->tbl_order}.orderDate, {$this->tbl_order}.totalPrice, {$this->tbl_order}.deliveryStatus as Status, {$this->tbl_payment_items}.paymentMethod as Bezahlart, {$this->tbl_payment_items}.payMethodDetail as Bezahldetail from {$this->tbl_order} JOIN {$this->tbl_user} ON {$this->tbl_order}.userId={$this->tbl_user}.userId JOIN {$this->tbl_payment_items} ON paymentitems.itemId={$this->tbl_order}.paymentId GROUP BY orderId";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result= $stmt->get_result();
        $orders=[];
        $i=0;
        while ($resSet=mysqli_fetch_assoc($result)){
            //get each order's details 
            $orderItemDetails=$this->fetchOrderDetails($resSet['orderId']);

            $orders[$i++]= new Order($resSet['userId'], $resSet['orderId'],  $resSet['quantity'], $resSet['totalPrice'], $resSet['orderDate'], $resSet['Customer'], $resSet['Bezahlart'], $resSet['Bezahldetail'], $orderItemDetails, $resSet['Status']);
        }
        
        $stmt->close();
        return($orders);
    }

    function fetchOrderDetails($orderId){
        $sql = "Select * from order_details join book on book.id= order_details.itemId  WHERE orderId= ?";
        $query = $this->con->prepare($sql);
        $query->bind_param("s", $orderId);
        $query->execute();
        $result= $query->get_result();
        $i=0;
        $orderItems=[];
        while($resSet = mysqli_fetch_assoc($result)){
            $orderItems[$i++]= new OrderItem($resSet['detailId'], $resSet['orderId'], $resSet['itemId'], $resSet['titel'], $resSet['preis'], $resSet['quantity']);
        }
        return $orderItems;

    }
    
     function addOrder(Order $order) {
        $customerId = $order->getCustomerId();
        $customerId=$order->getCustomerId();
        //to-Do: $orderItems array.... ############################
        $totalPrice = $order->getTotalPrice();   // TO-DO rather  recalculate from database??
        $quantity = $order->getQuantity();
        $orderDate = date_create(Date("Y-m-d H:i"));
        $date = new DateTime($orderDate);
        $date->add(new DateInterval('P1D')); // P1D means a period of 1 day
        $deliveryDate = $date->format('Y-m-d H.i');
        $deliveryStatus = $order->getDeliveryStatus();
        $paymentmethodId=$order->getPaymentMethodId();

        $orderItems[] = $order->getOrderitems();


        // Create the order with prepared statement
        $sql = "INSERT INTO " . $this->tbl_order . " (userId, paymentId, quantity, totalPrice, orderDate,  deliveryMethod, deliveryDate, deliveryStatus ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iiididssss", $customerId, $paymentmethodId, $quantity, $totalPrice,  $orderDate, $deliveryDate,$deliveryStatus);
        $stmt->execute();
    
        $result = $stmt->affected_rows;
        $last_id = $stmt->lastInsertId();  //need this new ID as foreign key for orderItems table
        if ($result == 1) {
            echo("<script>console.log('PHP: " . $result . "');</script>");
        } else {
            echo("<script>console.log('PHP: " . $result . "');</script>");
            return(false);
        }
        $count=0;
        /* foreach($item as $order->getOrderitems()){
           if(addOrderItem($item, $last_id, $book, $quantity)==true){

           };   
        }
        
        $this->updateStock($productId, $quantity);
 */
        return true;
    }

    public function addOrderItem($item, $last_id){

        // Create the order with prepared statement
        $sql = "INSERT INTO " . $this->tbl_payment_items . " (orderId, itemId, qantity ) 
        VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iiii", $item->getOrderId(), $item->getBook->getBookId(), $item->getQuantity()  );
        $stmt->execute();
        
        $result = $stmt->affected_rows;
        if ($result == 1) {
            echo("<script>console.log('PHP: " . $result . "');</script>");
            $last_id = $stmt->lastInsertId();  //need DB issued ID for inserting payment Info
        } else {
            echo("<script>console.log('PHP: " . $result . "');</script>");
            $stmt->close();
            return false;
        }
        return(true);

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

