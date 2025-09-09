<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "eventos");

if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha   = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND senha='$senha' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario'] = $usuario;

        if ($usuario === "ADM") {
            header("Location: inicial_adm.php");
            exit();
        } elseif ($usuario === "FUNC") {
            header("Location: inicial_func.php");
            exit();
        } else {
            header("Location: index.html");
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
