<!DOCTYPE html>

<html lang="EN">

<head>
    <?php include "components/head.php";?>

    <title>Produkte</title>

    <script>
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
          
          var shoppingCartBadge = document.getElementById("shopping-cart-badge");
          var currentCount = parseInt(shoppingCartBadge.innerText);
          var newCount = currentCount + 1;

          shoppingCartBadge.innerText = newCount.toString();
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
    url: 'addToCart.php',
    type: 'POST',
    data: { product_id: productId },
    success: function(response) {
      // Erfolgreiche Antwort erhalten, entsprechende Aktionen ausführen
      alert('Das Produkt wurde dem Warenkorb hinzugefügt.');
      // Hier können weitere Anpassungen am Benutzerinterface vorgenommen werden, um den aktualisierten Warenkorb darzustellen
    }
  });
}

    </script>

    </head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">
                <h1 class="headline">Produkte</h1>

                <div class="row col-md-6">

                <div class="mb-4">
                    <label for="category"><p><strong>Wählen Sie eine Kategorie aus:</strong></p></label>
                        <select id="category-select">
                            <option value="1">Roman</option>
                            <option value="2">Sachbuch</option>
                        </select>
                </div>

                <div class="mb-4">
                <h2 id="category-name"></h2>
                </div>

                <div id="products-container"></div>

        </div>

    </main>

    <footer class=" py-3 my-4">
        <?php include "components/footer.php";?>
    </footer>

</body>

</html>
