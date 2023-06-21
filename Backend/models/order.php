<?php
// order consits of the order fields and an array of items in this order (in essence-the shopping cart of that order)
class Order {
    private $orderId;
    private $customerId;
    private $orderItems = [];

    private $totalPrice;
    private $quantity;
    private $orderDate;
    private $paymentMethod;
    private $deliveryMethod; 
    private $deliveryAddress;
    private $deliveryStatus;
    private $deliveryDate;

    
    

    public function __construct($customerId, $orderId, $quantity, $totalPrice, $orderDate,  $orderItems) {
        $this->customerId=$customerId;
        $this->orderItems=$orderItems;
        $this->quantity=sizeof($orderItems);
        $this->totalPrice=$this->setOrderPrice();
        $this->orderDate=
        // $this->paymentMethod = $paymentMethod;
        // $this->deliveryMethod = $deliveryMethod;
        // $this->deliveryAddress = $deliveryAddress;
        $this->deliveryStatus = 0;  
    }



    public function getOrderId() {
        return $this->orderId;
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

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
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
    public function setOrderPrice(){
        $totalprice=0;
        foreach ($this->orderItems as $item) {
            $totalprice+=$item->book->price*$item->book->price;
        }
        return $totalprice;
    }


}

?>
