<?php
session_start();

if (!isset($_SESSION['cpf']) || !isset($_COOKIE['usuario_logado'])) {
    header('Location: index.php?expired=1');
    exit;
}

$tempoMaximo = 30 * 60; // 30 minutos em segundos

if (time() > $_COOKIE['usuario_logado']) {
    setcookie('usuario_logado', '', time() - 3600, "/");
    session_unset();
    session_destroy();

    echo '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8" />
        <title>Sessão Expirada</title>
        <script>
            alert("Sua sessão expirou!");
            window.location.href = "index.php";
        </script>
    </head>
    <body></body>
    </html>';
    exit;
} else {
    setcookie('usuario_logado', time() + $tempoMaximo, time() + $tempoMaximo, "/");

    $tempoRestante = $tempoMaximo * 1000; // milissegundos
}
?>
