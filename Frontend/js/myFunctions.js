$(document).ready(function () {

    console.log("Ready ... DOM loaded!");
    loadNavBar();

    // when clicked button with id="btnSubmitLogin" in login.php call function loginUser()"
    $("#btnSubmitLogin").on("click", function() {
        loginUser();
    });

    $("#btnSubmitRegistration").on("click", function () {
        registerUser();
    });

    $("#navLogout").on("click", function() {
        logoutUser();
    });

    // load user data in profile.php
    loadUserData();

    // load order overview for customer in orderOverviewCustomer.php
    loadOrderOverviewCustomer();

    // when clicked button from class showOrderDetails fill page invoice.php
    $(".showOrderDetails").on("click", function() {
        var orderDetailsForOrderId = $(this).attr("id");
        showOrderDetails(orderDetailsForOrderId);

        // if subtable is visible, hide it
        if ($(this).hasClass("active")) {
            $(".subtable-row").hide();
            $(this).removeClass("active");
        } else {
            // if subtable is not visible, show it
            $(".subtable-row").hide();
            $(this).addClass("active");
            $(this).closest("tr").next().show();
        }
    });

    // when clicked button from class showInvoiceCustomer fill page invoice.php
    $(".showInvoiceCustomer").on("click", function() {
        var invoiceForOrderNo = $(this).attr("id");
        // link to page invoice.php
        window.location.href = "../sites/invoice.php";
        createInvoice(invoiceForOrderNo);
    });
    
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
            saveEditedUserData();    
        });
    
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

    console.log("session variables:")
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
    // var creditCard = $("#creditCardRegistration").val();

    // Client validation
    if (salutation == null || firstName == "" || lastName == "" || address == "" || postcode == "" || location == "" || email == "" || username == "" || password == "" || passwordConfirmed == "") {
        console.log("Client validation failed!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    // Client - Email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        console.log("Invalid email format!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Ungültige E-Mail-Adresse!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    // Client - Password validation
    if (password.length < 8) {
        console.log("Password is too short!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Das Passwort muss mindestens 8 Zeichen lang sein!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
    if (!passwordRegex.test(password)) {
        console.log("Password is invalid!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Das Passwort muss mindestens einen Großbuchstaben, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen enthalten!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    if (password != passwordConfirmed) {
        console.log("Password and passwordConfirmed stimmen nicht überein!");
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Passwörter stimmen nicht überein!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    password = hashPasswordWithSHA512(password);
    
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
        // creditCard: creditCard
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
            if(response == "User exists") {
                alert('Der Benutzername ist bereits vergeben, bitte wählen Sie einen anderen.');
                window.location.href = "../sites/index.php";
            }else{

            alert('Sie wurden erfolgreich registriert, bitte loggen Sie sich ein, um fortzufahren.');
            window.location.href = "../sites/index.php";
        }

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

            alert('Sie wurden erfolgreich eingeloggt.');
            window.location.href = "../sites/index.php";

        },
        error: function (e) {
            var errorMessage = e.responseText;           
            console.log(errorMessage);
            alert(errorMessage);
            window.location.href = "../sites/login.php";

            // Fehlermeldung anzeigen oder andere Aktionen durchführen
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
    // var creditCard = profileUserData['creditCard'];
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
    // $("#creditCardProfile").val(creditCard);
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

/* function getUserData3(targetDiv){
    const parent = document.getElementById("dynComponentDiv");
    $("#dynComponentDiv").load('../../Backend/logic/services/UserAdmView.php');
  } */
  function getUserData2(targetDiv){
    var link='../../Backend/logic/services/UserAdmView.php';
    $.ajax({
        type:   "POST",
        url:    link,
        dataType: "html",
        success:function(data) {
            $("#dynComponentDiv").html(data);
        }
    });
    return false;  // for good measure
};

function fetchAllOrders(targetDiv){
    var link='../../Backend/logic/services/OrderAdminView.php';
    $.ajax({
        type:   "POST",
        url:    link,
        dataType: "html",
        success:function(data) {
            $("#dynComponentDivUsr").html(data);
        }
    });
    return false;  // for good measure
};


// ************************************************************
//          SAVE EDITED USER DATA
// ************************************************************

function saveEditedUserData() {

    var passwordForSavingProfile = prompt("Bitte geben Sie Ihr Passwort ein:");
    passwordForSavingProfile = hashPasswordWithSHA512(passwordForSavingProfile);
    var passwordCheck = checkPasswordForSavingProfile(passwordForSavingProfile);

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
        // var creditCard = $("#creditCardProfile").val();
        // var active = $("#activeProfile").val();
        // var admin = $("#adminProfile").val();

        if (salutation == null || firstName == "" || lastName == "" || address == "" || postcode == "" || location == "" || email == "" || username == "" || password == "") {
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
            // creditCard: creditCard,
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
//          USER-ADMIN routines (can reduce and combine with 
//                  earlier functions if time permits)
// ************************************************************

function updateUser(callerID, callerRole){ //formID received to identify which form in modals was submitting the call
    var callerRole;
    var user, userId;
    var password, passwordConfirmed, active, admin; 
  
    var userId = callerID; //hidden tag with userId on page
    //var salutation= document.getElementById("salutationRegistration" + callerID).value; 
    var firstName= document.getElementById("firstNameRegistration" + userId).value;
    var lastName= document.getElementById(("lastNameRegistration" + userId)).value;
    var address= document.getElementById("addressRegistration" + userId).value;
    var postcode= document.getElementById("postcodeRegistration" + userId).value;
    var location= document.getElementById("locationRegistration" + userId).value;
    var email= document.getElementById("emailRegistration" + userId).value;
    var username= document.getElementById("usernameRegistration" + userId).value;
    if(callerRole==1){ //AdminRole
       active= document.getElementById("flexSwitchCheckisActive"+ userId).checked==true ? 1 : 0;
       admin= document.getElementById("flexSwitchCheckisAdmin" + userId).checked ? 1 : 0;
    
    } else
    {//userRole
       password = document.getElementById("passwordRegistration" + userId).value;
       passwordConfirmed = document.getElementById("passwordConfirmedRegistration" + userId).value;
    }
  
    var x = document.getElementById("salutationRegistration" + userId); //cannot be called inside options call below...
    var salutation= x.options[x.selectedIndex].text;
  
     user = {
      userId: userId,
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
      active: active, 
      admin: admin
  }
  
  if(userDataValidation(user)==false) return;  //do not continue
  console.log(user);
  
  $.ajax({
    type: "POST",
    url: "/Backend/api.php",
    data: {
        user: user,
        callerRole: callerRole 
    },
    dataType: "html",
    cache: false,
    success: function (response) {
  
          console.log("Response from updateUser:");
          console.log("AJAX:" . response);
          
         
          //now updating the table and inserting into the prepared DIV
         
            var btn=document.getElementById("dismissbtn"+callerID);
            btn.click();
          
          getUserData2("#dynComponentDiv");
       
          //To-DO: innerHTML call update and open this users modal?  
          //alternatively: open adminUser or UserService depending on calling page...
          //window.location.href = "../sites/index.php";
      },
      error: function () {
        console.log("AJAX:" + response);
        console.log("AJAX:" + "Error response in updateUser:");
      }
  });
  }
  
  function userDataValidation(user){
    // Client validation
    if (user.salutation == null || user.firstName == "" || user.lastName == "" || user.address == "" || user.postcode == "" || user.location == "" || user.email == "" || user.username == "" || user.password == "" || user.passwordConfirmed == "" || user.paymentMethodDetail == "" ||user.paymentMethodNum<=0 ) {
      console.log("Client validation failed!");
      $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
      // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
      return false;
    }
    
    if (user.password != null && user.password != user.passwordConfirmed) {
      console.log("Password and passwordConfirmed stimmen nicht überein!");
      $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Passwörter stimmen nicht überein!</p>");
      // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
      return false;
    }
    }
    function deleteUser(callerID){
        console.log("starting deleteUser function()");
        var userId=callerID;
        //var userId = $("#affected_userID").val(); //hidden tag with userId on page.changed to parameter
        $.ajax({
          type: "GET",
          url: "../../Backend/api.php",
          data: {
              deleteId: userId,
          },
          dataType: "html",
          cache: false,
          success: function (response) {
      
              console.log("Response from deleteUser:");
              //console.log(response);
              alert(response);
              //To-DO: innerHTML call update and open this users modal?  
              //alternatively: open adminUser or UserService depending on calling page...
              //window.location.href = "../sites/index.php";
              var btn=document.getElementById("dismissbtn"+callerID);
                btn.click();
              
              getUserData2("#dynComponentDiv");
      
          },
          error: function () {
            console.log("Error in deleteUser JS");
          }
      });
        
      }

      function IsNumeric(val) {
        return Number(parseFloat(val)) === val;
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
        var orderId = orderData[i]['orderId'];
        var orderDate = orderData[i]['orderDate'];
        orderDate = orderDate.split(" ")[0];
        var deliveryDate = orderData[i]['deliveryDate'];
        deliveryDate = deliveryDate.split(" ")[0];
        var deliveryAddress = orderData[i]['deliveryAddress'];
        var deliveryStatus = orderData[i]['deliveryStatus'];
        var quantity = orderData[i]['quantity'];
        var totalPrice = orderData[i]['totalPrice'] + " EUR";

        $("#orderOverviewCustomerTableBody").append("<tr id='rowOrderId" + orderId +"'><td>" + orderId + "</td><td>" + orderDate + "</td><td>" + deliveryDate + "</td><td>" + deliveryAddress + "</td><td class='deliveryStatus" + deliveryStatus + "'>" + deliveryStatus + "</td><td>" + quantity + "</td><td>" + totalPrice + "</td><td><button type='button' id='orderDetails" + orderId + "' class='btn btn-primary showOrderDetails'>Details</button></td><td><button type='button' id='invoiceByOrderId" + orderId + "' class='btn btn-primary showInvoiceCustomer'>Rechnung drucken</button></td></tr>");
    }
}


// ************************************************************
//          CREATE INVOICE PAGE
// ************************************************************

function createInvoice(invoiceForOrderNo) {

    console.log("createInvoice() reached in myFunctions.js");

    var appendOrderDetails = "";
    var totalPrice = 0;

   var clickedOrderId = parseInt(invoiceForOrderNo.match(/\d+/)[0]);
   console.log("clickedOrderId: " + clickedOrderId);

    // add customer data to invoice
    var customerData = getUserData();
    var customerSalutation = customerData['salutation'];
    var customerFirstName = customerData['firstName'];
    var customerLastName = customerData['lastName'];
    var customerAddress = customerData['address'];
    var customerPostcode = customerData['postcode'];
    var customerLocation = customerData['location'];
    var customerId = customerData['userid'];

    console.log("customerSalutation: " + customerSalutation);

    // create invoice customer data
    $("#invoiceCustomerSalutation").text(customerSalutation);
    $("#invoiceCustomerName").text(customerFirstName + " " + customerLastName);
    $("#invoiceCustomerAddress").text(customerAddress);
    $("#invoiceCustomerZIPLocation").text(customerPostcode + " " + customerLocation);

    //$("#invoiceCustomerData").append("<p>" + customerSalutation + "</p><p>" + customerFirstName + " " + customerLastName + "</p><p>" + customerAddress + "</p><p>" + customerPostcode + " " + customerLocation + "</p>");

    // create random invoice number
    var min = 1;
    var max = 100;
    var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;

    // invoice date: date today
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    // create invoice details
    $("#invoiceDate").text("Rechnungsdatum: " + dd + "." + mm + "." + yyyy);
    //$("#invoiceDate").text("Rechnungsdatum: " + dd + "." + addLeadingZero(mm) + "." + addLeadingZero(yyyy));
    $("#invoiceNumber").text("Rechnungs-Nr.: " + randomNumber);
    $("#invoiceCustomerId").text("Kunden-Nr.: " + customerId);
    $("#invoiceOrderId").text("Bestell-Nr.: " + clickedOrderId);
    $("#invoiceDeliveryDate").text("Lieferdatum: " + dd + "." + mm + "." + yyyy);

    // get array with order data
    var orderData = getOrderDataByOrderId(clickedOrderId);
    totalPrice = orderData['totalPrice'];

    var orderDetails = getOrderDetails(clickedOrderId);


    // get every item from array order details by iterating through it and append it to table
    for (var i = 0; i < orderDetails.length; i++) {
        var pos = i + 1;
        var orderDetailsArticleQuantity = orderDetails[i]['quantity'];
        var orderDetailsArticleId = orderDetails[i]['itemId'];

        console.log("orderDetailsArticleQuantity: " + orderDetailsArticleQuantity);

        // get book details for every order position
        var bookDetails = getBookDetails(orderDetailsArticleId);

        console.log("bookDetails: " + bookDetails);

        var bookDetailsIsbn = bookDetails[0]['isbn'];
        var bookDetailsTitle = bookDetails[0]['titel'];
        var bookDetailsAutor = bookDetails[0]['autor'];
        var bookDetailsPrice = bookDetails[0]['preis'];
        var totalPerPos = orderDetailsArticleQuantity * bookDetailsPrice;

        console.log("bookDetailsIsbn: " + bookDetails[0]['isbn']);

        appendOrderDetails = appendOrderDetails + "<tr><td>" + pos + "</td><td>" + bookDetailsIsbn + "</td><td>" + bookDetailsTitle + "</td><td>" + bookDetailsAutor + "</td><td>" + bookDetailsPrice + " EUR</td><td>" + orderDetailsArticleQuantity + "</td><td class='totalPricePerPosition'>" + totalPerPos + " EUR</td></tr>";

    }

    $("#invoiceTableBody").append(appendOrderDetails);

    var subTotal = totalPrice / 1.10;
    var tax = totalPrice - subTotal;
    $("#subtotal").text(subTotal);
    $("#tax").text(tax);
    $("#total").text(totalPrice);

}




// ************************************************************
//          GET ALL ORDER DATA FOR SPECIFIC CUSTOMER
// ************************************************************

function getOrderData() {

    var orderData = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getOrderData",
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {
            console.log("Response from api.php in getOrderData():");
            console.log(response);
            orderData = response;
            
        },

        error: function (e) {
            // Error handling
            console.log("Error in error function of getOrderData()");
        }
    });

    return orderData;    

}



// ************************************************************
//          GET ORDER DATA BY ORDER ID
// ************************************************************

function getOrderDataByOrderId(orderId) {

    var orderData = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getOrderDataByOrderId",
        data: {
            orderId: orderId
        },
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {
            console.log("Response from api.php in getOrderDataByOrderId():");
            console.log(response);
            userData = response;            
        },

        error: function () {
            // Error handling
            console.log("Error in error function of getOrderDataByOrderId()");
        }
    });

    return orderData;    

}




// ************************************************************
//          SHOW ORDER DETAILS IN SUBTABLE
// ************************************************************

function showOrderDetails(orderDetailsForOrderId) {

    var appendOrderDetails = "";

   var clickedOrderId = parseInt(orderDetailsForOrderId.match(/\d+/)[0]);
   console.log("clickedOrderId: " + clickedOrderId);

   var orderDetails = getOrderDetails(clickedOrderId);

   // get every item from array by iterating through it and append it to table
    for (var i = 0; i < orderDetails.length; i++) {
        var pos = i + 1;
        var orderDetailsArticleQuantity = orderDetails[i]['quantity'];
        var orderDetailsArticleId = orderDetails[i]['itemId'];

        console.log("orderDetailsArticleQuantity: " + orderDetailsArticleQuantity);

        // get book details for every order position
        var bookDetails = getBookDetails(orderDetailsArticleId);

        var bookDetailsIsbn = bookDetails[0]['isbn'];
        var bookDetailsTitle = bookDetails[0]['titel'];
        var bookDetailsAutor = bookDetails[0]['autor'];
        var bookDetailsPrice = bookDetails[0]['preis'];
        var totalPerPos = orderDetailsArticleQuantity * bookDetailsPrice;

        appendOrderDetails = appendOrderDetails + "<tr><td>" + pos + "</td><td>" + bookDetailsIsbn + "</td><td>" + bookDetailsTitle + "</td><td>" + bookDetailsAutor + "</td><td>" + bookDetailsPrice + " EUR</td><td>" + orderDetailsArticleQuantity + "</td><td class='totalPricePerPosition'>" + totalPerPos + " EUR</td></tr>";

    }

    $("#rowOrderId" + clickedOrderId).after("<tr class='subtable-row'><td colspan='8'><div class='subtable'><table class='table table-striped'><thead><tr><th scope='col'>Pos</th><th scope='col'>ISBN</th><th scope='col'>Titel</th><th scope='col'>Autor</th><th scope='col'>Einzelpreis</th><th scope='col'>Menge</th><th scope='col'>Zeilensumme</th></tr></thead><tbody></tbody>" + appendOrderDetails + "</tbody></table></div></td></tr>");

}


// ************************************************************
//          GET ORDER DETAILS BY ORDER ID
// ************************************************************

function getOrderDetails(clickedOrderId) {

    var orderDetails = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getOrderDetails",
        data: {
            clickedOrderId: clickedOrderId
        },
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {
            console.log("Response from api.php in getOrderDetails():");
            console.log(response);
            orderDetails = response;            
        },

        error: function (e) {
            // Error handling
            console.log("Error in error function of getOrderDetails()");
        }
    });

    return orderDetails; 
 
 }



 // ************************************************************
//          GET BOOK DETAILS BY ARTICLE / ITEM ID
// ************************************************************

function getBookDetails(orderDetailsArticleId) {

    var bookDetails = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getBookDetails",
        data: {
            orderDetailsArticleId: orderDetailsArticleId
        },
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {
            console.log("Response from api.php in getBookDetails():");
            console.log(response);
            bookDetails = response;
            console.log("isbn from bookDetails: " + bookDetails[0]['isbn']);            
        },

        error: function (e) {
            // Error handling
            console.log("Error in error function of getBookDetails()");
        }
    });

    return bookDetails; 
 
 }





