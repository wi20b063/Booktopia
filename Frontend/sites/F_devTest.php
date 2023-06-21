<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    include "components/head.php";
    //path var necessary to overcome include relative path of an included file from different folder
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path."/Frontend/sites/components/head2.php");
    require_once ($path. '/Backend/models/user.php');
    require_once ($path . '/Backend/logic/services/admin_manageUsers.php');
    require_once ($path . '/Backend/logic/services/userService.php');
    require_once ($path . '/Backend/logic/services/orderService.php');
    ?>
    <title>Booktopia | Userverwaltung</title>

</head>
<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">
            <h1 class="headline">Übersicht aller User</h1>

<div class="row g-3">
        <div class="col-md-6 mb-4" style="background-color:lightgrey"><?php echo "" ?></div>
</div>

<form method="POST" enctype="multipart/form-data">

<div class="row g-3">
    <label for="userListFilter"><strong>Filter wählen:</strong></label>
    <div class="col-md-3">                        
        <select id="userListFilter" name="userListFilter" class="form-select" aria-label="Select filter option">
            <option selected></option>
            <option value="all">Alle</option>
            <option value="active">Aktiv</option>
            <option value="inactive">Inaktiv</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="filter" class="btn btn-blue">Bestätigen</button>
    </div>                                       
</div>
    <div class="mt-4 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col-sm-1">Nr.</th>
                    <th scope="col-sm-1">Anrede</th>
                    <th scope="col-sm-1">Vorname</th>
                    <th scope="col-sm-1">Nachname</th>
                    <th scope="col-sm-1">Username</th>
                    <th scope="col-sm-1">Email</th>
                    <th scope="col-sm-1">Aktiv</th>
                    <th scope="col-sm-1">Admin</th>
                    <th scope="col-sm-1">BezahlDetails</th>
                    <th scope="col-sm-1">AddressDetails</th>
                    <th scope="col-sm-1">Änderung</th>

                </tr>
            </thead>
            <tbody>
            <?php   
            $myAdminUserObj = new adminUser($con, $tbl_user);
            $res=$myAdminUserObj->getUsers();
            foreach($res as $userObj){
                $user_ID=$userObj->getuserId();
                ?>
                <tr>
                    
                    <td scope="row">
                    
                                        <!-- Button trigger modal show details and send corresponding
                                        reservationID with element data-bs-target (open modal -->
                        <?php
                            $paymentDetail=$userObj->getpaymentMethods(); 
                            $sizeofPaymentDetail=sizeof($paymentDetail);
                            
                            echo $user_ID; ?></td>
                        <td><?php echo $userObj->getSalutation(); ?></td>
                        <td><?php echo $userObj->getFirstName(); ?></td>
                        <td><?php echo $userObj->getLastName(); ?></td>
                        <td><?php echo $userObj->getUsername(); ?></td>
                        <td><?php echo $userObj->getEmail(); ?></td>
                        <?php if ($userObj->getActive()==1){ ?>
                            <td style="background-color:lightgreen">Yes</td>
                        <?php } else { ?>
                            <td style="background-color:lightpink">No</td> 
                        <?php }?>
                        <?php if ($userObj->getAdmin()==1){ ?>
                            <td style="background-color:lightblue">Admin</td>
                        <?php } else { ?>
                            <td style="background-color:lightgrey">User</td> 
                        <?php }?>
                        <!-- Button trigger modal change status and send corresponding
                             -->
                                        <td><button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#showPayDetails<?php echo $user_ID; ?>"><?php echo $sizeofPaymentDetail; ?>-Options</button></td>
                        <td><?php echo "space AddressDetails"; ?></td>
                        <!--  Modification Button and Modal    -->
                        <td><div><button type="button" class="btn btn-outline-warning" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#changeData<?php echo $user_ID; ?>">Modify </button></div></td>
                    <!-- Modal Change Status -->
                    <form action="" method="POST" enctype="multipart/form-data">
                            <div class="container">
                                <div class="modal fade" id="changeData<?php echo $user_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Buchungsstatus ändern</h5>
                                            <!-- Add reservationID as hidden input to submit with 
                                            submit "changeStatus" to be able to perform sql query 
                                            for selected reservationID -->
                                            <input type="hidden" id="reservationIDstatus" name="reservationIDstatus" value="<?php echo $userObj->getuserId(); ?>">
                                        </div>
                                        <div class="modal-body">                            
                                            <select class="form-select" aria-label="Default select example" id="changeStatusSelect" name="changeStatusSelect">
                                                <option selected>Status der Buchung mit der Buchungs-ID <?php echo $user_ID; ?> ändern.</option>
                                                <option value="new">Offen</option>
                                                <option value="reserved">Bestätigt</option>
                                                <option value="cancelled">Storniert</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                            <button type="submit" name="changeStatus" id="changeStatus" class="btn btn-danger">Speichern</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>          


                        <?php //get modal with Bezahldetails and hide it. link to spacer B.
                        
                        // get spacer for Address Details and linl
                        // Add button to MODIFY or DELETE user. -> get modal with All fields editable and Safe/confirm Button...

                        ?>
                <!-- Modal show details -->
                    <div class="container">
                                <div class="modal fade" id="showPayDetails<?php echo  $user_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Payment Options</h5>
                                            <input type="hidden" id="<?php echo $userObj->getuserId(); ?>" name="userID" value="<?php echo $userObj->getuserId(); ?>">
                                        </div>
                                        <div class="modal-body">                            
                                            <div class="row mb-5 gx-5">
                                            <div class="mb-5 mb-xxl-0">
                                                <div class="bg-secondary-soft px-4 py-5 rounded">
                                                        <div class="row">
                                                            <h4 class="mb-4 mt-0">Verfügbare Bezahloptionen</h4>
                                                            <!-- Entries for paymentotions-->
                                                            <?php
                                                            foreach($paymentDetail as $payObj){ ?>
                                                            <div class="col-md-6">
                                                                <p><strong>Bezahlmittel:</strong></p>
                                                                <p><?php echo $payObj->getpaymentType() ?></p>
                                                            </div>
                                                            <!-- PaymentDetails -->
                                                            <div class="col-md-6">
                                                                <p><strong>Nummer:</strong></p>
                                                                <p><?php echo $payObj->getpaymentMethodDetails(); ?>,00</p>
                                                            </div>
                                                            <?php
                                                            }
                                                            ?>
                                                            
                                                        <hr>
                                                        <div class="row mt-4">
                                                            <h4 class="mb-4 mt-0">Offene Bestellungen hier?</h4>
                                                            <!-- B-NR -->
                                                            <div class="col-md-6">
                                                                <p><strong>BestellNr u Datum:</strong></p>
                                                                <p><?php echo "yyy"; ?></p>
                                                            </div>
                                                            <!-- Anzahl u Preis -->
                                                            <div class="col-md-6">
                                                                <p><strong>Anzahl und Preis:</strong></p>
                                                                <p><?php echo "zzZ"; ?></p>
                                                            </div>                                                                           

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


                         
        </tbody>
        </table>

</form>                
                 
</div>                                                               
</div>
                
    </main>

    <footer class="py-3 my-4 fixed-bottom">
        <?php include "components/footer.php";?>
    </footer>

</body>

</html>