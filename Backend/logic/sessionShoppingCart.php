<?php
session_start();

// Verbindung zur Datenbank herstellen (Beispiel)
require_once (dirname(__FILE__,2) . "\config\dbaccess.php");

// Warenkorb aus der Session abrufen
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Funktion zum Abrufen der Produktinformationen basierend auf der Produkt-ID
function getProductDetails($con, $productId) {
    $sql = "SELECT * FROM book WHERE id = '$productId'";
    $result = $con->query($sql);
    
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