// ************************************************************
//          HASH PASSWORD WITH SHA512
// ************************************************************

function hashPasswordWithSHA512(password) {
    var hashedPassword = CryptoJS.SHA512(password).toString();
    return hashedPassword;
}


// ************************************************************
//          Denise
// ************************************************************


// Funktion zum Laden der Produkte basierend auf der ausgewählten Kategorie
function loadProducts(category) {
    $.ajax({
      url: '../../Backend/logic/server.php',
      type: 'GET',
      data: { category: category },
      dataType: 'json',
      success: function(response) {
        // Kategorienamen aktualisieren
        $('#category-name').text(response.category);

        // Produkte auf der Seite anzeigen
        var productsContainer = $('#products-container');
        productsContainer.empty();
        response.products.forEach(function(product) {
          var html = '<div class="product">' +
                     '<h3>' + product.titel + '</h3>';

          // Überprüfen, ob eine Bild-URL vorhanden ist
          if (product.image_url) {
            html += '<img src="' + product.image_url + '" alt="' + product.titel + '">';
          }

          html += '<p>Preis: ' + product.preis + '€' + '</p>';

          if (product.bewertung === 0) {
            html += '<p>Bewertung: Keine Bewertung</p>';
          } else {
            html += '<p>Bewertung: ' + product.bewertung + '</p>';
          }

          html += '</div>';
          html += '<button type="button" class="btn btn-secondary add-to-cart" data-product-id="' + product.id + '">In den Warenkorb</button>';

          html += '<hr>';

          html += '<br><br>';

          productsContainer.append(html);
        });

        // Auf den Klick des "In den Warenkorb" Buttons reagieren
        $('.add-to-cart').click(function() {
          var productId = $(this).data('product-id');
          addToCart(productId);
        });
      }
    });
  }

  // Funktion zum Hinzufügen des ausgewählten Produkts in den Warenkorb
