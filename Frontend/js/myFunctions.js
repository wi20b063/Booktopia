$(document).ready(function () {

    console.log("Ready ... DOM loaded!");
    loadNavBar();

    // when clicked button with id="btnSubmitLogin" in login.php call function loginUser()"
    $("#btnSubmitLogin").on("click", function() {
        // console.log("loginUser() called");
        loginUser();
    });

    $("#btnSubmitRegistration").on("click", function () {
        // console.log("registerUser() called");
        registerUser();
    });

    $("#navLogout").on("click", function() {
        // console.log("logoutUser() called");
        logoutUser();
    });

    // load user data in profile.php
    loadUserData();
    
    $("#btnEditProfile").on("click", function() {
        // make profile form fields editable
        var elementsInput = document.getElementsByClassName("editableProfile");
        for (var i = 0; i < elementsInput.length; i++) {
            elementsInput[i].disabled = false;
        }

        // hide button "Bearbeiten" + create button "save" and "cancel"
        $("#btnEditProfile").hide();
        $("#buttonDivProfile").append("<button type='submit' class='btn btn-primary' id='btnSaveProfile'>Speichern</button>");
        $("#buttonDivProfile").append("&nbsp;&nbsp;<a href='profile.php' class='btn btn-secondary' id='btnCancelEditeProfile'>Abbrechen</a>");

        $("#btnSaveProfile").on("click", function() {
            // var passwordForSavingProfile = prompt("Bitte geben Sie Ihr Passwort ein:");
            saveEditedUserData();    
        });
    
    });

    // load order overview for customer in orderOverviewCustomer.php
    // loadOrderOverviewCustomer();

    // when clicked button from class showInvoiceCustomer fill page invoice.php
    $(".showInvoiceCustomer").on("click", function() {
        var invoiceForOrderNo = $(this).attr("id");
        createInvoice(invoiceForOrderNo);
    });

    
});



// ************************************************************
//          LOAD NAVBAR
// ************************************************************

// function to define which user can see which navbar items
function loadNavBar() {

    var userSession = getSessionVariables();

    var sessionUsername = userSession['sessionUsername'];
    var sessionUserid = userSession['sessionUserid'];
    var sessionAdmin = userSession['sessionAdmin'];
    var sessionActive = userSession['sessionActive'];

    console.log("user description:")
    console.log("username: " + sessionUsername);
    console.log("userid: " + sessionUserid);
    console.log("admin: " + sessionAdmin);
    console.log("active: " + sessionActive);

    if ((sessionUsername != null || sessionUsername != undefined) && sessionActive != 0) {
        
        if (sessionAdmin == 1) {
            // show logout, profile, products, customers, vouchers / hide register, login, shopping cart            
            $("#navRegister").hide();
            $("#navShoppingCart").hide();
            $("#navLogin").hide();

            // append welcome message with <li> and <span> to id="navSearch"
            $("#navSearch").after("<li class='nav-item' id='navWelcomeUser'><span class='nav-link'><i>Willkommen " + sessionUsername + "!</i></span></li>");
            
                
        } else if (sessionAdmin == 0){
            // show logout, profile, shopping cart / hide register, login, products, customers, vouchers
            $("#navRegister").hide();                
            $("#navManageProducts").hide();
            $("#navManageCustomers").hide();
            $("#navManageVouchers").hide();
            $("#navLogin").hide();

            // append welcome message with <li> and <span> to id="navSearch"
            $("#navSearch").after("<li class='nav-item' id='navWelcomeUser'><span class='nav-link'><i>Willkommen " + sessionUsername + "!</i></span></li>");
    
        }

    } else {
        // show register and login / hide logout, profile, products, customers, vouchers
        $("#navManageProducts").hide();
        $("#navManageCustomers").hide();
        $("#navManageVouchers").hide();
        $("#navMyAccountDropdown").hide();
        $("#navLogout").hide();

    }
    
}


// ************************************************************
//          REGISTER USER
// ************************************************************

