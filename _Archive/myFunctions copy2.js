$(document).ready(function () {

    console.log("Ready ... DOM loaded!");
    loadNavBar();

    // when clicked button with id="btnSubmitLogin" in login.php call function loginUser()"
    $("#btnSubmitLogin").on("click", function() {
        console.log("loginUser() called");
        loginUser();
    });

    $("#btnSubmitRegistration").on("click", function () {
        console.log("registerUser() called");
        registerUser();
    });

    $("#navLougout").on("click", function() {
        console.log("logoutUser() called");
        logoutUser();
    });

    loadUserData();

    /* $("#btnEditProfile").on("click", function() {
        var elements = document.getElementsByTagName("input");
        for (var i = 0; i < elements.length; i++) {
            elements[i].disabled = false;
        }
    }); */
    
    $("#btnEditProfile").on("click", function() {
        var elementsInput = document.getElementsByTagName("input");
        for (var i = 0; i < elementsInput.length; i++) {
            elementsInput[i].disabled = false;
        }

        var elementsSelect = document.getElementsByTagName("select");
        for (var i = 0; i < elementsSelect.length; i++) {
            elementsSelect[i].disabled = false;
        }

        $("#btnEditProfile").hide();
        $("#buttonDivProfile").append("<button type='submit' class='btn btn-primary' id='btnSaveProfile'>Speichern</button>");

        $("#btnSaveProfile").on("click", function() {
            // var passwordForSavingProfile = prompt("Bitte geben Sie Ihr Passwort ein:");
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
        $("#navLougout").hide();

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
    
    console.log("registerUser() called");
    console.log("password: " + password);
    console.log("passwordConfirmed: " + passwordConfirmed);

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
    console.log("HashedPassword before ajax call:");
    console.log(password);
    
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

            console.log("Response from loginUser():");
            console.log(response.code);
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
            console.log("Response from logoutUser():");
            console.log(response);
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

            console.log("Response from api.php in getSessionVariables():");
            console.log(response);
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


    console.log("userDataUsername: " + username);

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

            console.log("Response from api.php in getUserData():");
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
    var passwordCheck = checkPassworfForSavingProfile(passwordForSavingProfile);

    // alert("passwordCheck: " + passwordCheck);
    console.log("passwordCheck in saveEditedUserData: " + passwordCheck);

    if (passwordCheck) {
        alert("Passwort korrekt!");

        var passwordForSavingProfile = prompt("Bitte geben Sie Ihr Passwort ein:");
        var salutation = $("#salutationProfile").val();
        var firstName = $("#firstNameProfile").val();
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


        return;
    } else {

        alert("Falsches Passwort!");

        // TO DO Funktion regsiterUser anpassen
        registerUser();
    }

    
        /* var passwordForSavingProfile = prompt("Bitte geben Sie Ihr Passwort ein:");
        var salutation = $("#salutationProfile").val();
        var firstName = $("#firstNameProfile").val();
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
            creditCard: creditCard,
            // active: active,
            // admin: admin
        }
    
        console.log(user);
    
        $.ajax({
            type: "POST",
            url: "../../Backend/api.php" + "?editUserData",
            data: {
                user: user,
                passwordForSavingProfile: passwordForSavingProfile
            },
            dataType: "html",
            cache: false,
            success: function (response) {
    
                console.log("Response from saveEditedUserData():");
                console.log(response);
                alert('Ihre Daten wurden erfolgreich gespeichert.');
                window.location.href = "../sites/profile.php";
    
            },
            error: function () {
            }
        }); */
}



// ************************************************************
//          CHECK PASSWORD FOR SAVING PROFILE
// ************************************************************

function checkPassworfForSavingProfile(passwordForSavingProfile) {

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
            console.log("passwordCheck 1 in checkPassworfForSavingProfile(): " + passwordCheck);

        },
        error: function (e) {

        }        

    });

    return passwordCheck;   

    
}



// ************************************************************
//          HASH PASSWORD WITH SHA512
// ************************************************************

function hashPasswordWithSHA512(password) {
    var hashedPassword = CryptoJS.SHA512(password).toString();
    return hashedPassword;
}
