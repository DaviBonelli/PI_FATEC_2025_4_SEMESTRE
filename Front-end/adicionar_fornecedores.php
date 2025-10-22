<?php
session_start();
require 'bd.php';
$usuario_id = $_SESSION['usuario_id'] ?? 0;
if (!$usuario_id) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$modo = $id ? 'editar' : 'adicionar';

$dados = [
    'nome' => '',
    'cnpj' => '',
    'categoria' => '',
    'telefone' => '',
    'endereco' => ''
];

if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            die("Fornecedor não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar fornecedor: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cnpj = trim($_POST['cnpj'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE fornecedores 
                                   SET nome = :nome, cnpj = :cnpj, categoria = :categoria, telefone = :telefone, endereco = :endereco
                                   WHERE id = :id AND usuario_id = :usuario_id");
            $stmt->execute([
                ':nome' => $nome,
                ':cnpj' => $cnpj,
                ':categoria' => $categoria,
                ':telefone' => $telefone,
                ':endereco' => $endereco,
                ':id' => $id,
                ':usuario_id' => $usuario_id
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO fornecedores (usuario_id, nome, cnpj, categoria, telefone, endereco) 
                                   VALUES (:usuario_id, :nome, :cnpj, :categoria, :telefone, :endereco)");
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':nome' => $nome,
                ':cnpj' => $cnpj,
                ':categoria' => $categoria,
                ':telefone' => $telefone,
                ':endereco' => $endereco
            ]);
        }

        header('Location: fornecedores.php');
        exit();
    } catch (PDOException $e) {
        die("Erro ao salvar fornecedor: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= strtoupper($modo) ?> FORNECEDOR</title>
    <link rel="stylesheet" href="style/fornecedor.css">
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
                <li><a href="fornecedores.php" class="ativo"><img src="../Imagens/fornecedor_icone.png" alt="Fornecedores"> Fornecedores</a></li>
                <li><a href="funcionarios.php"><img src="../Imagens/func_icone.png" alt="Funcionários"> Funcionários</a></li>
                <li><a href="relatorios.php"><img src="../Imagens/relatorio_icone.png" alt="Relatórios"> Relatórios</a></li>
                <li><a href="maquinas.php"><img src="../Imagens/maquina_icone.png" alt="Máquinas"> Máquinas</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="form-fornecedor">
                <div class="titulo-pagina">
                    <h2><?= $modo === 'editar' ? 'EDITAR FORNECEDOR' : 'ADICIONAR FORNECEDOR' ?></h2>
                    <div class="botoes">
                        <button type="submit" form="form-fornecedor"><?= $modo === 'editar' ? 'SALVAR' : 'ADICIONAR' ?></button>
                    </div>
                </div>
                <form id="form-fornecedor" method="POST">
                    <label for="nome">Nome da empresa</label>
                    <input type="text" id="nome" name="nome" placeholder="..." required
                           value="<?= htmlspecialchars($dados['nome']) ?>">

                    <label for="cnpj">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0001-00" required
                           value="<?= htmlspecialchars($dados['cnpj']) ?>">

                    <label for="categoria">Categoria</label>
                    <input type="text" id="categoria" name="categoria" placeholder="..." required
                           value="<?= htmlspecialchars($dados['categoria']) ?>">

                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="..." required
                           value="<?= htmlspecialchars($dados['telefone']) ?>">

                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" placeholder="..." required
                           value="<?= htmlspecialchars($dados['endereco']) ?>">
                </form>
            </div>
        </main>
    </div>
</body>
</html>
