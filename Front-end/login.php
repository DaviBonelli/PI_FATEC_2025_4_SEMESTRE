<?php
session_start();

$usuarios = [
    'ADM'  => '1234',
    'FUNC' => '123'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = strtoupper($_POST['usuario']); 
    $senha   = $_POST['senha'];

    if (isset($usuarios[$usuario]) && $usuarios[$usuario] === $senha) {
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario'] = $usuario;

        if ($usuario === "ADM") {
            header("Location: inicial_adm.php");
            exit();
        } elseif ($usuario === "FUNC") {
            header("Location: inicial_func.php");
            exit();
        }
    } else {
        echo "Usuário ou senha inválidos.";
    }
} else {
    header("Location: index.html");
    exit();
}
?>