function addToCart(productId) {
    $.ajax({
      url: '../../Backend/logic/services/addToCart.php',
      type: 'POST',
      data: { product_id: productId },
      success: function(response) {
    // Die Antwort enthält die Anzahl der Produkte im Warenkorb
    var count = parseInt(response);
    // Aktualisieren Sie die Anzeige der Warenkorb-Anzahl 
    $('#cart-count').text(count);
    
    // Speichern Sie den aktuellen Stand der Warenkorb-Anzahl in einer Session-Variablen
    $.ajax({
      url: '../../Backend/logic/services/save_cart_count.php', // Pfad zum serverseitigen Skript zum Speichern der Warenkorb-Anzahl
      type: 'POST',
      data: { cart_count: count },
      success: function(response) {
        // Erfolgsbehandlung
      },
      error: function(xhr, status, error) {
        // Fehlerbehandlung
      }
    });
  },
  error: function(xhr, status, error) {
    // Fehlerbehandlung
  }
    });
  
  }


  function searchNav(query) {
  // Make an AJAX request to the server
  $.ajax({
    url: '../../Backend/logic/search.php', // Replace with the correct path to your server-side search script
    type: 'POST',
    data: { query: query },
    dataType: 'json',
    success: function(response) {
      // Clear previous search results
      $('#search-results').empty();

      // Iterate through the response and display the results
      for (var i = 0; i < response.length; i++) {
        var result = response[i];
        console.log(result);
        $('#search-results').append('<div>' + result.titel + '</div>');
      }
    },
    error: function() {
      console.log('Error occurred during search');
    }
  });
}

