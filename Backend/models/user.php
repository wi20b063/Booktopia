<?php
 $path = $_SERVER['DOCUMENT_ROOT'];
 require_once ('paymentType.php');

// User Model --> already adapted according to the database

class User {

    private $userId;
    private $salutation;
    private $firstName;
    private $lastName;
    private $address;
    private $postcode;
    private $location;
    private $payMethods=[];
    private $email;
    private $username;
    private $password; 
    private $active;
    private $admin;


    public function __construct(int $userId, string $salutation, string $firstName,
                                string $lastName, string $address, string $postcode,
                                string $location, array $payMethods, string $email,
                                string $username, string $password, int $active, int $admin) { 
        $this->userId         = $userId;
        $this->salutation     = $salutation;
        $this->firstName      = $firstName;
        $this->lastName       = $lastName;
        $this->address        = $address;
        $this->postcode     = $postcode;
        $this->location       = $location;
        $this->payMethods   = $payMethods;
        $this->email          = $email;
        $this->username       = $username;
        $this->password       = $password;
        $this->active         = $active;
        $this->admin          = $admin;
    }

    public function getuserId() {
        return $this->userId;
    }
    
    public function getSalutation() {
        return $this->salutation;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getpostcode() {
        return $this->postcode;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getpaymentMethods() {
        return $this->payMethods;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getActive() {
        return $this->active;
    }
    public function getAdmin(){
        return $this->admin;
    }

}