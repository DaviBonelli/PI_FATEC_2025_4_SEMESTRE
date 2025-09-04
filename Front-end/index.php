<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="illustration-panel">
            <img src="../Imagens/inicio.png" alt="Ilustração">
        </div>
        <div class="login-panel">
            <div class="login-form">
                <h2>Login</h2>
                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label for="usuario">
                            <span class="icon">👤</span>
                            <input type="text" id="usuario" name="usuario" placeholder="Usuário">
                        </label>
                    </div>
                    <div class="input-group">
                        <label for="senha">
                            <span class="icon">🔒</span>
                            <input type="password" id="senha" name="senha" placeholder="Senha">
                        </label>
                    </div>
                    <p class="help-link">Precisa de ajuda?</p>
                    <button type="submit" class="login-button">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>