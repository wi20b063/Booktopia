<?php
session_start();

// Verbindung zur Datenbank herstellen (Beispiel)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstopia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Warenkorb aus der Session abrufen
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Funktion zum Abrufen der Produktinformationen basierend auf der Produkt-ID
function getProductDetails($conn, $productId) {
    $sql = "SELECT * FROM product WHERE id = '$productId'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Beispiel: Rückgabe eines assoziativen Arrays mit Produktinformationen
        return array(
            'titel' => $row['titel'],
            'preis' => $row['preis'],
            'quantity' => 1, // Anzahl im Warenkorb festgelegt
            'image_url' => $row['image_url'],
        );
    }
    
    return null;
}

?>

<!DOCTYPE html>
<html lang="EN">

<head>
    <?php include "components/head.php";?>

    <title>Warenkorb</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">
                <h1 class="headline">Warenkorb</h1>

                <?php if (count($cart) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Preis</th>
                                <th>Menge</th>
                                <th>Gesamt</th>
                                <th>Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $productId): ?>
                                <?php $product = getProductDetails($conn, $productId); ?>
                                <?php if ($product): ?>
                                    <tr>
                                        <td><?php echo $product['titel']; ?></td>
                                        <td><?php echo $product['preis'] . "€"; ?></td>
                                        <td><?php echo $product['quantity']; ?></td>
                                        <td><?php echo $product['preis'] * $product['quantity'] . "€"; ?></td>
                                        
                                        <td>
                                            <form method="POST" action="remove_from_Cart.php">
                                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                                <button type="submit" class="btn btn-danger" name="remove_from_Cart">Entfernen</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Gesamtsumme</strong></td>

                                <td>
                                    <?php
                                    $total = 0;
                                    foreach ($cart as $productId) {
                                        $product = getProductDetails($conn, $productId);
                                        if ($product) {
                                            $total += $product['preis'] * $product['quantity'];
                                        }
                                    }
                                    echo $total . "€";
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="text-center">
                        <a href="" class="btn btn-secondary">Zur Kasse gehen</a>
                    </div>
                <?php else: ?>
                    <p>Der Warenkorb ist leer.</p>
                <?php endif; ?>

                <?php $conn->close(); ?>
            </div>
        </div>
    </main>

    <footer class="py-3 my-4">
        <?php include "components/footer.php";?>
    </footer>
</body>

</html>