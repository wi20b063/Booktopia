<?php
session_start();

// Überprüfen, ob das Produkt in der Anfrage übergeben wurde
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Überprüfen, ob die Warenkorb-Session-Variablen vorhanden sind, andernfalls initialisieren sie
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Produkt zur Warenkorb-Session hinzufügen
    $_SESSION['cart'][] = $productId;
    $cartCount = count($_SESSION['cart']);
    
    // Erfolgsantwort senden
    echo json_encode($cartCount);
    echo 'success';
} else {
    
    // Fehlerantwort senden
    echo 'error';
}
?>
