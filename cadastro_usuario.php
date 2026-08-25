<?php

session_start();

require 'conexao.php';

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'] ?? '';

    if (empty($nome)) {
        $_SESSION['mensagem'] = "O nome deve ser preenchido.";
        header("Location: cadastro_usuario.php");
        exit;
    }

    $email = $_POST['email'] ?? '';

    if ((empty($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensagem'] = "Digite um e-mail válido.";
        header("Location: cadastro_usuario.php");
        exit;
    } else {
        $stmt = $conexao->prepare("INSERT INTO usuarios(nome_usuario, email_usuario) VALUES (?, ?)");
        $stmt->bind_param('ss', $nome, $email);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso.";
        } else {
            $_SESSION['mensagem'] = "Não foi possível realizar o cadastro.";
        }

        $stmt->close();
        header("Location: cadastro_usuario.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de usuários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gerenciamento de tarefas</h1>

        <nav>
            <a href="index.php">
                Gerenciar Tarefas
            </a>
            <a href="cadastro_tarefa.php">
                Cadastro de Tarefas
            </a>
            <a href="cadastro_usuario.php">
                Cadastro de Usuários
            </a>
        </nav>
    </header>

    <main>
        <h2>Cadastro de Usuários</h2>

        <?php
            if ($mensagem != "") {
                echo "<p>$mensagem</p>";
            }
        ?>

        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="text" id="email" name="email" required>

            <button type="submit">Cadastrar</button>
        </form>
    </main>
</body>
</html>