<?php
require 'autenticacao.php'; 
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


$tempoRestante = isset($tempoRestante) ? $tempoRestante : 300000; // 5 minutos
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
        .erro-borda {
            border: 2px solid red !important;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Cliente</h1>

    <?php if ($mensagem): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form id="cadastro" action="" method="POST" autocomplete="off">
        <label for="cpf">CPF:</label>
       <input type="text" id="cpf" name="cpf" placeholder="Digite o CPF" maxlength="14" />

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"  placeholder="Digite o Nome" maxlength="100" />

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

    window.onload = function () {
        const formulario = document.getElementById("cadastro");
        formulario.addEventListener("submit", validaFormulario);
        formulario.cpf.addEventListener("keypress", mascaraCPF);
    };

//-------------- *colocar no chave mestra em breve* ------------------------------//

    // Máscara de CPF para ficar como: 000.000.000-00
    function mascaraCPF(event) {
        const input = event.target;

        // verificar para que permitam apenas números
        if (event.keyCode < 48 || event.keyCode > 57 || input.value.length >= 14) {
            event.preventDefault();
            return;
        }

        // máscara com delay para depois de digitado aparecer os pontos e traço
        setTimeout(() => {
            let valor = input.value.replace(/\D/g, '');
            if (valor.length <= 3) {
                input.value = valor;
            } else if (valor.length <= 6) {
                input.value = valor.replace(/(\d{3})(\d+)/, "$1.$2");
            } else if (valor.length <= 9) {
                input.value = valor.replace(/(\d{3})(\d{3})(\d+)/, "$1.$2.$3");
            } else {
                input.value = valor.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, "$1.$2.$3-$4");
            }
        });
    }
//------------------------------------------------------------------------------//



//opcional--//

    // Validação do formulário
    function validaFormulario(event) {
        const cpfCampo = document.getElementById("cpf");
        const cpf = cpfCampo.value.replace(/\D/g, '');

        if (cpf.length !== 11 || !validaCPF(cpf)) {
            alert("CPF inválido!");
            cpfCampo.focus();
            cpfCampo.classList.add("erro-borda");
            event.preventDefault();
            return false;
        }

        cpfCampo.classList.remove("erro-borda");
        return true;
    }





//------------------------- Calculos para ver se é cpf "real" ---------------------------------------//


    // verificar se é um cpf real
    function validaCPF(cpf) {
        
        if (/^(\d)\1{10}$/.test(cpf)) return false; // falso se todos os numero forem iguais

        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = (soma * 10) % 11;
        if (resto === 10) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if (resto === 10) resto = 0;
        return resto === parseInt(cpf.charAt(10));
    }
    //logica vinda do site: https://dicasdeprogramacao.com.br/algoritmo-para-validar-cpf/
    //---------------------------------------------------------------------------------//
</script>

</body>
</html>
