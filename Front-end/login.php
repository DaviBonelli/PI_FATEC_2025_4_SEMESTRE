<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    echo "Usuário recebido: " . htmlspecialchars($usuario) . "<br>";
    echo "Senha recebida: " . htmlspecialchars($senha) . "<br>";

    if ($usuario === "admin" && $senha === "senha123") {
        echo "Login bem-sucedido!";
    } else {
        echo "Usuário ou senha inválidos.";
    }
} else {
    header("Location: index.html");
    exit();
}
?>