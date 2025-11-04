<?php
session_start();
include 'includes/conexao.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $senha = trim($_POST["senha"] ?? '');

    if ($email && $senha) {
        // Consulta segura com prepared statement
        $stmt = $mysqli->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && $senha === $user['senha']) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_email'] = $user['email'];

            header("Location: public/create-usuarios.php");
            exit;
        } else {
            $msg = "E-mail ou senha incorretos!";
        }
    } else {
        $msg = "Preencha todos os campos!";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login - Sistema de Tarefas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <?php if (!empty($_SESSION["usuario_id"])): ?>
        <div class="card shadow p-4 text-center">
          <h3 class="mb-3">Tchau, tchau, <?= htmlspecialchars($_SESSION["usuario_nome"]) ?>!</h3>
          <p class="text-muted">Você quer mesmo sair?</p>
          <div class="d-grid">
            <a href="?logout=1" class="btn btn-danger">Sair</a>
          </div>
        </div>

        <?php
        if (isset($_GET['logout'])) {
            session_destroy();
            header("Location: index.php");
            exit;
        }
        ?>

      <?php else: ?>
        <div class="card shadow p-4">
          <h3 class="text-center mb-4">Login</h3>

          <?php if ($msg): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($msg) ?></div>
          <?php endif; ?>

          <form method="post">
            <div class="mb-3">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required>
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
            <small>Não tem conta? <a href="public/cadastro-usuarios.php">Cadastre-se</a></small>
          </p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>