<?php
$host = "localhost";
$dbname = "eventos";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )");

    $usuarios = [
        ['usuario' => 'ADM', 'senha' => '123'],
        ['usuario' => 'FUNC', 'senha' => '123']
    ];

    foreach ($usuarios as $u) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $u['usuario']]);
        if ($stmt->rowCount() == 0) {
            $stmt_insert = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)");
            $stmt_insert->execute(['usuario' => $u['usuario'], 'senha' => $u['senha']]);
        }
    }

} catch (PDOException $e) {
    die("Erro ao conectar ou criar tabela no MySQL: " . $e->getMessage());
}
?>
