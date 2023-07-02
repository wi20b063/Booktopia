<?php
session_start();

if (isset($_POST['cart_count'])) {
    $cartCount = $_POST['cart_count'];
    
    // Speichern Sie die Warenkorb-Anzahl in der Session-Variablen
    $_SESSION['cart_count'] = $cartCount;
    
    // Erfolgsantwort senden
    echo 'success';
} else {
    // Fehlerantwort senden
    echo 'error';
}
?>