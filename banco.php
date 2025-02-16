<?php
// Conectar ao banco de dados (cria se não existir)
$db = new SQLite3('RestauranteDB.db');

// Criar a tabela Mesas, caso ela não exista
$query = "CREATE TABLE IF NOT EXISTS Mesas (
    Restaurante INTEGER PRIMARY KEY,
    Mesa TEXT
)";
$db->exec($query);

// Função para inserir dados na tabela
function inserirMesa($restaurante, $mesa) {
    global $db;
    $stmt = $db->prepare("INSERT INTO Mesas (Restaurante, Mesa) VALUES (:restaurante, :mesa)");
    $stmt->bindValue(':restaurante', $restaurante, SQLITE3_INTEGER);
    $stmt->bindValue(':mesa', $mesa, SQLITE3_TEXT);
    $stmt->execute();
}

// Função para realizar uma consulta
function consultarMesas() {
    global $db;
    $result = $db->query("SELECT * FROM Mesas");
    $mesas = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $mesas[] = $row;
    }
    return $mesas;
}

// Exemplo de inserção de uma mesa
// Inserir mesa apenas se os parâmetros estiverem presentes na URL
if (isset($_GET['R']) && isset($_GET['M'])) {
    $restaurante = $_GET['R'];
    $mesa = $_GET['M'];
    inserirMesa($restaurante, $mesa);
}

// Consultar todas as mesas no banco
$mesas = consultarMesas();
?>
