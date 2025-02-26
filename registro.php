<?php
$servername = "localhost"; 
$username = "root"; 
$password = "usbw"; 
$dbname = "RestauranteDB"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $nome = $_POST['nome'];

    $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p>Erro: O CPF já está cadastrado.</p>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE telefone = ?");
        $stmt->bind_param("s", $telefone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<p>Erro: O telefone já está cadastrado.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO Usuarios (cpf, telefone, nome) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $cpf, $telefone, $nome);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit(); 
            } else {
                echo "<p>Erro ao cadastrar usuário: " . $stmt->error . "</p>";
            }
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="./css/styles_registro.css">

</head>
<body>

    <div class="container">
        <h2>Cadastro de Usuário</h2>
        <form action="" method="POST">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required placeholder="Digite o CPF" maxlength="11">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required placeholder="Digite o telefone" maxlength="15">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required placeholder="Digite seu nome">

            <input type="submit" value="Cadastrar">
        </form>
    </div>

</body>
</html>
