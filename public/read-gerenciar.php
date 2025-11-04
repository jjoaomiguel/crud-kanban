<?php
include '../includes/conexao.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

$tarefas = $mysqli->query("
    SELECT t.id, t.descricao, t.setor, t.prioridade, t.data_cadastro, t.status_tarefa, u.nome AS usuario
    FROM tarefas t
    JOIN usuarios u ON t.usuario_responsavel = u.id
    ORDER BY t.data_cadastro DESC
");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Tarefas</title>
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
            <li class="nav-item ms-3">
                <a href="?logout=1" class="btn btn-danger btn-sm">Sair</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Tarefas Cadastradas</h2>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Setor</th>
                <th>Prioridade</th>
                <th>Data</th>
                <th>Status</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php while($t = $tarefas->fetch_assoc()): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['descricao']) ?></td>
                <td><?= htmlspecialchars($t['setor']) ?></td>
                <td><?= $t['prioridade'] ?></td>
                <td><?= $t['data_cadastro'] ?></td>
                <td><?= $t['status_tarefa'] ?></td>
                <td><?= htmlspecialchars($t['usuario']) ?></td>
                <td>
                    <a href="update-status.php?id=<?= $t['id'] ?>" class="btn btn-success btn-sm">Alterar Status</a>
                    <a href="edit-tarefa.php?id=<?= $t['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete-tarefa.php?id=<?= $t['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
