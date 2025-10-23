<?php
session_start();
require 'bd.php';

$usuario_id = $_SESSION['usuario_id'] ?? 0;
$id = $_GET['id'] ?? null;
$modo = $id ? 'editar' : 'adicionar';
$dados = [
    'titulo' => '',
    'tipo' => '',
    'status' => '',
    'descricao' => ''
];

if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            die("Ocorrência não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar ocorrência: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $status = $_POST['status'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE ocorrencias 
                                   SET titulo = :titulo, tipo = :tipo, status = :status, descricao = :descricao
                                   WHERE id = :id");
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':status' => $status,
                ':descricao' => $descricao,
                ':id' => $id
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO ocorrencias (usuario_id, titulo, tipo, status, descricao) 
                                   VALUES (:usuario_id, :titulo, :tipo, :status, :descricao)");
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':status' => $status,
                ':descricao' => $descricao
            ]);
        }

        header('Location: ocorrencias.php');
        exit();
    } catch (PDOException $e) {
        die("Erro ao salvar ocorrência: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $modo === 'editar' ? 'Editar' : 'Adicionar' ?> Ocorrência</title>
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
                <h2><?= strtoupper($modo) ?> OCORRÊNCIA</h2>
            </div>

            <form method="POST" class="form-ocorrencia">
                <label for="titulo">Título da ocorrência</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título" required
                       value="<?= htmlspecialchars($dados['titulo']) ?>">

                <label for="tipo">Tipo de manutenção</label>
                <select id="tipo" name="tipo" required>
                    <option value="" disabled <?= $dados['tipo'] == '' ? 'selected' : '' ?>>Preventiva / Corretiva</option>
                    <option value="Preventiva" <?= $dados['tipo'] == 'Preventiva' ? 'selected' : '' ?>>Preventiva</option>
                    <option value="Corretiva" <?= $dados['tipo'] == 'Corretiva' ? 'selected' : '' ?>>Corretiva</option>
                </select>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="" disabled <?= $dados['status'] == '' ? 'selected' : '' ?>>Selecione</option>
                    <option value="Pendente" <?= $dados['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="Em andamento" <?= $dados['status'] == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="Concluída" <?= $dados['status'] == 'Concluída' ? 'selected' : '' ?>>Concluída</option>
                </select>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" placeholder="Digite a descrição..."><?= htmlspecialchars($dados['descricao']) ?></textarea>

                <button type="submit"><?= $modo === 'editar' ? 'SALVAR ALTERAÇÕES' : 'ADICIONAR' ?></button>
            </form>
        </main>
    </div>
</body>
</html>
