<?php
session_start();

// Überprüfen, ob das Produkt in der Anfrage übergeben wurde
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Überprüfen, ob die Warenkorb-Session-Variablen vorhanden sind
    if (isset($_SESSION['cart'])) {
        // Produkt aus dem Warenkorb entfernen
        $index = array_search($productId, $_SESSION['cart']);
        if ($index !== false) {
            array_splice($_SESSION['cart'], $index, 1);
        }
    }
}

// Weiterleitung zum Warenkorb
header('Location: shoppingCart.php');
exit();
?>