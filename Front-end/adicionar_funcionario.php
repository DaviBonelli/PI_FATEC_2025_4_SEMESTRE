<?php
session_start();
require 'bd.php';

$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';
if ($tipo_usuario !== 'ADM') {
    die("Acesso negado.");
}

$id = $_GET['id'] ?? null;
$funcionario = [
    'nome' => '',
    'idade' => '',
    'funcao' => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id = :id");
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$funcionario) {
        die("Funcionário não encontrado.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $idade = $_POST['idade'] ?? '';
    $funcao = $_POST['funcao'] ?? '';

    if (empty($nome) || empty($idade) || empty($funcao)) {
        $erro = "Nome, idade e função são obrigatórios.";
    } else {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE funcionarios SET nome = :nome, idade = :idade, funcao = :funcao WHERE id = :id");
                $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare("INSERT INTO funcionarios (nome, idade, funcao) VALUES (:nome, :idade, :funcao)");
            }

            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':idade', (int)$idade, PDO::PARAM_INT);
            $stmt->bindValue(':funcao', $funcao);
            $stmt->execute();

            header('Location: funcionarios.php');
            exit();
        } catch (PDOException $e) {
            $erro = "Erro ao salvar funcionário: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Editar' : 'Adicionar' ?> Funcionário</title>
<link rel="stylesheet" href="style/style_base.css">
</head>
<body>
<div class="container-form">
    <h2><?= $id ? 'Editar' : 'Adicionar' ?> Funcionário</h2>
    <?php if (!empty($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="POST">
        <label>Nome</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($funcionario['nome']) ?>" required>

        <label>Idade</label>
        <input type="number" name="idade" value="<?= htmlspecialchars($funcionario['idade']) ?>" required min="0">

        <label>Função</label>
        <input type="text" name="funcao" value="<?= htmlspecialchars($funcionario['funcao']) ?>" required>

        <button type="submit"><?= $id ? 'Atualizar' : 'Adicionar' ?></button>
        <button type="button" onclick="window.location.href='funcionarios.php'">Cancelar</button>
    </form>
</div>
</body>
</html>
