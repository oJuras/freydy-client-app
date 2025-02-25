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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(to bottom, #9c5b3e, #d07f4f, #9c5b3e);
            background-size: 400% 400%;
            animation: gradientAnimation 10s ease infinite;
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

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #9c5b3e;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #9c5b3e;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            background-color: #d07f4f;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #9c5b3e;
        }

        p {
            text-align: center;
            color: #d07f4f;
        }
    </style>
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
