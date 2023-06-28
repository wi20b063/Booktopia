$(document).ready(function () {

    console.log("Ready ... DOM loaded!");

    //loadHead();
    //loadFooter();
    loadNavBar();

    // when clicked button with id=.... in pages including this script call the relevant function"
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

    $("#btnSubmitUpdateData").on("click", function () {
      console.log("updateUser() called");
      updateUser();
  });

  $("#btnSubmitDeleteData").on("click", function () {
    console.log("deleteUser() called");
    deleteUser();
});

  });


function loadNavBar() {

    var userSession = getSessionVariables();

    console.log(userSession);

    var sessionUsername = userSession['username'];
    var sessionUserid = userSession['userid'];
    var sessionAdmin = userSession['admin'];
    var sessionActive = userSession['active'];

    console.log("username: " + sessionUsername);
    console.log("userid: " + sessionUserid);
    console.log("admin: " + sessionAdmin);
    console.log("active: " + sessionActive);

    if (sessionUsername != null && sessionActive != 0) {
        
        if (sessionAdmin == 1) {
            console.log("returned admin from session.php");
            // show logout, profile, products, customers, vouchers / hide register, login, shopping cart
            /* $("#logout").show();
            $("#profile").show();
            $("#products").show();
            $("#customers").show();
            $("#vouchers").show(); */
            
            $("#navRegister").hide();
            $("#navShoppingCart").hide();
            $("#navLogin").hide();

            // append welcome message with <li> and <span> to id="navSearch"
            $("#navSearch").append("<li class='nav-item' id='navWelcomeUser'><span>Willkkommen " + sessionUsername + "!</span></li>");
            
                
        } else if (sessionAdmin == 0){
    
            console.log("returned user from session.php");
    
    
            // show logout and profile / hide register, login, products, customers, vouchers
            /* $("#logout").show();
            $("#profile").show(); */
    
            $("#navRegister").hide();                
            $("#navManageProducts").hide();
            $("#navManageCustomers").hide();
            $("#navManageVouchers").hide();
            $("#navLogin").hide();
    
        }

    } else {

        console.log("returned guest from session.php");

        // show register and login / hide logout, profile, products, customers, vouchers
        /* $("#register").show();
        $("#login").show(); */

        $("#navManageProducts").hide();
        $("#navManageCustomers").hide();
        $("#navManageVouchers").hide();
        $("#navProfile").hide();
        $("#navLougout").hide();
    }
}

function loginUser() {

    var username = $("#usernameLogin").val();
    var password = $("#passwordLogin").val();

    // Client validation username and password
    if (username == "" || password == "") {
        console.log("Client validation failed!");
        $("#errorLogin").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
        // noch ein hide einfügen, damit Error Nachricht wieder verschwindet
        return;
    }

    console.log("username: " + username);

    password = hashPasswordWithSHA512(password);
    console.log("Login - HashedPassword before ajax call:");
    console.log(password); 

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php",
        data: {
            username: username,
            password: password
        },
        dataType: "html",
        cache: false,
        success: function (response) {
            console.log("Response from loginUser():");
            console.log(response);
            alert('Sie wurden erfolgreich eingeloggt.');
            window.location.href = "../sites/index.php";
        },
        error: function () {
            // Fehler beim AJAX-Aufruf
            // Hier kannst du eine Fehlermeldung anzeigen oder andere Aktionen durchführen
            //$("#errorLogin").append("<p style='color:red; font-weight:bold;'>Fehler beim Login AJAX Aufruf!</p>");
        }
    });
}

function getUserData(targetDiv){
  const parent = document.getElementById("dynComponentDiv");
  $("#dynComponentDiv").load('../../Backend/logic/services/UserAdmin.php');
}

function fetchUserOrders(userId){
  $.ajax({
    type: "GET",
    url: "../../Backend/api.php",
    data: {
        userOrder: userId,
    },
    dataType: "html",
    cache: false,
    success: function (response) {

        console.log("Response from fetchUser:");
        console.log(response);
        $("#dynComponentDivUsr").load(response);

    },
    error: function () {
      console.log("Error in fetchUserOrder JS");
    }
});

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
        
        getUserData("#dynComponentDiv");

    },
    error: function () {
      console.log("Error in deleteUser JS");
    }
});
  
}

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
  url: "../../Backend/api.php",
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
        
        getUserData("#dynComponentDiv");
     
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
    var paymentMethodDetail = $("#paymentMethodDetails").val();
    var paymentMethodNum=$("#payselect1").val();
    var paymentMethod;
    var payMethodDetail;
  
    var paySel=$("#payselect1").selectedIndex;
    var x = document.getElementById("payselect1"); //cannot be called inside options call below...
    var paymentMethodName= x.options[x.selectedIndex].text;

    console.log("Selected ItemNum: ", paymentMethodNum);
    console.log("Selected ItemName: ", paymentMethodName);
    console.log("Selected Details: ", paymentMethodDetail);
    
    console.log("registerUser() called");
    console.log("password: " + password);
    console.log("passwordConfirmed: " + passwordConfirmed);

    console.log("username: " + username);
    password = hashPasswordWithSHA512(password);
    // need to also encrypt  it as we send the entire user object over the ajax call
    passwordConfirmed=hashPasswordWithSHA512(passwordConfirmed);  
    console.log("HashedPassword before ajax call:");
    console.log(password);
    var paymentType={
      userId: null,
      userPaymentItemId: null,
      paymentMethodName: paymentMethodName,
      paymentMethodNum: paymentMethodNum,
      payMethodDetail: paymentMethodDetail

    }
    
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
        paymentType: paymentType
    }
    if(userDataValidation(user)==false) return;  //do not continue
    console.log(user);


    $.ajax({
        type: "POST",
        url: "../../Backend/api.php",
        data: {
            user: user,
            action: "registration"
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
          console.log("Error in registerUser():");
        }
    });

    }


