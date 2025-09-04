<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if ($usuario === "adm" && $senha === "123") {
        echo "Login bem-sucedido!";
    } else {
        echo "Usuário ou senha inválidos.";
    }
} else {
    header("Location: index.html");
    exit();
}
?>