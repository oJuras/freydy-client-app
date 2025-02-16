<?php
// Conexão com o banco de dados
$servername = "localhost"; // ou o endereço do seu servidor
$username = "root"; // seu nome de usuário do banco
$password = "usbw"; // sua senha do banco (caso tenha)
$dbname = "RestauranteDB"; // nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe o CPF do formulário
    $cpf = $_POST['cpf'];

    // Consulta o banco de dados para verificar se o CPF existe
    $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // CPF encontrado, redireciona para a página de boas-vindas ou dashboard
        header('Location: app.html');  // Redireciona para a página de dashboard
        exit();
    } else {
        echo "<p>Usuário não encontrado com esse CPF.</p>";
    }

    // Fecha a declaração
    $stmt->close();
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Usuário</title>
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
            max-width: 400px;
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
        <h2>Login de Usuário</h2>
        <form action="" method="POST">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required placeholder="Digite o CPF" maxlength="11">

            <input type="submit" value="Entrar">
        </form>
    </div>

</body>
</html>
