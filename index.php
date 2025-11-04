<?php
// Conexão com o banco
$mysqli = new mysqli("localhost", "root", "", "tarefas_db");
if ($mysqli->connect_errno) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

session_start();

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$msg = "";

// Processamento do login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";

    // Busca o usuário com prepared statement
    $stmt = $mysqli->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();

    // Verifica se o usuário existe e a senha está correta
    if ($usuario && $usuario["senha"] === $senha) {
        $_SESSION["user_id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        header("Location: public/create-usuarios.php");
        exit;
    } else {
        $msg = "Email ou senha incorretos!";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <?php if (!empty($_SESSION["user_id"])): ?>
        <div class="card shadow p-4">
          <h3 class="text-center mb-3">Bem-vindo, <?= htmlspecialchars($_SESSION["nome"]) ?>!</h3>
          <p class="text-center text-muted">Sessão ativa.</p>
          <div class="d-grid">
            <a href="?logout=1" class="btn btn-danger">Sair</a>
          </div>
        </div>

      <?php else: ?>
        <div class="card shadow p-4">
          <h3 class="text-center mb-4">Login</h3>

          <?php if ($msg): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($msg) ?></div>
          <?php endif; ?>

          <form method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu email" required>
            </div>

            <div class="mb-3">
              <label for="senha" class="form-label">Senha</label>
              <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
          </form>

          <p class="mt-3 text-muted text-center">
            <small>Não tem uma conta? <a href="public/cadastro-usuarios.php">Cadastre-se</a></small>
          </p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>