function registerUser() {

    var salutation = $("#salutationRegistration").val();
    var firstName = $("#firstNameRegistration").val();
    var lastName = $("#lastNameRegistration").val();
    var address = $("#addressRegistration").val();
    var postcode = $("#postcodeRegistration").val();
    var location = $("#locationRegistration").val();
    var email = $("#emailRegistration").val();
    var username = $("#usernameRegistration").val();
    var password = $("#passwordRegistration").val();
    var passwordConfirmed = $("#passwordConfirmedRegistration").val();
    var creditCard = $("#creditCardRegistration").val();
    
    // console.log("registerUser() called");
    // console.log("password: " + password);
    // console.log("passwordConfirmed: " + passwordConfirmed);

    // Client validation
    if (salutation == null || firstName == "" || lastName == "" || address == "" || postcode == "" || location == "" || email == "" || username == "" || password == "" || passwordConfirmed == "" || creditCard == "") {
        console.log("Client validation failed!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    // !!!! noch offen E-Mail Prüfung / password Regex / creditCard Regex

    if (password != passwordConfirmed) {
        console.log("Password and passwordConfirmed stimmen nicht überein!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Passwörter stimmen nicht überein!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    password = hashPasswordWithSHA512(password);
    // console.log("HashedPassword before ajax call:");
    // console.log(password);
    
    var user = {
        salutation: salutation,
        firstName: firstName,
        lastName: lastName,
        address: address,
        postcode: postcode,
        location: location,
        email: email,
        username: username,
        password: password,
        passwordConfirmed: passwordConfirmed,
        creditCard: creditCard
    }

    console.log(user);

    $.ajax({
        type: "POST",
        url: "../../Backend/api.php",
        data: {
            user: user
        },
        dataType: "html",
        cache: false,
        success: function (response) {

            console.log("Response from registerUser():");
            console.log(response);
            alert('Sie wurden erfolgreich registriert, bitte loggen Sie sich ein, um fortzufahren.');
            window.location.href = "../sites/index.php";

        },
        error: function () {
        }
    });

    }



// ************************************************************
//          LOGIN USER
// ************************************************************

function loginUser() {

    var username = $("#usernameLogin").val();
    var password = $("#passwordLogin").val();
    // console.log("username: " + username);
    //var rememberMe = $("#rememberMeLogin").prop("checked");
    var rememberMe = $("#rememberMeLogin").is(":checked");
    console.log("rememberMe: " + rememberMe);

    // Client validation username and password
    if (username == "" || password == "") {
        console.log("Client validation failed!");
        $("#errorLogin").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    password = hashPasswordWithSHA512(password);
    // console.log("Login - HashedPassword before ajax call:");
    // console.log(password); 

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?loginUser",
        data: {
            username: username,
            password: password,
            rememberMe: rememberMe
        },
        dataType: "html",
        cache: false,
        success: function (response) {

            // console.log("Response from loginUser():");
            // console.log(response.code);
            /* console.log("1: " + JSON.stringify(code));
            console.log("2: " + message);
            console.log("3: " + array); */
            alert('Sie wurden erfolgreich eingeloggt.');
            window.location.href = "../sites/index.php";

            //if (response === "success") {
                // Erfolgreich eingeloggt
                // Hier kannst du entsprechende Aktionen durchführen, z.B. Weiterleitung zur Startseite

                // Set session variable for remember me
                /* if ($("#rememberMe").is(":checked")) {
                    sessionStorage.setItem("username", username);
                } */

                // Redirect to index.html

                //window.location.href = "../sites/index.php";

            //} else if (response === "error") {
                // Fehler beim Einloggen
                // Hier kannst du eine Fehlermeldung anzeigen oder andere Aktionen durchführen
                // Append error message to id="loginForm"
                //$("#loginForm").append("<p style='color:red; font-weight:bold;'>Fehler beim Einloggen!</p>");
            //} else {
                // Ungültige Antwort
                // Hier kannst du eine Fehlermeldung anzeigen oder andere Aktionen durchführen
                //alert("Ungültige Antwort vom Server");
            //}
        },
        error: function (e) {
            // Fehler beim AJAX-Aufruf

            var errorMessage = e.responseText;           
            console.log(errorMessage);
            alert(errorMessage);
            window.location.href = "../sites/login.php";

            // Hier kannst du eine Fehlermeldung anzeigen oder andere Aktionen durchführen
            //$("#errorLogin").append("<p style='color:red; font-weight:bold;'>Fehler beim Login AJAX Aufruf!</p>");
        }
    });
}



// ************************************************************
//          LOGOUT USER
// ************************************************************    

function logoutUser() {

    console.log("logoutUser() reached in myFunctions.js");

    $.ajax({
        type: "GET",
        // url: "../../Backend/logic/logout.php",
        url: "../../Backend/api.php" + "?logoutUser",
        dataType: "html",
        cache: false,
        success: function (response) {
            //console.log("Response from logoutUser():");
            // console.log(response);
            alert("Sie wurden erfolgreich ausgeloggt.");
            window.location.href = "index.php";
        },

        error: function (e) {
            // Error handling
            console.log("Error in error function of logoutUser()");
            alert("Fehler beim Ausloggen!");
        }
    });
}




// ************************************************************
//          GET SESSION VARIABLES
// ************************************************************

function getSessionVariables() {

    var userSession = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getSession",
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {

            // console.log("Response from api.php in getSessionVariables():");
            // console.log(response);
            userSession = response;
            
        },

        error: function () {
            // Error handling
            console.log("Error in error function of getSessionVariables()");
        }
    });

    return userSession;    

}



// ************************************************************
//          LOAD USER DATA
// ************************************************************

function loadUserData() {

    var profileUserData = getUserData();

    var salutation = profileUserData['salutation'];
    var firstName = profileUserData['firstName'];
    var lastName = profileUserData['lastName'];
    var address = profileUserData['address'];
    var postcode = profileUserData['postcode'];
    var location = profileUserData['location'];
    var email = profileUserData['email'];
    var username = profileUserData['username'];
    var password = profileUserData['password'];
    var creditCard = profileUserData['creditCard'];
    var active = profileUserData['active'];
    var admin = profileUserData['admin'];


    // console.log("userDataUsername: " + username);

    $("#salutationProfile").val(salutation);
    $("#firstNameProfile").val(firstName);
    $("#lastNameProfile").val(lastName);
    $("#addressProfile").val(address);
    $("#postcodeProfile").val(postcode);
    $("#locationProfile").val(location);
    $("#emailProfile").val(email);
    $("#usernameProfile").val(username);
    $("#passwordProfile").val(password);
    $("#creditCardProfile").val(creditCard);
    // $("#activeProfile").val(active);
    // $("#adminProfile").val(admin);

    // add input after label
    // $("#firstNameProfileDiv").append("<input type='text' name='firstName' id='firstNameProfile' placeholder='" + firstName + "' class='form-control' disabled>");

}


// ************************************************************
//          GET USER DATA
// ************************************************************

function getUserData() {

    var userData = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getUserData",
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {

            // console.log("Response from api.php in getUserData():");
            console.log(response);
            userData = response;
            
        },

        error: function () {
            // Error handling
            console.log("Error in error function of getUserData()");
        }
    });

    return userData;
}


