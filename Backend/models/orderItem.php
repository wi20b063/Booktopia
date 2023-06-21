<?php

class OrderItem {
    public $orderId;
    public $itemID; // i-th item within one order
    public $book;
    public $quantity;
   

    public function __construct($orderID, $itemID, $book, $quantity) {
        $this->orderId=$orderID;
        $this->itemID=$itemID;
        $this->$book=$book;
        $this->quantity=$quantity;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getItemID() {
        return $this->itemID;
    }
}

?>