function logoutUser() {

    console.log("logoutUser() reached in myFunctions.js");

    $.ajax({
        type: "GET",
        url: "../../Backend/logic/logout.php",
        dataType: "html",
        cache: false,
        success: function (response) {
            console.log("Response from logoutUser():");
            console.log(response);
            alert('Sie wurden erfolgreich ausgeloggt.');
        },

        error: function () {
            // Error handling
            console.log("Error in error function of logoutUser()");
            alert("Error in error function of logoutUser()");
        }
    });
    window.location.href = "../sites/index.php";
}


function getSessionVariables() {

    var userSession = [];

    $.ajax({
        type: "GET",
        url: "../../Backend/api.php" + "?getSession",
        dataType: "html",
        cache: false,
        success: function (response) {

            console.log("Response from getSessionVariables():");
            console.log(response);

            // !!! BUG respons returns undefined array
            // save responded array received via api.php
            userSession = response;
            console.log("username returned from api.php in function.js " + userSession['sessionUsername']);
            
        },

        error: function () {
            // Error handling
            console.log("Error in error function of getSessionVariables()");
        }
    });

   return userSession;

}

    


function hashPasswordWithSHA512(password) {
    var hashedPassword = CryptoJS.SHA512(password).toString();
    return hashedPassword;
}


/* function validate(input) {
    if (input == "") {
        // add following message to div 'errorRegistration'
        $("#errorRegistration").append("<p style='color:red; font-weight:bold;'>Bitte alle Felder ausfüllen!</p>");
        return;
    }
} */



/* function loadHead() {
    $.ajax({
        type: "GET",
        url: "../Frontend/sites/components/head.html",
        dataType: "html",
        cache: false,
        success: function (response) {
            //$("head").html(response);
            $("head").load("./Frontend/sites/components/head.html");
        }
    });
    //$("head").load("./Frontend/sites/components/head.html");
}

function loadFooter() {
    $.ajax({
        type: "GET",
        url: "../Frontend/sites/components/footer.html",
        dataType: "html",
        cache: false,
        success: function (response) {
            //$("footer").html(response);
            $("footer").load("../Frontend/sites/components/footer.html");
        }
    });
    //$("head").load("./Frontend/sites/components/head.html");
} */

/* (function() {
    //$("head").load(".Frontend/sites/components/head.html");
    $("nav").load("./Frontend/sites/components/nav.html");
    $("footer").load("./Frontend/sites/components/nav.html");
   }); */


// JavaScript-Code, um die Kategorien zu aktualisieren und neue Produkte vom Server abzurufen

// Kategorien auswählen
var kategorien = document.querySelectorAll('.kategorien li');

kategorien.forEach(function(kategorie) {
  kategorie.addEventListener('click', function() {
    // Aktuell ausgewählte Kategorie markieren
    kategorien.forEach(function(k) {
      k.classList.remove('ausgewählt');
    });
    this.classList.add('ausgewählt');
    
    // AJAX-Anfrage, um Produktdaten vom Server abzurufen
    var ausgewählteKategorie = this.textContent;
    // Hier AJAX-Anfrage an den Server senden und die Produktdaten der ausgewählten Kategorie abrufen
    // Beispiel mit Fetch API
    fetch('produkte.php')
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        // Neue Produkte anzeigen
        var neueProdukte = document.querySelector('.produkte ul');
        neueProdukte.innerHTML = '';
        
        data.forEach(function(produkt) {
          neueProdukte.innerHTML += `
            <li>
              <img src="${produkt.bild}" alt="${produkt.name}">
              <h3>${produkt.name}</h3>
              <p>Preis: $${produkt.preis}</p>
              <p>Bewertung: ${produkt.bewertung} von 5</p>
            </li>
          `;
        });
      })
      .catch(function(error) {
        console.log('Fehler beim Abrufen der Produktdaten: ' + error);
      });
  });
});



function addToCart(product) {
    // Perform an AJAX request to add the product to the shopping cart
    $.ajax({
        url: 'products.php', 
        type: 'POST',
        data: { product: product },
        dataType: 'json',
        success: function(response) {
            // Display a success message or perform any other desired action
            alert('Das Produkt wurde dem Warenkorb hinzugefügt.');
        },
        error: function(xhr, status, error) {
            // Display an error message or handle the error appropriately
            alert('Fehler beim Hinzufügen des Produkts zum Warenkorb.');
        }
    });
}


