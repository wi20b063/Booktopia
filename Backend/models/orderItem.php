<?php

class OrderItem {
    public $orderId;
    public $itemId; // i-th item within one order
    public $itemTitle;
    public $itemPrice;

    public $quantity;
   

    public function __construct($oderDetailId, $orderId, $itemId, $itemTitle, $itemPrice, $quantity) {
        $this->orderId=$orderId;
        $this->itemId=$itemId;
        $this->itemTitle=$itemTitle;
        $this->quantity=$quantity;
        $this->itemPrice=$itemPrice;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getItemQuantity() {
        return $this->quantity;
    }

    public function getItemId() {
        return $this->itemId;
    }
    public function getItemTitle() {
        return $this->itemTitle;
    }

    public function getItemPrice() {
        return $this->itemPrice;
    }
    /* public function getBook() {
        return $this->itemTitle;
    } */
}

?>
