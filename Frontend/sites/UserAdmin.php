<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    include "components/head.php";
    //path var necessary to overcome include relative path of an included file from different folder
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path."/Frontend/sites/components/head.php");
    //require_once ($path."/Frontend/sites/components/head2.php");
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
            <h2 class="headline">Übersicht aller User</h2>

<div class="row g-3">
        <div class="col-lg-6 mb-4" style="background-color:lightgrey"><?php echo "" ?></div>
</div>

<form method="POST" enctype="multipart/form-data">

<div class="row g-3">
    <label for="userListFilter"><strong>Filter wählen:</strong></label>
    <div class="col-md-3">                        
        <select id="userListFilter1" name="userListFilter1" onchange ="filterUserTable(this.value, 'usrTable', 6,7)" class="form-select" aria-label="Select filter option">
            <option selected></option>
            <option value="">Alle</option>
            <option value="Yes">Aktiv</option>
            <option value="No">Inaktiv</option>
            <option value="Admin">Admin</option>
        </select>
    </div>
                                       
</div>
    <div class="mt-4 table-responsive">
        <table class="table" id="usrTable">
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
                        
                        <!--  Modification Button and Modal    -->
                        <td><div><button type="button" class="btn btn-outline-warning" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#changeData<?php echo $user_ID; ?>">Modify </button></div></td>
                    <!-- Modal Change Status -->
                    <form action="" method="POST" enctype="multipart/form-data">
                            <div class="container">
                                <div class="modal fade" id="changeData<?php echo $user_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Benutzerdaten verwalten</h4>
                                            <!-- Add reservationID as hidden input to submit with 
                                            submit "changeStatus" to be able to perform sql query 
                                            for selected reservationID -->
                                            <input type="hidden" id="reservationIDstatus" name="reservationIDstatus" value="<?php echo $userObj->getuserId(); ?>">
                                        </div>
                                        <!-- Registration Form with Bootstrap-->
                                        <div class="container">
                                            <div class="row col-lg-12">
                                                <form class="data-form" id="registrationForm" action="registration.php" method="post"
                                                    autocomplete="on">

                                                    <div class="col-md-4">
                                                        <label for="salutation">Anrede: *</label><br>
                                                        <select name="salutation" id="salutationRegistration" style=" margin-bottom: 15px;"
                                                            class="form-control">
                                                            <option disabled selected value> <?php echo $userObj->getSalutation() ?></option>
                                                            <option value="Frau">Frau</option>
                                                            <option value="Herr">Herr</option>
                                                            <option value="Divers">Divers</option>
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="firstName">Vorname:</label>
                                                            <input type="text" name="firstName" id="firstNameRegistration" selected value="<?php echo $userObj->getFirstName() ?>" style=" margin-bottom: 15px;"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="lastName">Nachname:</label>
                                                            <input type="text" name="lastName" id="lastNameRegistration" selected value="<?php echo $userObj->getLastName() ?>"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="md-6">
                                                            <label for="address">Adresse:</label><br>
                                                            <input type="text" name="address" id="addressRegistration" selected value="<?php echo $userObj->getAddress() ?>"style=" margin-bottom: 15px;"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="postcode">PLZ:</label><br>
                                                            <input type="int" name="postcode" id="postcodeRegistration" selected value="<?php echo $userObj->getpostalCode() ?>"style=" margin-bottom: 15px;"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="location">Ort:</label><br>
                                                            <input type="text" name="location" id="locationRegistration" selected value="<?php echo $userObj->getLocation() ?>"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="email">E-Mail-Adresse:</label><br>
                                                            <input type="email" name="email" id="emailRegistration" selected value="<?php echo $userObj->getEmail() ?>"style=" margin-bottom: 15px;"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="username">Benutzername:</label><br>
                                                            <input type="text" name="username" id="usernameRegistration" selected value="<?php echo $userObj->getUsername() ?>"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckisActive" style=" margin-bottom: 15px;" <?php ($userObj->getActive()==1) ?  ($check= "checked") : ($check= "unchecked"); echo $check;?>>
                                                                <label class="form-check-label" for="flexSwitchCheckDefault">Active</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckisAdmin" style=" margin-bottom: 15px;" <?php ($userObj->getAdmin()==1) ?  ($check= "checked") : ($check= "unchecked"); echo $check;?>>
                                                                <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="mb-5">
                                                        <button type="button" class="btn btn-danger" name="btnSubmitRegistration" class="btn btn-primary"
                                                            id="btnSubmitRegistration">Update Daten</button>
                                                            <!--  TO-DO: call update userdata....    -->
                                                    </div>

                                                </form>

                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                            
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
                                            <h4 class="modal-title">Bezahlmittel Ansicht</h4>
                                            <input type="hidden" id="<?php echo $userObj->getuserId(); ?>" name="userID" value="<?php echo $userObj->getuserId(); ?>">
                                        </div>
                                        <div class="modal-body">                            
                                            <div class="row mb-5 gx-5">
                                            <div class="mb-5 mb-xxl-0">
                                                <div class="bg-secondary-soft px-4 py-5 rounded">
                                                        <div class="row">
                                                            <h5 class="mb-4 mt-0">Gefundene Zahlungsmittel</h5>
                                                            <div class="col-lg-6">
                                                                <p><strong>Bezahlmittel:</strong></p>
                                                                
                                                            </div>
                                                            <!-- PaymentDetails -->
                                                            <div class="col-lg-6">
                                                                <p><strong>Nummer:</strong></p>
                                                                
                                                            </div>
                                                            <!-- Entries for paymentotions-->
                                                            <?php
                                                            foreach($paymentDetail as $payObj){ ?>
                                                            <div class="col-lg-6">
                                                                <p><?php echo $payObj->getpaymentType() ?></p>
                                                            </div>
                                                            <!-- PaymentDetails -->
                                                            <div class="col-lg-6">
                                                                <p><?php echo $payObj->getpaymentMethodDetails(); ?></p>
                                                            </div>
                                                            <?php
                                                            }
                                                            ?>
                                                            
                                                        <hr>
                                                        <div class="row mt-4">
                                                            <h5 class="mb-4 mt-0">Offene Bestellungen hier?</h5>
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
<script type="text/javascript">
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