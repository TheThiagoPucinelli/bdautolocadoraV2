<?php
require 'autenticacao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Autolocadora</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <h1>Bem-vindo à Autolocadora</h1>
    <form style="width:auto; max-width:600px; margin:40px auto; text-align:center; margin-top:0px;">
        <a href="cadastro_cliente.php" class="btn">Cadastrar Cliente</a><br><br><br>
        <a href="cadastro_marca.php" class="btn">Cadastrar Marca</a><br><br><br>
        <a href="cadastro_veiculo.php" class="btn">Cadastrar Veículo</a><br><br><br>
        <a href="cadastro_locacao.php" class="btn">Cadastrar Locação</a><br><br><br>
        <a href="DB_total.php" class="btn">Todos os Dados das locações</a><br><br><br>
    </form>

<script>
  setTimeout(() => {
    alert('Sua sessão expirou! Faça Login Novamente!');
    window.location.href = 'index.php';
  }, <?= $tempoRestante ?>);
</script>

</body>
</html>