$(document).ready(function() {
    var searchInput = $("#searchInput");
    var resultsContainer = $("#results");
  
    searchInput.on("input", function() {
      var searchQuery = $(this).val();
  
      if (searchQuery.length >= 1) {
        searchProducts(searchQuery);
      } else {
        resultsContainer.empty();
      }
    });
  
    function searchProducts(searchQuery) {
      $.ajax({
        url: "navBar.php", // Pfad zur Serverseite für die Produktsuche
        method: "GET",
        data: { query: searchQuery },
        dataType: "json",
        success: function(response) {
          displayResults(response);
        },
        error: function(xhr, status, error) {
          console.log("Fehler bei der Produktsuche: " + error);
        }
      });
    }
  
    function displayResults(results) {
      resultsContainer.empty();
  
      if (results.length > 0) {
        for (var i = 0; i < results.length; i++) {
          var product = results[i];
          var productCard = $("<div>").addClass("product-card").text(product.name);
          resultsContainer.append(productCard);
        }
      } else {
        var noResultsMessage = $("<p>").text("Keine Ergebnisse gefunden.");
        resultsContainer.append(noResultsMessage);
      }
    }
  });
  
  

  function addToCart(productTitle) {
    $.ajax({
        url: 'addToCart.php',
        type: 'POST',
        data: { productTitle: productTitle },
        dataType: 'json',
        success: function(response) {
            // Update the cart data based on the response
            cart = response.cart;

            // Update the shopping cart display
            updateCartDisplay();
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
}



$(document).ready(function() {
    // Funktion zum Laden der Produkte basierend auf der ausgewählten Kategorie
    function loadProducts(category) {
      $.ajax({
        url: 'server.php',
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
  
    // Kategorieauswahl überwachen und Produkte laden
    $('#category-select').change(function() {
      var selectedCategory = $(this).val();
      loadProducts(selectedCategory);
    });
  
    // Standardmäßig erste Kategorie laden
    var defaultCategory = $('#category-select').val();
    loadProducts(defaultCategory);
  });
  
  // Funktion zum Hinzufügen des ausgewählten Produkts in den Warenkorb
  function addToCart(productId) {
    $.ajax({
      url: 'add_to_cart.php',
      type: 'POST',
      data: { product_id: productId },
      success: function(response) {
        // Erfolgreiche Antwort erhalten, entsprechende Aktionen ausführen
        alert('Das Produkt wurde dem Warenkorb hinzugefügt.');
        // Hier können weitere Anpassungen am Benutzerinterface vorgenommen werden, um den aktualisierten Warenkorb darzustellen
      }
    });
  }
  


  // Das Suchfeld und das Ergebnis-Div abrufen
var searchInput = document.getElementById('searchInput');
var searchResults = document.getElementById('searchResults');

// Funktion, die aufgerufen wird, wenn der Benutzer etwas in das Suchfeld eingibt
searchInput.addEventListener('input', function() {
  var searchTerm = searchInput.value; // Die eingegebene Suchanfrage

  // Hier können Sie AJAX verwenden, um die Produkte aus der Datenbank zu holen
  // und die Ergebnisse dynamisch anzuzeigen
  // Nehmen wir an, die Funktion getProductsFromDatabase(searchTerm) holt die Produkte
  // basierend auf der Suchanfrage aus der Datenbank und gibt sie als Array zurück

  function getProductsFromDatabase(searchTerm) {
    return new Promise(function(resolve, reject) {
      // AJAX-Anfrage erstellen
      var xhr = new XMLHttpRequest();
      
      // URL für die Produktsuche anpassen, je nachdem wie Ihre Backend-API strukturiert ist
      var url = '/api/products?search=' + encodeURIComponent(searchTerm);
      
      xhr.open('GET', url, true);
      
      // Event-Handler für die AJAX-Antwort registrieren
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            resolve(response.products); // Die Produkte aus der AJAX-Antwort extrahieren und auflösen
          } else {
            reject(new Error('Fehler beim Abrufen der Produkte'));
          }
        }
      };
      
      // AJAX-Anfrage senden
      xhr.send();
    });
  }


  var products = getProductsFromDatabase(searchTerm);

  // Ergebnisse anzeigen
  displayResults(products);
});

// Funktion, die die Ergebnisse anzeigt
function displayResults(products) {
  // Leeren des Ergebnis-Divs
  searchResults.innerHTML = '';

  // Ergebnisse durchlaufen und sie dem Ergebnis-Div hinzufügen
  for (var i = 0; i < products.length; i++) {
    var product = products[i];

    // Erstellen eines Elements für das Produkt und es dem Ergebnis-Div hinzufügen
    var productElement = document.createElement('div');
    productElement.textContent = product.titel; // Hier können Sie den Namen oder andere Informationen des Produkts anzeigen
    searchResults.appendChild(productElement);
  }
}
