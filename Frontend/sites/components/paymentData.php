<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/backend/models/paymentType.php');
require_once($path . "/Frontend/sites/components/head.php");
?>


<head>
    <title>PaymetInfo</title>
</head>

<body>
        

        <label for="category"> <p><strong> Hinzufügen von Zahlungsmittel.</strong></p>
        <p> Änderungen und hinzufügen weiterer Zahlungsmittel ist später jederzeit im Kundenbreich möglich.
                            </label>
            <div class="container">
                    <form class="data-form" id="registrationForm" action="registration.php" method="post"
                        autocomplete="on">
                </div>
        </div>
        <div class="grid">
    <div class="row">
        <div class="col-lg-3 col-md-3 ">
        <select name="payselect1" id="payselect1"  style=" margin-bottom: 5px;">
                    <option  disabled selected value>---Zahlungsmittel--- </option>
                        <?php
                        foreach (PaymentMethod::cases() as $payOption) {
                            ?>
                            <option value="<?php echo $payOption->value; ?>"><?php echo $payOption->name; ?>
                            </option>
                            <?php
                        }
                        ?> 
                    </select>
        </div>
        <div class="col-lg-4 col-md-4  ">
        <label for="paymentTypeDetails">Bezahlmitteldetails:</label>
                                    <input type="text" name="paymentMethodDetails" id="paymentMethodDetails"  selected
                                        value="ABC123456" class="form-control" required>
        </div>
    </div>
    
   <!-- modified and moved to myfunctions.js... in HTML tag: onchange="paymentInput()" 
    <script type="text/javascript">
        function paymentInput(){
    myValue= this.document.getElementById("payselect1");
  console.log('selectedValue, value: ', myValue.value);
  console.log('actual Text:  ',  this.document.getElementById("payselect1").options[myValue.selectedIndex].text);
  console.log('paymentDetail:', this.document.getElementById("paymentMethodDetails").value);

}
</script> -->



</body>

</html>