<?php
include '../includes/conexao.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome && $email) {
        $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        $stmt->execute();
        header('Location: create-usuarios.php');
        exit;
    } else {
        $erro = 'Preencha todos os campos.';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color:rgb(0, 86, 179);">
    <div class="container-fluid">
        <h3 class="text-white">Gerenciamento de Tarefas</h3>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-white" href="create-usuarios.php">Usuários</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="create-tarefas.php">Tarefas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="read-gerenciar.php">Gerenciar</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Cadastro de Usuários</h2>
    <?php if($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nome:</label>
            <input name="nome" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email:</label>
            <input name="email" type="email" class="form-control" required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Cadastrar</button>
        </div>
    </form>
</div>
</body>
</html>
