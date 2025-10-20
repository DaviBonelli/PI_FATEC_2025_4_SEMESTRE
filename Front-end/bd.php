<?php
$host = "localhost";
$dbname = "bluedev";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE $dbname");

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )");

    $usuarios = [
        ['usuario' => 'ADM', 'senha' => password_hash('1234', PASSWORD_DEFAULT)],
        ['usuario' => 'FUNC', 'senha' => password_hash('123', PASSWORD_DEFAULT)]
    ];

    foreach ($usuarios as $u) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $u['usuario']]);
        if ($stmt->rowCount() == 0) {
            $stmt_insert = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)");
            $stmt_insert->execute([
                'usuario' => $u['usuario'],
                'senha' => $u['senha']
            ]);
        }
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS ocorrencias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        titulo VARCHAR(100) NOT NULL,
        tipo VARCHAR(50) NOT NULL,
        status VARCHAR(50) NOT NULL,
        descricao TEXT,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS fornecedores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        nome_empresa VARCHAR(100) NOT NULL,
        categoria VARCHAR(50) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        endereco VARCHAR(255) NOT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )");

} catch (PDOException $e) {
    die("Erro ao conectar ou criar banco/tabelas no MySQL: " . $e->getMessage());
}
?>