// ************************************************************
//          SAVE EDITED USER DATA
// ************************************************************

function saveEditedUserData() {

    var passwordForSavingProfile = prompt("Bitte geben Sie Ihr Passwort ein:");
    passwordForSavingProfile = hashPasswordWithSHA512(passwordForSavingProfile);
    var passwordCheck = checkPasswordForSavingProfile(passwordForSavingProfile);

    // alert("passwordCheck: " + passwordCheck);
    console.log("passwordCheck in saveEditedUserData: " + passwordCheck);

    // if entered password matches password in database save edited user data
    if (passwordCheck) {
        alert("Passwort korrekt!");

        var salutation = $("#salutationProfile").val();
        var firstName = $("#firstNameProfile").val();
        console.log("firstName to be changed: " + firstName);
        var lastName = $("#lastNameProfile").val();
        var address = $("#addressProfile").val();
        var postcode = $("#postcodeProfile").val();
        var location = $("#locationProfile").val();
        var email = $("#emailProfile").val();
        var username = $("#usernameProfile").val();
        var password = $("#passwordProfile").val();
        var creditCard = $("#creditCardProfile").val();
        // var active = $("#activeProfile").val();
        // var admin = $("#adminProfile").val();

        if (salutation == null || firstName == "" || lastName == "" || address == "" || postcode == "" || location == "" || email == "" || username == "" || password == "" || creditCard == "") {
            console.log("Client validation failed!");
            $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
            // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
            return;
        }

        // !!!! noch offen E-Mail Prüfung / password Regex / creditCard Regex


        password = hashPasswordWithSHA512(password);
    
        var editedUser = {
            salutation: salutation,
            firstName: firstName,
            lastName: lastName,
            address: address,
            postcode: postcode,
            location: location,
            email: email,
            username: username,
            password: password,
            creditCard: creditCard,
            // active: active,
            // admin: admin
        }

        $.ajax({
            type: "POST",
            url: "../../Backend/api.php" + "?saveEditedUserData",
            data: {
                editedUser: editedUser
            },
            dataType: "html",
            cache: false,
            success: function (response) {
    
                // console.log("Response from saveEditedUserData():");
                // console.log(response);
                alert('Nutzerdaten wurden erfolgreich geändert.');
                window.location.href = "../sites/profile.php";
    
            },
            error: function () {
            }
        });
        
    } else {

        alert("Falsches Passwort!");

    }

    
}



