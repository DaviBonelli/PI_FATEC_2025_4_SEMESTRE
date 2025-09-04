<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if ($usuario === "adm" && $senha === "123") {
        
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario'] = $usuario; 

        header("Location: index.html"); 
        exit(); 
    } else {
        echo "Usuário ou senha inválidos.";
    }
} else {
    header("Location: index.html");
    exit();
}
?>