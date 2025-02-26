<?php
$servername = "localhost";  
$username = "root";         
$password = "usbw";         
$dbname = "RestauranteDB";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$restaurante = isset($_GET['R']) ? $_GET['R'] : null;

if ($restaurante === null) {
    echo "Parâmetro 'R' é obrigatório na URL.";
    exit;
}

$stmt_restaurante = $conn->prepare("SELECT nome FROM Restaurantes WHERE id_restaurante = ?");
$stmt_restaurante->bind_param("i", $restaurante);  
$stmt_restaurante->execute();
$result_restaurante = $stmt_restaurante->get_result();
$restaurante_nome = $result_restaurante->fetch_assoc()['nome'];

$stmt_cardapio = $conn->prepare("SELECT nome_item, descricao, preco, id_item FROM Cardapios WHERE id_restaurante = ?");
$stmt_cardapio->bind_param("i", $restaurante);  
$stmt_cardapio->execute();
$result_cardapio = $stmt_cardapio->get_result();

$cardapio = [];
if ($result_cardapio->num_rows > 0) {
    while($row = $result_cardapio->fetch_assoc()) {
        $cardapio[] = $row;
    }
} else {
    echo "Nenhum item de cardápio encontrado para o restaurante '$restaurante_nome'.";
}

$stmt_restaurante->close();
$stmt_cardapio->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio do Restaurante</title>
    <link rel="stylesheet" href="./css/styles_pedido.css">
    <script src="./js/scripts_pedido.js"></script>
</head>
<body onload="atualizarCarrinho(); atualizarTotal();">
    <header>
        <?php echo htmlspecialchars($restaurante_nome); ?>
        <div class="menu-hamburger" onclick="toggleMenu()">
            &#9776;
        </div>
    </header>

    <div class="cardapio-title">Cardápio</div>

    <div class="cardapio">
        <?php foreach ($cardapio as $item): ?>
            <div class="cardapio-item">
                <p><strong><?php echo htmlspecialchars($item['nome_item']); ?></strong></p>
                <p class="descricao"><?php echo htmlspecialchars($item['descricao']); ?></p>
                <p class="preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                <button class="adicionar-carrinho" onclick="adicionarCarrinho('<?php echo $item['id_item']; ?>', '<?php echo htmlspecialchars($item['nome_item']); ?>', <?php echo $item['preco']; ?>)">
                    Adicionar ao Carrinho
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="menu-carrinho" class="menu-carrinho">
        <div class="fechar" onclick="toggleMenu()">×</div>
        <h3>Itens no Carrinho</h3>
        <ul id="carrinho-lista">
        </ul>
        <p><strong>Total: R$ <span id="total"></span></strong></p>
        <button class="limpar-carrinho" onclick="limparCarrinho()">Limpar Carrinho</button>
    </div>

    <div id="numero-itens" onclick="toggleMenu()">0</div>

</body>
</html>