// ************************************************************
//          CHECK PASSWORD FOR SAVING PROFILE
// ************************************************************

function checkPasswordForSavingProfile(passwordForSavingProfile) {

    var passwordCheck = null;

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?checkPasswordForSavingProfile",
        data: {
            passwordForSavingProfile: passwordForSavingProfile
        },
        dataType: "html",
        cache: false,
        async: false,
        success: function (response) {
            passwordCheck = response;
            // console.log("passwordCheck 1 in checkPasswordForSavingProfile(): " + passwordCheck);

        },
        error: function (e) {

        }        

    });

    return passwordCheck;   
    
}



// ************************************************************
//          LOAD ORDER OVERVIEW FOR CUSTOMER
// ************************************************************

function loadOrderOverviewCustomer() {

    // get array with order data
    var orderData = getOrderData();

    // get every items from array by iterating through it and append it to table
    for (var i = 0; i < orderData.length; i++) {
        var orderNumber = orderData['orderNumber'];
        var orderDate = orderData['orderDate'];
        var orderStatus = orderData['orderStatus'];
        var orderDeliverDate = orderData['orderDeliverDate'];
        var orderTotal = orderData['orderTotal'];
        var orderDetails = orderData['orderDetails'];

        $("#orderOverviewCustomerTable").append("<tr><td>" + orderNumber + "</td><td>" + orderDate + "</td><td class='orderStatus'"
         + orderStatus + ">" + orderStatus + "</td><td>" + orderDeliverDate + "</td><td>" + orderTotal + "</td><td>" 
         + orderDetails + "</td><td><button type='button' id='invoiceOrderNo" + orderNumber + "' class='btn btn-primary showInvoiceCustomer'>Rechnung drucken</button></td></tr>");
    }
}


// ************************************************************
//          GET ALL ORDER DATA
// ************************************************************

/* function getOrderData() {

    var orderData = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getOrderData",
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {

            console.log("Response from api.php in getUserData():");
            console.log(response);
            userData = response;
            
        },

        error: function () {
            // Error handling
            console.log("Error in error function of getUserData()");
        }
    });

    return orderData;    

} */

// ************************************************************
//          GET ORDER DATA BY ORDER NO
// ************************************************************

/* function getOrderData(orderNo) {

    var orderData = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getOrderDataByOrderNo",
        data: {
            orderNo: orderNo
        },
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {

            console.log("Response from api.php in getUserData():");
            console.log(response);
            userData = response;
            
        },

        error: function () {
            // Error handling
            console.log("Error in error function of getUserData()");
        }
    });

    return orderData;    

} */


// ************************************************************
//          SHOW ORDER DETAILS IN MODAL
// ************************************************************

