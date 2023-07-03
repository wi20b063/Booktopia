    <?php
// Verbindung zur Datenbank herstellen
require_once (dirname(__FILE__,2) . "\config\dbaccess.php");


// Funktion zum Abrufen der Produkte basierend auf der ausgewählten Kategorie
function getProductsByCategory($categoryId) {
    global $con;
    
    // SQL-Abfrage, um Produkte basierend auf der ausgewählten Kategorie abzurufen
    $sql = "SELECT * FROM book WHERE category_id = $categoryId";
    $result = $con->query($sql);
    
    $products = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    return $products;
}

// Die ausgewählte Kategorie vom Client empfangen
$category = $_GET['category'];

// Mapping der Kategorie-IDs zu Kategorienamen
$categoryMapping = [
    1 => 'Roman',
    2 => 'Sachbuch',
];

// Kategorie-ID in den Kategorienamen umwandeln
$categoryName = $categoryMapping[$category];

// Produkte basierend auf der ausgewählten Kategorie abrufen
$products = getProductsByCategory($category);

// JSON-Antwort mit den Produktdaten und dem Kategorienamen senden
$response = [
    'category' => $categoryName,
    'products' => $products
];

header('Content-Type: application/json');
echo json_encode($response);

$con->close();
?>
