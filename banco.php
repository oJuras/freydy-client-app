<?php
$db = new SQLite3('RestauranteDB.db');

$query = "CREATE TABLE IF NOT EXISTS Mesas (
    Restaurante INTEGER PRIMARY KEY,
    Mesa TEXT
)";
$db->exec($query);

function inserirMesa($restaurante, $mesa) {
    global $db;
    $stmt = $db->prepare("INSERT INTO Mesas (Restaurante, Mesa) VALUES (:restaurante, :mesa)");
    $stmt->bindValue(':restaurante', $restaurante, SQLITE3_INTEGER);
    $stmt->bindValue(':mesa', $mesa, SQLITE3_TEXT);
    $stmt->execute();
}

function consultarMesas() {
    global $db;
    $result = $db->query("SELECT * FROM Mesas");
    $mesas = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $mesas[] = $row;
    }
    return $mesas;
}

if (isset($_GET['R']) && isset($_GET['M'])) {
    $restaurante = $_GET['R'];
    $mesa = $_GET['M'];
    inserirMesa($restaurante, $mesa);
}

$mesas = consultarMesas();
?>
