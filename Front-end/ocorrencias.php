<?php
session_start();
require 'bd.php';

$tipo_usuario = $_SESSION['tipo_usuario'] ?? ''; 
$usuario_id   = $_SESSION['usuario_id'] ?? 0;
$statusFiltro = $_GET['status'] ?? '';

try {
    if ($tipo_usuario === 'ADM') {
        if (!empty($statusFiltro)) {
            $stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE status = :status ORDER BY id DESC");
            $stmt->bindValue(':status', $statusFiltro, PDO::PARAM_STR);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM ocorrencias ORDER BY id DESC");
        }
    } else {
        if (!empty($statusFiltro)) {
            $stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE usuario_id = :usuario_id AND status = :status ORDER BY id DESC");
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $statusFiltro, PDO::PARAM_STR);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE usuario_id = :usuario_id ORDER BY id DESC");
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        }
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
<link rel="stylesheet" href="style/style_base.css">
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
            
            <div class="filtro-container">
    <img src="../Imagens/filtro.png" alt="Filtrar" class="icone-filtro" id="abrirFiltro">
    <div class="menu-filtro" id="menuFiltro">
        <form method="GET" action="">
            <select name="status" id="filtroStatus" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="Pendente" <?= (($_GET['status'] ?? '') === 'Pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="Em andamento" <?= (($_GET['status'] ?? '') === 'Em andamento') ? 'selected' : '' ?>>Em andamento</option>
                <option value="Concluído" <?= (($_GET['status'] ?? '') === 'Concluído') ? 'selected' : '' ?>>Concluído</option>
            </select>
        </form>
    </div>
</div>
                <button onclick="window.location.href='adicionar_ocorrencia.php'">ADICIONAR</button>
                <?php if ($tipo_usuario === 'ADM'): ?>
                    <button id="btnRemover" type="button" class="btn-remover">REMOVER</button>
                <?php endif; ?>
            </div>
        </div>

        <form id="formRemover" method="POST" action="remover_ocorrencia.php">
            <div class="lista-ocorrencias">
                <?php if (!empty($ocorrencias)): ?>
                   <?php foreach ($ocorrencias as $oc): ?>
    <?php
$imagemCaminho = '';
if (!empty($oc['imagem'])) {
    $imagemCaminho = str_starts_with($oc['imagem'], 'uploads/') 
        ? $oc['imagem'] 
        : 'uploads/' . ltrim($oc['imagem'], '/');
}
?>
    <div class="ocorrencia-card">
        <?php if ($tipo_usuario === 'ADM'): ?>
            <input type="checkbox" name="ocorrencias[]" value="<?= $oc['id'] ?>" class="checkbox-ocorrencia">
        <?php endif; ?>
        <div class="info">
            <h3><?= htmlspecialchars($oc['titulo']) ?></h3>
            <p><strong>Tipo:</strong> <?= htmlspecialchars($oc['tipo']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($oc['status']) ?></p>
            <?php if (!empty($oc['descricao'])): ?>
                <div class="descricao">
                    <strong>Descrição:</strong> 
                    <?= nl2br(htmlspecialchars(mb_strimwidth($oc['descricao'], 0, 60, '...'))) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="acoes">
            <a href="adicionar_ocorrencia.php?id=<?= $oc['id'] ?>">
                <img src="../Imagens/editar.png" alt="Editar">
            </a>
            <br>
            <a href="#"
               class="ver-mais"
               data-id="<?= $oc['id'] ?>"
               data-titulo="<?= htmlspecialchars($oc['titulo'], ENT_QUOTES) ?>"
               data-tipo="<?= htmlspecialchars($oc['tipo'], ENT_QUOTES) ?>"
               data-status="<?= htmlspecialchars($oc['status'], ENT_QUOTES) ?>"
               data-descricao="<?= htmlspecialchars($oc['descricao'] ?? '', ENT_QUOTES) ?>"
               data-imagem="<?= htmlspecialchars($imagemCaminho, ENT_QUOTES) ?>">
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
        </form>
    </main>
</div>

<div id="modalOcorrencia" class="modal">
    <div class="modal-content">
        <span class="fechar">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p><strong>Tipo:</strong> <span id="modalTipo"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <div id="modalDescricao"></div>
        <div class="modal-imagem-wrapper">
            <img id="modalImagem" src="" alt="Imagem da Ocorrência">
        </div>
        <div class="botoes-modal">
            <button id="btnEditar" class="btn-editar">Editar</button>
            <button class="btn-fechar" onclick="fecharModal()">Fechar</button>
        </div>
    </div>
</div>

<script>
const filtroIcone = document.getElementById('abrirFiltro');
const menuFiltro = document.getElementById('menuFiltro');

filtroIcone.addEventListener('click', () => {
    menuFiltro.classList.toggle('ativo');
});

window.addEventListener('click', (e) => {
    if (!menuFiltro.contains(e.target) && e.target !== filtroIcone) {
        menuFiltro.classList.remove('ativo');
    }
});

const modal = document.getElementById('modalOcorrencia');
const fechar = document.querySelector('.fechar');
const btnEditar = document.getElementById('btnEditar');

function abrirModal(ocorrencia) {
    document.getElementById('modalTitulo').textContent = ocorrencia.titulo;
    document.getElementById('modalTipo').textContent = ocorrencia.tipo;
    document.getElementById('modalStatus').textContent = ocorrencia.status;
    document.getElementById('modalDescricao').innerHTML = ocorrencia.descricao ? `<strong>Descrição:</strong><br>${ocorrencia.descricao}` : '';
    const imgModal = document.getElementById('modalImagem');
    if (ocorrencia.imagem) {
        imgModal.src = ocorrencia.imagem; 
        imgModal.style.display = 'block';
    } else {
        imgModal.style.display = 'none';
    }
    btnEditar.onclick = () => window.location.href = `adicionar_ocorrencia.php?id=${ocorrencia.id}`;
    modal.style.display = 'flex';
}

document.querySelectorAll('.acoes a.ver-mais').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const ocorrencia = {
            id: btn.dataset.id,
            titulo: btn.dataset.titulo,
            tipo: btn.dataset.tipo,
            status: btn.dataset.status,
            descricao: btn.dataset.descricao,
            imagem: btn.dataset.imagem
        };
        abrirModal(ocorrencia);
    });
});

function fecharModal() {
    modal.style.display = 'none';
}

fechar.onclick = fecharModal;
window.onclick = e => { if (e.target === modal) fecharModal(); };

</script>
</body>
</html>
