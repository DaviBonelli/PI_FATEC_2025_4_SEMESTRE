<?php
session_start();
require 'bd.php';

$tipo_usuario = $_SESSION['tipo_usuario'] ?? ''; 
$usuario_id   = $_SESSION['usuario_id'] ?? 0;

try {
    if ($tipo_usuario === 'ADM') {
        $stmt = $pdo->prepare("SELECT * FROM ocorrencias ORDER BY id DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE usuario_id = :usuario_id ORDER BY id DESC");
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    $ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar ocorrências: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Ocorrências</title>
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
                <?php if ($tipo_usuario === 'ADM'): ?>
                    <li><a href="fornecedores.php"><img src="../Imagens/fornecedor_icone.png" alt="Fornecedores"> Fornecedores</a></li>
                    <li><a href="funcionarios.php"><img src="../Imagens/func_icone.png" alt="Funcionários"> Funcionários</a></li>
                    <li><a href="maquinas.php"><img src="../Imagens/maquina_icone.png" alt="Máquinas"> Máquinas</a></li>
                <?php endif; ?>
                <li><a href="relatorios.php"><img src="../Imagens/relatorio_icone.png" alt="Relatórios"> Relatórios</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="titulo-pagina">
                <h2>OCORRÊNCIAS</h2>
                <div class="botoes">
                    <button onclick="window.location.href='adicionar_ocorrencia.php'">ADICIONAR</button>
                </div>
            </div>

            <div class="lista-ocorrencias">
                <?php if (!empty($ocorrencias)): ?>
                    <?php foreach ($ocorrencias as $oc): ?>
                        <div class="ocorrencia-card">
                            <div class="info">
                                <h3><?= htmlspecialchars($oc['titulo']) ?></h3>
                                <p><strong>Tipo:</strong> <?= htmlspecialchars($oc['tipo']) ?></p>
                                <p><strong>Status:</strong> <?= htmlspecialchars($oc['status']) ?></p>
                                <?php if (!empty($oc['descricao'])): ?>
                                    <div class="descricao">
                                        <strong>Descrição:</strong> <?= nl2br(htmlspecialchars($oc['descricao'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="acoes">
                                <a href="editar_ocorrencia.php?id=<?= $oc['id'] ?>">
                                    <img src="../Imagens/editar.png" alt="Editar">
                                </a>
                                <a href="ver_ocorrencia.php?id=<?= $oc['id'] ?>">
                                    <img src="../Imagens/visualizar.png" alt="Ver Mais">
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sem-ocorrencias">
                        <p>Nenhum resultado disponível no momento.</p>
                        <img src="../Imagens/nada_encontrado.png" alt="Sem ocorrências">
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
