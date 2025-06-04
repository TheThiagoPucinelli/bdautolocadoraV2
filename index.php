<?php
session_start();
require 'db/conexao.php';

// Verifica se a sessão expirou
$mensagemExpirada = false;
if (isset($_GET['expired']) && $_GET['expired'] == 1) {
    $mensagemExpirada = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Busca usuário pelo CPF
    $stmt = $pdo->prepare("SELECT usuario_cpf, usuario_senha, usuario_tipo FROM tbusuario WHERE usuario_cpf = ?");
    $stmt->execute([$cpf]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['usuario_senha'])) {
        // Login válido: salva dados na sessão
        $_SESSION['cpf'] = $usuario['usuario_cpf'];
        $_SESSION['tipo'] = $usuario['usuario_tipo'];

        // Define cookie com validade de 30 minutos
        $tempoExpiracao = 1800; // 30 minutos
        setcookie('usuario_logado', time() + $tempoExpiracao, time() + $tempoExpiracao, "/");

        header('Location: dashboard.php');
        exit;
    } else {
        $erro = "CPF ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <h1>Login</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red; text-align:center;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <?php if ($mensagemExpirada): ?>
        <p style="color: orange; text-align:center;">Sua sessão expirou. Faça login novamente.</p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required placeholder="Digite o CPF" maxlength="11">

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required placeholder="Digite a Senha">

        <input type="submit" class="btn" value="Entrar">

        <p class="centered-text" style="margin-top: 15px;">
            <a href="cadastro_usuario.php" class="link-secondary">Não possui cadastro? Clique aqui.</a>
        </p>
    </form>

    <div style="text-align:center; margin-top: 15px;">
        <a href="cadastro_usuario.php" style="color: #0065c4; text-decoration: none;">Ainda não tem cadastro? Clique aqui.</a>
    </div>
</body>
</html>
