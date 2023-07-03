<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/backend/models/order.php');
require_once($path . '/backend/models/book.php');
require_once($path . '/backend/models/orderItem.php');
// order consits of the order fields and an array of items in this order (in essence-the shopping cart of that order)
enum OrderStatus: string
{
    case Bestellt="1";
    case Bestätigt="2";
    case Geliefert="3";
}
class Order {
    private $orderId;
    private $customerId;
    private  $customerFullname;
    private $orderItems=[];
    private $totalPrice;
    private $quantity;
    private $orderDate;
    private $paymentMethodId;
    private $paymentMethod;
    private $deliveryMethod; 
    private $deliveryAddress;
    private string $deliveryStatus;
    private $deliveryDate;

    
    
    public function __construct($customerId, $orderId, $quantity, $totalPrice, $orderDate, $customerFullname,  $paymentMethod, $paymentMeanNr,  array $orderItems, string $deliveryStatus ) {
        $this->customerId=$customerId;
        $this->orderId=$orderId;
        $this->quantity=sizeof($orderItems);
        //$this->totalPrice=$this->setOrderPrice();
        $this->totalPrice=$totalPrice;
        $this->orderDate=$orderDate = date_create(Date("Y-m-d H:i"));
        // $this->paymentMethod = $paymentMethod;
        // $this->deliveryMethod = $deliveryMethod;
        // $this->deliveryAddress = $deliveryAddress;
        $this->deliveryStatus = $deliveryStatus;  
        $this->orderItems=$orderItems;
        $this->customerFullname=$customerFullname;
        $this->paymentMethod=$paymentMethod;

    }



    public function getOrderId() {
        return $this->orderId;
    }

    public function getCustomerFullName() {
        return $this->customerFullname;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }
    public function getPaymentMethodId() {
        return $this->paymentMethodId;
    }
    public function getCustomerId() {
        return $this->customerId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTotalPrice() {
        return $this->totalPrice;
    }
    public function getOrderItems() {
        return $this->orderItems;
    }
    public function getOrderDate() {
        return $this->orderDate;
    }


    public function getDeliveryMethod() {
        return $this->deliveryMethod;
    }

    public function getDeliveryAddress() {
        return $this->deliveryAddress;
    }

    public function getDeliveryStatus() {
        return $this->deliveryStatus;
    }

    public function getDeliveryDate() {
        return $this->deliveryDate;
    }
    public function setDeliveryStatus($deliveryStatus){
        $this->deliveryStatus=$deliveryStatus;
    }
    public function setDeliveryDate($deliveryDate){
        $this->deliveryStatus=$deliveryDate;
    }
    /* public function setOrderPrice(){
        $totalprice=0;
        foreach ($this->orderItems as $item) {
            $totalprice+=$item->book->price*$item->book->price;
        }
        return $totalprice;
    } */


}

?>