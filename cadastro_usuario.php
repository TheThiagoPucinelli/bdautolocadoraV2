<?php

require 'db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'cliente';

    if (empty($cpf) || empty($senha)) {
        $error = "Preencha CPF e senha.";
    } else {
        // Verifica se CPF já existe
        $stmt = $pdo->prepare("SELECT usuario_cpf FROM tbusuario WHERE usuario_cpf = ?");
        $stmt->execute([$cpf]);
        if ($stmt->fetch()) {
            $error = "CPF já cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO tbusuario (usuario_cpf, usuario_senha, usuario_tipo) VALUES (?, ?, ?)");
            $stmt->execute([$cpf, $hash, $tipo]);
            $success = "Usuário cadastrado com sucesso!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <h1>Cadastro de Usuário</h1>

    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php elseif (!empty($success)): ?>
        <p class="success-msg"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" id="cpf" maxlength="11" required placeholder="Digite seu CPF">

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">

        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo">
            <option value="cliente" selected>Cliente</option>
            <option value="admin">Administrador</option>
        </select>

       <center> <button type="submit" class="btn">Cadastrar</button></center>

        <p class="centered-text">
    <a href="index.php" class="link-secondary">Já possuí cadastro? Clique aqui</a>
</p>

    </form>

   
</body>
</html>
