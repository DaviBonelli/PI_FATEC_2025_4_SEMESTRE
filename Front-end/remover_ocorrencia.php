<?php
session_start();
require 'bd.php';

if (!isset($_POST['ocorrencias']) || empty($_POST['ocorrencias'])) {
    die("ID da ocorrência não informado.");
}

$ids = $_POST['ocorrencias'];

// Converte para números inteiros para segurança
$ids = array_map('intval', $ids);

try {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM ocorrencias WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    header("Location: ocorrencias.php");
    exit;

} catch (PDOException $e) {
    die("Erro ao remover ocorrências: " . $e->getMessage());
}
?>
