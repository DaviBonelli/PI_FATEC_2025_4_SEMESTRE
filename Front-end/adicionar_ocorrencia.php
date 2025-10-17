<?php
session_start();
require 'bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $status = $_POST['status'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO ocorrencias (usuario_id, titulo, tipo, status, descricao) 
                               VALUES (:usuario_id, :titulo, :tipo, :status, :descricao)");
        $stmt->execute([
            ':usuario_id' => $_SESSION['usuario_id'],
            ':titulo' => $titulo,
            ':tipo' => $tipo,
            ':status' => $status,
            ':descricao' => $descricao
        ]);
        header('Location: ocorrencias.php'); 
        exit();
    } catch (PDOException $e) {
        die("Erro ao adicionar ocorrência: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Ocorrência</title>
    <link rel="stylesheet" href="style/ocorrencia.css">
</head>
<body>
    <div class="navbar">
        <img src="../Imagens/logo_cliente.jpeg" alt="Logo Cliente" class="logo-cliente">
        <a href="index.php" class="logout-icon">
            <img src="../Imagens/icone_sair.png" alt="Sair">
        </a>
    </div>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="ocorrencias.php"><img src="../Imagens/ocorrencia_icone.png" alt="Ocorrências"> Ocorrências</a></li>
                <li><a href="fornecedores.php"><img src="../Imagens/fornecedor_icone.png" alt="Fornecedores"> Fornecedores</a></li>
                <li><a href="funcionarios.php"><img src="../Imagens/func_icone.png" alt="Funcionários"> Funcionários</a></li>
                <li><a href="relatorios.php"><img src="../Imagens/relatorio_icone.png" alt="Relatórios"> Relatórios</a></li>
                <li><a href="maquinas.php"><img src="../Imagens/maquina_icone.png" alt="Máquinas"> Máquinas</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="titulo-pagina">
                <h2>ADICIONAR OCORRÊNCIA</h2>
            </div>

            <form method="POST" class="form-ocorrencia">
                <label for="titulo">Título da ocorrência</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título" required>

                <label for="tipo">Tipo de manutenção</label>
                <select id="tipo" name="tipo" required>
                    <option value="" disabled selected>Preventiva / Corretiva</option>
                    <option value="Preventiva">Preventiva</option>
                    <option value="Corretiva">Corretiva</option>
                </select>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="" disabled selected>Pendente</option>
                </select>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" placeholder="Digite a descrição..."></textarea>

                <button type="submit">ADICIONAR</button>
            </form>
        </main>
    </div>
</body>
</html>