function dragAndDrop(image_url) {
    $.ajax({
        url: '../../Backend/logic/services/getIdFromImage.php',
        type: 'POST',
        data: { image_url: image_url },
        success: function(response) {
            productId = response;
            addToCart(productId);
    
            // Create a new element to display the dropped product
            var productElement = $('<div class="dropped-product"></div>');
    
            // Append the product element to the cart drop target
            $('#cart-drop-target').append(productElement);
        }
    });
    }

    // ************************************************************
//          BOOK-PRODUCT-ADMIN routines 
// ************************************************************


function deleteBookFromDB(bookId){

    console.log("Deleting item " + bookId);
    $.ajax({
      type: "GET",
      url: "/Backend/api.php",
      data: {
          deleteBook: bookId,
      },
      dataType: "json",
      cache: false,
      success: function (response) {
    
            console.log("Response from updateBook:");
            console.log("AJAX:" + response);
            //now updating the table and inserting into the prepared DIV

              alert("Daten gelöscht")
            //getUserData2("#dynComponentDivBook");
            //window.location.href = "../sites/index.php";
        },
        error: function () {
      
          console.log("AJAX:" + "Error response in updateBook:");
        }
    });
}

function updateProductToDB(bookId) {

    var title=  document.getElementById("bookTitleform" + bookId).value;
    var author= document.getElementById("authorform" + bookId).value;
    var rating= document.getElementById("ratingform" + bookId).value;
    var isbn=   document.getElementById("isbnform" + bookId).value;
    var genre=  document.getElementById("genreform" + bookId).value;
    var language= document.getElementById("languageform" + bookId).value;
    var price=    document.getElementById("priceform" + bookId).value;
    var  descr=   document.getElementById("bookinfoform" + bookId).value;
    var stock=    document.getElementById("stockform" + bookId).value;
    var  image=   document.getElementById("imgpathformA" + bookId).value;
    var bookId=    bookId
   
    book={
    
     title :    title,
     author:    author,
     rating:    rating,
     isbn:      isbn,
     genre:     genre,
     language:  language,
     price:     price,
     descr:     descr,
     stock:     stock,
     image:     image,
     bookId:    bookId
}

//validation
if(document.getElementById("bookIdform"+bookId).checkValidity()== false){
    
}
if (title == "" || author == "" || price=="" || stock=="" || rating=="" || (!Number.isInteger(parseInt(rating))) || (!Number.isInteger(parseInt(stock)))) {
    console.log("Input validation failed!");
    $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Bitte Plichtfelder (korrekt) ausfüllen!</p>");
    alert("Eingabe nicht vollständig");
    return;
}
  imgChanged= localStorage.getItem("isNewBookImage");
  console.log("Entering api call with imgagechange=" + imgChanged + " for "  + book);
  $.ajax({
    type: "POST",
    url: "/Backend/api.php",
    data: {
        updateBook: book,
        imgChanged: imgChanged 
    },
    dataType: "json",
    cache: false,
    success: function (response) {
  
          console.log("Response from updateBook:");
          console.log("AJAX:" + response);
          
         
          //now updating the table and inserting into the prepared DIV
         
            alert("Daten gespeichert")
          
          //getUserData2("#dynComponentDivBook");
       
          //To-DO: innerHTML call update and open this users modal?  
          //alternatively: open adminUser or UserService depending on calling page...
          //window.location.href = "../sites/index.php";
      },
      error: function () {
    
        console.log("AJAX:" + "Error response in updateBook:");
      }
  });
  }

  
