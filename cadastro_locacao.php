<?php
require 'autenticacao.php';

require 'db/conexao.php';
require 'classes/Locacao.php';
require 'classes/Cliente.php';
require 'classes/Veiculo.php';

$clientes = Cliente::listar($pdo);
$veiculos = Veiculo::listar($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['veiculo'], $_POST['cliente'], $_POST['data_inicio'], $_POST['data_fim'])) {
        $veiculo = $_POST['veiculo'];
        $cliente = $_POST['cliente'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];

        if (empty($veiculo) || empty($cliente) || empty($data_inicio) || empty($data_fim)) {
            $erro = "Todos os campos devem ser preenchidos.";
        } else {
            $locacao = new Locacao($veiculo, $cliente, $data_inicio, $data_fim);
            try {
                $locacao->inserir($pdo);
                $sucesso = "Locação cadastrada com sucesso!";
            } catch (Exception $e) {
                $erro = "Erro ao cadastrar a locação: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Locação</title>
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

<h1>Cadastro de Locação</h1>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php elseif (isset($sucesso)): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<form action="" method="POST">
    <label for="veiculo">Veículo:</label>
    <select id="veiculo" name="veiculo" required>
        <option value="">Selecione</option>
        <?php foreach ($veiculos as $v): ?>
            <option value="<?= htmlspecialchars($v['veiculo_placa']) ?>">
                <?= htmlspecialchars($v['veiculo_placa']) ?> - <?= htmlspecialchars($v['marca_descricao']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="cliente">Cliente:</label>
    <select id="cliente" name="cliente" required>
        <option value="">Selecione</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= htmlspecialchars($c['cliente_cpf']) ?>">
                <?= htmlspecialchars($c['cliente_nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="data_inicio">Data de Início:</label>
    <input type="date" id="data_inicio" name="data_inicio" required>

    <label for="data_fim">Data de Fim:</label>
    <input type="date" id="data_fim" name="data_fim" required>

    <input type="submit" class="btn" value="Cadastrar Locação">
</form>

<center>
    <form action="dashboard.php" method="get">
        <button type="submit" class="voltar-btn">Voltar para o Início</button>
    </form>
</center>
<h2>Locações Cadastradas</h2>
<iframe src="listar_locacoes.php"></iframe>

<script>
  setTimeout(() => {
    alert('Sua sessão expirou! Faça Login Novamente!');
    window.location.href = 'index.php';
  }, <?= $tempoRestante ?>);
</script>

</body>
</html>
