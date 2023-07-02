<?php
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    
    // Überprüfen, ob die Warenkorb-Session-Variablen vorhanden sind
    if (isset($_SESSION['cart'])) {
        // Entfernen Sie das Produkt aus dem Warenkorb
        $index = array_search($productId, $_SESSION['cart']);
        if ($index !== false) {
            array_splice($_SESSION['cart'], $index, 1);
            
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        
        // Aktualisieren Sie die Warenkorb-Anzahl
        $cartCount = count($_SESSION['cart']);
        $_SESSION['cart_count'] = $cartCount;
        
        // Erfolgsantwort senden
        echo $cartCount;
    } else {
        // Fehlerantwort senden
        echo 'error';
    }
} else {
    // Fehlerantwort senden
    echo 'error';
}
// Weiterleitung zum Warenkorb
header('Location: ../../../Frontend/sites/shoppingCart.php');
exit();
?>