/* function showOrderDetails(ID) {

    $orderDetails = getOrderDataById(ID);


    // append order data to modal "modalOrderDetails"
    $("modalOrderDetails").append(
        "<div class='modal fade' id='showDetails'" + ID + "tabindex='-1' role='dialog' aria-labelledby='statusModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Bestelldetails</h5>
                        <input type='hidden' id='reservationIDstatus' name='reservationIDstatus' value='" + ID + "'></input>
                    </div>
                    <div class='modal-body'>                            
                        <div class='row mb-5 gx-5'>
                            <div class='mb-5 mb-xxl-0'>
                                <div class='bg-secondary-soft px-4 py-5 rounded'>
                                    <div class='row'>
                                        <h4 class='mb-4 mt-0'>Bestelldetails</h4>
                                        <div class='col-md-6'>
                                            <p><strong>Bestellnummer:</strong></p>
                                            <p>" + ID + "</p>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <table class="table tableInvoiceDetails">
                                            <thead>
                                                <tr>
                                                    <th scope='col'>Pos.</th>
                                                    <th scope='col'>Artikel-Nr.</th>
                                                    <th scope='col'>Bezeichnung</th>
                                                    <th scope='col'>Einzelpreis</th>
                                                    <th scope='col'>Menge</th>
                                                    <th scope='col'>Gesamtpreis</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceTableBody">
                                                <tr>
                                                    <th scope=" row">1</th>
                                                    <td>1234</td>
                                                    <td>Der mit dem Wolf tanzt</td>
                                                    <td>16 EUR</td>
                                                    <td>1</td>
                                                    <td>16 EUR</td>
                                                </tr>" +
                                                // for each order item get data
                                            + "</tbody>
                                        </table> 

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Schließen</button>                                                            </div>
                    </div>
                </div>
            </div>
        </div>");

    
    

} */



// ************************************************************
//          CREATE INVOICE PAGE
// ************************************************************

/* function createInvoice(invoiceForOrderNo) {

    // add customer data to invoice
    var customerData = getCustomerData();
    var customerSalutation = customerData['salutation'];
    var customerFirstName = customerData['firstName'];
    var customerLastName = customerData['lastName'];
    var customerAddress = customerData['address'];
    var customerPostcode = customerData['postcode'];
    var customerLocation = customerData['location'];
    var customerId = customerData['userid'];

    $("#invoiceCustomerSalutation").val(customerSalutation);
    $("#invoiceCustomerName").val(customerFirstName + " " + customerLastName);
    $("#invoiceCustomerAddress").val(customerAddress);
    $("#invoiceCustomerZIPLocation").val(customerPostcode + " " + customerLocation);

    // create random invoice number
    var min = 1;
    var max = 100;
    var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;

    // date today
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    $("#invoiceNumber").val("Rechnungs-Nr.: " + randomNumber);
    $("#invoiceDate").val("Rechnungsdatum: " + dd + "." + addLeadingZero(mm) + "." + addLeadingZero(yyyy));
    $("#customerId").val("Kunden-Nr.: " + customerId);

    // get array with order data
    var orderData = getOrderDataById(invoiceForOrderNo);

    // get every items from array by iterating through it and append it to table
    for (var i = 0; i < orderData.length; i++) {
/*         var orderNumber = orderData['orderNumber'];
        var orderDate = orderData['orderDate'];
        var orderStatus = orderData['orderStatus'];
        var orderDeliverDate = orderData['orderDeliverDate'];
        var orderTotal = orderData['orderTotal'];
        var orderDetails = orderData['orderDetails']; */
/*        var orderPosition = orderData['orderPosition'];

        // get information for every order position by iterating through it and append it to table
        for (var j = 0; j < orderPosition.length; j++) {
            var orderPositionArticleNo = orderPosition['articleNo'];
            var orderPositionArticleName = orderPosition['articleName'];
            var orderPositionArticlePrice = orderPosition['articlePrice'];
            var orderPositionArticleQuantity = orderPosition['articleQuantity'];
            var orderPositionArticleTotal = orderPositionArticleQuantity * orderPositionArticlePrice;

            $("#invoiceTableBody").append("<tr><td>" + (j+1) + "</td><td>" + orderPositionArticleNo + "</td><td>" + orderPositionArticleName + "</td><td>" + orderPositionArticlePrice + " EUR</td><td>" + orderPositionProductQuantity + "</td><td class='totalPricePerPosition'>" + orderPositionArticleTotal + " EUR</td></tr>");
        }
    }
} */





// ************************************************************
//          HASH PASSWORD WITH SHA512
// ************************************************************

function hashPasswordWithSHA512(password) {
    var hashedPassword = CryptoJS.SHA512(password).toString();
    return hashedPassword;
}
