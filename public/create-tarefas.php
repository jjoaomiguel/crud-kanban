<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao'] ?? '');
    $setor = trim($_POST['setor'] ?? '');
    $prioridade = $_POST['prioridade'] ?? 'Média';
    $status = $_POST['status_tarefa'] ?? 'Fazer';
    $data = date('Y-m-d');
    $usuario_id = $_SESSION['usuario_id'];

    if ($descricao && $setor) {
        $stmt = $mysqli->prepare("INSERT INTO tarefas (descricao, setor, prioridade, data_cadastro, status_tarefa, usuario_responsavel) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $descricao, $setor, $prioridade, $data, $status, $usuario_id);
        $stmt->execute();
        header('Location: read-gerenciar.php');
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
    <title>Criar Tarefa</title>
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
            <li class="nav-item"><a class="nav-link text-white" href="../index.php">Sair</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Nova Tarefa</h2>
    <?php if($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Descrição:</label>
            <input name="descricao" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Setor:</label>
            <input name="setor" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Prioridade:</label>
            <select name="prioridade" class="form-select">
                <option>Baixa</option>
                <option selected>Média</option>
                <option>Alta</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status:</label>
            <select name="status_tarefa" class="form-select">
                <option>Fazer</option>
                <option>Fazendo</option>
                <option>Pronto</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
</body>
</html>
