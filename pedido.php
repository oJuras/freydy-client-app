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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #8B4513, #800000, #8B4513, #800000);
            background-size: 400% 400%;
            animation: gradientAnimation 10s ease infinite;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        header {
            background-color: #800000;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 2em;
            text-transform: uppercase;
            border-bottom: 5px solid #8B4513;
            position: relative;
        }

        .menu-hamburger {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;  
        }

        .menu-hamburger span {
            position: absolute;
            top: -10px;
            left: -10px;
            background-color: #800000;
            color: white;
            padding: 5px 10px;
            font-size: 1.2em;
            border-radius: 50%;
        }

        h2 {
            color: #800000;
            font-size: 1.5em;
            text-align: center;
            margin-top: 20px;
        }

        .cardapio-title {
            color: #fff;
            background-color: #8B4513;
            padding: 15px;
            font-size: 2.5em;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            border-radius: 10px;
        }

        .cardapio-item {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cardapio-item p {
            margin: 0;
            font-size: 1.3em;
            color: #8B4513;
        }

        .cardapio-item .descricao {
            font-size: 1.1em;
            color: #555;
        }

        .cardapio-item .preco {
            font-size: 1.2em;
            color: #800000;
            font-weight: bold;
        }

        .adicionar-carrinho {
            background-color: #8B4513;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .adicionar-carrinho:hover {
            background-color: #800000;
        }

        .menu-carrinho {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background-color: #800000;
            color: white;
            padding: 20px;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            transform: translateX(100%);
            z-index: 9999;
        }

        .menu-carrinho.open {
            display: block; 
            transform: translateX(0);
        }

        .menu-carrinho h3 {
            margin-top: 0;
            font-size: 1.5em;
        }

        .menu-carrinho ul {
            list-style-type: none;
            padding: 0;
        }

        .menu-carrinho ul li {
            margin: 15px 0;
            padding: 10px;
            background-color: #8B4513;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-carrinho ul li span {
            display: block;
        }

        .menu-carrinho .fechar {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            cursor: pointer;
        }

        #numero-itens {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #8B4513;
            color: white;
            font-size: 1.5em;
            border-radius: 50%;
            padding: 10px 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            cursor: pointer;
        }

        .limpar-carrinho {
            background-color: #800000;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .limpar-carrinho:hover {
            background-color: #8B4513;
        }

        .remover-item {
            background-color: #800000;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 1.2em;
            cursor: pointer;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .remover-item:hover {
            background-color: #8B4513;
        }
    </style>
</head>
<body>
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

    <script>
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

        function adicionarCarrinho(idItem, nomeItem, preco) {
            carrinho.push({ id: idItem, nome: nomeItem, preco: preco });
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarCarrinho();
            atualizarTotal();
            if (!document.getElementById('menu-carrinho').classList.contains('open')) {
                toggleMenu(); 
            }
        }

        function atualizarCarrinho() {
            const carrinhoLista = document.getElementById('carrinho-lista');
            const numeroItens = document.getElementById('numero-itens');
            carrinhoLista.innerHTML = '';
            carrinho.forEach((item, index) => {
                const li = document.createElement('li');
                li.innerHTML = `${item.nome} - R$ ${item.preco.toFixed(2).replace('.', ',')}
                <button class="remover-item" onclick="removerItemCarrinho(${index})">X</button>`;
                carrinhoLista.appendChild(li);
            });
            numeroItens.textContent = carrinho.length;
        }

        function atualizarTotal() {
            const total = carrinho.reduce((sum, item) => sum + item.preco, 0);
            document.getElementById('total').textContent = total.toFixed(2).replace('.', ',');
        }

        function toggleMenu() {
            const menuCarrinho = document.getElementById('menu-carrinho');
            menuCarrinho.classList.toggle('open');
        }

        function limparCarrinho() {
            carrinho = [];
            localStorage.removeItem('carrinho');
            atualizarCarrinho();
            atualizarTotal();
            toggleMenu();
        }

        function removerItemCarrinho(index) {
            carrinho.splice(index, 1);
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarCarrinho();
            atualizarTotal();
        }

        atualizarCarrinho();
        atualizarTotal();
    </script>
</body>
</html>
