<!DOCTYPE html>

<html lang="EN">

<head>
    <?php include "components/head.php";?>
    <?php include "../../Backend/logic/sessionShoppingCart.php";?>

    <title>Produkte</title>

    <script src="../../Frontend/js/myFunctions.js"></script>

    <script>
      $(document).ready(function() {
          // Kategorieauswahl überwachen und Produkte laden
          $('#category-select').change(function() {
            var selectedCategory = $(this).val();
            loadProducts(selectedCategory);
          });

          // Standardmäßig erste Kategorie laden
          var defaultCategory = $('#category-select').val();
          loadProducts(defaultCategory);
      });
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

