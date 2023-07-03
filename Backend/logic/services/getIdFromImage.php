<?php
// Verbindung zur Datenbank herstellen (Beispiel)
require_once (dirname(__FILE__, 3) . "/config/dbaccess.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Bild-URL aus der POST-Anfrage abrufen und decodieren
    $image_url_original = urldecode($_POST['image_url']);

    $filename = basename($image_url_original);

    $image_url = "../res/img/" . $filename;

    // Produkt-ID basierend auf der Bild-URL abrufen
    $productId = getIdFromImage($con, $image_url);

    // Produkt-ID als JSON-Response zurückgeben, ohne Anführungszeichen für numerische Werte
    echo json_encode($productId, JSON_NUMERIC_CHECK);
}

// Funktion zum Abrufen der Produkt-ID basierend auf der Bild-URL
function getIdFromImage($con, $image_url) {
    $sql = "SELECT id FROM book WHERE image_url = '$image_url'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    // Wenn keine Übereinstimmung gefunden wurde, gib null zurück
    return 0;
}

?>
