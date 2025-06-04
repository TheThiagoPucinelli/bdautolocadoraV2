<?php
require 'autenticacao.php'; // Script de autenticação
require 'db/conexao.php';
require 'classes/Cliente.php';

$mensagem = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $endereco = $_POST['endereco'] ?? '';

    $cliente = new Cliente($cpf, $nome, $endereco);

    if ($cliente->inserir($pdo)) {
        $mensagem = "Cliente cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar cliente.";
    }
}

// Define o tempo restante da sessão (em ms) para o alert de expiração
$tempoRestante = isset($tempoRestante) ? $tempoRestante : 300000; // 5 min padrão, ajuste se precisar
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="css/Style.css" />
    <style>
        .voltar-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .voltar-btn:hover {
            background-color: #45a049;
        }
        .mensagem {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Cliente</h1>

    <?php if ($mensagem): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required placeholder="Digite o CPF" maxlength="11" />

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required placeholder="Digite o Nome" maxlength="100" />

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" placeholder="Digite o Endereço" maxlength="150" />

        <input type="submit" class="btn" value="Cadastrar" />
    </form>

    <center>
        <form action="dashboard.php" method="get">
            <button type="submit" class="voltar-btn">Voltar para o Início</button>
        </form>
    </center>
    <h2 style="text-align:center;">Clientes Cadastrados</h2>

    <iframe src="listar_clientes.php" style="width:90%; height:300px; border:none; margin-top:20px;"></iframe>

    <script>
        setTimeout(() => {
            alert('Sua sessão expirou! Faça Login Novamente!');
            window.location.href = 'index.php';
        }, <?= (int)$tempoRestante ?>);
    </script>
</body>
</html>
