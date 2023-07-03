<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    
    //path var necessary to overcome include relative path of an included file from different folder
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path."/Frontend/sites/components/head.php");
    require_once ($path. '/Backend/models/user.php');
    require_once ($path . '/Backend/logic/services/userAdmin.php');
    require_once ($path . '/Backend/logic/services/userService.php');
    ?>


     	

</head>
<body>
    <main>
        <div class="content">
            <div class="container">
                <h2 class="headline">Übersicht aller User</h2>

                <div class="row g-3">
                    <div class="col-lg-6 mb-4" style="background-color:lightgrey"><?php echo "" ?></div>
                </div>

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

                    $myAdminUserObj = new adminUser($con, $tbl_user, $tbl_payment_items);
                    $res=$myAdminUserObj->getUsers();
                    foreach($res as $userObj){
                        $user_ID=$userObj->getuserId();
                        $userformID="userDataForm_".$user_ID; //needed to identify the form in myFunctions.JS amongst all forms in each modal. we 'll pass it to the onclick action...
                        ?>
                        <tr>
                            
                            <td scope="row">
                            
                                                <!-- Button trigger mo__ show details and send corresponding
                                                reservationID with element data-bs-target (open mo__ -->
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
                                <!-- Button trigger modal__ change status and send corresponding
                                    -->
                                                <td><button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#showPayDetails<?php echo $user_ID; ?>"><?php echo $sizeofPaymentDetail; ?>-Options</button></td>
                                
                                <!--  Modification Button and Mod___    -->
                                <td><div><button type="button" class="btn btn-outline-warning" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#changeData<?php echo $user_ID; ?>">Modify </button></div></td>
                            <!-- Mod__ Change Status -->
                        
                                    <div class="container">
                                        <div class="modal fade" id="changeData<?php echo $user_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Benutzerdaten verwalten</h4>
                                                    <!-- Add reservationID as hidden input to submit with 
                                                    submit "changeStatus" to be able to perform sql query 
                                                    for selected reservationID -->
                                                    <input type="hidden" id="affected_userID" name="affected_userID" value="<?php echo $userObj->getuserId(); ?>">
                                                </div>
                                                <!-- Registration Form with Bootstrap-->
                                                <div class="container">
                                                    <div class="row col-lg-12">
                                                        <form class="data-form" id="userDataForm<?php echo $user_ID; ?>" autocomplete="on" method="PUT">
                                                            <div class="col-md-4">
                                                                <label for="salutation">Anrede: *</label><br>
                                                                <select name="salutation" id="salutationRegistration<?php echo $user_ID; ?>" style=" margin-bottom: 15px;"
                                                                    class="form-control">
                                                                    <option value="Frau"<?php if($userObj->getSalutation()=='Frau') {echo " selected";}  ?>>Frau</option>
                                                                    <option value="Herr"<?php if($userObj->getSalutation()=='Herr') {echo " selected";}  ?>>Herr</option>
                                                                    <option value="Divers"<?php if($userObj->getSalutation()=='Divers') {echo " selected";};  ?>>Divers</option>
                                                                </select>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="firstName">Vorname:</label>
                                                                    <input type="text" name="firstName" id="firstNameRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getFirstName() ?>" style=" margin-bottom: 15px;"
                                                                        class="form-control" required>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="lastName">Nachname:</label>
                                                                    <input type="text" name="lastName" id="lastNameRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getLastName() ?>"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="md-6">
                                                                    <label for="address">Adresse:</label><br>
                                                                    <input type="text" name="address" id="addressRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getAddress() ?>"style=" margin-bottom: 15px;"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="postcode">PLZ:</label><br>
                                                                    <input type="int" name="postcode" id="postcodeRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getpostcode() ?>"style=" margin-bottom: 15px;"
                                                                        class="form-control" required>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="location">Ort:</label><br>
                                                                    <input type="text" name="location" id="locationRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getLocation() ?>"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="email">E-Mail-Adresse:</label><br>
                                                                    <input type="email" name="email" id="emailRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getEmail() ?>"style=" margin-bottom: 15px;"
                                                                        class="form-control" required>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="username">Benutzername:</label><br>
                                                                    <input type="text" name="username" id="usernameRegistration<?php echo $user_ID; ?>" selected value="<?php echo $userObj->getUsername() ?>"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckisActive<?php echo $user_ID; ?>" style=" margin-bottom: 15px;" <?php ($userObj->getActive()==1) ?  ($check= "checked") : ($check= "unchecked"); echo $check;?>>
                                                                        <label class="form-check-label" for="flexSwitchCheckDefault">Active</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckisAdmin<?php echo $user_ID; ?>" style=" margin-bottom: 15px;" <?php ($userObj->getAdmin()==1) ?  ($check= "checked") : ($check= "unchecked"); echo $check;?>>
                                                                        <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    
                                                                    <button type="button" onclick="updateUser(<?php $callerRole = 1; echo $user_ID .','. $callerRole ;?>)" class="btn btn-warning" name="btnSubmitUpdateData" class="btn btn-primary" id="btnSubmitUpdateData">Update Daten</button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="button" onclick="deleteUser(<?php echo $user_ID ;?>)" class="btn btn-danger" name="btnSubmitDeleteData" class="btn btn-primary"
                                                                        id="btnSubmitDeleteData">Lösche User</button>
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
                                <?php //get mod__ with Bezahldetails and hide it. link to spacer B.
                                // get spacer for Address Details and linl
                                // Add button to MODIFY or DELETE user. -> get mod__ with All fields editable and Safe/confirm Button... ?>
                        <!-- Mod___ show details -->
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
             <script> console.log("DONE With USER TABLE") </script>
            </tbody>
        </table>
         
    </div>                                                               
</div>
                
    </main>


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