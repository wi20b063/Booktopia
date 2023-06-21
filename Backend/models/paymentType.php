<?php
 enum PaymentMethod: string
 {
     case KreditKarte="1";
     case Paypal="2";
     case Zahlschein="3";
 }
class PaymenMetType{
    private $userId;
    private $userPayMethodId;
    private string $payMethod;
    private $payMethodDetail;


    public function __construct(int $userId, int $userPayMethodId, string $payMethod, string $paymemntMethodDetail) {
        $this->userId=$userId;
        $this->userPayMethodId=$userPayMethodId;
        $this->payMethod=$payMethod;
        $this->payMethodDetail=$paymemntMethodDetail;
    }
public function getpaymentType(){
    return $this->payMethod;
}
public function getpaymentMethodDetails(){
    return $this->payMethodDetail;
    
}
public function getpaymentMethodId(){
    return $this->userPayMethodId;
}

}

?>
