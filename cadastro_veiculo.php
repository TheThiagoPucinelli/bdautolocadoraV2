<?php
require 'autenticacao.php';

require 'db/conexao.php';
require 'classes/Veiculo.php';
require 'classes/Marca.php';

$marcas = Marca::listar($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Veiculo($_POST['placa'], $_POST['marca'], $_POST['descricao']);
    $v->inserir($pdo);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Veículo</title>
    <link rel="stylesheet" href="css/style.css" />
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
    </style>
</head>
<body>

<h1>Cadastro de Veículo</h1>

<form action="" method="POST">
    <label for="placa">Placa:</label>
    <input type="text" id="placa" name="placa" required placeholder="Digite a Placa">

    <label for="marca">Marca:</label>
    <select id="marca" name="marca" required>
        <option value="">Selecione</option>
        <?php foreach ($marcas as $m): ?>
            <option value="<?= $m['marca_codigo'] ?>"><?= htmlspecialchars($m['marca_descricao']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="descricao">Descrição:</label>
    <input type="text" id="descricao" name="descricao" placeholder="Digite a Descrição">

    <input type="submit" class="btn" value="Cadastrar">
</form>

<center>
    <form action="dashboard.php" method="get">
        <button type="submit" class="voltar-btn">Voltar para o Início</button>
    </form>
</center>
<h2 style="text-align:center;">Veículos Cadastrados</h2>
<iframe src="listar_veiculos.php"></iframe>

<script>
  setTimeout(() => {
    alert('Sua sessão expirou! Faça Login Novamente!');
    window.location.href = 'index.php';
  }, <?= $tempoRestante ?>);
</script>


</body>
</html>