// ************************************************************
//          FILTERING PRODUCT and Categories ADMIN and SHOPPING
// ************************************************************

function filterUserTable( tableid, filterVal){
 
    tag = document.getElementById("userListFilter1");
    filterText= tag.options[tag.selectedIndex].text.toUpperCase();
      let table = document.getElementById(tableid);
      tr = table.getElementsByTagName("tr");
      len=tr.length;
    
      //check which column for comparing filter applies
      switch(filterVal) {
        case "1":
            case "2":
                case "3":
                    case "4":
                        col=4;
                        break;
        case "5":
            case "6":
                case "7":
                    col=5;
                    break;
        default:
            col=7;
      }
      // Loop through all table rows, and hide those who don't match the search query
      for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[col];
            // first need to filter the dummy-empty book 
                bookID = document.getElementById(tableid).rows[i].cells.item(0).innerHTML;
                if(bookID =="0"){
                    tr[i].style.display = "none";
                    continue;
                }
            // if empty/all filer, show all except Blank
                if(filterVal==""){
                    
                        tr[i].style.display = "";
                        continue;
                }
                if (td ) { //text compare filters
                    txtValue = td.textContent || td.innerText;
                    txtValue=txtValue.toUpperCase();
                    
                    if(col<7){
                        if (txtValue.localeCompare(filterText)==0 ) {
                                tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    } else {
                        
                        if(txtValue.includes(filterText) ){
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
    
                
                }
            }
        }
    
    // ************************************************************
    //          Img Upload utilities
    // ************************************************************
    
    function preview(id) {
        document.getElementById('imgpathformA' + id).value =document.getElementById('imgfileform' + id).value;
        document.getElementById('frame' + id).src = URL.createObjectURL(event.target.files[0]);
        localStorage.setItem("isNewBookImage", true);
        
    }
    
    function clearImageUploadInput(id, oldImg) {
        document.getElementById('imgfileform' + id).value = null;
        document.getElementById('imgpathformA' + id).value =oldImg;
        document.getElementById('frame' + id).src = oldImg;
        localStorage.setItem("isNewBookImage", false);
    
    }