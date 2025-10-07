<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial Administrador</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #fff;
            color: #000;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1px solid #eee;
            padding: 20px 10px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .sidebar li img {
            width: 20px;
            height: 20px;
        }

        .sidebar li:hover {
            color: #291aadff;
            transform: translateX(5px);
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .main-content h1 {
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .logo {
            max-width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><img src="../Imagens/ocorrencia_icone.png" alt="Ocorrências"> Ocorrências</li>
                <li><img src="../Imagens/fornecedor_icone.png" alt="Fornecedores"> Fornecedores</li>
                <li><img src="../Imagens/func_icone.png" alt="Funcionários"> Funcionários</li>
                <li><img src="../Imagens/relatorio_icone.png" alt="Relatórios"> Relatórios</li>
                <li><img src="../Imagens/maquina_icone.png" alt="Máquinas"> Máquinas</li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>SEJA BEM-VINDO!</h1>
            <img src="../Imagens/logo_cliente.jpeg" alt="Logo Arearty" class="logo">
        </main>
    </div>
</body>
</html>
