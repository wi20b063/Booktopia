<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path . '/Backend/logic/services/orderService.php');
    require_once ($path."/Frontend/sites/components/head.php");
    require_once ($path. '/Backend/models/user.php');
    require_once ($path . '/Backend/logic/services/userAdmin.php');
    require_once ($path . '/Backend/logic/services/userService.php');

    ?>
    <title>Booktopia | Orders View</title>

</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include_once ($path."/Frontend/sites/components/navBar.php");?>
    </nav>
    <main>
<body>
<!-- --------This block is inserted by the Ajax result -------- -->
    <div class="component" name="dynComponentDiv" id="dynComponentDiv" >

    <div class="content">
            <div class="container">
                <h2 class="headline">Übersicht über Bestellungen</h2>

                <div class="row g-3">
                    <div class="col-lg-6 mb-4" style="background-color:lightgrey"><?php echo "" ?></div>
                </div>

                <div class="row g-3">
                    <label for="userListFilter"><strong>Filter wählen:</strong></label>
                    <div class="col-md-3">                        
                        <select id="userListFilter1" name="userListFilter1" onchange ="filterUserTable(this.value, 'usrTable', 6,7)" class="form-select" aria-label="Select filter option">
                            <option selected></option>
                            <option value="">Alle</option>
                            <option value="1">Bestellt</option>
                            <option value="2">Storniert</option>
                            <option value="3">Geliefert</option>
                        </select>
                    </div>                                
                </div>
            
            <div class="mt-4 table-responsive">
                <table class="table" id="usrTable">
                    <thead>
                        <tr>
                            <th scope="col-sm-1">Nr.</th>
                            <th scope="col-sm-1">Kundenname</th>
                            <th scope="col-sm-1">BestNr.</th>
                            <th scope="col-sm-1">Bestell-Datum</th>
                            <th scope="col-sm-1">Summe</th>
                            <th scope="col-sm-1">Bezahlart</th>
                            <th scope="col-sm-1">vorauss. Lieferung</th>
                            <th scope="col-sm-1">Status</th>
                            <th scope="col-sm-1">Artikel/Bearb</th>
                            <th scope="col-sm-1">Stornieren</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php   

                      $orderU= new OrderService($con, $tbl_order, $tbl_book, $tbl_order_details, $tbl_user, $tbl_payment_items);
                      $myOrderU=$orderU->fetchAllOrders(); //Sample  with 2 Orders
                      $line=0;
                    foreach($myOrderU as $myOrder){
                        $user_ID=$myOrder->getCustomerId();
                        $line++;
                        $userformID="userDataForm_".$user_ID; //needed to identify the form in myFunctions.JS amongst all forms in each modal. we 'll pass it to the onclick action...
                        $itemDetails=$myOrder->getOrderItems();
                        ?>
                        <tr>
                        <?php
                                    $numOfItems=$myOrder->getQuantity(); 
                                ?>
                           
                                <td><?php echo $line; ?></td>
                                <td><?php echo $myOrder->getCustomerFullName(); ?></td>
                                <td><?php echo $myOrder->getOrderId(); ?></td>
                                <td><?php echo $myOrder->getOrderDate()->format('Y-m-d H:i:s'); ?></td>
                                <td><?php echo $myOrder->getTotalPrice(); ?></td>
                                <td><?php echo $myOrder->getPaymentMethod(); ?></td>

                                <td><?php echo $myOrder->getDeliveryDate(); ?></td>
                                <?php 
                                $test=$myOrder->getDeliveryStatus();
                                    if ($myOrder->getDeliveryStatus()=='ordered'){ ?>
                                        <td style="background-color:lightgreen">Bestellt</td>
                                    <?php } elseif ($myOrder->getDeliveryStatus()=='delivered'){ ?>
                                        <td style="background-color:lightgrey">Geliefert</td>
                                    <?php } else { ?>
                                        <td style="background-color:lightpink">Storniert</td> 
                                <?php }?>
                                <!-- Button trigger modal__ change status and send corresponding-->
                                <td><button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#showOrderDetails<?php echo $user_ID; ?>"><?php echo $myOrder->getQuantity(); ?>-Options</button></td>

                                <!--  Modification Button and Moddal___    -->
                                <td><div><button type="button" class="btn btn-outline-warning" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#storno<?php echo $user_ID; ?>">Stornieren </button></div></td>
                                <!-- Mod__ Change Status -->
                        
                                    <div class="container">
                                        <div class="modal fade" id="storno<?php echo $user_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Bestellung verwalten</h4>
                                                    <!-- Add reservationID as hidden input to submit with 
                                                    submit "changeStatus" to be able to perform sql query 
                                                    for selected reservationID -->
                                                    <input type="hidden" id="affected_userID" name="affected_userID" value="<?php echo $myOrder->getOrderId() ?>">
                                                </div>
                                                <!-- Registration Form with Bootstrap-->
                                                <div class="container">
                                                    <div class="row col-lg-12">
                                                        <form class="data-form" id="userDataForm<?php echo $user_ID; ?>" autocomplete="on" method="PUT">
                                                            <div class="col-md-8">
                                                                <label for="salutation">Bestellung soll storniert werden?</label><br>
                                                                
                                                            </div>

                                                            <div class="row">
                                                                
                                                                <div class="col-md-6">
                                                                    <button type="button" onclick="deleteOrder(<?php echo $user_ID ;?>)" class="btn btn-danger" name="btnSubmitDeleteData" class="btn btn-primary"
                                                                        id="btnSubmitDeleteData">Stornieren</button>
                                                                </div>
                                                                    <!--  TO-DO: call update userdata....    -->
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" id="dismissbtn<?php echo $user_ID; ?>" class="btn btn-secondary" data-bs-dismiss="modal">Zurück</button>  
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php //get mod__ with Orderdetails and hide it. link to spacer B.
                                // get spacer for Address Details and linl
                                // Add button to MODIFY or DELETE user. -> get mod__ with All fields editable and Safe/confirm Button... ?>
                        <!-- Mod___ show details -->
                            <div class="container">
                                        <div class="modal fade" id="showOrderDetails<?php echo  $user_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Bestelldetails</h4>
                                                    <input type="hidden" id="<?php echo $myOrder->getOrderId(); ?>" name="userID" value="<?php echo $myOrder->getOrderId(); ?>">
                                                </div>
                                                <div class="modal-body">                            
                                                    <div class="row mb-5 gx-5">
                                                        <div class="mb-5 mb-xxl-0">
                                                            <div class="bg-secondary-soft px-4 py-5 rounded">
                                                                    <div class="row">
                                                                        <h5 class="mb-4 mt-0">Bestell-Details</h5>
                                                                        <div class="col-lg-4">
                                                                            <p><strong>Artikel</strong></p>
                                                                            
                                                                        </div>
                                        
                                                                        <div class="col-lg-4">
                                                                            <p><strong>Preis</strong></p>
                                                                            
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <p><strong>Anzahl</strong></p>
                                                                            
                                                                        </div>
                                                                        <!-- Entries for paymentotions-->
                                                                        <?php
                                                                        foreach($itemDetails as $item){ ?>
                                                                        <div class="col-lg-4">
                                                                            <p><?php echo $item->getItemTitle() ?></p>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <p><?php echo $item->getItemPrice() ?></p>
                                                                        </div>
                                                                        <!-- PaymentDetails -->
                                                                        <div class="col-lg-4">
                                                                            <p><?php echo $item->getItemQuantity(); ?></p>
                                                                        </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <hr>
                                                                    <div class="row mt-4">
                                                                      
                                                                    </div> <!-- Row END -->
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                </div> 
                            
                    <?php
                    
                    }
                    ?>
             <script> console.log("DONE With USER TABLE") </script>
            </tbody>
        </table>
         
    </div>                                                               
</div>
         
        
    </div> 
<!-- -------- End of  Ajax result insert block ---------->               
    </main>

    <footer class="py-3 my-4 fixed-bottom">
        <?php include_once ($path."/Frontend/sites/components/footer.php");?>
    </footer>
<script type="text/javascript">


       $(document).ready(function() {
        var users;
       // getUserData("dynComponentDiv");

      }); 


function filterUserTable(filter, tableid, col1, col2){
    filter = filter.toUpperCase();
  table = document.getElementById(tableid);
  tr = table.getElementsByTagName("tr");
  len=tr.length;
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td1 = tr[i].getElementsByTagName("td")[col1];
    td2 = tr[i].getElementsByTagName("td")[col2];
    if (td1 && td2) {
      txtValue1 = td1.textContent || td1.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1 ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
    </script>
</body>

</html>

<?php

// for testing add, delete, modify user...




?>